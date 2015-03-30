<?php
    
class Payunity_Shop_Install
{
    public function attachEvents()
    {
        \CoreShop\Plugin::getEventManager()->attach("install.post", array($this, "installPost"));
        \CoreShop\Plugin::getEventManager()->attach("uninstall.pre", array($this, "uninstallPre"));
    }
    
    public function installPost($e)
    {
        $shopInstaller = $e->getParam("installer");

        $shopInstaller->createObjectBrick("CoreShopPaymentPayunity", PIMCORE_PLUGINS_PATH . "/Payunity/install/objectbrick-CoreShopPaymentPayunity.json");
    }
    
    public function uninstallPre($e)
    {
        $shopInstaller = $e->getParam("installer");

        $shopInstaller->removeObjectBrick("CoreShopPaymentPayunity");
    }
}