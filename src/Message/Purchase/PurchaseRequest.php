<?php

namespace Omnipay\Checkout\Message\Purchase;

use GuzzleHttp\Psr7\Response;
use Omnipay\Checkout\Generic\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    /**
     * Return the data that is used as request body/content.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'currency');
        return [
            'source' => ['type' => 'token', 'token' => $this->getToken()],
            'amount' => $this->getAmountInteger(),
            'currency' => strtoupper($this->getCurrency()),
            'description' => $this->getDescription(),
            'metadata' => $this->getMetaData(),
            'reference' => $this->getTransactionId(),
            'payment_ip' => $this->getClientIp(),
            '3ds' => $this->get3DS(),
        ];
    }
    
    public function get3DS() {
        return $this->getParameter('3DS');
    }

    public function set3DS($value)
    {
        return $this->setParameter('3DS', $value);
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function sendData($data)
    {
        $response = $this->sendRequest($data);
        return $this->response = new PurchaseResponse($this, $response);
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments';
    }
}
