<?php

namespace Omnipay\Checkout\Tests;

use Omnipay\Checkout\CheckoutGateway;
use Omnipay\Checkout\Message\Purchase\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;

class CheckoutGatewayTest extends GatewayTestCase
{
    public $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new CheckoutGateway($this->getHttpClient(), $this->getHttpRequest());
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
