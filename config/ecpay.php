<?php

return [
    /**
     * 付款主機位置
     * production 正式主機、test 測試主機
     */
    'payment_host' => [
        'production' => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5',
        'test'       => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5',
    ],

    /**
     * 訂單主機位置
     * production 正式主機、test 測試主機
     */
    'trade_host' => [
        'production' => 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
        'test'       => 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5',
    ],
    
    /**
     * 是否開啟測試模式
     */
    'test_mode' => true,

    /**
     * Hash Key
     */
    'hash_key' => env('ECPAY_HASH_KEY', '5294y06JbISpM5x9'),

    /**
     * Hash IV
     */
    'hash_iv' => env('ECPAY_HASH_IV', 'v77hoKGq4kWxNNIS'),

    /**
     * Merchant ID
     */
    'merchant_id' => env('ECPAY_MERCHANT_ID', '2000132'),

    /**
     * 加密方式
     */
    'encrypt_type' => 1,

    /**
     * 語系，預設繁體中文
     * [ENG, KOR, JPN, CHI]
     */
    'language' => null,

    /**
     * 交易返回網址
     */
    'return_url' => 'http://www.ecpay.com.tw/receive.php',

    /**
     * 產生返回網站的按鈕
     */
    'client_back_url' => null,

    /**
     * Server 端回傳付款相關資訊
     * ATM、CVS、barcode使用
     */
    'payment_info_url' => null,

    /**
     * Client 端回傳付款相關資訊
     * ATM、CVS、barcode使用
     */
    'client_redirect_url' => null
];