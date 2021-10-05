<?php

namespace Delta935142\Ecpay\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentStatus extends Facade
{
    /**
     * 取得註冊名稱
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Delta935142\Ecpay\PaymentStatus::class;
    }
}