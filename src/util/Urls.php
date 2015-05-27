<?php

namespace tanlabs\alipay\util;

class Urls
{
    public static function toQueryString($para, $urlencode = false)
    {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            if ($urlencode) {
                $arg.=$key."=".urlencode($val)."&";
            } else {
                $arg.=$key."=".$val."&";
            }
        }
        $arg = substr($arg,0,count($arg)-2);

        if(get_magic_quotes_gpc()) { 
            $arg = stripslashes($arg);
        }
        
        return $arg;
    }
}
