<?php


class Payunity_Plugin  extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface 
{
    protected static $configPath = "/var/config/payunity.xml";
    /**
     * @var Payunity_Shop
     */
    private static $shop;
    
    public function preDispatch($e)
    {
        parent::preDispatch();
        
        self::getShop()->attachEvents();
    }

    /**
     * @return Payunity_Shop
     */
    public static function getShop() {
        if(!self::$shop) {
            self::$shop = new Payunity_Shop();
        }
        return self::$shop;
    }

    public static function isInstalled()
    {
        return file_exists(PIMCORE_WEBSITE_PATH . self::$configPath);
    }

    public static function install()
    {
        //Install Config File
        $configFile = '<?xml version="1.0"?>
                        <zend-config xmlns:zf="http://framework.zend.com/xml/zend-config-xml/1.0/">
                            <payunity>
                                <channelId></channelId>
                                <mode></mode>
                                <sandbox>1</sandbox>
                                <senderId></senderId>
                                <userId></userId>
                                <userPwd></userPwd>
                            </payunity>
                        </zend-config>';
                        
        file_put_contents(PIMCORE_WEBSITE_PATH . self::$configFile, $configFile);
    }
    
    public static function uninstall()
    {
        unlink(PIMCORE_WEBSITE_PATH . self::$configPath);
    }
    
    /**
     * @static
     * @return array
     */
    public static function getConfigArray()
    {
        $config = new Zend_Config_Xml(PIMCORE_WEBSITE_PATH . self::$configPath);

        return $config;
    }

    
    public static function getUrl()
    {
        $pageURL = "http";
         
        if ($_SERVER["HTTPS"] == "on") 
        {
            $pageURL .= "s";
        }
        
        $pageURL .= "://";
        
        if ($_SERVER["SERVER_PORT"] != "80") 
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } 
        else 
        {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
            
        return $pageURL;
    }
}


