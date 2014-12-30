<?php


class Payunity_Plugin  extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface 
{
/*

    public function init() {
        // register your events here

        // using anonymous function
        Pimcore::getEventManager()->attach("document.postAdd", function ($event) {
            // do something
            $document = $event->getTarget();
        });

        // using methods
        Pimcore::getEventManager()->attach("document.postUpdate", array($this, "handleDocument"));

        // for more information regarding events, please visit:
        // http://www.pimcore.org/wiki/display/PIMCORE/Event+API+%28EventManager%29+since+2.1.1
        // http://framework.zend.com/manual/1.12/de/zend.event-manager.event-manager.html
        // http://www.pimcore.org/wiki/pages/viewpage.action?pageId=12124202

    }
*/

    protected static $installedFileName = "/var/config/.Payunity";

    public static function isInstalled()
    {
        return file_exists(PIMCORE_WEBSITE_PATH . self::$installedFileName);
    }
    
    /*
public function preDispatch($e)
    {
        include_once(PIMCORE_PLUGINS_PATH . '/Payunity/vendor/autoload.php');
    }
*/

    public static function install()
    {
        touch(PIMCORE_WEBSITE_PATH . self::$installedFileName);
    }
    
    public static function uninstall()
    {
        unlink(PIMCORE_WEBSITE_PATH . self::$installedFileName);
    }
}


