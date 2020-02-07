<?php

namespace Omnipay\Checkout\Generic;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
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

    /** Breakpoint for the methods used to capture the request. */

    public function getMetaData()
    {
        return $this->getParameter('meta_data');
    }

    public function setMetaData($value)
    {
        return $this->setParameter('meta_data', $value);
    }

    public function getEndpoint()
    {
        if ($this->getTestMode()) {
            return self::LIVE_ENDPOINT;
        }

        return self::SANDBOX_ENDPOINT;
    }

    public function sendRequest($data)
    {
        $response = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8',
            'Authorization' => $this->getSecretKey()
        ], empty($data) ? json_encode($data) : null);

        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }
}
