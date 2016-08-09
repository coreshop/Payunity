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

use CoreShop\Model;

/**
 * Class Payunity_AdminController
 */
class Payunity_AdminController extends \CoreShop\Plugin\Controller\Admin
{
    public function getAction()
    {
        $config = new Model\Configuration\Listing();
        $config->setFilter(function ($entry) {
            if (startsWith($entry['key'], "PAYUNITY.")) {
                return true;
            }

            return false;
        });

        $valueArray = array();

        foreach ($config->getConfigurations() as $c) {
            $valueArray[$c->getKey()] = $c->getData();
        }

        $response = array(
            "values" => $valueArray,
        );

        $this->_helper->json($response);
        $this->_helper->json(false);
    }

    public function setAction()
    {
        $values = \Zend_Json::decode($this->getParam("data"));

        $values = array_htmlspecialchars($values);

        foreach ($values as $key => $value) {
            Model\Configuration::set($key, $value);
        }

        $this->_helper->json(array("success" => true));
    }
}
