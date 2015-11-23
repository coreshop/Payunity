<?php

use CoreShop\Controller\Action\Payment;
use Pimcore\Model\Object\Objectbrick\Data\CoreShopPaymentPayunity;
use Pimcore\Model\Object\CoreShopPayment;

use CoreShop\Tool;


class Payunity_CoreshopController extends Payment {
    
    public function paymentReturnAction () {
        // reachable via /plugin/Payunity/coreshop/payment-return
        
        parent::paymentReturnAction();
        
        $returnvalue = $_REQUEST['PROCESSING_RESULT'];

        if ($returnvalue) {
            $transactionIdentification = $_REQUEST['IDENTIFICATION_TRANSACTIONID'];

            $payment = CoreShopPayment::findByTransactionIdentifier($transactionIdentification);

            if ($payment) 
            {
                if (strstr($returnvalue, "ACK")) 
                {
                    $dataBrick = new CoreShopPaymentPayunity($payment);
                    $dataBrick->setIdentificationUniqeId($_POST['IDENTIFICATION_UNIQUEID']);
                    $dataBrick->setIdentificationShortId($_POST['IDENTIFICATION_SHORTID']);
            
                    $payment->getPaymentInformation()->setCoreShopPaymentPayunity($dataBrick);
                    $payment->setDatePayment(new Zend_Date($_POST['PROCESSING_TIMESTAMP']));
                    $payment->setPayed(true);
                    $payment->save();

                    $this->paymentSuccess($payment);


                    print Pimcore\Tool::getHostUrl() . $this->view->url(array("action" => "thankyou", "lang" => $payment->getOrder()->getLang()), "coreshop_checkout");
                } 
                else 
                {
                    $this->paymentFail();

                    print Pimcore\Tool::getHostUrl() . $this->view->url(array("action" => "error", "lang" => $payment->getOrder()->getLang()), "coreshop_checkout");
                }
            } 
            else 
            {
                $this->paymentFail();

                print Pimcore\Tool::getHostUrl() . $this->view->url(array("action" => "error", "lang" => "de"), "coreshop_checkout");
            }
        } 
        else 
        {
            $this->paymentFail();

            print Pimcore\Tool::getHostUrl() . $this->view->url(array("action" => "error", "lang" => "de"), "coreshop_checkout");
        }
        
        exit;
    }
}
