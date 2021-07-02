<?php

namespace Delta935142\Ecpay;

Class Invoice extends \Delta935142\Ecpay\Requests\Invoice
{
    public function __construct(array $items, int $delayDay = 0) 
    {
        parent::__construct();

        $this->relateNumber = uniqid();
        $this->delayDay     = $delayDay;
        $this->items        = $this->items($items);
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
     * 品項
     *
     * @param array $items
     * @return array
     */
    private function items(array $items): array
    {
        $arr = [];

        foreach ($items as $item) {
            $arr[] = [
                'Name'    => $item['name'],
                'Count'   => $item['quantity'],
                'Word'    => $item['word'],
                'Price'   => $item['price'],
                'TaxType' => $this->taxType
            ];
        }

        return $arr;
    }
}