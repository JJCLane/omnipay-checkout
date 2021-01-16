<?php

namespace Omnipay\Checkout\Message\Purchase;

use GuzzleHttp\Psr7\Response;
use Omnipay\Checkout\Generic\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    public const PAYMENT_REGULAR = 'Regular';
    public const PAYMENT_RECURRING = 'Recurring';
    public const PAYMENT_MOTO = 'MOTO';

    /**
     * Return the data that is used as request body/content.
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = [
            'amount' => $this->getAmountInteger(),
            'currency' => strtoupper($this->getCurrency()),
            'description' => $this->getDescription(),
            'metadata' => $this->getMetaData(),
            'reference' => $this->getTransactionId(),
            'payment_ip' => $this->getClientIp(),
            'source' => $this->getSourceData(),
            '3ds' => $this->get3DS(),
            'merchant_initiated' => $this->getMerchantInitiated(),
        ];

        if ($this->getPreviousPaymentId()) {
            $data['previous_payment_id'] = $this->getPreviousPaymentId();
        }
        if ($this->getPaymentType()) {
            $data['payment_type'] = $this->getPaymentType();
        }
        $customer = [];
        if ($this->getCustomerReference()) {
            $customer['id'] = $this->getCustomerReference();
        }
        if ($this->getBillingEmail()) {
            $customer['email'] = $this->getBillingEmail();
        }
        if ($this->getCustomerName()) {
            $customer['name'] = $this->getCustomerName();
        }

        if ($customer !== []) {
            $data['customer'] = $customer;
        }

        return $data;
    }

    public function getSource(): ?array
    {
        return $this->getParameter('source');
    }

    public function setSource(array $value): self
    {
        return $this->setParameter('source', $value);
    }

    public function getCustomerName(): ?string
    {
        return $this->getParameter('customerName');
    }

    public function setCustomerName(string $value): self
    {
        return $this->setParameter('customerName', $value);
    }

    public function getBillingEmail(): ?string
    {
        return $this->getParameter('billingEmail');
    }

    public function setBillingEmail(string $value): self
    {
        return $this->setParameter('billingEmail', $value);
    }

    public function getCustomerReference(): ?string
    {
        return $this->getParameter('customerReference');
    }

    public function setCustomerReference(string $value): self
    {
        return $this->setParameter('customerReference', $value);
    }
    
    public function get3DS(): ?array
    {
        return $this->getParameter('3DS');
    }

    public function set3DS(array $value): self
    {
        return $this->setParameter('3DS', $value);
    }

    public function getPreviousPaymentId(): ?string
    {
        return $this->getParameter('previousPaymentId');
    }

    public function setPreviousPaymentId(string $value): self
    {
        return $this->setParameter('previousPaymentId', $value);
    }

    public function getMerchantInitiated(): bool
    {
        return $this->parameters->get('merchantInitiated', false);
    }

    public function setMerchantInitiated(bool $value): self
    {
        return $this->setParameter('merchantInitiated', $value);
    }

    public function getPaymentType(): ?string
    {
        return $this->getParameter('paymentType');
    }

    public function setPaymentType(string $value): self
    {
        return $this->setParameter('paymentType', $value);
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function sendData($data)
    {
        $response = $this->sendRequest($data);
        return $this->response = new PurchaseResponse($this, $response);
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . '/payments';
    }

    private function getSourceData()
    {
        if ($this->getSource()) {
            return $this->getSource();
        } elseif ($this->getCardReference()) {
            $source = ['type' => 'id', 'id' => $this->getCardReference()];
            $card = $this->getCard();
            if ($card !== null && $card->getCvv()) {
                $source['cvv'] = $card->getCvv();
            }
            return $source;
        } elseif ($this->getToken()) {
            return ['type' => 'token', 'token' => $this->getToken()];
        } elseif ($card = $this->getCard()) {
            return [
                'type' => 'card',
                'number' => $card->getNumber(),
                'expiry_month' => $card->getExpiryMonth(),
                'expiry_year' => $card->getExpiryYear(),
                'name' => $card->getName(),
                'cvv' => $card->getCvv(),
                'billing_address' => [
                    'address_line1' => $card->getBillingAddress1(),
                    'address_line2' => $card->getBillingAddress2(),
                    'city' => $card->getBillingCity(),
                    'state' => $card->getBillingState(),
                    'zip' => $card->getBillingPostcode(),
                    'country' => $card->getBillingCountry()
                ],
                'phone' => ['number' => $card->getPhone()],
            ];
        } elseif ($this->getCustomerReference() || $this->getBillingEmail()) {
            $source = ['type' => 'customer'];
            if ($this->getCustomerReference()) {
                $source['id'] = $this->getCustomerReference();
            } elseif ($this->getBillingEmail()) {
                $source['email'] = $this->getBillingEmail();
            }
            return $source;
        } else {
            // one of source, cardReference, token, or card is required
            $this->validate('source');
        }
    }
}
