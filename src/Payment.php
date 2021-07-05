<?php

namespace Delta935142\Ecpay;

use Delta935142\Ecpay\SDK\AllInOne;

Class Payment
{
    /**
     * 訂單編號
     *
     * @var string
     */
    protected $tradeNo = '';

    /**
     * 交易時間
     *
     * @var string
     */
    protected $tradeDateTime;

    /**
     * 交易描述
     *
     * @var string
     */
    protected $tradeDesc;

    /**
     * 交易金額
     *
     * @var integer
     */
    protected $total = 0;

    /**
     * 回傳網址
     *
     * @var string
     */
    protected $returnUrl;

    /**
     * 商品
     *
     * @var array
     */
    protected $items = [];

    /**
     * 分店代號
     *
     * @var string
     */
    protected $storeID;

    /**
     * 自訂欄位
     *
     * @var array
     */
    protected $customFields = [];

    /**
     * 發票物件
     *
     * @var Invoice
     */
    protected $invoiceObj;

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
            ? config('ecpay.dev_host')
            : config('ecpay.host');

        $this->obj->HashKey     = config('ecpay.hash_key');
        $this->obj->HashIV      = config('ecpay.hash_iv');
        $this->obj->MerchantID  = config('ecpay.merchant_id');
        $this->obj->EncryptType = config('ecpay.encrypt_type');

        if (config('ecpay.language')) $this->obj->Send['Language'] = config('ecpay.language');
        if (config('ecpay.client_back_url')) $this->obj->Send['ClientBackURL'] = config('ecpay.client_back_url');

        $this->tradeDateTime = date('Y/m/d H:i:s'); 
        $this->returnUrl = config('ecpay.return_url');
    }

    /**
     * 設定訂單編號
     *
     * @param string $tradeNo
     * @return Payment
     */
    public function tradeNo(string $tradeNo): Payment
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    /**
     * 設定交易時間
     *
     * @param string $datetime
     * @return Payment
     */
    public function tradeDateTime(string $datetime): Payment
    {
        $this->tradeDateTime = date('Y/m/d H:i:s', strtotime($datetime));

        return $this;
    }

    /**
     * 設定交易描述
     *
     * @param string $text
     * @return Payment
     */
    public function tradeDesc(string $text): Payment
    {
        $this->tradeDesc = $text;

        return $this;
    } 

    /**
     * 設定總金額
     *
     * @param integer $total
     * @return Payment
     */
    public function total(int $total): Payment
    {
        $this->total = $total;

        return $this;
    }
    
    /**
     * 設定回傳網址
     *
     * @param string $url
     * @return Payment
     */
    public function returnUrl(string $url): Payment
    {
        $this->returnUrl = $url;

        return $this;
    }

    /**
     * 加入品項
     * 
     * ['Name' => "歐付寶黑芝麻豆漿", 'Price' => 2000, 'Currency' => "元", 'Quantity' => 1, 'URL' => "dedwed"]
     *
     * @param array $item
     * @return Payment
     */
    public function item(array $item): Payment
    {
        $row = [
            'Name'     => $item['name'],
            'Price'    => $item['price'],
            'Currency' => $item['currency'],
            'Quantity' => $item['quantity']
        ];

        if (isset($item['url'])) $row['URL'] = $item['url'];

        array_push($this->items, $row);

        return $this;
    }

    /**
     * 設定品項
     *
     * [['Name' => "歐付寶黑芝麻豆漿", 'Price' => 2000, 'Currency' => "元", 'Quantity' => 1, 'URL' => "dedwed"]]
     * 
     * @param array $items
     * @return Payment
     */
    public function items(array $items): Payment
    {
        $arr = [];

        foreach ($items as $item) {
            $arr[] = [
                'Name'     => $item['name'],
                'Price'    => $item['price'],
                'Currency' => $item['currency'],
                'Quantity' => $item['quantity']
            ];

            if (isset($item['url'])) $arr[]['URL'] = $item['url'];
        }

        $this->items = $arr;

        return $this;
    }

    /**
     * 設定分店ID
     *
     * @param string $id
     * @return Payment
     */
    public function storeID(string $id): Payment
    {
        $this->storeID = $id;

        return $this;
    }

    /**
     * 設定自訂欄位
     *
     * @param array $arr
     * @return Payment
     */
    public function customFields(array $arr): Payment
    {
        $this->customFields = $arr;

        return $this;
    }

    /**
     * 設定發票
     *
     * @param Invoice $invoice
     * @return Payment
     */
    public function invoice(Invoice $invoice): Payment
    {
        $this->invoiceObj = $invoice;

        return $this;
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
    public function credit(int $installment = 0, int $amount = 0, bool $redeem = false, bool $unionPay = false)
    {
        $orderId = $this->tradeNo ?? uniqid();

        $this->obj->Send['MerchantTradeNo']   = $orderId;
        $this->obj->Send['MerchantTradeDate'] = $this->tradeDateTime;
        $this->obj->Send['TradeDesc']         = $this->tradeDesc ?? $orderId;
        $this->obj->Send['TotalAmount']       = $this->total;
        $this->obj->Send['ReturnURL']         = $this->returnUrl;
        $this->obj->Send['ChoosePayment']     = 'Credit';
        $this->obj->Send['IgnorePayment']     = 'GooglePay';
        $this->obj->Send['Items']             = $this->items;

        $this->obj->Send['StoreID'] = $this->storeID;
        if (count($this->customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $this->obj->Send['CustomField'.(string) ($i + 1)] = $this->customFields[$i];
            }
        }

        $this->obj->SendExtend['CreditInstallment'] = $installment;
        $this->obj->SendExtend['InstallmentAmount'] = $amount;
        $this->obj->SendExtend['Redeem']            = $redeem;
        $this->obj->SendExtend['UnionPay']          = $unionPay;

        $this->setInvoice();

        return $this->obj->CheckOut();
    }

    /**
     * ATM
     *
     * @param integer $expire 繳費期限(天)
     * @return void
     */
    public function ATM(int $expire = 3)
    {
        $orderId = $this->tradeNo ?? uniqid();

        $this->obj->Send['MerchantTradeNo']   = $orderId;
        $this->obj->Send['MerchantTradeDate'] = $this->tradeDateTime;
        $this->obj->Send['TradeDesc']         = $this->tradeDesc ?? $orderId;
        $this->obj->Send['TotalAmount']       = $this->total;
        $this->obj->Send['ReturnURL']         = $this->returnUrl;
        $this->obj->Send['ChoosePayment']     = 'ATM';
        $this->obj->Send['Items']             = $this->items;

        $this->obj->Send['StoreID'] = $this->storeID;
        if (count($this->customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $this->obj->Send['CustomField'.(string) ($i + 1)] = $this->customFields[$i];
            }
        }

        $this->obj->SendExtend['ExpireDate']     = $expire;
        $this->obj->SendExtend['PaymentInfoURL'] = "";

        $this->setInvoice();

        return $this->obj->CheckOut();
    }

    /**
     * WebATM
     *
     * @return void
     */
    public function webATM()
    {
        $orderId = $this->tradeNo ?? uniqid();

        $this->obj->Send['ReturnURL']         = $this->returnUrl;
        $this->obj->Send['MerchantTradeNo']   = $orderId;                     
        $this->obj->Send['MerchantTradeDate'] = $this->tradeDateTime; 
        $this->obj->Send['TotalAmount']       = $this->total;                
        $this->obj->Send['TradeDesc']         = $this->tradeDesc ?? $orderId;
        $this->obj->Send['ChoosePayment']     = 'WebATM';
        $this->obj->Send['Items']             = $this->items;

        $this->obj->Send['StoreID'] = $this->storeID;
        if (count($this->customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $this->obj->Send['CustomField'.(string) ($i + 1)] = $this->customFields[$i];
            }
        }

        $this->setInvoice();

        return $this->obj->CheckOut();
    }
    
    /**
     * 電子發票參數
     *
     * @return void
     */
    private function setInvoice()
    {
        if ($this->invoiceObj) {
            $this->obj->Send['InvoiceMark'] = $this->invoice->getInvoiceMark();

            $this->obj->SendExtend['RelateNumber']       = $this->invoice->getRelateNumber();
            $this->obj->SendExtend['CustomerIdentifier'] = $this->invoice->getIdentifier();
            $this->obj->SendExtend['CustomerName']       = $this->invoice->getName();
            $this->obj->SendExtend['CustomerEmail']      = $this->invoice->getEmail();
            $this->obj->SendExtend['CustomerPhone']      = $this->invoice->getPhone();
            $this->obj->SendExtend['TaxType']            = $this->invoice->getTaxType();
            $this->obj->SendExtend['CustomerAddr']       = $this->invoice->getAddress();
            $this->obj->SendExtend['InvoiceItems']       = $this->invoice->getItems();
            $this->obj->SendExtend['InvoiceRemark']      = $this->invoice->getRemark();
            $this->obj->SendExtend['ClearanceMark']      = $this->invoice->getClearanceMark();
            $this->obj->SendExtend['Donation']           = $this->invoice->getDonation();
            $this->obj->SendExtend['Print']              = $this->invoice->getPrint();
            $this->obj->SendExtend['LoveCode']           = $this->invoice->getLoveCode();
            $this->obj->SendExtend['CarruerType']        = $this->invoice->getCarruerType();
            $this->obj->SendExtend['CarruerNum']         = $this->invoice->getCarruerNum();
            $this->obj->SendExtend['DelayDay']           = $this->invoice->getDelayDay();
            $this->obj->SendExtend['InvType']            = $this->invoice->getInvType();
        }
    }
}