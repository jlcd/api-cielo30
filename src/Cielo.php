<?php

namespace jlcd\Cielo;

use jlcd\Cielo\Resources\CieloPayment;
use jlcd\Cielo\Resources\CieloCustomer;
use jlcd\Cielo\Resources\CieloOrder;
use jlcd\Cielo\Resources\CieloCreditCard;
use jlcd\Cielo\Responses\ResourceResponse;
use jlcd\Cielo\Requests\TokenizeCardRequest;
use jlcd\Cielo\Exceptions\ResourceErrorException;


use Cielo\API30\Merchant;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;

use Cielo\API30\Ecommerce\Request\CieloRequestException;

class Cielo
{
    private $app         = null;
    private $merchant    = null;
    private $environment = null;

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app         = $app;
        $this->merchant    = new Merchant(config('cielo.merchant_id'), config('cielo.merchant_key'));
        $this->environment = config('cielo.environment') == 'production' ? Environment::production() : Environment::sandbox();
    }

    /**
     * Executes a payment request on Cielo
     * @param CieloPayment $cieloPayment
     * @param CieloCustomer $cieloCustomer
     * @param CieloOrder $cieloOrder
     * @throws ResourceErrorException
     * @return ResourceResponse
     */
    public function payment(CieloPayment $cieloPayment, CieloOrder $cieloOrder, CieloCustomer $cieloCustomer = null)
    {
        $sale = new Sale($cieloOrder->getId());

        $payment = $sale->payment($cieloPayment->getValue(), $cieloPayment->getInstallments());

        $creditCard = $cieloPayment->getCreditCard();

        $payment->setType(Payment::PAYMENTTYPE_CREDITCARD);
        $paymentCard = $payment->creditCard($creditCard->getSecurityCode(), $creditCard->getBrand());

        if ($creditCard->getToken()) {
            $paymentCard->setCardToken($creditCard->getToken());
        } else {
            $paymentCard->setExpirationDate($creditCard->getExpirationDate())
                        ->setCardNumber($creditCard->getCardNumber())
                        ->setHolder($creditCard->getHolder());
        }

        if ($cieloCustomer) {
            $customer = $sale->customer($cieloCustomer->getName());
        }

        try {
            $sale = (new CieloEcommerce($this->merchant, $this->environment))->createSale($sale);

            $paymentData = $sale->getPayment()->jsonSerialize();
            $paymentStatus = $sale->getPayment()->getStatus();
            $paymentMessage = $sale->getPayment()->getReturnMessage();
        } catch (CieloRequestException $e) {
            $error = $e->getCieloError();
            throw new ResourceErrorException($error->getMessage(), $error->getCode());
        }

        return new ResourceResponse($paymentStatus, $paymentMessage, $paymentData);
    }

    /**
     * Executes a payment refund request on Cielo
     * @param CieloPayment $cieloPayment
     * @throws ResourceErrorException
     * @return ResourceResponse
     */
    public function cancelPayment(CieloPayment $cieloPayment)
    {
        try {
            $sale = (new CieloEcommerce($this->merchant, $this->environment))->cancelSale($cieloPayment->getId(), $cieloPayment->getValue());

            $paymentData = $sale->jsonSerialize();
            $paymentStatus = $sale->getStatus();
            $paymentMessage = $sale->getReturnMessage();
        } catch (CieloRequestException $e) {
            $error = $e->getCieloError();
            throw new ResourceErrorException($error->getMessage(), $error->getCode());
        }

        return new ResourceResponse($paymentStatus, $paymentMessage, $paymentData);
    }

    /**
     * Executes a payment capture request on Cielo
     * @param CieloPayment $cieloPayment
     * @throws ResourceErrorException
     * @return ResourceResponse
     */
    public function capturePayment(CieloPayment $cieloPayment)
    {
        try {
            $sale = (new CieloEcommerce($this->merchant, $this->environment))->captureSale($cieloPayment->getId(), $cieloPayment->getValue());

            $paymentData = $sale->jsonSerialize();
            $paymentStatus = $sale->getStatus();
            $paymentMessage = $sale->getReturnMessage();
        } catch (CieloRequestException $e) {
            $error = $e->getCieloError();
            throw new ResourceErrorException($error->getMessage(), $error->getCode());
        }

        return new ResourceResponse($paymentStatus, $paymentMessage, $paymentData);
    }

    /**
     * Executes a payment request on Cielo
     * @param CieloCreditCard $cieloCreditCard
     * @param CieloCustomer $cieloCustomer
     * @throws ResourceErrorException
     * @return ResourceResponse
     */
    public function tokenizeCreditCard(CieloCreditCard $cieloCreditCard, CieloCustomer $cieloCustomer)
    {
        try {
            $sale = new Sale();
            $sale->CustomerName   = $cieloCustomer->getName();
            $sale->CardNumber     = $cieloCreditCard->getCardNumber();
            $sale->Holder         = $cieloCreditCard->getHolder();
            $sale->ExpirationDate = $cieloCreditCard->getExpirationDate();
            $sale->Brand          = $cieloCreditCard->getBrand();

            $token = (new TokenizeCardRequest($this->merchant, $this->environment))->execute($sale);

            $paymentData = $token;
            $paymentStatus = 1;
            $paymentMessage = 'Operation Successful';
        } catch (CieloRequestException $e) {
            $error = $e->getCieloError();
            throw new ResourceErrorException($error->getMessage(), $error->getCode());
        }

        return new ResourceResponse($paymentStatus, $paymentMessage, $paymentData);
    }
}
