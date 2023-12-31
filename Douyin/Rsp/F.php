<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: dy.proto

namespace Douyin\Rsp;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>douyin.Rsp.F</code>
 */
class F extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint64 q1 = 1;</code>
     */
    protected $q1 = 0;
    /**
     * Generated from protobuf field <code>uint64 q3 = 3;</code>
     */
    protected $q3 = 0;
    /**
     * Generated from protobuf field <code>string q4 = 4;</code>
     */
    protected $q4 = '';
    /**
     * Generated from protobuf field <code>uint64 q5 = 5;</code>
     */
    protected $q5 = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $q1
     *     @type int|string $q3
     *     @type string $q4
     *     @type int|string $q5
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Dy::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint64 q1 = 1;</code>
     * @return int|string
     */
    public function getQ1()
    {
        return $this->q1;
    }

    /**
     * Generated from protobuf field <code>uint64 q1 = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setQ1($var)
    {
        GPBUtil::checkUint64($var);
        $this->q1 = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 q3 = 3;</code>
     * @return int|string
     */
    public function getQ3()
    {
        return $this->q3;
    }

    /**
     * Generated from protobuf field <code>uint64 q3 = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setQ3($var)
    {
        GPBUtil::checkUint64($var);
        $this->q3 = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string q4 = 4;</code>
     * @return string
     */
    public function getQ4()
    {
        return $this->q4;
    }

    /**
     * Generated from protobuf field <code>string q4 = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setQ4($var)
    {
        GPBUtil::checkString($var, True);
        $this->q4 = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint64 q5 = 5;</code>
     * @return int|string
     */
    public function getQ5()
    {
        return $this->q5;
    }

    /**
     * Generated from protobuf field <code>uint64 q5 = 5;</code>
     * @param int|string $var
     * @return $this
     */
    public function setQ5($var)
    {
        GPBUtil::checkUint64($var);
        $this->q5 = $var;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(F::class, \Douyin\Rsp_F::class);

