<?php

namespace app;

use Douyin\ChatMessage;
use Douyin\CommonTextMessage;
use Douyin\GiftMessage;
use Douyin\LikeMessage;
use Douyin\MatchAgainstScoreMessage;
use Douyin\MemberMessage;
use Douyin\ProductChangeMessage;
use Douyin\PushFrame;
use Douyin\Response;
use Douyin\RoomUserSeqMessage;
use Douyin\SocialMessage;
use Douyin\UpdateFanTicketMessage;
use Exception;
use Workerman\Connection\AsyncTcpConnection;

class ParseMsg
{
    protected $ws_server;

    public function __construct($ws_server)
    {
        $this->ws_server = $ws_server;
    }

    /**
     *
     * @param AsyncTcpConnection $con
     * @param $data
     * @throws Exception
     */
    public function on_message(AsyncTcpConnection $con, $data)
    {
        $pushFrame = new PushFrame();
        $pushFrame->mergeFromString($data);
        $logId = $pushFrame->getLogId();
        $payload = $pushFrame->getPayload();
        $decompressed = gzdecode($payload);
        $payloadPackage = new Response();
        $payloadPackage->mergeFromString($decompressed);


        $intInternalExt = $payloadPackage->getInternalExt();

        if ($payloadPackage->getNeedAck() == true) {
            self::sendAck($con, $logId, $intInternalExt);
        }

        foreach ($payloadPackage->getMessagesList() as $item) {
            $data = [];
            switch ($item->getMethod()) {
                case 'WebcastMatchAgainstScoreMessage':
                    $data = self::unPackMatchAgainstScoreMessage($item->getPayload());
                    break;
                case 'WebcastLikeMessage':
                    $data = self::unPackWebcastLikeMessage($item->getPayload());
                    break;
                case 'WebcastChatMessage':
                    $data = self::unPackWebcastChatMessage($item->getPayload());
                    break;
                case 'WebcastMemberMessage':
                    $data = self::unPackWebcastMemberMessage($item->getPayload());
                    break;
                case 'WebcastGiftMessage':
                    $data = self::unPackWebcastGiftMessage($item->getPayload());
                    break;
                case 'WebcastSocialMessage':
                    $data = self::unPackWebcastSocialMessage($item->getPayload());
                    break;
                case 'WebcastRoomUserSeqMessage':
                    $data = self::unPackWebcastRoomUserSeqMessage($item->getPayload());
                    break;
                case 'WebcastUpdateFanTicketMessage':
                    $data = self::unPackWebcastUpdateFanTicketMessage($item->getPayload());
                    break;
                case 'WebcastCommonTextMessage':
                    $data = self::unPackWebcastCommonTextMessage($item->getPayload());
                    break;
                case 'WebcastProductChangeMessage':
                    $data = self::unPageWebcastProductChangeMessage($item->getPayload());
                    break;
            }
            if (!empty($data)) {
                $this->ws_server->send(json_encode($data));
            }
        }
    }

    protected static function sendAck($con, $logId, $intInternalExt)
    {
        $pf = new PushFrame();
        $pf->setPayloadType('ack');
        $pf->setLogId($logId);
        $pf->setPayloadType($intInternalExt);
        $sendMsg = $pf->serializeToString();
        $con->send($sendMsg);
    }

    /**
     *
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastUpdateFanTicketMessage($payload)
    {
        $updateFanTicket = new UpdateFanTicketMessage();
        $updateFanTicket->mergeFromString($payload);
        $arr = $updateFanTicket->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     *
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPageWebcastProductChangeMessage($payload)
    {
        $productChangeMessage = new ProductChangeMessage();
        $productChangeMessage->mergeFromString($payload);
        $arr = $productChangeMessage->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     *
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastCommonTextMessage($payload)
    {
        $commonTextMessage = new CommonTextMessage();
        $commonTextMessage->mergeFromString($payload);
        $arr = $commonTextMessage->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     * 在线观众TOP3
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastRoomUserSeqMessage($payload)
    {
        $roomUserMessage = new RoomUserSeqMessage();
        $roomUserMessage->mergeFromString($payload);
        $arr = $roomUserMessage->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     * ➕直播间关注消息
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastSocialMessage($payload)
    {
        $socialMessage = new SocialMessage();
        $socialMessage->mergeFromString($payload);
        $arr = $socialMessage->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     * 🎁直播间礼物消息
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastGiftMessage($payload)
    {
        $giftMessage = new GiftMessage();
        $giftMessage->mergeFromString($payload);
        $arr = $giftMessage->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     * 👍直播间点赞消息
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastLikeMessage($payload)
    {
        $likeMessage = new LikeMessage();
        $likeMessage->mergeFromString($payload);
        $arr = $likeMessage->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     * 🤷不知道是啥的消息
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackMatchAgainstScoreMessage($payload)
    {
        $matchAgainst = new MatchAgainstScoreMessage();
        $matchAgainst->mergeFromString($payload);
        $arr = $matchAgainst->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     * 📧直播间弹幕消息
     * @param $payload
     * @return string
     * @throws Exception
     */
    protected static function unPackWebcastChatMessage($payload)
    {
        $chat = new ChatMessage();
        $chat->mergeFromString($payload);
        $arr = $chat->serializeToJsonString();

        return json_decode($arr, true);
    }

    /**
     *  🚹🚺直播间成员加入消息
     * @param $payload
     * @return mixed
     * @throws Exception
     */
    protected static function unPackWebcastMemberMessage($payload)
    {

        $member = new MemberMessage();
        $member->mergeFromString($payload);
        $arr = $member->serializeToJsonString();

        return json_decode($arr, true);
    }
}