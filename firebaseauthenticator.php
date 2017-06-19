<?php

require_once(__DIR__.'/classes/FirebaseClient.php');

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