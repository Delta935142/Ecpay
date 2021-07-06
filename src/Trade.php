<?php

namespace Delta935142\Ecpay;

use Illuminate\Support\Collection;
use Delta935142\Ecpay\SDK\AllInOne;

Class Trade
{
    /**
     * AllInOne
     *
     * @var AllInOne
     */
    protected $obj;

    public function __construct()
    {
        $this->obj = new AllInOne();
        $this->obj->ServiceURL = (config('ecpay.test_mode'))
            ? config('ecpay.trade_host.test')
            : config('ecpay.trade_host.production');

        $this->obj->HashKey     = config('ecpay.hash_key');
        $this->obj->HashIV      = config('ecpay.hash_iv');
        $this->obj->MerchantID  = config('ecpay.merchant_id');
        $this->obj->EncryptType = config('ecpay.encrypt_type');
    }

    /**
     * 查詢
     *
     * @param string $tradeNo
     * @return Collection
     */
    public function query(string $tradeNo): Collection
    {
        $this->obj->Query['MerchantTradeNo'] = $tradeNo;
        $this->obj->Query['TimeStamp']       = time();

        try {
            $response = $this->obj->QueryTradeInfo();
        } catch (\Exception $e) {
            return collect(['success' => false, 'content' => $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => $response]);
    }
}