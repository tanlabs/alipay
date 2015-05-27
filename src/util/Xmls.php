<?php

namespace tanlabs\alipay\util;

class Xmls
{
    public static function encode($obj, $root_node = 'xml') {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
        $xml .= self::encodeObject($obj, $root_node, $depth = 0);
        $xml = str_replace(PHP_EOL, "", str_replace("\t", "", $xml));
        return $xml;
    }

    private static function encodeObject($data, $node, $depth) {
        $xml = str_repeat("\t", $depth);
        $xml .= "<{$node}>" . PHP_EOL;
        foreach($data as $key => $val) {
            if(is_array($val) || is_object($val)) {
                $xml .= self::encodeObject($val, $key, ($depth + 1));
            } else {
                $xml .= str_repeat("\t", ($depth + 1));
                $xml .= "<{$key}>" . htmlspecialchars($val) . "</{$key}>" . PHP_EOL;
            }
        }
        $xml .= str_repeat("\t", $depth);
        $xml .= "</{$node}>" . PHP_EOL;
        return $xml;
    }
}

