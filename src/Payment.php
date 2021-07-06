<?php

namespace Delta935142\Ecpay;

use Illuminate\Support\Collection;
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
            ? config('ecpay.payment_host.test')
            : config('ecpay.payment_host.production');

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
     * @return Collection
     */
    public function credit(int $installment = 0, int $amount = 0, bool $redeem = false, bool $unionPay = false): Collection
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

        try {
            $html = $this->obj->CheckOut();
        } catch (\Exception $e) {
            return collect(['success' => false, 'content' => $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => $html]);
    }

    /**
     * ATM
     *
     * @param integer $expire 繳費期限(天)
     * @return Collection
     */
    public function ATM(int $expire = 3): Collection
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

        $this->obj->SendExtend['ClientRedirectURL'] = config('client_redirect_url');
        $this->obj->SendExtend['ExpireDate']        = $expire;
        $this->obj->SendExtend['PaymentInfoURL']    = config('ecpay.payment_info_url');

        $this->setInvoice();

        try {
            $html = $this->obj->CheckOut();
        } catch (\Exception $e) {
            return collect(['success' => false, 'content' => $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => $html]);
    }

    /**
     * WebATM
     *
     * @return Collection
     */
    public function webATM(): Collection
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

        try {
            $html = $this->obj->CheckOut();
        } catch (\Exception $e) {
            return collect(['success' => false, 'content' => $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => $html]);
    }

    /**
     * CVS
     *
     * @param integer $expire 繳費期限(天)
     * @return Collection
     */
    public function CVS(int $expire = 7): Collection
    {
        $orderId = $this->tradeNo ?? uniqid();

        $this->obj->Send['ReturnURL']         = $this->returnUrl;
        $this->obj->Send['MerchantTradeNo']   = $orderId;                     
        $this->obj->Send['MerchantTradeDate'] = $this->tradeDateTime; 
        $this->obj->Send['TotalAmount']       = $this->total;                
        $this->obj->Send['TradeDesc']         = $this->tradeDesc ?? $orderId;
        $this->obj->Send['ChoosePayment']     = 'CVS';
        $this->obj->Send['Items']             = $this->items;

        if (count($this->customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $this->obj->Send['Desc_'.(string) ($i + 1)] = $this->customFields[$i];
            }
        }
        $this->obj->SendExtend['PaymentInfoURL']    = config('ecpay.payment_info_url');
        $this->obj->SendExtend['ClientRedirectURL'] = config('client_redirect_url');
        $this->obj->SendExtend['StoreExpireDate']   = $expire;

        $this->setInvoice();

        try {
            $html = $this->obj->CheckOut();
        } catch (\Exception $e) {
            return collect(['success' => false, 'content' => $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => $html]);
    }

    /**
     * Barcode
     *
     * @param integer $expire 繳費期限(天)
     * @return Collection
     */
    public function barcode(int $expire = 7): Collection
    {
        $orderId = $this->tradeNo ?? uniqid();

        $this->obj->Send['ReturnURL']         = $this->returnUrl;
        $this->obj->Send['MerchantTradeNo']   = $orderId;                     
        $this->obj->Send['MerchantTradeDate'] = $this->tradeDateTime; 
        $this->obj->Send['TotalAmount']       = $this->total;                
        $this->obj->Send['TradeDesc']         = $this->tradeDesc ?? $orderId;
        $this->obj->Send['ChoosePayment']     = 'BARCODE';
        $this->obj->Send['Items']             = $this->items;

        if (count($this->customFields)) {
            for ($i = 0; $i < 4; $i++) {
                $this->obj->Send['Desc_'.(string) ($i + 1)] = $this->customFields[$i];
            }
        }
        $this->obj->SendExtend['PaymentInfoURL']    = config('ecpay.payment_info_url');
        $this->obj->SendExtend['ClientRedirectURL'] = config('client_redirect_url');
        $this->obj->SendExtend['StoreExpireDate']   = $expire;

        $this->setInvoice();

        try {
            $html = $this->obj->CheckOut();
        } catch (\Exception $e) {
            return collect(['success' => false, 'content' => $e->getMessage()]);
        }

        return collect(['success' => true, 'content' => $html]);
    }
    
    /**
     * 電子發票參數
     *
     * @return void
     */
    private function setInvoice()
    {
        if ($this->invoiceObj) {
            $this->obj->Send['InvoiceMark'] = $this->invoiceObj->getInvoiceMark();

            $this->obj->SendExtend['RelateNumber']       = $this->invoiceObj->getRelateNumber();
            $this->obj->SendExtend['CustomerIdentifier'] = $this->invoiceObj->getIdentifier();
            $this->obj->SendExtend['CustomerName']       = $this->invoiceObj->getName();
            $this->obj->SendExtend['CustomerEmail']      = $this->invoiceObj->getEmail();
            $this->obj->SendExtend['CustomerPhone']      = $this->invoiceObj->getPhone();
            $this->obj->SendExtend['TaxType']            = $this->invoiceObj->getTaxType();
            $this->obj->SendExtend['CustomerAddr']       = $this->invoiceObj->getAddress();
            $this->obj->SendExtend['InvoiceItems']       = $this->invoiceObj->getItems();
            $this->obj->SendExtend['InvoiceRemark']      = $this->invoiceObj->getRemark();
            $this->obj->SendExtend['ClearanceMark']      = $this->invoiceObj->getClearanceMark();

            if (!$this->invoiceObj->getIdentifier())
                $this->obj->SendExtend['Donation'] = $this->invoiceObj->getDonation();

            $this->obj->SendExtend['Print']       = $this->invoiceObj->getPrint();
            $this->obj->SendExtend['LoveCode']    = $this->invoiceObj->getLoveCode();
            $this->obj->SendExtend['CarruerType'] = $this->invoiceObj->getCarruerType();
            $this->obj->SendExtend['CarruerNum']  = $this->invoiceObj->getCarruerNum();
            $this->obj->SendExtend['DelayDay']    = $this->invoiceObj->getDelayDay();
            $this->obj->SendExtend['InvType']     = $this->invoiceObj->getInvType();
        }
    }
}