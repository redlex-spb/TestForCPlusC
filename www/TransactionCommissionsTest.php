<?php

use PHPUnit\Framework\TestCase;

class TransactionCommissionsTest extends TestCase
{
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new TransactionCommissions();
    }

    protected function tearDown()
    {
        $this->fixture = null;
    }

    /**
     * @dataProvider providerData
     */

    public function testData($a, $b, $c, $d)
    {
        $this->assertEquals($d, $this->fixture->startCalculation($a, $b, $c));
    }

    public function providerData()
    {
        return array(
            array(45717360, 100.00, "EUR", 1),
            array(516793, 50.00, "USD", 0.5),
            array(45417360, 10000.00, "JPY", 1.69),
            array(41417360, 130.00, "USD", 2.6),
            array(4745030, 2000.00, "GBP", 40)
        );
    }
}