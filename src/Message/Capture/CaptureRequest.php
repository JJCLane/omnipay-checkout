<?php

namespace Omnipay\Checkout\Message\Capture;

use Omnipay\Checkout\Generic\AbstractRequest;

class CaptureRequest extends AbstractRequest
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

        return $this->response = new CaptureResponse($this, $response);
    }

    public function getEndpoint()
    {
        return sprintf('%s/payments/%s/captures',
            parent::getEndpoint(), $this->getTransactionId(),
        );
    }
}
