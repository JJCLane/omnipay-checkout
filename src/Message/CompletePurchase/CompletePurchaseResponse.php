<?php

namespace Omnipay\Checkout\Message\CompletePurchase;

use Omnipay\Checkout\Generic\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class CompletePurchaseResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return isset($this->data['approved']) && $this->data['approved'] === true;
    }

    public function isPending()
    {
        return isset($this->data['status']) && $this->data['status'] === 'Pending';
    }

    /**
     * Get the risk array from the response of purchaseRequest.
     *
     * @return array|null
     */
    public function getRisk()
    {
        if (isset($this->data['risk'])) {
            return $this->data['risk'];
        }

        return null;
    }

    public function getRedirectedData()
    {
        return $this->getData();
    }


}
