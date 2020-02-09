<?php

namespace Omnipay\Checkout\Message\Capture;

use Omnipay\Checkout\Generic\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class CaptureResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        if(isset($this->data['error_type'])) {
            return false;
        }

        return true;
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