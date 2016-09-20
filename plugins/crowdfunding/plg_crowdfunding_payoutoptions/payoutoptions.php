<?php
/**
 * @package         CrowdfundingPayoutOptions
 * @subpackage      Plugins
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

jimport('Prism.libs.GuzzleHttp.init');
jimport('Crowdfunding.init');
jimport('Crowdfundingfinance.init');

/**
 * Crowdfunding Payout Options Plugin
 *
 * @package        CrowdfundingPayoutOptions
 * @subpackage     Plugins
 */
class plgCrowdfundingPayoutOptions extends JPlugin
{
    protected $autoloadLanguage = true;

    protected $version = '2.4';

    /**
     * @var Prism\Log\Log
     */
    protected $log;

    protected $textPrefix = 'PLG_CROWDFUNDING_PAYOUTOPTIONS';
    protected $debugType  = 'PLG_CROWDFUNDING_PAYOUTOPTIONS';

    /**
     * Application object.
     *
     * @var \JApplicationSite
     */
    protected $app;

    public function __construct(&$subject, $config = array())
    {
        parent::__construct($subject, $config);

        // Create log object
        $this->log = new Prism\Log\Log();

        // Set file adapter.
        $file = \JPath::clean($this->app->get('log_path') . DIRECTORY_SEPARATOR . 'plg_crowdfunding_payout_options.php');
        $this->log->addAdapter(new Prism\Log\Adapter\File($file));
    }

