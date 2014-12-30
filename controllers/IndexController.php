<?php


class Payunity_IndexController extends Pimcore_Controller_Action_Admin {
    
    public function indexAction () {

        // reachable via http://your.domain/plugin/Payunity/index/index

    }
    
    public function testAction()
    {
        // reachable via http://test.astro4love.com/plugin/Payunity/index/test
        
        
        
        $payment->doPayment();
    }
    
    public function responseAction()
    {
        print_r($_POST);
        exit;
    }
}
