<?php

namespace Omnipay\Checkout\Message\CompletePurchase;

use GuzzleHttp\Psr7\Response;
use Omnipay\Checkout\Generic\AbstractRequest;
use Omnipay\Checkout\Message\CompletePurchase\CompletePurchaseResponse;

class CompletePurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        return [];
    }
    
    public function getHttpMethod()
    {
        return 'GET';
    }

    public function sendData($data)
    {
        $this->validate('transactionReference');
        $response = $this->sendRequest($data);
        return $this->response = new CompletePurchaseResponse($this, $response);
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments/' . $this->getTransactionReference();
    }
}
