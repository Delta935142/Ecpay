<?php

namespace Delta935142\Ecpay\Tests;

//use PHPUnit\Framework\TestCase;
use Delta935142\Ecpay\Trade;

class TradeTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * query test
     *
     * @return void
     */
    public function test_query()
    {
        $no = 'Test20210706092610';
        $response = (new Trade())->query($no);

        $this->assertEquals(true, $response['success']);
    }
}