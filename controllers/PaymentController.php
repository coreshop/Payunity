<?php
/**
 * Payunity
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

use CoreShop\Controller\Action\Payment;
use Pimcore\Model\Object\Objectbrick\Data\CoreShopPaymentPayunity;

/**
 * Class Payunity_PaymentController
 */
class Payunity_PaymentController extends Payment
{
    public function paymentAction()
    {
        $identifier = uniqid();

        $this->cart->setCustomIdentifier($identifier);
        $this->cart->save();

        $payment = new \Payunity\Payment(array(
            "securitySender" => \CoreShop\Model\Configuration::get("PAYUNITY.SENDERID"),
            "userLogin" => \CoreShop\Model\Configuration::get("PAYUNITY.USERID"),
            "userPwd" => \CoreShop\Model\Configuration::get("PAYUNITY.USERPWD"),
            "transactionChannel" => \CoreShop\Model\Configuration::get("PAYUNITY.CHANNELID"),
            "transactionMode" => \CoreShop\Model\Configuration::get("PAYUNITY.MODE"),
            "requestVersion" => "1.0",
            "identificationTransactionId" => $identifier,
            "frontendEnabled" => true,
            "frontendPopup" => false,
            "frontendMode" => "WPF_PRESELECTION",
            "frontendLanguage" => "de",
            "paymentCode" => "CC.DB",
            "frontendHeight" => "100%",
            "frontendResponseUrl" => $this->getModule()->url($this->getModule()->getIdentifier(), "payment-return"),
            "presentationAmount" => $this->cart->getTotal(),
            "presentationCurrency" => "EUR",
            "presentationUsage" => "Astro4Love - Shop",
            "sandbox" => \CoreShop\Model\Configuration::get("PAYUNITY.SANDBOX")
        ));

        $this->redirect($payment->doPayment());
    }

    public function paymentReturnAction()
    {
        $returnvalue = $_REQUEST['PROCESSING_RESULT'];

        if ($returnvalue) {
            $transactionIdentification = $_REQUEST['IDENTIFICATION_TRANSACTIONID'];
            $cart = \CoreShop\Model\Cart::findByCustomIdentifier($transactionIdentification);

            if ($cart instanceof \CoreShop\Model\Cart) {
                if (strstr($returnvalue, "ACK")) {
                    $order = $cart->createOrder(
                        \CoreShop\Model\Order\State::getByIdentifier('PAYMENT'),
                        $this->getModule(),
                        $cart->getTotal(),
                        $this->view->language
                    );

                    $payments = $order->getPayments();

                    foreach ($payments as $p) {
                        $dataBrick = new CoreShopPaymentPayunity($p);
                        $dataBrick->setIdentificationUniqeId($_POST['IDENTIFICATION_UNIQUEID']);
                        $dataBrick->setIdentificationShortId($_POST['IDENTIFICATION_SHORTID']);

                        $p->save();
                    }

                    echo Pimcore\Tool::getHostUrl() . $this->getModule()->getConfirmationUrl($order);
                } else {
                    echo Pimcore\Tool::getHostUrl() . $this->getModule()->getErrorUrl();
                }
            } else {
                echo Pimcore\Tool::getHostUrl() . $this->getModule()->getErrorUrl();
            }
        } else {
            echo Pimcore\Tool::getHostUrl() . $this->getModule()->getErrorUrl();
        }
        
        exit;//We only need to output a URL for Payunity
    }

    public function errorAction()
    {
        //TODO: make view
        echo "some error occured";
        exit;
    }

    public function confirmationAction()
    {
        $orderId = $this->getParam("order");

        if ($orderId) {
            $order = \Pimcore\Model\Object\CoreShopOrder::getById($orderId);

            if ($order instanceof \CoreShop\Model\Order) {
                $this->session->order = $order;
            }
        }

        parent::confirmationAction();
    }

    /**
     * @return Payunity\Shop
     */
    public function getModule()
    {
        return parent::getModule();
    }
}
