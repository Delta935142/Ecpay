<?php

namespace Delta935142\Ecpay;

use Delta935142\Ecpay\SDK\Abstracts\InvoiceState;
use Delta935142\Ecpay\SDK\Abstracts\TaxType;
use Delta935142\Ecpay\SDK\Abstracts\InvType;

Class Invoice
{
    protected $invoiceMark;
    protected $relateNumber;
    protected $name;
    protected $identifier;
    protected $email;
    protected $phone;
    protected $taxType;
    protected $address;
    protected $items = [];
    protected $remark;
    protected $invType;
    protected $carruerType;
    protected $carruerNum;
    protected $donation = '0';
    protected $loveCode;
    protected $print = '1';
    protected $delayDay = 0;

    public function __construct() 
    {
        $this->invoiceMark = InvoiceState::Yes;
        $this->taxType     = TaxType::Dutiable;
        $this->invType     = InvType::General;

        $this->relateNumber = uniqid();
    }

    /**
     * 唯一編號
     *
     * @param string $string
     * @return Invoice
     */
    public function relateNumber(string $string): Invoice
    {
        $this->relateNumber = $string;

        return $this;
    }

    /**
     * 名稱
     *
     * @param string $name
     * @return Invoice
     */
    public function name(string $name): Invoice
    {
        $this->name = $name;

        return $this;
    }

    /**
     * 統一編號
     *
     * @param string $identifier
     * @return Invoice
     */
    public function identifier(string $identifier): Invoice
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Email
     *
     * @param string $email
     * @return Invoice
     */
    public function email(string $email): Invoice
    {
        $this->email = $email;

        return $this;
    }

    /**
     * 電話
     *
     * @param string $phone
     * @return Invoice
     */
    public function phone(string $phone): Invoice
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * 地址
     *
     * @param string $address
     * @return Invoice
     */
    public function address(string $address): Invoice
    {
        $this->address = $address;

        return $this;
    }

    /**
     * 稅率
     * 1: 應稅 2: 零稅率 3: 免稅
     *
     * @param string $tax
     * @return Invoice
     */
    public function taxType(string $tax): Invoice
    {
        $this->taxType = $tax;

        return $this;
    }

    /**
     * 備註
     *
     * @param string $remark
     * @return Invoice
     */
    public function remark(string $remark): Invoice
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * 載具類別
     * 1.特店載具 2.自然人憑證 3.手機條碼
     *
     * @param string $type
     * @return Invoice
     */
    public function carruerType(string $type): Invoice
    {
        $this->carruerType = $type;

        return $this;
    }

    /**
     * 載具編號
     *
     * @param string $string
     * @return Invoice
     */
    public function carruerNum(string $string): Invoice
    {
        $this->carruerNum = $string;

        return $this;
    }

    /**
     * 捐贈
     *
     * @param string $code
     * @return Invoice
     */
    public function donation(string $code): Invoice
    {
        $this->donation = '1';
        $this->loveCode = $code;
        $this->print = '0';

        return $this;
    }

    /**
     * 延遲日
     *
     * @param integer $days
     * @return Invoice
     */
    public function delayDays(int $days): Invoice
    {
        $this->delayDay = $days;

        return $this;
    }

    /**
     * 品項
     *
     * @param array $items
     * @return Invoice
     */
    public function items(array $items): Invoice
    {
        foreach ($items as $item) {
            $this->items[] = [
                'Name'    => $item['name'],
                'Count'   => $item['quantity'],
                'Word'    => $item['word'],
                'Price'   => $item['price'],
                'TaxType' => $this->taxType
            ];
        }

        return $this;
    }

    /**
     * InvoiceMark
     *
     * @return string
     */
    public function getInvoiceMark(): string
    {
        return $this->invoiceMark;
    }

    /**
     * 取得唯一編碼
     *
     * @return string
     */
    public function getRelateNumber(): string
    {
        return $this->relateNumber;
    }

    /**
     * 取得名稱
     *
     * @return string|null
     */
    public function getName():? string
    {
        return $this->name;
    }

    /**
     * 取得Email
     *
     * @return string|null
     */
    public function getEmail():? string
    {
        return $this->email;
    }

    /**
     * 取得電話
     *
     * @return string|null
     */
    public function getPhone():? string
    {
        return $this->phone;
    }

    /**
     * 取得地址
     *
     * @return string|null
     */
    public function getAddress():? string
    {
        return $this->address;
    }

    /**
     * 取得統一編號
     *
     * @return string|null
     */
    public function getIdentifier():? string
    {
        return $this->identifier;
    }

    /**
     * 取得備註
     *
     * @return string|null
     */
    public function getRemark():? string
    {
        return $this->remark;
    }

    /**
     * 取得品項
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * 取得稅額
     *
     * @return string
     */
    public function getTaxType(): string
    {
        return $this->taxType;
    }

    /**
     * 取得通關方式
     *
     * @return string|null
     */
    public function getClearanceMark():? string
    {
        return $this->taxType == '2'? '1' : null;
    }

    /**
     * 取得載具類別
     *
     * @return string|null
     */
    public function getCarruerType():? string
    {
        if ($this->print == '1') {
            $this->carruerType = null;
        } else {
            if ($this->identifier)
                $this->carruerType = $this->carruerType ? '3' : null;
        }

        return $this->carruerType;
    }

    /**
     * 取得載具編號
     *
     * @return string|null
     */
    public function getCarruerNum():? string
    {
        if (!$this->carruerType || $this->carruerType == '1') 
            $this->carruerNum = null;

        return $this->carruerNum;
    }
    
    /**
     * 取得是否捐贈
     *
     * @return string
     */
    public function getDonation(): string
    {
        if ($this->identifier) {
            $this->donation = '0';
            $this->print = '1';
            $this->loveCode = null;
        }

        return $this->donation;
    }

    /**
     * 取得列印
     *
     * @return string|null
     */
    public function getPrint():? string
    {
        return $this->print;
    }

    /**
     * 取得愛心碼
     *
     * @return string|null
     */
    public function getLoveCode():? string
    {
        return $this->loveCode;
    }

    /**
     * 取得字軌類別
     *
     * @return string
     */
    public function getInvType(): string
    {
        return $this->invType;
    }

    /**
     * 取得延遲日
     *
     * @return integer
     */
    public function getDelayDay(): int
    {
        return $this->delayDay;
    }
}