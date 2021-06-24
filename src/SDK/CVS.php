<?php

namespace Delta935142\Ecpay\SDK;

use Delta935142\Ecpay\SDK\Abstracts\Verification;

class CVS extends Verification
{
    public $arPayMentExtend = array(
        'Desc_1'            => '',
        'Desc_2'            => '',
        'Desc_3'            => '',
        'Desc_4'            => '',
        'PaymentInfoURL'    => '',
        'ClientRedirectURL' => '',
        'StoreExpireDate'   => ''
    );

    // 過濾多餘參數
    function filter_string($arExtend = array(),$InvoiceMark = '')
    {
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
}