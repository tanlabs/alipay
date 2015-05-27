<?php

namespace tanlabs\alipay;

class AlipayConfig
{
    public $partner;

    public $seller_email;

    public $key;
    
    public $private_key_path;

    public $ali_public_key_path;

    public $sign_type = 'MD5';

    public $input_charset = 'utf-8';

    public $cacert;

    public $transport = 'http';

    public function __construct()
    {
    }

    public function AlipayConfig()
    {
    }
}

