<?php

namespace Omnipay\Checkout\Generic;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Number;
use Money\Parser\DecimalMoneyParser;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse as OriginalAbstractResponse;
use Omnipay\Common\Message\ResponseInterface;

class AbstractResponse extends OriginalAbstractResponse implements ResponseInterface
{
    /**
     * @var ISOCurrencies
     */
    protected $currencies;

    /**
     * @var bool
     */
    protected $zeroAmountAllowed = true;

    /**
     * @var bool
     */
    protected $negativeAmountAllowed = false;

    public function getTransactionReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }

        if (isset($this->data['action_id'])) {
            return $this->data['action_id'];
        }

        return null;
    }

    public function getTransactionId()
    {
        if (isset($this->data['reference'])) {
            return $this->data['reference'];
        }

        return null;
    }

    /**
     * Get a customer reference, for createCustomer requests.
     *
     * @return string|null
     */
    public function getCustomerReference()
    {
        if (isset($this->data['customer'])) {
            return $this->data['customer']['id'];
        }

        return null;
    }

    /**
     * Get a card reference, for createCard or createCustomer requests.
     *
     * @return string|null
     */
    public function getCardReference()
    {
        if (isset($this->data['source']['type']) && $this->data['source']['type'] === 'card') {
            return $this->data['source']['id'];
        }
    }

    /**
     * Get the card data from the response.
     *
     * @return array|null
     */
    public function getCard()
    {
        if (isset($this->data['source']) && $this->data['source']['type'] === 'card') {
            return $this->data['source'];
        }

        return null;
    }

    /**
     * Get the card data from the response of purchaseRequest.
     *
     * @return array|null
     */
    public function getSource()
    {
        if (isset($this->data['source'])) {
            return $this->data['source'];
        }

        return null;
    }

    public function getCode()
    {
        if (isset($this->data['response_code'])) {
            return $this->data['response_code'];
        }

        return $this->data['statusCode'];
    }

    public function getMessage()
    {
        if(isset($this->data['error_codes'])) {
            return $this->data['error_codes'][0];
        }

        if (isset($this->data['status'])) {
            return $this->data['status'];
        }

        return null;
    }

    public function isSuccessful()
    {
        return true;
    }

    /**
     * @return ISOCurrencies
     */
    protected function getCurrencies()
    {
        if ($this->currencies === null) {
            $this->currencies = new ISOCurrencies();
        }

        return $this->currencies;
    }

    /**
     * Get the payment currency code.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->data['currency'];
    }

    /**
     * @param  string|int|null $amount
     * @return null|Money
     * @throws InvalidRequestException
     */
    private function getMoney($amount = null)
    {
        $currencyCode = $this->getCurrency() ?: 'USD';
        $currency = new Currency($currencyCode);

        $amount = $amount !== null ? $amount : $this->data['amount'];

        if ($amount === null) {
            return null;
        } elseif ($amount instanceof Money) {
            $money = $amount;
        } elseif (is_integer($amount)) {
            $money = new Money($amount, $currency);
        } else {
            $moneyParser = new DecimalMoneyParser($this->getCurrencies());

            $number = Number::fromString($amount);

            // Check for rounding that may occur if too many significant decimal digits are supplied.
            $decimal_count = strlen($number->getFractionalPart());
            $subunit = $this->getCurrencies()->subunitFor($currency);
            if ($decimal_count > $subunit) {
                throw new InvalidResponseException('Amount precision is too high for currency.');
            }

            $money = $moneyParser->parse((string) $number, $currency);
        }
        // Check for a negative amount.
        if (!$this->negativeAmountAllowed && $money->isNegative()) {
            throw new InvalidResponseException('A negative amount is not allowed.');
        }

        // Check for a zero amount.
        if (!$this->zeroAmountAllowed && $money->isZero()) {
            throw new InvalidResponseException('A zero amount is not allowed.');
        }

        return $money;
    }

    /**
     * Validates and returns the formatted amount.
     *
     * @throws InvalidRequestException on any validation failure.
     * @return string The amount formatted to the correct number of decimal places for the selected currency.
     */
    public function getAmount()
    {
        $money = $this->getMoney();

        if ($money !== null) {
            $moneyFormatter = new DecimalMoneyFormatter($this->getCurrencies());

            return $moneyFormatter->format($money);
        }
    }

    /**
     * Get the payment amount as an integer.
     *
     * @return integer
     */
    public function getAmountInteger()
    {
        $money = $this->getMoney();

        if ($money !== null) {
            return (int) $money->getAmount();
        }
    }
}
