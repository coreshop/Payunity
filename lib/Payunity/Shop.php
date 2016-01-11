<?php

namespace Payunity;

use CoreShop\Model\Cart;
use CoreShop\Model\Order;
use CoreShop\Model\Plugin\Payment as CorePayment;
use CoreShop\Plugin as CorePlugin;

use Payunity\Shop\Install;

class Shop extends CorePayment
{
    public static $install;

    /**
     * @throws \Zend_EventManager_Exception_InvalidArgumentException
     */
    public function attachEvents()
    {
        self::getInstall()->attachEvents();

        CorePlugin::getEventManager()->attach("payment.getProvider", function($e) {
            //$cart = $e->getParam("cart");

            return $this;
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Payunity";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return "/plugins/Payunity/static/img/payunity.gif";
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return "Payunity";
    }

    /**
     * @param Cart $cart
     * @return int
     */
    public function getPaymentFee(Cart $cart)
    {
        return 0;
    }

    /**
     * Process Validation for Payment
     *
     * @param Cart $cart
     * @return mixed
     */
    public function process(Cart $cart) {
        //$coreShopPayment = $order->createPayment($this, $order->getTotal());
        $config = Plugin::getConfigArray();

        $identifier = uniqid();

        $cart->setCustomIdentifier($identifier);
        $cart->save();

        $payment = new Payment(array(
            "securitySender" => $config->payunity->senderId,
            "userLogin" => $config->payunity->userId,
            "userPwd" => $config->payunity->userPwd,
            "transactionChannel" => $config->payunity->channelId,
            "transactionMode" => $config->payunity->mode,
            "requestVersion" => "1.0",
            "identificationTransactionId" => $identifier,
            "frontendEnabled" => true,
            "frontendPopup" => false,
            "frontendMode" => "WPF_PRESELECTION",
            "frontendLanguage" => "de",
            "paymentCode" => "CC.DB",
            "frontendHeight" => "100%",
            "frontendResponseUrl" => $this->url($this->getIdentifier(), "payment-return"),
            "presentationAmount" => $cart->getTotal(),
            "presentationCurrency" => "EUR",
            "presentationUsage" => "Astro4Love - Shop",
            "sandbox" => $config->payunity->sandbox == 1 ? true : false
        ));

        return $payment->doPayment();
    }

    /**
     * Get url for confirmation link
     *
     * @param Order $order
     * @return string
     */
    public function getConfirmationUrl($order) {
        return $this->url($this->getIdentifier(), 'confirmation') . "?order=" . $order->getId();
    }

    /**
     * get url for validation link
     *
     * @return string
     */
    public function getProcessValidationUrl() {
        return $this->url($this->getIdentifier(), 'validate');
    }

    /**
     * get url payment link
     *
     * @return string
     */
    public function getPaymentUrl() {
        return $this->url($this->getIdentifier(), 'payment');
    }

    /**
     * get error url
     *
     * @return string
     */
    public function getErrorUrl() {
        return $this->url($this->getIdentifier(), 'error');
    }

    /**
     * @return Install
     */
    public static function getInstall() {
        if(!self::$install) {
            self::$install = new Install();
        }
        return self::$install;
    }
}