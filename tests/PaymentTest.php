<?php

namespace Delta935142\Ecpay\Tests;

//use PHPUnit\Framework\TestCase;
use Delta935142\Ecpay\Payment;

class PaymentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * send credit
     *
     * @return void
     */
    public function test_credit()
    {
        $payment = new Payment();
        $result = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();

        $this->assertIsString($result);
    }

    /**
     * send ATM
     *
     * @return void
     */
    public function test_ATM()
    {
        $payment = new Payment();
        $result = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();

        $this->assertIsString($result);
    }

    /**
     * send webATM
     *
     * @return void
     */
    public function test_webAtm()
    {
        $payment = new Payment();
        $result = $payment->tradeNo('Test'.date('YmdHis'))
            ->total(100)
            ->items([['name' => "歐付寶黑芝麻豆漿", 'price' => 100, 'currency' => "元", 'quantity' => 1]])
            ->credit();

        $this->assertIsString($result);
    }
}