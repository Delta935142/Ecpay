<?php

namespace Delta935142\Ecpay\SDK\Abstracts;

abstract class ActionType 
{
    /**
     * 關帳
     */
    const C = 'C';

    /**
     * 退刷
     */
    const R = 'R';

    /**
     * 取消
     */
    const E = 'E';

    /**
     * 放棄
     */
    const N = 'N';
}