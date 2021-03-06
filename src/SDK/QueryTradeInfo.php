<?php

namespace Delta935142\Ecpay\SDK;

use Delta935142\Ecpay\SDK\Abstracts\Aio;
use Delta935142\Ecpay\SDK\Abstracts\CheckMacValue;

class QueryTradeInfo extends Aio
{
    static function CheckOut($arParameters = array(),$HashKey ='',$HashIV ='',$ServiceURL = ''){
        $arErrors = array();
        $arParameters['TimeStamp'] = time();
        $arFeedback = array();
        $arConfirmArgs = array();

        $EncryptType = $arParameters["EncryptType"];
        unset($arParameters["EncryptType"]);

        // 呼叫查詢。
        if (sizeof($arErrors) == 0) {
            $arParameters["CheckMacValue"] = CheckMacValue::generate($arParameters,$HashKey,$HashIV,$EncryptType);
            // 送出查詢並取回結果。
            $szResult = static::ServerPost($arParameters,$ServiceURL);
            $szResult = str_replace(' ', '%20', $szResult);
            $szResult = str_replace('+', '%2B', $szResult);

            // 轉結果為陣列。
            parse_str($szResult, $arResult);
            // 重新整理回傳參數。
            foreach ($arResult as $keys => $value) {
                if ($keys == 'CheckMacValue') {
                    $szCheckMacValue = $value;
                } else {
                    $arFeedback[$keys] = $value;
                    $arConfirmArgs[$keys] = $value;
                }
            }

            // 驗證檢查碼。
            if (sizeof($arFeedback) > 0) {
                $szConfirmMacValue = CheckMacValue::generate($arConfirmArgs,$HashKey,$HashIV,$EncryptType);
                if ($szCheckMacValue != $szConfirmMacValue) {
                    array_push($arErrors, 'CheckMacValue verify fail.');
                }
            }
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }

        return $arFeedback ;
    }
}