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
 * @copyright  Copyright (c) 2015-2016 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace Payunity;

use Payunity\Shop;
use Pimcore\API\Plugin\AbstractPlugin;
use Pimcore\API\Plugin\PluginInterface;

/**
 * Class Plugin
 * @package Payunity
 */
class Plugin  extends AbstractPlugin implements PluginInterface
{
    /**
     * @var int
     */
    private static $requiredCoreShopBuild = 72;

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
        $p = PIMCORE_PLUGINS_PATH . '/CoreShop/Plugin.xml';

        if( !file_exists($p)) {
            return false;
        }

        $config = new \Zend_Config_Xml($p);
        if( (int) $config->plugin->pluginRevision < self::$requiredCoreShopBuild) {
            return false;
        }

        try {
            \Pimcore\Model\Object\Objectbrick\Definition::getByKey("CoreShopPaymentPayunity");

            return true;
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Install Plugin
     */
    public static function install()
    {
        if( !class_exists("CoreShop\\Version") || (int) \CoreShop\Version::getBuildNumber() < self::$requiredCoreShopBuild ) {
            return 'You need CoreShop (at least build' . self::$requiredCoreShopBuild .') to run this plugin.';
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
        if (class_exists("\\CoreShop\\Plugin")) {
            \CoreShop\Plugin::uninstallPlugin(self::getShop()->getInstall());
        }
    }

    /**
     * @return string
     */
    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH . '/CoreShopCod/static/texts';
    }

    /**
     * @param string $language
     * @return string path to the translation file relative to plugin directory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(self::getTranslationFileDirectory() . "/$language.csv")) {
            return "/Payunity/static/texts/$language.csv";
        } else {
            return '/Payunity/static/texts/en.csv';
        }
    }
}
