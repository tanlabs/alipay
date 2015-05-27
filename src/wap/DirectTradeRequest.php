<?php

namespace tanlabs\alipay\wap;

class DirectTradeRequest
{
    public $service = 'alipay.wap.trade.create.direct';

    public $format = 'xml';

    public $v = '2.0';

    public $partner;

    public $req_id;

    public $sec_id;

    public $sign;

    public $req_data;
}

