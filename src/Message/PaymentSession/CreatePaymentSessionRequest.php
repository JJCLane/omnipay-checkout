<?php

namespace Omnipay\Checkout\Message\PaymentSession;

use ArrayObject;
use Omnipay\Checkout\Generic\AbstractRequest;

class CreatePaymentSessionRequest extends AbstractRequest
{

    public function getData()
    {
        return [
            'reference' => $this->getTransactionId(),
            'amount' => $this->getAmountInteger(),
            'currency' => strtoupper($this->getCurrency()),
            'description' => $this->getDescription(),
            'metadata' => $this->getMetaData() ?? new ArrayObject(),
            'enabled_payment_methods' => $this->getEnabledPaymentMethods(),
            'payment_type' => $this->getPaymentType(),
            'billing' => [
                'address' => [
                    'address_line1' => $this->getBillingAddress1() ?? '',
                    'address_line2' => $this->getBillingAddress2() ?? '',
                    'city' => $this->getBillingCity() ?? '',
                    'state' => $this->getBillingState() ?? '',
                    'zip' => $this->getBillingPostcode() ?? '',
                    'country' => $this->getBillingCountry(),
                ],
            ],
            'customer' => [
                'email' => $this->getBillingEmail(),
                'name' => $this->getCustomerName(),
            ],
            'ip_address' => $this->getClientIp(),
            'success_url' => $this->getReturnUrl(),
            'failure_url' => $this->getCancelUrl(),
        ];
    }
    
    public function getHttpMethod()
    {
        return 'POST';
    }

    public function sendData($data)
    {
        $this->validate('transactionId', 'amount', 'currency', 'billingCountry', 'returnUrl', 'cancelUrl');
        $response = $this->sendRequest($data);
        return $this->response = new CreatePaymentSessionResponse($this, $response);
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payment-sessions';
    }

    public function getBillingEmail(): ?string
    {
        return $this->getParameter('billingEmail');
    }

    public function setBillingEmail(string $value): self
    {
        return $this->setParameter('billingEmail', $value);
    }

    public function getCustomerName(): ?string
    {
        return $this->getParameter('customerName');
    }

    public function setCustomerName(string $value): self
    {
        return $this->setParameter('customerName', $value);
    }

    public function get3DS(): ?array
    {
        return $this->getParameter('3DS');
    }

    public function set3DS(array $value): self
    {
        return $this->setParameter('3DS', $value);
    }

    public function getEnabledPaymentMethods(): ?array
    {
        return $this->getParameter('enabledPaymentMethods');
    }

    public function setEnabledPaymentMethods(array $value): self
    {
        return $this->setParameter('enabledPaymentMethods', $value);
    }

    public function getPaymentType(): ?string
    {
        return $this->getParameter('paymentType');
    }

    public function setPaymentType(string $value): self
    {
        return $this->setParameter('paymentType', $value);
    }

    /**
     * Get the billing address, line 1.
     *
     * @return string
     */
    public function getBillingAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    /**
     * Sets the billing address, line 1.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }

    /**
     * Get the billing address, line 2.
     *
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    /**
     * Sets the billing address, line 2.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingAddress2($value)
    {
        return $this->setParameter('billingAddress2', $value);
    }

    /**
     * Get the billing city.
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }

    /**
     * Sets billing city.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }

    /**
     * Get the billing postcode.
     *
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    /**
     * Sets the billing postcode.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }

    /**
     * Get the billing state.
     *
     * @return string
     */
    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }

    /**
     * Sets the billing state.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }

    /**
     * Get the billing country name.
     *
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * Sets the billing country name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }

    /**
     * Get the billing phone number.
     *
     * @return string
     */
    public function getBillingPhone()
    {
        return $this->getParameter('billingPhone');
    }

    /**
     * Sets the billing phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
    }
}
