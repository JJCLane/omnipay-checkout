<?php

namespace Omnipay\Checkout;

use Omnipay\Checkout\Message\Purchase\PurchaseRequest;
use Omnipay\Checkout\Message\Refund\RefundRequest;
use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    /**
     * Return the name of the gateway for the container.
     *
     * @return void
     */
    public function getName()
    {
        return 'Checkout';
    }

    public function getDefaultParameters()
    {
        return [
            'secret_key' => '',
            'public_key' => '',
            'test_mode' => false,
        ];
    }

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

    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }
}
