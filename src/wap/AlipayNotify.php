<?php

namespace tanlabs\alipay\wap;

use tanlabs\alipay\AlipayConfig;
use tanlabs\alipay\core\AlipaySigner;
use tanlabs\alipay\util\Md5;
use tanlabs\alipay\util\Rsa;
use tanlabs\alipay\util\Urls;

use Httpful\Request;

class AlipayNotify
{
    public $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

    public $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

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

    function AlipayNotify($config)
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->config = $config;
        $this->signer = new AlipaySigner($config);
    }

    public function verify($notifyResponse)
    {
        if ($this->config->sign_type == '0001') {
            $notifyResponse->notify_data = Rsa::decrypt($notifyResponse->notify_data, $this->config->private_key_path);
        }
        
        $notifyXml = simplexml_load_string($notifyResponse->notify_data);
        $notifyId = (string) $notifyXml->notify_id;
        
        $notifyIdVerified = false;
        if (!empty($notifyId)) {
            $notifyIdVerified = $this->verifyNotifyId($notifyId);
        }

        // data should be in following order
        $data = [
            'service' => $notifyResponse->service,
            'v' => $notifyResponse->v,
            'sec_id' => $notifyResponse->sec_id,
            'notify_data' => $notifyResponse->notify_data,
        ];

        $signatureVerified = $this->signer->verify($data, $notifyResponse->sign, false);
        
        return $notifyIdVerified && $signatureVerified;
    }

    private function verifyNotifyId($notifyId)
    {
        $transport = strtolower(trim($this->config->transport));
        $partner = trim($this->config->partner);
        $verifyUrl = $transport == 'https' ? $this->https_verify_url : $this->http_verify_url;
        $verifyUrl = $verifyUrl . "partner=" . $partner . "&notify_id=" . $notifyId;
        
        $response = Request::get($verifyUrl)->send();
        return strcasecmp("true",  $response->body) == 0;
    }
}
