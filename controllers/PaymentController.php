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
 * @copyright  Copyright (c) 2015 Dominik Pfaffenbauer (http://dominik.pfaffenbauer.at)
 * @license    http://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

use CoreShop\Controller\Action\Payment;
use Pimcore\Model\Object\Objectbrick\Data\CoreShopPaymentPayunity;
use Pimcore\Model\Object\CoreShopPayment;
use CoreShop\Tool;

class Payunity_PaymentController extends Payment
{
    
    public function paymentReturnAction()
    {
        $returnvalue = $_REQUEST['PROCESSING_RESULT'];

        if ($returnvalue) {
            $transactionIdentification = $_REQUEST['IDENTIFICATION_TRANSACTIONID'];
            $cart = \CoreShop\Model\Cart::findByCustomIdentifier($transactionIdentification);

            if ($cart instanceof \CoreShop\Model\Cart) {
                if (strstr($returnvalue, "ACK")) {
                    $order = $this->getModule()->createOrder($cart, \CoreShop\Model\OrderState::getById(\CoreShop\Model\Configuration::get("SYSTEM.ORDERSTATE.PAYMENT")), $cart->getTotal(), "en"); //TODO: Fix Language

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
