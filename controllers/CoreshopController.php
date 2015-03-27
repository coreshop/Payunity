<?php


class Payunity_CoreshopController extends CoreShop_Controller_Action_Payment {
    
    public function paymentReturnAction () {
        // reachable via /plugin/Payunity/coreshop/payment-return
        
        parent::paymentReturnAction();
        
        $returnvalue = $_REQUEST['PROCESSING_RESULT'];

        if ($returnvalue) {
            $transactionIdentification = $_REQUEST['IDENTIFICATION_TRANSACTIONID'];

            $payment = Object_CoreShopPayment::findByTransactionIdentifier($transactionIdentification);

            if ($payment) 
            {
                if (strstr($returnvalue, "ACK")) 
                {
                    $dataBrick = new Object_Objectbrick_Data_CoreShopPaymentPayunity($payment);
                    $dataBrick->setIdentificationUniqeId($_POST['IDENTIFICATION_UNIQUEID']);
                    $dataBrick->setIdentificationShortId($_POST['IDENTIFICATION_SHORTID']);
            
                    $payment->getPaymentInformation()->setCoreShopPaymentPayunity($dataBrick);
                    $payment->setDatePayment(new Zend_Date($_POST['PROCESSING_TIMESTAMP']));
                    $payment->setPayed(true);
                    $payment->save();
                    
                    print CoreShop_Tool::getWebsiteUrl() . $this->view->url(array("action" => "thankyou", "lang" => $payment->getOrder()->getLang()), "coreshop_checkout");
                } 
                else 
                {
                    print CoreShop_Tool::getWebsiteUrl() . $this->view->url(array("action" => "error", "lang" => $payment->getOrder()->getLang()), "coreshop_checkout");
                }
            } 
            else 
            {
                print CoreShop_Tool::getWebsiteUrl() . $this->view->url(array("action" => "error", "lang" => "de"), "coreshop_checkout");
            }
        } 
        else 
        {
            print CoreShop_Tool::getWebsiteUrl() . $this->view->url(array("action" => "error", "lang" => "de"), "coreshop_checkout");
        }
        
        exit;
    }
}
