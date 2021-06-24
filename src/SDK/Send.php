<?php

namespace Delta935142\Ecpay\SDK;

use Delta935142\Ecpay\SDK\Abstracts\Aio;
use Delta935142\Ecpay\SDK\Abstracts\CheckMacValue;

class Send extends Aio
{
    //付款方式物件
    public static $PaymentObj ;

    protected static function process($arParameters = array(),$arExtend = array())
    {
        //宣告付款方式物件
        $PaymentMethod    = "Delta935142\\Ecpay\\SDK\\".$arParameters['ChoosePayment'];
        self::$PaymentObj = new $PaymentMethod();

        //檢查參數
        $arParameters = self::$PaymentObj->check_string($arParameters);

        //檢查商品
        $arParameters = self::$PaymentObj->check_goods($arParameters);

        //檢查各付款方式的額外參數&電子發票參數
        $arExtend = self::$PaymentObj->check_extend_string($arExtend,$arParameters['InvoiceMark']);

        //過濾
        $arExtend = self::$PaymentObj->filter_string($arExtend,$arParameters['InvoiceMark']);

        //合併共同參數及延伸參數
        return array_merge($arParameters,$arExtend) ;
    }


    static function CheckOut($target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL='')
    {
        $arParameters = self::process($arParameters,$arExtend);
        //產生檢查碼
        $szCheckMacValue = CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);

        //生成表單，自動送出
        $szHtml = parent::HtmlEncode($target, $arParameters, $ServiceURL, $szCheckMacValue, '');
        echo $szHtml ;
        exit;
    }

    static function CheckOutString($paymentButton = 'Submit',$target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL='')
    {
        $arParameters = self::process($arParameters,$arExtend);
        //產生檢查碼
        $szCheckMacValue = CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);

        //生成表單
        $szHtml = parent::HtmlEncode($target, $arParameters, $ServiceURL, $szCheckMacValue, $paymentButton) ;
        return  $szHtml ;
    }
}