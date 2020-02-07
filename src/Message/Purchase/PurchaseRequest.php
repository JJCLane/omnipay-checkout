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
            'amount' => $this->getAmountInteger(),
            'currency' => strtoupper($this->getCurrency()),
            'description' => $this->getDescription(),
            'metadata' => $this->getMetaData(),
        ];
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
