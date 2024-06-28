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

    protected static $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36 Edg/126.0.0.0';

    protected static $versionCode = 180800;
    protected static $webcastSdkVersion = "1.0.14-beta.0";


    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $worker = new Worker('websocket://0.0.0.0:4200');

        $worker->onMessage = function ($conn, $data) {
            $data = json_decode($data, true);


            $response = self::parseLive($data['url']);
            $ttwid = self::getTtwid($response);
            $liveRoomId = self::getRoomId($response);

            //获取 signature
            $userUniqueId = $this->getUserUniqueId();
            $sigParams = [
                "live_id" => "1",
                "aid" => "6383",
                "version_code" => self::$versionCode,
                "webcast_sdk_version" => self::$webcastSdkVersion,
                "room_id" => $liveRoomId,
                "sub_room_id" => "",
                "sub_channel_id" => "",
                "did_rule" => "3",
                "user_unique_id" => $userUniqueId,
                "device_platform" => "web",
                "device_type" => "",
                "ac" => "",
                "identity" => "audience"
            ];


            $xmsStub = $this->getXMsStub($sigParams);
            $signature = $this->getSignature($xmsStub);

            $webcast5_params = [
                "room_id" => $liveRoomId,
                "compress" => 'gzip',
                "version_code" => self::$versionCode,
                "webcast_sdk_version" => self::$webcastSdkVersion,
                "live_id" => "1",
                "did_rule" => "3",
                "user_unique_id" => $userUniqueId,
                "identity" => "audience",
                "signature" => $signature,
            ];
            $wssUrl = $this->getWssUrl($webcast5_params);

            $webSocketUrl = $this->buildRequestUrl($wssUrl);

            $new_url = str_replace("wss://", "ws://", $webSocketUrl);



            $header = [
                'cookie' => 'ttwid=' . $ttwid,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            ];
//            $webSocketUrl = 'ws://webcast3-ws-web-lq.douyin.com/webcast/im/push/v2/?app_name=douyin_web&version_code=180800&webcast_sdk_version=1.3.0&update_version_code=1.3.0&compress=gzip&internal_ext=internal_src:dim|wss_push_room_id:' . $liveRoomId . '|wss_push_did:7188358506633528844|dim_log_id:20230521093022204E5B327EF20D5CDFC6|fetch_time:1684632622323|seq:1|wss_info:0-1684632622323-0-0|wrds_kvs:WebcastRoomRankMessage-1684632106402346965_WebcastRoomStatsMessage-1684632616357153318&cursor=t-1684632622323_r-1_d-1_u-1_h-1&host=https://live.douyin.com&aid=6383&live_id=1&did_rule=3&debug=false&maxCacheMessageNumber=20&endpoint=live_pc&support_wrds=1&im_path=/webcast/im/fetch/&user_unique_id=7188358506633528844&device_platform=web&cookie_enabled=true&screen_width=1440&screen_height=900&browser_language=zh&browser_platform=MacIntel&browser_name=Mozilla&browser_version=5.0%20(Macintosh;%20Intel%20Mac%20OS%20X%2010_15_7)%20AppleWebKit/537.36%20(KHTML,%20like%20Gecko)%20Chrome/113.0.0.0%20Safari/537.36&browser_online=true&tz_name=Asia/Shanghai&identity=audience&room_id=' . $liveRoomId . '&heartbeatDuration=0&signature=00000000';

            // ssl需要访问443端口
            $wsClient = new AsyncTcpConnection($new_url);

            // 设置以ssl加密方式访问，使之成为wss
            $wsClient->transport = 'ssl';
            $wsClient->headers = $header;

            $parseMsg = new ParseMsg($conn);

            $wsClient->onMessage = [$parseMsg, 'on_message'];

            $wsClient->connect();

            $wsClient->onError = function ($connection, $code, $msg) {
                var_dump($code);
                var_dump($msg);
            };

        };
        Worker::runAll();

        return 0;
    }

    protected static function parseLive($liveUrl)
    {
        $client = new Client();

        return $client->get($liveUrl, [
            'headers' => [
                'authority' => 'live.douyin.com',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                'accept-language' => 'zh-CN,zh;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6',
                'cache-control' => 'max-age=0',
                'cookie' => 'xgplayer_user_id=251959789708; passport_assist_user=Cj1YUtyK7x-Br11SPK-ckKl61u5KX_SherEuuGPYIkLjtmV3X8m3EU1BAGVoO541Sp_jwUa8lBlNmbaOQqheGkoKPOVVH42rXu6KEb9WR85pUw4_qNHfbcotEO-cml5itrJowMBlYXDaB-GDqJwNMxMElMoZUycGhzdNVAT4XxCJ_74NGImv1lQgASIBA3Iymus%3D; n_mh=nNwOatDm453msvu0tqEj4bZm3NsIprwo6zSkIjLfICk; LOGIN_STATUS=1; store-region=cn-sh; store-region-src=uid; sid_guard=b177a545374483168432b16b963f04d5%7C1697713285%7C5183999%7CMon%2C+18-Dec-2023+11%3A01%3A24+GMT; ttwid=1%7C9SEGPfK9oK2Ku60vf6jyt7h6JWbBu4N_-kwQdU-SPd8%7C1697721607%7Cc406088cffa073546db29932058720720521571b92ba67ba902a70e5aaffd5d6; odin_tt=1f738575cbcd5084c21c7172736e90f845037328a006beefec4260bf8257290e2d31b437856575c6caeccf88af429213; __live_version__=%221.1.1.6725%22; device_web_cpu_core=16; device_web_memory_size=8; live_use_vvc=%22false%22; csrf_session_id=38b68b1e672a92baa9dcb4d6fd1c5325; FORCE_LOGIN=%7B%22videoConsumedRemainSeconds%22%3A180%7D; __ac_nonce=0658d6780004b23f5d0a8; __ac_signature=_02B4Z6wo00f01Klw1CQAAIDAXxndAbr7OHypUNCAAE.WSwYKFjGSE9AfNTumbVmy1cCS8zqYTadqTl8vHoAv7RMb8THl082YemGIElJtZYhmiH-NnOx53mVMRC7MM8xuavIXc-9rE7ZEgXaA13; webcast_leading_last_show_time=1703765888956; webcast_leading_total_show_times=1; webcast_local_quality=sd; xg_device_score=7.90435294117647; live_can_add_dy_2_desktop=%221%22; msToken=sTwrsWOpxsxXsirEl0V0d0hkbGLze4faRtqNZrIZIuY8GYgo2J9a0RcrN7r_l179C9AQHmmloI94oDvV8_owiAg6zHueq7lX6TgbKBN6OZnyfvZ6OJyo2SQYawIB_g==; tt_scid=NyxJTt.vWxv79efmWAzT2ZAiLSuybiEOWF0wiVYs5KngMuBf8oz5sqzpg5XoSPmie930; pwa2=%220%7C0%7C1%7C0%22; download_guide=%223%2F20231228%2F0%22; msToken=of81bsT85wrbQ9nVOK3WZqQwwku95KW-wLfjFZOef2Orr8PRQVte27t6Mkc_9c_ROePolK97lKVG3IL5xrW6GY6mdUDB0EcBPfnm8-OAShXzlELOxBBCdiQYIjCGpQ==; IsDouyinActive=false; odin_tt=7409a7607c84ba28f27c62495a206c66926666f2bbf038c847b27817acbdbff28c3cf5930de4681d3cfd4c1139dd557e; ttwid=1%7C9SEGPfK9oK2Ku60vf6jyt7h6JWbBu4N_-kwQdU-SPd8%7C1697721607%7Cc406088cffa073546db29932058720720521571b92ba67ba902a70e5aaffd5d6',
                'referer' => 'https://live.douyin.com/721566130345?cover_type=&enter_from_merge=web_live&enter_method=web_card&game_name=&is_recommend=&live_type=game&more_detail=&room_id=7317569386624125734&stream_type=vertical&title_type=&web_live_tab=all',
                'upgrade-insecure-requests' => '1',
                'user-agent' => self::$userAgent
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

    protected function getUserUniqueId(): string
    {
        return strval(mt_rand(7300000000000000000, 7999999999999999999));
    }

    protected function getXMsStub($params): string
    {
        $sig_params = implode(',', array_map(
            function ($k, $v) {
                return $k . '=' . $v;
            },
            array_keys($params),
            $params
        ));
        return md5($sig_params);
    }

    protected function getSignature($x_ms_stub)
    {
        $client = new Client();
        $url = 'http://localhost:3010/signature';

        try {
            $response = $client->post($url, [
                'body' => $x_ms_stub,
                'headers' => [
                    'Content-Type' => 'text/plain; charset=utf-8',
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                return $response->getBody()->getContents();
            } else {
                echo "Error getting signature";
                return "00000000";
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return "00000000";
        }
    }

    protected function getWssUrl($webcast5_params)
    {
        $base_url = "wss://webcast5-ws-web-lf.douyin.com/webcast/im/push/v2/?";
        $query_params = [];

        foreach ($webcast5_params as $key => $value) {
            $query_params[] = "$key=$value";
        }

        return $base_url . implode('&', $query_params);
    }

    function buildRequestUrl($url)
    {
        $parsed_url = parse_url($url);
        parse_str($parsed_url['query'], $existing_params);

        $existing_params['aid'] = '6383';
        $existing_params['device_platform'] = 'web';
        $existing_params['browser_language'] = 'zh-CN';
        $existing_params['browser_platform'] = 'Win32';

//        $USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'; // 示例用户代理
        $USER_AGENT = self::$userAgent;
        $user_agent_parts = explode('/', $USER_AGENT);
        $existing_params['browser_name'] = explode(' ', $user_agent_parts[0])[0];
        $existing_params['browser_version'] = substr($USER_AGENT, strpos($USER_AGENT, $existing_params['browser_name']) + strlen($existing_params['browser_name']) + 1);

        $new_query_string = http_build_query($existing_params);
        $new_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'] . '?' . $new_query_string;

        if (isset($parsed_url['fragment'])) {
            $new_url .= '#' . $parsed_url['fragment'];
        }

        return $new_url;
    }

}
