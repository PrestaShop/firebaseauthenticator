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
                'base_url' => 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/',
                'defaults' => array(
                    'timeout'         => 10,
                    'allow_redirects' => false,
                    'query' => array(
                        'key' => $this->apiKey,
                    ),
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                )
            )
        );
    }

    /**
     * Auth user with email & password
     *
     * @link https://firebase.google.com/docs/reference/rest/auth/#section-sign-in-email-password Firebase documentation
     * @param string $email
     * @param string $password
     * @return object
     */
    public function signInWithEmailAndPassword($email, $password)
    {
        $response = $this->client->post('verifyPassword', array(
            'json' => array(
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true,
            ),
        ));
        $body = json_decode((string)$response->getBody());

        $this->checkErrors($response, $body);
        return $body;
    }

    /**
     * Get user details related to API token in order to authentify him
     *
     * @link https://firebase.google.com/docs/reference/rest/auth/#section-get-account-info Firebase documentation
     * @param string $token
     * @return array
     */
    public function signInWithToken($token)
    {
        $response = $this->client->post('getAccountInfo', array(
            'json' => array(
                'idToken' => $token,
            ),
        ));
        $body = json_decode((string)$response->getBody());

        $this->checkErrors($response, $body);
        return $body->users;
    }

    /**
     * Firebase API returns a specific response structure in case of error
     * https://firebase.google.com/docs/reference/rest/auth/#section-error-format
     *
     * @param GuzzleHttp\Message\ResponseInterface $response
     * @param object $body JSON response content
     * @throws Exception
     */
    protected function checkErrors($response, $body)
    {
        if ($body->error) {
            throw new Exception('API answered with errror '. $response->error->message, $response->error->code);
        }
        if ($response->getStatusCode() !== 200) {
            throw new Exception('Unexpected HTTP status '.$response->getStatusCode().' returned', $response->getStatusCode());
        }
    }
}
