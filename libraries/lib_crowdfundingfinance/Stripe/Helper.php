<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Stripe
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Crowdfundingfinance\Stripe;

use Joomla\Registry\Registry;
use Crowdfundingfinance\Payout;
use League\OAuth2\Client\Token\AccessToken;
use AdamPaterson\OAuth2\Client\Provider\Stripe;

// Import Stripe library.
jimport('Prism.libs.init');

defined('JPATH_PLATFORM') or die;

/**
 * This class provides some helpful functions used for interacting with Stripe.
 *
 * @package      Crowdfundingfinance
 * @subpackage   Stripe
 */
abstract class Helper
{
    /**
     * Check if the user access token connected to payout exists. If it does not exist, returns null.
     *
     * If it exist, check for its expiration. If the token has been expired, refresh it with new one.
     *
     * @param array   $apiKeys
     * @param Payout  $payout
     * @param int     $expires
     *
     * @return Registry|AccessToken|null
     */
    public static function getPayoutAccessToken($apiKeys, Payout $payout, $expires = 7)
    {
        try {
            $token = $payout->getStripe();

            // Try to get an access token (using the authorization code grant)
            $alias = (!$apiKeys['test']) ? 'production' : 'test';

            if ($token === null or !$token->get('stripeconnect.'.$alias.'.access_token')) {
                return null;
            }

            $options = array(
                'access_token'  => $token->get('stripeconnect.'.$alias.'.access_token'),
                'refresh_token' => $token->get('stripeconnect.'.$alias.'.refresh_token'),
                'expires'       => $token->get('stripeconnect.'.$alias.'.expires')
            );

            $accessToken = new AccessToken($options);

            if ($accessToken->hasExpired()) {
                $provider = new Stripe([
                    'clientId'      => $apiKeys['client_id'],
                    'clientSecret'  => $apiKeys['secret_key']
                ]);

                $accessToken = $provider->getAccessToken('refresh_token', ['refresh_token' => $token->get('stripeconnect.'.$alias.'.refresh_token')]);

                // Prepare expiration date.
                $date = new \JDate();
                $date->add(new \DateInterval('P'.$expires.'D'));

                $token->set('stripeconnect.'.$alias.'.access_token', $accessToken->getToken());
                $token->set('stripeconnect.'.$alias.'.refresh_token', $accessToken->getRefreshToken());
                $token->set('stripeconnect.'.$alias.'.expires', $date->getTimestamp());

                $payout->setStripe($token);
                $payout->storeStripe();
            }

        } catch (\Exception $e) {
            \JLog::add($e->getMessage());
            return null;
        }

        return $accessToken;
    }

    /**
     * Get the keys from extension options.
     *
     * @param Registry $params
     *
     * @return array
     */
    public static function getKeys($params)
    {
        $keys = array();

        if (!$params->get('stripe_sandbox_enabled', 1)) { // Live server keys.
            $keys['client_id']  = \JString::trim($params->get('stripe_client_id'));
            $keys['secret_key'] = \JString::trim($params->get('stripe_secret_key'));
            $keys['published_key'] = \JString::trim($params->get('stripe_published_key'));
            $keys['test'] = false;
        } else {// Test server keys.
            $keys['client_id'] = \JString::trim($params->get('stripe_sandbox_client_id'));
            $keys['secret_key'] = \JString::trim($params->get('stripe_sandbox_secret_key'));
            $keys['published_key'] = \JString::trim($params->get('stripe_sandbox_published_key'));
            $keys['test'] = true;
        }

        return $keys;
    }

    /**
     * Get the keys from extension options.
     *
     * @param array $keys
     * @param string $stripeUserId
     *
     * @return bool
     */
    public static function deauthorize(array $keys, $stripeUserId)
    {
        $data = array(
            'client_secret'  => \JString::trim($keys['secret_key']),
            'client_id'      => \JString::trim($keys['client_id']),
            'stripe_user_id' => $stripeUserId,
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://connect.stripe.com/oauth/deauthorize');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (is_string($response)) {
            $response = new Registry($response);

            if ($response->get('error')) {
                \JLog::add($response->get('error_description'));
                return false;
            }
        }

        curl_close($ch);

        return true;
    }
}
