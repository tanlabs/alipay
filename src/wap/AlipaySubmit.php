<?php

namespace tanlabs\alipay\wap;

use tanlabs\alipay\AlipayConfig;
use tanlabs\alipay\core\AlipayException;
use tanlabs\alipay\core\AlipaySigner;

use Httpful\Request;

class AlipaySubmit
{
    public $config;
    public $signer;

    public $alipay_gateway_new = 'http://wappaygw.alipay.com/service/rest.htm?';

    public function __construct($config) 
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->config = $config;
        $this->signer = new AlipaySigner($config);
    }

    public function AlipaySubmit($config)
    {
        if ( ! $config instanceof AlipayConfig ) {
            throw new AlipayException('The config must be an instance of AlipayConfig');
        }
        $this->__construct($config);
        $this->signer = new AlipaySigner($config);
    }

    public function directTrade($request)
    {
        $sign = $this->signer->sign($request);
        $request->sign = $sign;
        $response = Request::post($this->alipay_gateway_new)
            ->body(http_build_query($request))
            ->sendsType(\Httpful\Mime::FORM)
            ->send();

        if ($response->code == 200 && strpos($response->body, 'res_error') === false) {
            $resbody = urldecode($response->body);
            $para_split = explode('&', $resbody);

            $params = [];
            foreach ($para_split as $item) {
                $nPos = strpos($item, '=');
                $nLen = strlen($item);
                $key = substr($item, 0, $nPos);
                $value = substr($item, $nPos+1, $nLen-$nPos-1);
                $params[$key] = $value;
            }

            if( ! empty ($params['res_data'])) {
                if($this->config->sign_type == '0001') {
                    $params['res_data'] = Rsa::decrypt($params['res_data'], $this->config->private_key_path);
                }
            }

            $result = new DirectTradeResponse();
            foreach ($result as $key=>$value) {
                $result->{$key} = $params[$key];
            }

            return $result;
        }
        return null;
    }

    public function authExecute($request)
    {
        $sign = $this->signer->sign($request);
        $request->sign = $sign;
        $method = "GET";
        $button_name = "Confirm";

        $post_url = $this->alipay_gateway_new . "_input_charset=" . trim(strtolower($this->config->input_charset));

        $html = "<form id='alipaysubmit' name='alipaysubmit' style='display:none' action='". $post_url . "' method='" . $method."'>";
        foreach ($request as $key=>$val) {
            $html.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        $html = $html."<input type='submit' value='".$button_name."'></form>";
        
        $html = $html."<script>document.forms['alipaysubmit'].submit();</script>";
        
        return $html;
    }
}