    /**
     * This method prepares a code that will be included to step "Extras" on project wizard.
     *
     * @param string    $context This string gives information about that where it has been executed the trigger.
     * @param stdClass    $item    A project data.
     * @param Joomla\Registry\Registry $params  The parameters of the component
     *
     * @return null|string
     */
    public function onExtrasDisplay($context, $item, $params)
    {
        if (strcmp('com_crowdfunding.project.extras', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }
        
        if (!isset($item->user_id) or !$item->user_id) {
            return null;
        }

        // A flag that shows the options are active.
        if (!$this->params->get('display_paypal', 0) and !$this->params->get('display_banktransfer', 0) and !$this->params->get('display_stripe', 0)) {
            return '';
        }

        $activeTab = '';
        if ($this->params->get('display_paypal', 0)) {
            $activeTab = 'paypal';
        } elseif ($this->params->get('display_banktransfer', 0)) {
            $activeTab = 'banktransfer';
        } elseif ($this->params->get('display_stripe', 0)) {
            $activeTab = 'stripe';
        }

        $payout = new Crowdfundingfinance\Payout(JFactory::getDbo());
        $payout->setSecretKey($this->app->get('secret'));
        $payout->load(array('project_id' => $item->id));

        // Create payout record, if it does not exists.
        if (!$payout->getId()) {
            $payout->setProjectId($item->id);
            $payout->store();
        }

        // Check if Stripe connected.
        if ($this->params->get('display_stripe', 0)) {
            $stripeWarning   = null;
            $stripeButton   = array();

            $cfFinanceParams = JComponentHelper::getParams('com_crowdfundingfinance');

            // Get keys.
            $apiKeys = Crowdfundingfinance\Stripe\Helper::getKeys($cfFinanceParams);
            if (!$apiKeys['client_id']) {
                $stripeWarning = JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_ERROR_STRIPE_NOT_CONFIGURED');
            }

            $token = Crowdfundingfinance\Stripe\Helper::getPayoutAccessToken($apiKeys, $payout, $cfFinanceParams->get('stripe_expiration_period', 7));

            // Generate state HASH and use it as a session key that contains redirect URL.
            $state       = Prism\Utilities\StringHelper::generateRandomString(32);
            $stateData   = array(
                'redirect_url' => base64_encode(JRoute::_(CrowdfundingHelperRoute::getFormRoute($item->id, 'extras'), false)),
                'project_id'   => $item->id
            );

            $this->app->setUserState($state, $stateData);

            if (!$token) {
                $stripeButton[] = '<div class="mt-20">';
                $stripeButton[] = '<a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=' . $apiKeys['client_id'] . '&scope=read_write&state=' . $state . '&redirect_uri='.rawurlencode($this->params->get('stripe_redirect_uri')).'">';
                $stripeButton[] = '<img src="media/com_crowdfundingfinance/images/stripe/' . $cfFinanceParams->get('button', 'blue-on-dark') . '.png" width="190" height="33" />';
                $stripeButton[] = '</a>';
                $stripeButton[] = '</div>';
            } else {
                $url = JRoute::_('index.php?option=com_crowdfundingfinance&task=payouts.deauthorize&payment_service=stripeconnect&pid='.(int)$item->id.'&state='.$state.'&'.JSession::getFormToken().'=1');

                $stripeButton[] = '<div class="mt-20">';
                $stripeButton[] = '<p class="alert alert-info"><span class="fa fa-info-circle"></span> '.JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_STRIPE_CONNECTED').'</p>';
                $stripeButton[] = '<a href="'.$url.'" class="btn btn-danger" id="js-cff-btn-stripe-disconnect">';
                $stripeButton[] = '<span class="fa fa-chain-broken"></span> '.JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_DISCONNECT_STRIPE');
                $stripeButton[] = '</a>';
                $stripeButton[] = '</div>';
            }
        }

        // Load jQuery
        JHtml::_('jquery.framework');
        JHtml::_('Prism.ui.pnotify');
        JHtml::_('Prism.ui.joomlaHelper');

        // Get the path for the layout file
        $path = JPath::clean(JPluginHelper::getLayoutPath('crowdfunding', 'payoutoptions'));

        // Render the login form.
        ob_start();
        include $path;
        $html = ob_get_clean();

        return $html;
    }

    /**
     * Authorize user to payment gateway.
     *
     * @param string                   $context
     * @param Joomla\Registry\Registry $params
     *
     * @return null|array
     */
    public function onPayoutsAuthorize($context, $params)
    {
        if (strcmp('com_crowdfundingfinance.payouts.authorize.stripeconnect', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        // Prepare output data.
        $output = array(
            'redirect_url' => '',
            'message'      => ''
        );

        $errorOutput = array(
            'redirect_url' => JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()),
            'message'      => ''
        );

        // DEBUG DATA
        JDEBUG ? $this->log->add(JText::_($this->textPrefix . '_DEBUG_GET_RESPONSE_AUTHORIZE'), $this->debugType, $_GET) : null;

        $userId = JFactory::getUser()->get('id');
        if (!$userId) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_NOT_REGISTERED_USER');
            return $errorOutput;
        }

        // Get token
        $code  = $this->app->input->get('code');
        $state = $this->app->input->get('state');
        if (!$code or !$state) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_INVALID_AUTHORIZATION_DATA');
            return $errorOutput;
        }

        // Get project ID and redirect URL from the session.
        $stateData = $this->app->getUserState($state);
        if (count($stateData) === 0 or (!$stateData['redirect_url'] or !$stateData['project_id'])) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_INVALID_AUTHORIZATION_DATA');
            return $errorOutput;
        }

        $cfFinanceParams = JComponentHelper::getParams('com_crowdfundingfinance');

