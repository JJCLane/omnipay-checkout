<?php

namespace Omnipay\Checkout\Message\PaymentSession;

use Omnipay\Checkout\Generic\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class CreatePaymentSessionResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return isset($this->data['id']) && $this->getCode() === 201;
    }

    public function getRedirectedData()
    {
        return $this->getData();
    }


}
