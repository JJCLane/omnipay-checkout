<?php

namespace Omnipay\Checkout\Tests\Message;

use Omnipay\Tests\TestCase;
use Mockery;
use Omnipay\Checkout\Generic\AbstractRequest;

class AbstractRequestTest extends TestCase
{
    public $request;

    public function setUp(): void
    {
        $this->request = Mockery::mock(AbstractRequest::class)->makePartial();
        $this->request->initialize();
    }

    /** @test */
    public function test_meta_data_is_valid()
    {
        $values_1 = $this->request->setMetaData([
            'example' => 'value',
        ]);

        $this->assertEquals($this->request, $values_1);

        $values_2 = $this->request->setMetaData([
            'example2' => 'value2',
        ]);

        $this->assertEquals($this->request, $values_2);
    }
}