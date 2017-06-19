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

use GuzzleHttp\Client;

class FirebaseClient
{
    /**
     * Client used to request Firebase API
     * @var Client
     */
    protected $client;

    /**
     * API key used for calls to Firebase
     * @var string
     */
    protected $apiKey;

    public function __construct(array $params = array())
    {
        if (isset($params['api_key'])) {
            $this->apiKey = $params['api_key'];
        } else {
            $this->apiKey = Configuration::get('FIREBASE_API_KEY');
        }

        $this->client = new Client(
            array(
                'base_url' => 'https://www.googleapis.com/identitytoolkit/v3/relyingparty',
                'defaults' => array(
                    'timeout'         => 10,
                    'allow_redirects' => false,
                    'query' => array(
                        'key' => $this->apiKey,
                    ),
                )
            )
        );
    }

    public function signInWithEmailAndPassword($email, $password)
    {
        $response = (string) $this->client->post('/verifyPassword', array(
            'query' => array(
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ),
        ))->getBody();

        $response = json_decode($response);

        $this->checkErrors($response);
        return $response;
    }

    protected function checkErrors($response)
    {
        // {
        //    "error": {
        //      "errors": [
        //        {
        //          "domain": "global",
        //          "reason": "invalid",
        //          "message": "CREDENTIAL_TOO_OLD_LOGIN_AGAIN"
        //        }
        //      ],
        //      "code": 400,
        //      "message": "CREDENTIAL_TOO_OLD_LOGIN_AGAIN"
        //    }
        // }

        if ($response->error) {
            throw new Exception('API answered with errror '. $response->error->message, $response->error->code);
        }
    }
}