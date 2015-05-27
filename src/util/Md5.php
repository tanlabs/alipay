<?php

namespace tanlabs\alipay\util;

class Md5
{
    /**
     * @param $value
     * @param $key private key
     * @return
     */
    public static function sign($value, $key)
    {
        $value = $value . $key;
        return md5($value);
    }

    /**
     * @param $value
     * @param $sign
     * @param $key private key
     * @return
     */
    public static function verify($value, $sign, $key)
    {
        $value = $value . $key;
        $mysgin = md5($value);
        return $mysgin == $sign;
    }
}
