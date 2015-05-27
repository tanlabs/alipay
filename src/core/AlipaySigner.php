<?php

namespace tanlabs\alipay\core;

use tanlabs\alipay\AlipayConfig;
use tanlabs\alipay\util\Md5;
use tanlabs\alipay\util\Rsa;
use tanlabs\alipay\util\Urls;

class AlipaySigner
{
    public $config;

    function __construct($config)
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->config = $config;
    }

    function AlipaySigner($config)
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->config = $config;
    }

    public function sign($data)
    {
        // filter
        $filtered = [];
        foreach ($data as $key=>$value) {
            if($key != "sign" && $key != "sign_type" && $value != "") {
                $filtered[$key] = $value;
            }
        }

        // sort
        $sorted = $filtered;
        ksort($sorted);
        reset($sorted);

        $queryString = Urls::toQueryString($sorted);

        $result = "";
        switch (strtoupper(trim($this->config->sign_type))) {
            case "MD5" :
                $result = Md5::sign($queryString, $this->config->key);
                break;
            case "RSA" :
                $result = Rsa::sign($queryString, $this->config->private_key_path);
                break;
            case "0001" :
                $result = Rsa::sign($queryString, $this->config->private_key_path);
                break;
            default :
                $result = "";
        }

        return $result;
    }

    public function verify($data, $sign, $sort=true)
    {
        // filter
        $filtered = [];
        foreach ($data as $key=>$value) {
            if($key != "sign" && $key != "sign_type" && $value != "") {
                $filtered[$key] = $value;
            }
        }

        // sort
        $sorted = $filtered;
        if ($sort === true) {
            ksort($sorted);
            reset($sorted);
        }

        $queryString = Urls::toQueryString($sorted);
        
        $result = false;
        switch (strtoupper(trim($this->config->sign_type))) {
            case "MD5" :
                $result = Md5::verify($queryString, $sign, $this->config->key);
                break;
            case "RSA" :
                $result = Rsa::verify($queryString, trim($this->config->ali_public_key_path), $sign);
                break;
            case "0001" :
                $result = Rsa::verify($queryString, trim($this->config->ali_public_key_path), $sign);
                break;
            default :
                $result = false;
        }

        return $result;
    }
}
