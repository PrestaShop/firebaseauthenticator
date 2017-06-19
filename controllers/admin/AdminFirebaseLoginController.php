<?php
/*
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

require_once(__DIR__.'/../../classes/FirebaseClient.php');


class AdminFirebaseLoginController extends AdminLoginController
{
    // Declared from parent class
    /*public function checkToken() { return true; }

    public function viewAccess() { return true; }*/

    public function postProcess()
    {
        if (!Tools::isSubmit('submitLogin')) {
            return;
        }

        /* Check fields validity */
        $passwd = trim(Tools::getValue('passwd'));
        $email = trim(Tools::getValue('email'));
        if (empty($email)) {
            $this->errors[] = $this->trans('Email is empty.', array(), 'Admin.Notifications.Error');
        } elseif (!Validate::isEmail($email)) {
            $this->errors[] = $this->trans('Invalid email address.', array(), 'Admin.Notifications.Error');
        }

        if (empty($passwd)) {
            $this->errors[] = $this->trans('The password field is blank.', array(), 'Admin.Notifications.Error');
        } elseif (!Validate::isPasswd($passwd)) {
            $this->errors[] = $this->trans('Invalid password.', array(), 'Admin.Notifications.Error');
        }

        $this->returnOnError();

        /**
         * If form correct, go on the authentication with Firebase
         * https://firebase.google.com/docs/reference/rest/auth/#section-sign-in-email-password
         */
        $client = new FirebaseClient();
        try {
            $user = $client->signInWithEmailAndPassword($email, $passwd);
        } catch (Exception $e) {
            PrestaShopLogger::addLog(sprintf($this->trans('Failed authentication with Firebase: %s', array(), 'Admin.Advparameters.Feature'), $e->getMessage()), 1);
        }

        if (!isset($user) || !$user->registered || $user->email !== $email) {
            return parent::postProcess();
        }

        // Find employee
        $this->context->employee = new Employee();
        $is_employee_loaded = $this->context->employee->getByEmail($email);
        $employee_associated_shop = $this->context->employee->getAssociatedShops();

        // If employee not found, we fallback on the basic auth
        if (!$is_employee_loaded) {
            $this->context->employee->logout();
            return parent::postProcess();
        }

        if (empty($employee_associated_shop) && !$this->context->employee->isSuperAdmin()) {
            $this->errors[] = $this->trans('This employee does not manage the shop anymore (either the shop has been deleted or permissions have been revoked).', array(), 'Admin.Login.Notification');
            $this->returnOnError();
        }
        
        PrestaShopLogger::addLog(sprintf($this->trans('Back office connection from %s', array(), 'Admin.Advparameters.Feature'), Tools::getRemoteAddr()), 1, null, '', 0, true, (int)$this->context->employee->id);

        $this->context->employee->remote_addr = (int)ip2long(Tools::getRemoteAddr());
        // Update cookie
        $cookie = Context::getContext()->cookie;
        $cookie->id_employee = $this->context->employee->id;
        $cookie->email = $this->context->employee->email;
        $cookie->profile = $this->context->employee->id_profile;
        $cookie->passwd = $this->context->employee->passwd;
        $cookie->remote_addr = $this->context->employee->remote_addr;

        if (!Tools::getValue('stay_logged_in')) {
            $cookie->last_activity = time();
        }

        $cookie->write();

        // If there is a valid controller name submitted, redirect to it
        if (isset($_POST['redirect']) && Validate::isControllerName($_POST['redirect'])) {
            $url = $this->context->link->getAdminLink($_POST['redirect']);
        } else {
            $tab = new Tab((int)$this->context->employee->default_tab);
            $url = $this->context->link->getAdminLink($tab->class_name);
        }
        $this->returnOnError();

        if (Tools::isSubmit('ajax')) {
            die(json_encode(array('hasErrors' => false, 'redirect' => $url)));
        } else {
            $this->redirect_after = $url;
        }
    }

    /**
     * This function kill the execution if the errors array has been filled.
     */
    protected function returnOnError()
    {
        if (count($this->errors)) {
            if ($this->context->employee) {
                $this->context->employee->logout();
            }
            die(json_encode(array('hasErrors' => true, 'errors' => $this->errors)));
        }
    }
}