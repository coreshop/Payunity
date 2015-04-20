<?php

class Payunity_Shop_Install implements \CoreShop\Model\Plugin\InstallPlugin
{
    public function attachEvents()
    {
        \CoreShop\Plugin::getEventManager()->attach("install.post", array($this, "installPost"));
        \CoreShop\Plugin::getEventManager()->attach("uninstall.pre", array($this, "uninstallPre"));
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

    public function install(\CoreShop\Plugin\Install $installer)
    {
        $installer->createObjectBrick("CoreShopPaymentPayunity", PIMCORE_PLUGINS_PATH . "/Payunity/install/objectbrick-CoreShopPaymentPayunity.json");
    }

    public function uninstall(\CoreShop\Plugin\Install $installer)
    {
        $installer->removeObjectBrick("CoreShopPaymentPayunity");
    }
}