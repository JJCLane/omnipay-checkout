<?php

namespace Omnipay\Checkout\Generic;

use Omnipay\Common\Message\AbstractRequest as OriginalAbstractRequest;

class AbstractRequest extends OriginalAbstractRequest
{
    const LIVE_ENDPOINT = 'https://api.checkout.com';
    const SANDBOX_ENDPOINT = 'https://api.sandbox.checkout.com';

    public function getSecretKey()
    {
        return $this->getParameter('secret_key');
    }

    public function setSecretKey(string $value)
    {
        return $this->setParameter('secret_key', $value);
    }

    public function getPublicKey()
    {
        return $this->getParameter('public_key');
    }

    public function setPublicKey(string $value)
    {
        return $this->setParameter('public_key', $value);
    }
}
