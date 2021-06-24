<?php

namespace Delta935142\Ecpay\SDK\Abstracts;

abstract class ExtraPaymentInfo 
{
    /**
     * 需要額外付款資訊。
     */
    const Yes = 'Y';

    /**
     * 不需要額外付款資訊。
     */
    const No = 'N';
}