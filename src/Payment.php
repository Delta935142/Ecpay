<?php

namespace Delta935142\Ecpay;

Class Payment extends \Delta935142\Ecpay\Requests\Payment
{
    /**
     * 設定訂單編號
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
     * 設定交易時間
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
     * 設定交易描述
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
     * 設定總金額
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
     * 設定回傳網址
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
     * 加入品項
     * 
     * ['Name' => "歐付寶黑芝麻豆漿", 'Price' => 2000, 'Currency' => "元", 'Quantity' => 1, 'URL' => "dedwed"]
     *
     * @param array $item
     * @return Payment
     */
    public static function item(array $item): Payment
    {
        $row = [
            'Name'     => $item['name'],
            'Price'    => $item['price'],
            'Currency' => $item['currency'],
            'Quantity' => $item['quantity']
        ];

        if (isset($item['url'])) $row['URL'] = $item['url'];

        array_push(self::$items, $row);

        return new self();
    }

    /**
     * 設定品項
     *
     * [['Name' => "歐付寶黑芝麻豆漿", 'Price' => 2000, 'Currency' => "元", 'Quantity' => 1, 'URL' => "dedwed"]]
     * 
     * @param array $items
     * @return Payment
     */
    public static function items(array $items): Payment
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

        self::$items = $arr;

        return new self();
    }

    /**
     * 設定分店ID
     *
     * @param string $id
     * @return Payment
     */
    public static function storeID(string $id): Payment
    {
        self::$storeID = $id;

        return new self();
    }

    /**
     * 設定自訂欄位
     *
     * @param array $arr
     * @return Payment
     */
    public static function customFields(array $arr): Payment
    {
        self::$customFields = $arr;

        return new self();
    }

    /**
     * 設定發票
     *
     * @param Invoice $invoice
     * @return Payment
     */
    public static function invoice(Invoice $invoice): Payment
    {
        self::$invoice = $invoice;

        return new self();
    }
}

