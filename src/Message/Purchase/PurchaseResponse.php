<?php

namespace Omnipay\Checkout\Message\Purchase;

use Omnipay\Checkout\Generic\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $redirectEndpoint = null;

    public function isSuccessful()
    {
        return isset($this->data['approved']) && $this->data['approved'] === true;
    }

    public function isPending()
    {
        return isset($this->data['status']) && $this->data['status'] === 'Pending';
    }

    public function isRedirect()
    {
        if ($this->getCode() === 202 && isset($this->data['_links']['redirect'])) {
            return true;
        }

        return false;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['_links']['redirect']['href'];
        }
    }

    public function getRedirectedData()
    {
        return $this->getData();
    }


}
