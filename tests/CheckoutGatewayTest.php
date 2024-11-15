<?php

namespace Omnipay\Checkout\Tests;

use Omnipay\Checkout\Gateway;
use Omnipay\Checkout\Message\Purchase\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;

class CheckoutGatewayTest extends GatewayTestCase
{
    public $gateway;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    /** @test */
    public function test_purchase_instance()
    {
        $request = $this->gateway->purchase([
            'amount' => '20.00',
        ]);

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertEquals('20.00', $request->getAmount());
    }
}
