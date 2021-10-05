<?php

namespace Delta935142\Ecpay;

use Illuminate\Support\Collection;
use Delta935142\Ecpay\SDK\AllInOne;

Class PaymentStatus
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
        $this->obj->HashKey     = config('ecpay.hash_key');
        $this->obj->HashIV      = config('ecpay.hash_iv');
        $this->obj->MerchantID  = config('ecpay.merchant_id');
        $this->obj->EncryptType = config('ecpay.encrypt_type');
    }

    /**
     * ATM
     *
     * @param array $input
     * @return Collection
     */
    public function response(array $input): Collection
    {
        try {
            $this->obj->CheckOutFeedback($input);
        } catch(\Exception $e) {
            return collect(['success' => false, 'content' => '0|' . $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => '1|OK']);
    }
}