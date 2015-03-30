<?php
    
class Payunity_Shop implements \CoreShop\Plugin\Payment
{
    public static $install;
    
    public function attachEvents()
    {
        self::getInstall()->attachEvents();
        
        \CoreShop\Plugin::getEventManager()->attach("payment.getProvider", function($e) {
            //$cart = $e->getParam("cart");

            return $this;
        });
        
        \CoreShop\Plugin::getEventManager()->attach('controller.init', function($e) {
            $controller = $e->getTarget();
            
            $controller->view->setScriptPath(
                array_merge(
                    $controller->view->getScriptPaths(),
                    array(
                        PIMCORE_PLUGINS_PATH . '/Payunity/views/scripts/',
                        PIMCORE_WEBSITE_PATH . '/coreshop/Payunity/views/scripts/'
                    )
                )
            );
        });
    }
    
    public function getName()
    {
        return "Payunity";
    }
    
    public function getDescription()
    {
        return "";
    }
    
    public function getImage()
    {
        return "/plugins/Payunity/static/img/payunity.gif";
    }
    
    public function getIdentifier()
    {
        return "payment_payunity";
    }
    
    public function getPaymentFee(\Pimcore\Model\Object\CoreShopCart $cart)
    {
        return 0;
    }
    
    public function processPayment(\Pimcore\Model\Object\CoreShopOrder $order)
    {
        $coreShopPayment = $order->createPayment($this, $order->getTotal());
        $config = Payunity_Plugin::getConfigArray();

        $payment = new Payunity_Payment(array(
            "securitySender" => $config->payunity->senderId,
            "userLogin" => $config->payunity->userId,
            "userPwd" => $config->payunity->userPwd,
            "transactionChannel" => $config->payunity->channelId,
            "transactionMode" => $config->payunity->mode,
            "requestVersion" => "1.0",
            "identificationTransactionId" => $coreShopPayment->getTransactionIdentifier(),
            "frontendEnabled" => true,
            "frontendPopup" => false,
            "frontendMode" => "WPF_PRESELECTION",
            "frontendLanguage" => "de",
            "paymentCode" => "CC.DB",
            "frontendHeight" => "100%",
            "frontendResponseUrl" => "/plugin/Payunity/coreshop/payment-return",
            "presentationAmount" => $coreShopPayment->getAmount(),
            "presentationCurrency" => "EUR",
            "presentationUsage" => "Astro4Love - Shop",
            "sandbox" => $config->payunity->sandbox == 1 ? true : false
        ));

        $payment->doPayment();
        
        return "payunity/shop/start-payment";
    }
    
    /**
     * @return Payunity_Shop_Install
     */
    public static function getInstall() {
        if(!self::$install) {
            self::$install = new Payunity_Shop_Install();
        }
        return self::$install;
    }
}