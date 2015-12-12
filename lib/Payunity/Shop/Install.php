<?php

namespace Payunity\Shop;

use CoreShop\Plugin;
use CoreShop\Model\Plugin\InstallPlugin;

use CoreShop\Plugin\Install as Installer;

class Install implements InstallPlugin
{
    public function attachEvents()
    {
        Plugin::getEventManager()->attach("install.post", array($this, "installPost"));
        Plugin::getEventManager()->attach("uninstall.pre", array($this, "uninstallPre"));
    }
    
    public function installPost($e)
    {
        $shopInstaller = $e->getParam("installer");

        $this->install($shopInstaller);

    }
    
    public function uninstallPre($e)
    {
        $shopInstaller = $e->getParam("installer");

        $this->uninstall($shopInstaller);
    }

    public function install(Installer $installer)
    {
        $installer->createObjectBrick("CoreShopPaymentPayunity", PIMCORE_PLUGINS_PATH . "/Payunity/install/objectbrick-CoreShopPaymentPayunity.json");
    }

    public function uninstall(Installer $installer)
    {
        $installer->removeObjectBrick("CoreShopPaymentPayunity");
    }
}