<?php

namespace Delta935142\Ecpay\SDK;

use Delta935142\Ecpay\SDK\Abstracts\Verification;

class WebATM extends Verification
{
    public $arPayMentExtend = array();

    //過濾多餘參數
    function filter_string($arExtend = array(),$InvoiceMark = '')
    {
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
}