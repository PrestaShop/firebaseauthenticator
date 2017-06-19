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

function doAjaxLogin(redirect) {
    $('#error').hide();
    $('#login_form').fadeIn('slow', function () {
        $.ajax({
            type: "POST",
            headers: {"cache-control": "no-cache"},
            url: "index.php" + '?rand=' + new Date().getTime(),
            async: true,
            dataType: "json",
            data: {
                ajax: "1",
                token: "",
                controller: "AdminLogin",
                module: "firebaseauthenticator",
                submitLogin: "1",
                passwd: $('#passwd').val(),
                email: $('#email').val(),
                redirect: redirect,
                stay_logged_in: $('#stay_logged_in:checked').val()
            },
            beforeSend: function () {
                feedbackSubmit();
                l.start();
            },
            success: function (jsonData) {
                if (jsonData.hasErrors) {
                    displayErrors(jsonData.errors);
                    l.stop();
                } else {
                    window.location.assign(jsonData.redirect);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                l.stop();
                $('#error').html('<h3>TECHNICAL ERROR:</h3><p>Details: Error thrown: ' + XMLHttpRequest + '</p><p>Text status: ' + textStatus + '</p>').removeClass('hide');
                $('#login_form').fadeOut('slow');
            }
        });
    });
}
