<?php

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

        $this->registerHook('displayAdminLogin');
        $this->registerHook('actionAdminLoginOptionsModifier');
        return true;
    }

    public function hookDisplayAdminLogin($params)
    {
        return $this->displayHook(__FUNCTION__, $params);
    }

    public function hookActionAdminLoginOptionsModifier($params)
    {
        dump(__FUNCTION__, $params);
    }

    public function displayHook($name, $params)
    {
        dump($name, $params);
        return '<div class="form-group">
                            <h2>
                              Authenticate with my awesome module:
                              <span class="help-box" data-toggle="popover" data-content="That\'s an original input :o" data-original-title="" title=""></span>
                            </h2>

                            <div class="row">
                              <div class="col-xl-12 col-lg-12">
                                  <button class="btn btn-primary btn-lg btn-block ladda-button">LOG ME IN!</button>
                                  <input id="" name="form[module][adminproductpage][]" class="form-control" type="text" value="'.$name.'">
                              </div>
                            </div>
                          </div>';
    }
}