        $apiKeys = Crowdfundingfinance\Stripe\Helper::getKeys($cfFinanceParams);
        if (!$apiKeys['client_id'] or !$apiKeys['secret_key']) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_CONFIGURATION');
            return $errorOutput;
        }

        // Prepare expiration date.
        $date = new JDate();
        $date->add(new DateInterval('P'.$cfFinanceParams->get('stripe_expiration_period', '7').'D'));

        // Prepare Stripe data.
        $provider = new AdamPaterson\OAuth2\Client\Provider\Stripe([
            'clientId'      => $apiKeys['client_id'],
            'clientSecret'  => $apiKeys['secret_key']
        ]);
        $token = $provider->getAccessToken('authorization_code', ['code' => $code]);

        // Get resource owner.
        $resourceOwner = $provider->getResourceOwner($token);
        
        $alias = (!$apiKeys['test']) ? 'production' : 'test';
        $stripe = new \Joomla\Registry\Registry(array(
            'stripeconnect' => array(
                $alias => array(
                    'access_token'  => $token->getToken(),
                    'refresh_token' => $token->getRefreshToken(),
                    'expires'       => $date->getTimestamp(),
                    'account_id'    => $resourceOwner->getId()
                )
            )
        ));
        
        $payout = new Crowdfundingfinance\Payout(JFactory::getDbo());
        $payout->setSecretKey($this->app->get('secret'));

        $payout->load(array('project_id' => (int)$stateData['project_id']));

        // Create user record.
        if (!$payout->getId()) {
            $payout->setProjectId((int)$stateData['project_id']);
        }
        
        $payout->setStripe($stripe);
        $payout->store();

        // Get next URL.
        $output['redirect_url'] = base64_decode($stateData['redirect_url']);

        return $output;
    }

    /**
     * Disconnect user from payment gateway.
     *
     * @param string                   $context
     * @param Joomla\Registry\Registry $params
     *
     * @return null|array
     */
    public function onPayoutsDeauthorize($context, $params)
    {
        if (strcmp('com_crowdfundingfinance.payouts.deauthorize.stripeconnect', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        // Prepare output data.
        $output = array(
            'redirect_url' => '',
            'message'      => ''
        );

        $errorOutput = array(
            'redirect_url' => JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute()),
            'message'      => ''
        );

        // DEBUG DATA
        JDEBUG ? $this->log->add(JText::_($this->textPrefix . '_DEBUG_GET_RESPONSE_AUTHORIZE'), $this->debugType, $_GET) : null;

        $userId = JFactory::getUser()->get('id');
        if (!$userId) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_NOT_REGISTERED_USER');
            return $errorOutput;
        }

        // Get token
        $state = $this->app->input->get('state');
        if (!$state) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_INVALID_AUTHORIZATION_DATA');
            return $errorOutput;
        }

        // Get project ID and redirect URL from the session.
        $stateData = $this->app->getUserState($state);
        if (count($stateData) === 0 or (!$stateData['redirect_url'] or !$stateData['project_id'])) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_INVALID_AUTHORIZATION_DATA');
            return $errorOutput;
        }

        $cfFinanceParams = JComponentHelper::getParams('com_crowdfundingfinance');

        $apiKeys = Crowdfundingfinance\Stripe\Helper::getKeys($cfFinanceParams);
        if (!$apiKeys['client_id'] or !$apiKeys['secret_key']) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_CONFIGURATION');
            return $errorOutput;
        }

        $payout = new Crowdfundingfinance\Payout(JFactory::getDbo());
        $payout->setSecretKey($this->app->get('secret'));

        $payout->load(array('project_id' => (int)$stateData['project_id']));
        
        if (!$payout->getId()) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_INVALID_PAYOUT');
            return $errorOutput;
        }

        $alias = (!$apiKeys['test']) ? 'production' : 'test';
        
        $stripeData = $payout->getStripe();
        if (!$stripeData->get('stripeconnect.'.$alias.'.account_id')) {
            $errorOutput['message'] = JText::_($this->textPrefix . '_ERROR_NOT_CONNECTED');
            return $errorOutput;
        }

        Crowdfundingfinance\Stripe\Helper::deauthorize($apiKeys, $stripeData->get('stripeconnect.'.$alias.'.account_id'));

        $stripeData->set('stripeconnect.'.$alias.'.access_token', '');
        $stripeData->set('stripeconnect.'.$alias.'.refresh_token', '');
        $stripeData->set('stripeconnect.'.$alias.'.account_id', '');
        $stripeData->set('stripeconnect.'.$alias.'.expires', 0);

        $payout->setStripe($stripeData);
        $payout->storeStripe();
        
        // Get next URL.
        $output['redirect_url'] = base64_decode($stateData['redirect_url']);

        return $output;
    }
}
