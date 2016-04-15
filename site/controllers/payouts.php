<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Payouts
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * This controller provides functionality
 * that helps to payout plugins to prepare their payment data.
 *
 * @package        CrowdfundingFinance
 * @subpackage     Payouts
 *
 */
class CrowdfundingFinanceControllerPayouts extends JControllerLegacy
{
    protected $projectId;

    protected $app;

    /**
     * Tasks that needs form token.
     *
     * @var array
     */
    protected $tokenTasks = array('deauthorize');

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->app = JFactory::getApplication();
        /** @var $app JApplicationSite */

        // Get project id.
        $this->projectId = $this->input->getUint('pid');

        // Task used from user to authorize or deauthorize to their payment gateways.
        $this->registerTask('authorize', 'process');
        $this->registerTask('deauthorize', 'process');
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param    string $name   The model name. Optional.
     * @param    string $prefix The class prefix. Optional.
     * @param    array  $config Configuration array for model. Optional.
     *
     * @return    CrowdfundingFinanceModelPayout    The model.
     * @since    1.5
     */
    public function getModel($name = 'Payout', $prefix = 'CrowdfundingFinanceModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    /**
     * Task used for processing events.
     *
     * @throws Exception
     */
    public function process()
    {
        // Get the task.
        $task    = JString::strtolower($this->input->getCmd('task'));
        if (!$task) {
            throw new Exception(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_TASK'));
        }

        // Check for request forgeries.
        if (in_array($task, $this->tokenTasks, true)) {
            if (strcmp('POST', $this->app->input->getMethod()) !== 0) {
                JSession::checkToken('GET') or jexit(JText::_('JINVALID_TOKEN'));
            } else {
                JSession::checkToken('POST') or jexit(JText::_('JINVALID_TOKEN'));
            }
        }

        // Get component parameters
        $params = JComponentHelper::getParams('com_crowdfunding');
        /** @var  $params Joomla\Registry\Registry */

        // Check for disabled payment functionality
        if ($params->get('debug_payment_disabled', 0)) {
            throw new Exception(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_PAYMENT_HAS_BEEN_DISABLED_MESSAGE'));
        }

        // Get payment gateway name.
        $paymentService = $this->input->getCmd('payment_service');
        if (!$paymentService) {
            throw new UnexpectedValueException(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_PAYMENT_GATEWAY'));
        }
        
        $output = array();

        // Trigger the event
        try {
            $context = 'com_crowdfundingfinance.payouts.'.$task.'.' . JString::strtolower($paymentService);

            // Import Crowdfunding Payment Plugins
            $dispatcher = JEventDispatcher::getInstance();
            JPluginHelper::importPlugin('crowdfunding');

            // Trigger the event.
            $results = $dispatcher->trigger('onPayouts'.JString::ucwords($task), array($context, &$params));

            // Get the result, that comes from the plugin.
            if (is_array($results) and count($results) > 0) {
                foreach ($results as $result) {
                    if ($result !== null and is_array($result)) {
                        $output = $result;
                        break;
                    }
                }
            }

        } catch (UnexpectedValueException $e) {
            $this->setMessage($e->getMessage(), 'notice');
            $this->setRedirect(JRoute::_(CrowdfundingHelperRoute::getDiscoverRoute(), false));
            return;
        } catch (Exception $e) {
            // Store log data in the database
            JLog::add($e->getMessage());

            throw new Exception(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'));
        }

        $redirectUrl = Joomla\Utilities\ArrayHelper::getValue($output, 'redirect_url');
        $message     = Joomla\Utilities\ArrayHelper::getValue($output, 'message');
        if (!$redirectUrl) {
            throw new UnexpectedValueException(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_REDIRECT_URL'));
        }

        if (!$message) {
            $this->setRedirect($redirectUrl);
        } else {
            $this->setRedirect($redirectUrl, $message, 'notice');
        }
    }
}
