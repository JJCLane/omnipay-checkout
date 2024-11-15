<?php

namespace Omnipay\Checkout\Tests\Message;

use Omnipay\Checkout\Message\Purchase\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public $request;

    public $data = [
        'amount' => '200.00',
        'currency' => 'bam',
        'description' => 'Example purchase',
        'source' => []
    ];

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->data);
    }

    /** @test */
    public function test_passed_data_is_valid()
    {
        $content = (object) $this->request->getData();

        $this->assertEquals(20000, $content->amount);
        $this->assertEquals('BAM', $content->currency);
        $this->assertEquals('Example purchase', $content->description);
    }

    /** @test */
    public function test_request_can_be_send_succesfully()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('pay_mbabizu24mvu3mela5njyhpit4', $response->getTransactionReference());
    }

    /** @test */
    public function test_request_can_fail()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());

        $errors = $response->getMessage();

        $this->assertEquals('payment_source_required', $errors);

    }
}
