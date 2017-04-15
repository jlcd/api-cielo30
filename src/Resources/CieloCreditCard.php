<?php

namespace jlcd\Cielo\Resources;

class CieloCreditCard
{
    private $cardNumber     = null;
    private $expirationDate = null;
    private $brand          = null;
    private $securityCode   = null;
    private $holder         = null;
    private $token          = null;

    public function getCardNumber()
    {
        return $this->cardNumber;
    }
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
    public function getBrand()
    {
        return $this->brand;
    }
    public function getSecurityCode()
    {
        return $this->securityCode;
    }
    public function getHolder()
    {
        return $this->holder;
    }
    public function getToken()
    {
        return $this->token;
    }

    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    }
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
    }
    public function setHolder($holder)
    {
        $this->holder = $holder;
    }
    public function setToken($token)
    {
        $this->token = $token;
    }
}
