<?php

namespace App\Form;

class PaymentData
{
    private $name;
    private $cardNumber;
    private $expMonth;
    private $expYear;
    private $cvc;
    private $amount;
    private $stripeToken;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getExpMonth(): ?int
    {
        return $this->expMonth;
    }

    public function setExpMonth(int $expMonth): self
    {
        $this->expMonth = $expMonth;

        return $this;
    }

    public function getExpYear(): ?int
    {
        return $this->expYear;
    }

    public function setExpYear(int $expYear): self
    {
        $this->expYear = $expYear;

        return $this;
    }

    public function getCvc(): ?int
    {
        return $this->cvc;
    }

    public function setCvc(int $cvc): self
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(string $stripeToken): self
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }
}
