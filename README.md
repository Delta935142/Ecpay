# Laravel 綠界金流套件

## 安裝
安裝套件需要先安裝 Composer

#### Step 1 - 安裝套件
在專案的跟目錄下執行:

```shell
$ composer require delta935142/ecpay
```

#### Step 2 - 註冊服務
開啟 `config/app.php`, 並且在 providers 陣列中加入下列:

```php
Delta935142\Ecpay\EcpayServiceProvider::class,
```

#### Step 3 - 建立 config
執行下列指令

```shell
$ php artisan vendor:publish --provider="Delta935142\Ecpay\EcpayServiceProvider::class,"
```

#### Step 4 - 設定 .env
開啟 `.env`, 並且加入下面兩個環境變數

```env
ECPAY_HASH_KEY=
ECPAY_HASH_IV=
ECPAY_MERCHANT_ID=
```

## 使用方式

#### 信用卡

**Example:**

```php
<?php namespace Your\Namespace;

// ...

use Delta935142\Ecpay\Facades\Payment;

class YourClass
{
    public function yourMethod()
    {
        return Payment::tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();
    }
}
```

#### ATM

**Example:**

```php
<?php namespace Your\Namespace;

// ...

use Delta935142\Ecpay\Facades\Payment;

class YourClass
{
    public function yourMethod()
    {
        return Payment::tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->ATM();
    }
}
```

#### WebATM

**Example:**

```php
<?php namespace Your\Namespace;

// ...

use Delta935142\Ecpay\Facades\Payment;

class YourClass
{
    public function yourMethod()
    {
        return Payment::tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->WebATM();
    }
}
```

#### 方法

- `tradeNo(string $tradeNo)`: 交易代號。
- `tradeDateTime(string $datetime)`: 交易時間。
- `tradeDesc(string $text)`: 交易描述。
- `total(int $total)`: 總金額。
- `returnUrl(string $url)`: 回傳網址。
- `items(array $items)`: 項目。如上面範例。
- `storeID(string $id)`: 分店代號。
- `customFields(array $arr)`: 自訂欄位。
- `invoice(Invoice $invoice)`: 發票物件。