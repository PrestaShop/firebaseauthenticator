<?php
/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2017 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

require_once(dirname(__FILE__).'/classes/FirebaseClient.php');

class FirebaseAuthenticator extends Module
{
    public function __construct()
    {
        $this->name = 'firebaseauthenticator';
        $this->tab = 'administration';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Hooks for admin login page', array(), 'Modules.Autoupgrade.Admin');
        $this->description = $this->trans('Provides hooks to login your shop.', array(), 'Modules.Autoupgrade.Admin');

        //$this->ps_versions_compliancy = array('min' => '1.7.2.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        parent::install();
        $this->registerHook('actionAdminLoginControllerSetMedia');
        return true;
    }

    public function hookActionAdminLoginControllerSetMedia()
    {
        $this->context->controller->addJs($this->_path.'views/js/login.js');
    }
}
