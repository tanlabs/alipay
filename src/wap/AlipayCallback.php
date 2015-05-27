<?php

namespace tanlabs\alipay\wap;

use tanlabs\alipay\AlipayConfig;
use tanlabs\alipay\core\AlipaySigner;

use Httpful\Request;

class AlipayCallback
{
    public $config;
    public $signer;

    function __construct($config)
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->config = $config;
        $this->signer = new AlipaySigner($config);
    }

    function AlipayCallback($config)
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->config = $config;
        $this->signer = new AlipaySigner($config);
    }

    public function verify($callbackResponse)
    {
        $signatureVerified = $this->signer->verify($callbackResponse, $callbackResponse->sign, false);
        
        return $signatureVerified;
    }
}
