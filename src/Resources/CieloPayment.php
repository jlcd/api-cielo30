<?php

namespace jlcd\Cielo\Resources;

class CieloPayment
{
    private $id             = null;
    private $value          = null;
    private $creditCard     = null;
    private $installments   = 1;
    private $softDescriptor = null;

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }
    public function getCreditCard()
    {
        return $this->creditCard;
    }
    public function getInstallments()
    {
        return $this->installments;
    }
    public function getSoftDescriptor()
    {
        return $this->softDescriptor;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setValue($value)
    {
        $this->value = $value;
    }
    public function setCreditCard(CieloCreditCard $creditCard)
    {
        $this->creditCard = $creditCard;
    }
    public function setInstallments($installments)
    {
        $this->installments = $installments;
    }
    public function setSoftDescriptor($softDescriptor)
    {
        $this->softDescriptor = $softDescriptor;
    }
}
