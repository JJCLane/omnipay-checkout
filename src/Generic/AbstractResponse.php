<?php

namespace Omnipay\Checkout\Generic;

use Omnipay\Common\Message\AbstractResponse as OriginalAbstractResponse;

class AbstractResponse extends OriginalAbstractResponse
{
    public function getTransactionReference()
    {
        if(isset($this->data['id'])) {
            return $this->data['id'];
        }

        if(isset($this->data['action_id'])) {
            return $this->data['action_id'];
        }

        return null;
    }

    public function getCode()
    {
        return $this->data['statusCode'];
    }

    public function getMessage()
    {
        if(isset($this->data['error_codes'])) {
            return $this->data['error_codes'];
        }

        return null;
    }

    public function isSuccessful()
    {
        return true;
    }
}
