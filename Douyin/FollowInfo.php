<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dy.proto

namespace Douyin;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>douyin.FollowInfo</code>
 */
class FollowInfo extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint64 followingCount = 1;</code>
     */
    protected $followingCount = 0;
    /**
     * Generated from protobuf field <code>uint64 followerCount = 2;</code>
     */
    protected $followerCount = 0;
    /**
     * Generated from protobuf field <code>uint64 followStatus = 3;</code>
     */
    protected $followStatus = 0;
    /**
     * Generated from protobuf field <code>uint64 pushStatus = 4;</code>
     */
    protected $pushStatus = 0;
    /**
     * Generated from protobuf field <code>string remarkName = 5;</code>
     */
    protected $remarkName = '';
    /**
     * Generated from protobuf field <code>string followerCountStr = 6;</code>
     */
    protected $followerCountStr = '';
    /**
     * Generated from protobuf field <code>string followingCountStr = 7;</code>
     */
    protected $followingCountStr = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $followingCount
     *     @type int|string $followerCount
     *     @type int|string $followStatus
     *     @type int|string $pushStatus
     *     @type string $remarkName
     *     @type string $followerCountStr
     *     @type string $followingCountStr
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Dy::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint64 followingCount = 1;</code>
     * @return int|string
     */
    public function getFollowingCount()
    {
        return $this->followingCount;
    }

    /**
     * Generated from protobuf field <code>uint64 followingCount = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setFollowingCount($var)
    {
        GPBUtil::checkUint64($var);
        $this->followingCount = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 followerCount = 2;</code>
     * @return int|string
     */
    public function getFollowerCount()
    {
        return $this->followerCount;
    }

    /**
     * Generated from protobuf field <code>uint64 followerCount = 2;</code>
     * @param int|string $var
     * @return $this
     */
    public function setFollowerCount($var)
    {
        GPBUtil::checkUint64($var);
        $this->followerCount = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 followStatus = 3;</code>
     * @return int|string
     */
    public function getFollowStatus()
    {
        return $this->followStatus;
    }

    /**
     * Generated from protobuf field <code>uint64 followStatus = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setFollowStatus($var)
    {
        GPBUtil::checkUint64($var);
        $this->followStatus = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 pushStatus = 4;</code>
     * @return int|string
     */
    public function getPushStatus()
    {
        return $this->pushStatus;
    }

    /**
     * Generated from protobuf field <code>uint64 pushStatus = 4;</code>
     * @param int|string $var
     * @return $this
     */
    public function setPushStatus($var)
    {
        GPBUtil::checkUint64($var);
        $this->pushStatus = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string remarkName = 5;</code>
     * @return string
     */
    public function getRemarkName()
    {
        return $this->remarkName;
    }

    /**
     * Generated from protobuf field <code>string remarkName = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setRemarkName($var)
    {
        GPBUtil::checkString($var, True);
        $this->remarkName = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string followerCountStr = 6;</code>
     * @return string
     */
    public function getFollowerCountStr()
    {
        return $this->followerCountStr;
    }

    /**
     * Generated from protobuf field <code>string followerCountStr = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setFollowerCountStr($var)
    {
        GPBUtil::checkString($var, True);
        $this->followerCountStr = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string followingCountStr = 7;</code>
     * @return string
     */
    public function getFollowingCountStr()
    {
        return $this->followingCountStr;
    }

    /**
     * Generated from protobuf field <code>string followingCountStr = 7;</code>
     * @param string $var
     * @return $this
     */
    public function setFollowingCountStr($var)
    {
        GPBUtil::checkString($var, True);
        $this->followingCountStr = $var;

        return $this;
    }

}

