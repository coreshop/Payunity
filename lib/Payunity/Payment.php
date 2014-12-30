<?php


class Payunity_Payment
{
    public $securitySender;
    
    public $userLogin;
    
    public $userPwd;
    
    public $transactionChannel;
    
    public $transactionMode;
    
    public $requestVersion;
    
    public $identificationTransactionId;
    
    public $frontendEnabled;
    
    public $frontendPopup;
    
    public $frontendMode;
    
    public $frontendLanguage;
    
    public $paymentCode;
    
    public $frontendCssPath;
    
    public $frontendJavascriptPath;
    
    public $frontendHeight;
    
    public $frontendResponseUrl;
    
    public $presentationAmount;

    public $presentationCurrency;
    
    public $sandbox = false;
    
    public static $LIVE_URL = "https://payunity-core.eu/frontend/payment.prc";
    public static $TEST_URL = "https://test.payunity-core.eu/frontend/payment.prc";
    
    public function __construct($config)
    {
        foreach($config as $key=>$value)
        {
            if(property_exists($this,$key))
            {
                $this->$key = $property;
            }
        }
        
        if(!isset($this->securitySender) || $this->userLogin || $this->userPwd || $this->transactionChannel)
        {
            throw Exception("missing required parameters");
        }
    }
    
    public function getSecuritySender() {
        return $this->securitySender;
    }

    public function setSecuritySender($securitySender) {
        $this->securitySender = $securitySender;
        return $this;
    }

    public function getUserLogin() {
        return $this->userLogin;
    }

    public function setUserLogin($userLogin) {
        $this->userLogin = $userLogin;
        return $this;
    }

    public function getUserPwd() {
        return $this->userPwd;
    }

    public function setUserPwd($userPwd) {
        $this->userPwd = $userPwd;
        return $this;
    }

    public function getTransactionChannel() {
        return $this->transactionChannel;
    }

    public function setTransactionChannel($transactionChannel) {
        $this->transactionChannel = $transactionChannel;
        return $this;
    }

    public function getTransactionMode() {
        return $this->transactionMode;
    }

    public function setTransactionMode($transactionMode) {
        $this->transactionMode = $transactionMode;
        return $this;
    }

    public function getRequestVersion() {
        return $this->requestVersion;
    }

    public function setRequestVersion($requestVersion) {
        $this->requestVersion = $requestVersion;
        return $this;
    }

    public function getIdentificationTransactionId() {
        return $this->identificationTransactionId;
    }

    public function setIdentificationTransactionId($identificationTransactionId) {
        $this->identificationTransactionId = $identificationTransactionId;
        return $this;
    }

    public function getFrontendEnabled() {
        return $this->frontendEnabled;
    }

    public function setFrontendEnabled($frontendEnabled) {
        $this->frontendEnabled = $frontendEnabled;
        return $this;
    }

    public function getFrontendPopup() {
        return $this->frontendPopup;
    }

    public function setFrontendPopup($frontendPopup) {
        $this->frontendPopup = $frontendPopup;
        return $this;
    }

    public function getFrontendMode() {
        return $this->frontendMode;
    }

    public function setFrontendMode($frontendMode) {
        $this->frontendMode = $frontendMode;
        return $this;
    }

    public function getFrontendLanguage() {
        return $this->frontendLanguage;
    }

    public function setFrontendLanguage($frontendLanguage) {
        $this->frontendLanguage = $frontendLanguage;
        return $this;
    }

    public function getPaymentCode() {
        return $this->paymentCode;
    }

    public function setPaymentCode($paymentCode) {
        $this->paymentCode = $paymentCode;
        return $this;
    }

    public function getFrontendCssPath() {
        return $this->frontendCssPath;
    }

    public function setFrontendCssPath($frontendCssPath) {
        $this->frontendCssPath = $frontendCssPath;
        return $this;
    }

    public function getFrontendJavascriptPath() {
        return $this->frontendJavascriptPath;
    }

    public function setFrontendJavascriptPath($frontendJavascriptPath) {
        $this->frontendJavascriptPath = $frontendJavascriptPath;
        return $this;
    }

    public function getFrontendHeight() {
        return $this->frontendHeight;
    }

    public function setFrontendHeight($frontendHeight) {
        $this->frontendHeight = $frontendHeight;
        return $this;
    }

    public function getFrontendResponseUrl() {
        return $this->frontendResponseUrl;
    }

    public function setFrontendResponseUrl($frontendResponseUrl) {
        $this->frontendResponseUrl = $frontendResponseUrl;
        return $this;
    }

    public function getPresentationAmount() {
        return $this->presentationAmount;
    }

    public function setPresentationAmount($presentationAmount) {
        $this->presentationAmount = $presentationAmount;
        return $this;
    }

    public function getPresentationCurrency() {
        return $this->presentationCurrency;
    }

    public function setPresentationCurrency($presentationCurrency) {
        $this->presentationCurrency = $presentationCurrency;
        return $this;
    }

    public function getSandbox() {
        return $this->sandbox;
    }

    public function setSandbox($sandbox) {
        $this->sandbox = $sandbox;
        return $this;
    }
}


