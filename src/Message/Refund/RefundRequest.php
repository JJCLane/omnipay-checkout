<?php

namespace Omnipay\Checkout\Message\Refund;

use Omnipay\Checkout\Generic\AbstractRequest;

class RefundRequest extends AbstractRequest
{
    /**
     * Return the data that is used as request body/content.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount');

        return [
            'amount' => $this->getAmountInteger(),
            'reference' => $this->getTransactionReference(),
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

        return $this->response = new RefundResponse($this, $response);
    }

    public function getEndpoint()
    {
        return sprintf('%s/payments/%s/refunds',
            parent::getEndpoint(), $this->getTransactionId(),
        );
    }
}
