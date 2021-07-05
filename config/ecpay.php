<?php

return [
    /**
     * 主機位置
     */
    'host' => 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5',

    /**
     * 測試主機位置
     */
    'dev_host' => 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5',
    
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
];