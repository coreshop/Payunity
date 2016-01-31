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

use Payunity\Shop;
use Pimcore\API\Plugin\AbstractPlugin;
use Pimcore\API\Plugin\PluginInterface;

class Plugin  extends AbstractPlugin implements PluginInterface
{
    protected static $configPath = "/var/config/payunity.xml";

    /**
     * @var Shop
     */
    private static $shop;

    /**
     * @param $e
     */
    public function preDispatch($e)
    {
        parent::preDispatch();
        
        self::getShop()->attachEvents();
    }

    /**
     * @return Shop
     */
    public static function getShop()
    {
        if (!self::$shop) {
            self::$shop = new Shop();
        }
        return self::$shop;
    }

    /**
     * Check if Plugin is installed
     *
     * @return bool
     */
    public static function isInstalled()
    {
        return file_exists(PIMCORE_WEBSITE_PATH . self::$configPath);
    }

    /**
     * Install Plugin
     */
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

        if (!file_exists(PIMCORE_WEBSITE_PATH . self::$configPath)) {
            file_put_contents(PIMCORE_WEBSITE_PATH . self::$configPath, $configFile);
        }

        if (class_exists("\\CoreShop\\Plugin")) {
            \CoreShop\Plugin::installPlugin(self::getShop()->getInstall());
        }
    }

    /**
     * Uninstall Plugin
     */
    public static function uninstall()
    {
        if (file_exists(PIMCORE_WEBSITE_PATH . self::$configPath)) {
            unlink(PIMCORE_WEBSITE_PATH . self::$configPath);
        }

        if (class_exists("\\CoreShop\\Plugin")) {
            \CoreShop\Plugin::uninstallPlugin(self::getShop()->getInstall());
        }
    }
    
    /**
     * @static
     * @return array
     */
    public static function getConfigArray()
    {
        $config = new \Zend_Config_Xml(PIMCORE_WEBSITE_PATH . self::$configPath);

        return $config;
    }
}
