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

namespace Payunity;

use Pimcore\Tool;

class Payment
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
    
    public $presentationUsage;
    
    public $sandbox = false;
    
    public static $LIVE_URL = "https://payunity.com/frontend/payment.prc";
    public static $TEST_URL = "https://test.payunity-core.eu/frontend/payment.prc";
    
    public static $PROPERTY_KEYS = array(
        
        "securitySender" => "SECURITY.SENDER",
        "userLogin" => "USER.LOGIN",
        "userPwd" => "USER.PWD",
        "transactionChannel" => "TRANSACTION.CHANNEL",
        "transactionMode" => "TRANSACTION.MODE",
        "requestVersion" => "REQUEST.VERSION",
        "identificationTransactionId" => "IDENTIFICATION.TRANSACTIONID",
        "frontendEnabled" => "FRONTEND.ENABLED",
        "frontendPopup" => "FRONTEND.POPUP",
        "frontendMode" => "FRONTEND.MODE",
        "frontendLanguage" => "FRONTEND.LANGUAGE",
        "paymentCode" => "PAYMENT.CODE",
        "frontendCssPath" => "FRONTEND.CSS_PATH",
        "frontendJavascriptPath" => "FRONTEND.JSCRIPT_PATH",
        "frontendHeight" => "FRONTEND.HEIGHT",
        "frontendResponseUrl" => "FRONTEND.RESPONSE_URL",
        "presentationAmount" => "PRESENTATION.AMOUNT",
        "presentationCurrency" => "PRESENTATION.CURRENCY",
        "presentationUsage" => "PRESENTATION.USAGE"
        
    );
    
    public function __construct($config)
    {
        foreach($config as $key=>$value)
        {
            if(property_exists($this,$key))
            {
                $setter = "set" . ucfirst($key);
                
                if(method_exists($this, $setter))
                {
                    $this->$setter($value);
                }
            }
        }
        
        $requiredParameters = array(
            "securitySender",
            "userLogin",
            "userPwd",
            "transactionChannel"
        );
        
        foreach($requiredParameters as $req)
        {
            if(!isset($this->$req))
                throw new \Exception("missing required parameter '$req'");
        }
        
        if(substr($this->frontendResponseUrl, 0, 1) == "/")
        {
            $serverUrl = Tool::getHostUrl();
            
            $this->setFrontendResponseUrl($serverUrl. $this->frontendResponseUrl);
            
        }
    }
    
    public function doPayment()
    {
        $parameters = $this->getParameters();
        $result = "";

        foreach (array_keys($parameters) AS $key)
        {
            $$key .= is_bool($parameters[$key]) ? $parameters[$key] ? "true" : "false" : $parameters[$key];
            $$key = urlencode($$key);
            $$key .= "&";
            $var = strtoupper($key);
            $value = $$key;
            $result .= "$var=$value";
        }
        
        $strPOST = stripslashes($result);

        $url = $this->getSandbox() ? self::$TEST_URL : self::$LIVE_URL;
        
        $cpt = curl_init();
        curl_setopt($cpt, CURLOPT_URL, $url);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($cpt, CURLOPT_USERAGENT, "php ctpepost");
        curl_setopt($cpt, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cpt, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cpt, CURLOPT_POST, 1);
        curl_setopt($cpt, CURLOPT_POSTFIELDS, $strPOST);
        $curlresultURL = curl_exec($cpt);
        $curlerror = curl_error($cpt);
        $curlinfo = curl_getinfo($cpt);
        curl_close($cpt);
        
        $r_arr = explode("&",$curlresultURL);
        
        foreach($r_arr AS $buf)
        {
            $temp = urldecode($buf);
            $temp = explode("=",$temp,2);
            
            $postatt = $temp[0];
            $postvar = $temp[1];
            $returnvalue[$postatt]=$postvar;
        }
        
        $processingresult = $returnvalue['POST.VALIDATION'];
        $redirectURL = $returnvalue['FRONTEND.REDIRECT_URL'];
        
        if ($processingresult == "ACK")
        {
            if (strstr($redirectURL, "http")) // redirect url is returned ==> everything ok
            {
                return $redirectURL;
            }
            else
            {
                return "ERROR URL";
            }
        }// there is a connection-problem to the ctpe server ... redirect to error page (change the URL to YOUR error page)
        else
        {
            throw new \Exception($strPOST);
        }
    }
    
    protected function getParameters()
    {
        $properties = array_keys(get_class_vars(get_class($this)));
        $parameters = array();
        
        foreach($properties as $property)
        {
            $parameterName = $this->getKeyForPropertyName($property);
            
            if($parameterName)
            {
                $getter = "get" . ucfirst($property);
                
                if(method_exists($this, $getter))
                {
                    $parameters[$parameterName] = $this->$getter();
                }
            }
        }
        
        return $parameters;
    }
    
    protected function getKeyForPropertyName($propertyName)
    {
        if(array_key_exists($propertyName, self::$PROPERTY_KEYS))
            return self::$PROPERTY_KEYS[$propertyName];
        
        return null;
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
    
    public function getPresentationUsage() {
        return $this->presentationUsage;
    }

    public function setPresentationUsage($presentationUsage) {
        $this->presentationUsage = $presentationUsage;
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


