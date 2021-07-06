<?php

namespace Delta935142\Ecpay\Tests;

//use PHPUnit\Framework\TestCase;
use Delta935142\Ecpay\Payment;
use Delta935142\Ecpay\Invoice;

class PaymentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * credit test
     *
     * @return void
     */
    public function test_credit()
    {
        $payment = new Payment();
        $response = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();

        $this->assertEquals(true, $response->get('success'));
    }

    /**
     * ATM test
     *
     * @return void
     */
    public function test_ATM()
    {
        $payment = new Payment();
        $response = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();

        $this->assertEquals(true, $response->get('success'));
    }

    /**
     * webATM test
     *
     * @return void
     */
    public function test_webAtm()
    {
        $payment = new Payment();
        $response = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();

        $this->assertEquals(true, $response->get('success'));
    }

    /**
     * CVS test
     *
     * @return void
     */
    public function test_cvs()
    {
        $payment = new Payment();
        $response = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->CVS();

        $this->assertEquals(true, $response->get('success'));
    }

    /**
     * barcode test
     *
     * @return void
     */
    public function test_barcode()
    {
        $payment = new Payment();
        $response = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->barcode();

        $this->assertEquals(true, $response->get('success'));
    }

    /**
     * invoice test
     *
     * @return void
     */
    public function test_invoice()
    {
        $this->assertIsObject($this->invoice());
    }

    /**
     * invoice in payment test
     *
     * @return void
     */
    public function test_invoiceInPayment()
    {
        $invoice = $this->invoice();

        $payment = new Payment();
        $response = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->invoice($invoice)
            ->credit();

        $this->assertEquals(true, $response->get('success'));
    }

    /**
     * invoice
     *
     * @return Invoice
     */
    private function invoice(): Invoice
    {
        $invoice = new Invoice();

        return $invoice->name('xxx')
            ->identifier('12345678')
            ->email('test@gmail.com')
            ->phone('0212345678')
            ->address('xxxxxxxxxx')
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'word' => "個", 'quantity' => 1]])
            ->remark('xxxxxxxx')
            ->carruerType('3')
            ->carruerNum('/aaasssss');
    }
}