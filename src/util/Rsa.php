<?php

namespace tanlabs\alipay\util;

class Rsa
{
    /**
     * @param $data
     * @param private_key_path
     * @return
     */
    public static function sign($data, $private_key_path)
    {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);

        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * @param $data
     * @param $ali_public_key_path
     * @param $sign
     * @return
     */
    public static function verify($data, $ali_public_key_path, $sign)
    {
        $pubKey = file_get_contents($ali_public_key_path);
        $res = openssl_get_publickey($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);    
        return $result;
    }

    /**
     * @param $content
     * @param $private_key_path
     * @return
     */
    public static function descrypt($content, $private_key_path)
    {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        $content = base64_decode($content);
        
        $result  = '';
        for($i = 0; $i < strlen($content)/128; $i++  ) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
    }
}
