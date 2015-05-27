<?php

namespace tanlabs\alipay\wap;

class AuthExecuteRequest
{
    public $service = 'alipay.wap.auth.authAndExecute';

    public $format = 'xml';

    public $v = '2.0';

    public $partner;

    public $sec_id;

    public $sign;

    public $req_data;
}
