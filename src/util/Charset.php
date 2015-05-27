<?php

namespace tanlabs\alipay\util;

class Charset
{
    /**
     * @param $input string to encode
     * @param $output_charset
     * @param $input_charset
     * @return encoded string
     */
    public static function encode($input, $output_charset, $input_charset)
    {
        $output = "";
        if(!isset($output_charset)) $output_charset = $input_charset;
        if($input_charset == $output_charset || $input ==null ) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input,$output_charset,$input_charset);
        } elseif(function_exists("iconv")) {
            $output = iconv($input_charset,$output_charset,$input);
        } else die("sorry, you have no libs support for charset change.");
        return $output;
    }

    /**
     * @param $input string to decode
     * @param $input_charset
     * @param $output_charset
     * @return decoded string
     */
    public static function decode($input, $input_charset, $output_charset)
    {
        $output = "";
        if(!isset($input_charset)) $input_charset = $input_charset;
        if($input_charset == $output_charset || $input ==null ) {
            $output = $input;
        } elseif (function_exists("mb_convert_encoding")) {
            $output = mb_convert_encoding($input,$output_charset,$input_charset);
        } elseif(function_exists("iconv")) {
            $output = iconv($input_charset,$output_charset,$input);
        } else die("sorry, you have no libs support for charset changes.");
        return $output;
    }
}

