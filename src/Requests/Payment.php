<?php

namespace Delta935142\Ecpay\Requests;

use Delta935142\Ecpay\SDK\AllInOne;

Class Payment
{
    /**
     * 訂單編號
     *
     * @var string
     */
    protected static $tradeNo = '';

    /**
     * 交易時間
     *
     * @var string
     */
    protected static $tradeDateTime;

    /**
     * 交易描述
     *
     * @var string
     */
    protected static $tradeDesc;

    /**
     * 交易金額
     *
     * @var integer
     */
    protected static $total = 0;

    /**
     * 回傳網址
     *
     * @var string
     */
    protected static $returnUrl;

    /**
     * 商品
     *
     * @var array
     */
    protected static $items = [];

    /**
     * 分店代號
     *
     * @var string
     */
    protected static $storeID;

    /**
     * 自訂欄位
     *
     * @var array
     */
    protected static $customFields = [];

    /**
     * 發票物件
     *
     * @var Invoice
     */
    protected static $invoice;
    
    /**
     * 信用卡付款
     *
     * @param integer $installment 分期[3,6,12,18,24]
     * @param integer $amount 分期金額
     * @param boolean $redeem 紅利折抵
     * @param boolean $unionPay 聯營卡
     * @return void
     */
    public static function credit(int $installment = 0, int $amount = 0, bool $redeem = false, bool $unionPay = false)
    {
        $obj = self::setEnv();

        $orderId = self::$tradeNo ?? uniqid();

        $obj->Send['MerchantTradeNo']   = $orderId;
        $obj->Send['MerchantTradeDate'] = self::$tradeDateTime;
        $obj->Send['TradeDesc']         = self::$tradeDesc ?? $orderId;
        $obj->Send['TotalAmount']       = self::$total;
        $obj->Send['ReturnURL']         = self::$returnUrl;
        $obj->Send['ChoosePayment']     = 'Credit';
        $obj->Send['IgnorePayment']     = 'GooglePay';
        $obj->Send['Items']             = self::$items;

        $obj->Send['StoreID'] = self::$storeID;
        if (count(self::$customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $obj->Send['CustomField'.$i + 1] = self::$customFields[$i];
            }
        }

        $obj->SendExtend['CreditInstallment'] = $installment;
        $obj->SendExtend['InstallmentAmount'] = $amount;
        $obj->SendExtend['Redeem']            = $redeem;
        $obj->SendExtend['UnionPay']          = $unionPay;

        $obj = self::setInvoice($obj);

        $obj->CheckOut();
    }

    /**
     * ATM
     *
     * @param integer $expire 繳費期限(天)
     * @return void
     */
    public static function atm(int $expire = 3)
    {
        $obj = self::setEnv();

        $orderId = self::$tradeNo ?? uniqid();

        $obj->Send['MerchantTradeNo']   = $orderId;
        $obj->Send['MerchantTradeDate'] = self::$tradeDateTime;
        $obj->Send['TradeDesc']         = self::$tradeDesc ?? $orderId;
        $obj->Send['TotalAmount']       = self::$total;
        $obj->Send['ReturnURL']         = self::$returnUrl;
        $obj->Send['ChoosePayment']     = 'ATM';
        $obj->Send['Items']             = self::$items;

        $obj->Send['StoreID'] = self::$storeID;
        if (count(self::$customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $obj->Send['CustomField'.$i + 1] = self::$customFields[$i];
            }
        }

        $obj->SendExtend['ExpireDate']     = $expire;
        $obj->SendExtend['PaymentInfoURL'] = "";

        $obj = self::setInvoice($obj);

        $obj->CheckOut();
    }

    /**
     * WebATM
     *
     * @return void
     */
    public static function webATM()
    {
        $obj = self::setEnv();

        $orderId = self::$tradeNo ?? uniqid();

        $obj->Send['ReturnURL']         = self::$returnUrl;
        $obj->Send['MerchantTradeNo']   = $orderId;                     
        $obj->Send['MerchantTradeDate'] = self::$tradeDateTime; 
        $obj->Send['TotalAmount']       = self::$total;                
        $obj->Send['TradeDesc']         = self::$tradeDesc ?? $orderId;
        $obj->Send['ChoosePayment']     = 'WebATM';
        $obj->Send['Items']             = self::$items;

        $obj->Send['StoreID'] = self::$storeID;
        if (count(self::$customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $obj->Send['CustomField'.$i + 1] = self::$customFields[$i];
            }
        }

        $obj = self::setInvoice($obj);

        $obj->CheckOut();
    }

    /**
     * 設定環境
     *
     * @return AllInOne
     */
    private static function setEnv(): AllInOne
    {
        $obj = new AllInOne();

        $obj->ServiceURL = (config('ecpay.test_mode'))
            ? config('ecpay.dev_host')
            : config('ecpay.host');

        $obj->HashKey     = config('ecpay.hash_key');
        $obj->HashIV      = config('ecpay.hash_iv');
        $obj->MerchantID  = config('ecpay.merchant_id');
        $obj->EncryptType = config('ecpay.encrypt_type');

        
        if (config('ecpay.language')) $obj->Send['Language'] = config('ecpay.language');
        if (config('ecpay.client_back_url')) $obj->Send['ClientBackURL'] = config('ecpay.client_back_url');

        self::$tradeDateTime = date('Y/m/d H:i:s'); 
        self::$returnUrl = config('ecpay.return_url');

        return $obj;
    }
    
    /**
     * 電子發票參數
     *
     * @param AllInOne $obj
     * @return AllInOne
     */
    private static function setInvoice(AllInOne $obj): AllInOne
    {
        if (self::$invoice) {
            $obj->Send['InvoiceMark'] = self::$invoice->getInvoiceMark();

            $obj->SendExtend['RelateNumber']       = self::$invoice->getRelateNumber();
            $obj->SendExtend['CustomerIdentifier'] = self::$invoice->getIdentifier();
            $obj->SendExtend['CustomerName']       = self::$invoice->getName();
            $obj->SendExtend['CustomerEmail']      = self::$invoice->getEmail();
            $obj->SendExtend['CustomerPhone']      = self::$invoice->getPhone();
            $obj->SendExtend['TaxType']            = self::$invoice->getTaxType();
            $obj->SendExtend['CustomerAddr']       = self::$invoice->getAddress();
            $obj->SendExtend['InvoiceItems']       = self::$invoice->getItems();
            $obj->SendExtend['InvoiceRemark']      = self::$invoice->getRemark();
            $obj->SendExtend['ClearanceMark']      = self::$invoice->getClearanceMark();
            $obj->SendExtend['Donation']           = self::$invoice->getDonation();
            $obj->SendExtend['Print']              = self::$invoice->getPrint();
            $obj->SendExtend['LoveCode']           = self::$invoice->getLoveCode();
            $obj->SendExtend['CarruerType']        = self::$invoice->getCarruerType();
            $obj->SendExtend['CarruerNum']         = self::$invoice->getCarruerNum();
            $obj->SendExtend['DelayDay']           = self::$invoice->getDelayDay();
            $obj->SendExtend['InvType']            = self::$invoice->getInvType();
        }

        return $obj;
    }
}