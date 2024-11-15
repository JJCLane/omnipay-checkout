<?php

namespace Omnipay\Checkout\Tests\Message;

use Omnipay\Checkout\Message\Capture\CaptureRequest;
use Omnipay\Tests\TestCase;

class CaptureRequestTest extends TestCase
{
    public $request;

    public $data = [
        'amount' => '200.00',
        'currency' => 'bam',
        'description' => 'Example capture',
    ];

    public function setUp(): void
    {
        $this->request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize($this->data);
    }

    /** @test */
    public function test_passed_data_is_valid()
    {
        $content = (object) $this->request->getData();

        $this->assertEquals(20000, $content->amount);
        $this->assertEquals('BAM', $content->currency);
        $this->assertEquals('Example capture', $content->description);
    }

    /** @test */
    public function test_request_can_be_send_succesfully()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('act_y3oqhf46pyzuxjbcn2giaqnb44', $response->getTransactionReference());
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
