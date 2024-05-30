<?php

namespace app\command;

use app\ParseMsg;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Worker;


class TestTest extends Command
{
    protected static $defaultName = 'test:test';
    protected static $defaultDescription = 'test test';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $worker = new Worker('websocket://0.0.0.0:4200');

        $worker->onMessage = function ($conn, $data) {
            $data = json_decode($data, true);


            $response = self::parseLive($data['url']);
            $ttwid = self::getTtwid($response);
            $liveRoomId = self::getRoomId($response);
            $header = [
                'cookie' => 'ttwid=' . $ttwid,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            ];
            $webSocketUrl = 'ws://webcast3-ws-web-lq.douyin.com/webcast/im/push/v2/?app_name=douyin_web&version_code=180800&webcast_sdk_version=1.3.0&update_version_code=1.3.0&compress=gzip&internal_ext=internal_src:dim|wss_push_room_id:' . $liveRoomId . '|wss_push_did:7188358506633528844|dim_log_id:20230521093022204E5B327EF20D5CDFC6|fetch_time:1684632622323|seq:1|wss_info:0-1684632622323-0-0|wrds_kvs:WebcastRoomRankMessage-1684632106402346965_WebcastRoomStatsMessage-1684632616357153318&cursor=t-1684632622323_r-1_d-1_u-1_h-1&host=https://live.douyin.com&aid=6383&live_id=1&did_rule=3&debug=false&maxCacheMessageNumber=20&endpoint=live_pc&support_wrds=1&im_path=/webcast/im/fetch/&user_unique_id=7188358506633528844&device_platform=web&cookie_enabled=true&screen_width=1440&screen_height=900&browser_language=zh&browser_platform=MacIntel&browser_name=Mozilla&browser_version=5.0%20(Macintosh;%20Intel%20Mac%20OS%20X%2010_15_7)%20AppleWebKit/537.36%20(KHTML,%20like%20Gecko)%20Chrome/113.0.0.0%20Safari/537.36&browser_online=true&tz_name=Asia/Shanghai&identity=audience&room_id=' . $liveRoomId . '&heartbeatDuration=0&signature=00000000';

            // ssl需要访问443端口
            $wsClient = new AsyncTcpConnection($webSocketUrl);

            // 设置以ssl加密方式访问，使之成为wss
            $wsClient->transport = 'ssl';
            $wsClient->headers = $header;

            $parseMsg = new ParseMsg($conn);

            $wsClient->onMessage = [$parseMsg, 'on_message'];

            $wsClient->connect();


        };
        Worker::runAll();
    }

    protected static function parseLive($liveUrl)
    {
        $client = new Client();

        return $client->get($liveUrl, [
            'headers' => [
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
                'cookie' => 'ttwid=1%7C1g-HrGfzSYZaXrmDGn9w5e0gmJDn5Hkz8yTQx3tXY6o%7C1679877535%7C3baa4f9d7da4861135dcff464e44ab5ee31293bf67dfed370995982862d3466e; store-region=cn-ha; store-region-src=uid; __ac_nonce=0658a92d5000cb2bc2904;',
            ],
            'verify' => false
        ]);
    }

    protected static function getTtwid($response)
    {
        $cookieString = $response->getHeader('Set-Cookie');

        $cookieArray = explode(';', $cookieString[0]);
        $ttwidStr = $cookieArray[0];

        return substr($ttwidStr, strpos($ttwidStr, '=') + 1);
    }

    protected static function getRoomId($response)
    {
        $html = $response->getBody()->getContents();
        $pattern = '/roomId\\\\":\\\\"(\d+)\\\\"/';
        preg_match($pattern, $html, $matches);


        return $matches[1];
    }

}
