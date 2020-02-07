<?php

namespace Omnipay\Checkout\Message\Purchase;

use Omnipay\Checkout\Generic\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $redirectEndpoint = null;

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        if(isset($this->data['error_type'])) {
            return false;
        }

        return true;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return 'placeholder';
        }
    }

    public function getRedirectedData()
    {
        return $this->getData();
    }
}
