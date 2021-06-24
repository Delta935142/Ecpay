<?php

namespace Delta935142\Ecpay\SDK\Abstracts;

abstract class InvoiceState 
{
    /**
     * 需要開立電子發票。
     */
    const Yes = 'Y';

    /**
     * 不需要開立電子發票。
     */
    const No = '';
}