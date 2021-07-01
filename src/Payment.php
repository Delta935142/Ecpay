<?php

namespace Delta935142\Ecpay;

use Delta935142\Ecpay\SDK\AllInOne;
use Delta935142\Ecpay\SDK\Abstracts\InvoiceState;
use Delta935142\Ecpay\SDK\Abstracts\TaxType;
use Delta935142\Ecpay\SDK\Abstracts\InvType;

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
     * set trade no
     *
     * @param string $tradeNo
     * @return Payment
     */
    public static function tradeNo(string $tradeNo): Payment
    {
        self::$tradeNo = $tradeNo;

        return new self();
    }

    /**
     * set trade datetime
     *
     * @param string $datetime
     * @return Payment
     */
    public static function tradeDateTime(string $datetime): Payment
    {
        self::$tradeDateTime = date('Y/m/d H:i:s', strtotime($datetime));

        return new self();
    }

    /**
     * set trade description
     *
     * @param string $text
     * @return Payment
     */
    public static function tradeDesc(string $text): Payment
    {
        self::$tradeDesc = $text;

        return new self();
    } 

    /**
     * set total amount
     *
     * @param integer $total
     * @return Payment
     */
    public static function total(int $total): Payment
    {
        self::$total = $total;

        return new self();
    }
    
    /**
     * set return url
     *
     * @param string $url
     * @return Payment
     */
    public static function returnUrl(string $url): Payment
    {
        self::$returnUrl = $url;

        return new self();
    }

    /**
     * add item
     * 
     * ['Name' => "歐付寶黑芝麻豆漿", 'Price' => 2000, 'Currency' => "元", 'Quantity' => 1, 'URL' => "dedwed"]
     *
     * @param array $item
     * @return Payment
     */
    public static function item(array $item): Payment
    {
        array_push(self::$items, $item);

        return new self();
    }

    /**
     * set items
     *
     * [['Name' => "歐付寶黑芝麻豆漿", 'Price' => 2000, 'Currency' => "元", 'Quantity' => 1, 'URL' => "dedwed"]]
     * 
     * @param array $items
     * @return Payment
     */
    public static function items(array $items): Payment
    {
        self::$items = $items;

        return new self();
    }

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
        $obj = new AllInOne();

        $obj->ServiceURL = (config('ecpay.test_mode'))
            ? config('ecpay.dev_host')
            : config('ecpay.host');

        $obj->HashKey     = config('ecpay.hash_key');
        $obj->HashIV      = config('ecpay.hash_iv');
        $obj->MerchantID  = config('ecpay.merchant_id');
        $obj->EncryptType = config('ecpay.encrypt_type');

        $orderId = self::$tradeNo ?? uniqid();

        $obj->Send['MerchantTradeNo']   = $orderId;
        $obj->Send['MerchantTradeDate'] = self::$tradeDateTime ?? date('Y/m/d H:i:s');
        $obj->Send['TradeDesc']         = self::$tradeDesc ?? $orderId;
        $obj->Send['TotalAmount']       = self::$total;
        $obj->Send['ReturnURL']         = self::$returnUrl ?? config('ecpay.return_url');
        $obj->Send['ChoosePayment']     = 'Credit';
        $obj->Send['IgnorePayment']     = 'GooglePay';
        $obj->Send['Items']             = self::$items;

        $obj->SendExtend['CreditInstallment'] = $installment;
        $obj->SendExtend['InstallmentAmount'] = $amount;
        $obj->SendExtend['Redeem']            = $redeem;
        $obj->SendExtend['UnionPay']          = $unionPay;

        # 電子發票參數
        /*
        $obj->Send['InvoiceMark'] = InvoiceState::Yes;
        $obj->SendExtend['RelateNumber'] = "Test".time();
        $obj->SendExtend['CustomerEmail'] = 'test@ecpay.com.tw';
        $obj->SendExtend['CustomerPhone'] = '0911222333';
        $obj->SendExtend['TaxType'] = TaxType::Dutiable;
        $obj->SendExtend['CustomerAddr'] = '台北市南港區三重路19-2號5樓D棟';
        $obj->SendExtend['InvoiceItems'] = array();
        // 將商品加入電子發票商品列表陣列
        foreach ($obj->Send['Items'] as $info) {
            array_push($obj->SendExtend['InvoiceItems'],array('Name' => $info['Name'],'Count' =>
                $info['Quantity'],'Word' => '個','Price' => $info['Price'],'TaxType' => TaxType::Dutiable));
        }
        $obj->SendExtend['InvoiceRemark'] = '測試發票備註';
        $obj->SendExtend['DelayDay'] = '0';
        $obj->SendExtend['InvType'] = InvType::General;
        */

        //產生訂單(auto submit至ECPay)
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
        $obj = new AllInOne();

        $obj->ServiceURL = (config('ecpay.test_mode'))
            ? config('ecpay.dev_host')
            : config('ecpay.host');

        $obj->HashKey     = config('ecpay.hash_key');
        $obj->HashIV      = config('ecpay.hash_iv');
        $obj->MerchantID  = config('ecpay.merchant_id');
        $obj->EncryptType = config('ecpay.encrypt_type');

        $orderId = self::$tradeNo ?? uniqid();

        $obj->Send['MerchantTradeNo']   = $orderId;
        $obj->Send['MerchantTradeDate'] = self::$tradeDateTime ?? date('Y/m/d H:i:s');
        $obj->Send['TradeDesc']         = self::$tradeDesc ?? $orderId;
        $obj->Send['TotalAmount']       = self::$total;
        $obj->Send['ReturnURL']         = self::$returnUrl ?? config('ecpay.return_url');
        $obj->Send['ChoosePayment']     = 'ATM';
        $obj->Send['Items']             = self::$items;

        $obj->SendExtend['ExpireDate']     = $expire;
        $obj->SendExtend['PaymentInfoURL'] = "";            //伺服器端回傳付款相關資訊。

        $obj->CheckOut();
    }

    /**
     * WebATM
     *
     * @return void
     */
    public function webATM()
    {
        $obj = new AllInOne();

        $obj->ServiceURL = (config('ecpay.test_mode'))
            ? config('ecpay.dev_host')
            : config('ecpay.host');

        $obj->HashKey     = config('ecpay.hash_key');
        $obj->HashIV      = config('ecpay.hash_iv');
        $obj->MerchantID  = config('ecpay.merchant_id');
        $obj->EncryptType = config('ecpay.encrypt_type');

        $orderId = self::$tradeNo ?? uniqid();

        $obj->Send['ReturnURL']         = self::$returnUrl ?? config('ecpay.return_url');
        $obj->Send['MerchantTradeNo']   = $orderId;                     
        $obj->Send['MerchantTradeDate'] = self::$tradeDateTime ?? date('Y/m/d H:i:s'); 
        $obj->Send['TotalAmount']       = self::$total;                
        $obj->Send['TradeDesc']         = self::$tradeDesc ?? $orderId;
        $obj->Send['ChoosePayment']     = 'WebATM';
        $obj->Send['Items']             = self::$items;

        $obj->CheckOut();
    }
}

