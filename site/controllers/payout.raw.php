<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Payout controller class.
 *
 * @package        CrowdfundingFinance
 * @subpackage     Component
 * @since          1.6
 */
class CrowdfundingFinanceControllerPayout extends JControllerForm
{
    /**
     * Method to get a model object, loading it if required.
     *
     * @param    string $name   The model name. Optional.
     * @param    string $prefix The class prefix. Optional.
     * @param    array  $config Configuration array for model. Optional.
     *
     * @return   CrowdfundingFinanceModelPayout   The model.
     * @since    1.5
     */
    public function getModel($name = 'Payout', $prefix = 'CrowdfundingFinanceModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
    
    public function save($key = null, $urlVar = null)
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        /** @var $app JApplicationSite */
        
        $response = new Prism\Response\Json();

        $userId    = JFactory::getUser()->get('id');
        if (!$userId) {
            // Send response to the browser
            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            $app->close();
        }
        
        // Get project ID.
        $projectId = $this->input->post->get('project_id');

        // Validate project owner
        $validator = new Crowdfunding\Validator\Project\Owner(JFactory::getDbo(), $projectId, $userId);
        if (!$validator->isValid()) {
            // Send response to the browser
            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_PROJECT'))
                ->failure();

            echo $response;
            $app->close();
        }

        $data = array(
            'id'                => $projectId,
            'paypal_email'      => $this->input->post->get('paypal_email', null, 'string'),
            'paypal_first_name' => $this->input->post->get('paypal_first_name'),
            'paypal_last_name'  => $this->input->post->get('paypal_last_name'),
            'iban'              => $this->input->post->get('iban'),
            'bank_account'      => $this->input->post->get('bank_account', null, 'string')
        );

        $model = $this->getModel();
        /** @var $model CrowdfundingFinanceModelPayout */

        try {

            $model->save($data);

        } catch (Exception $e) {

            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            $app->close();
        }

        $response
            ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_SUCCESS'))
            ->setText(JText::_('COM_CROWDFUNDINGFINANCE_PAYOUT_DATA_SAVED_SUCCESSFULLY'))
            ->success();

        echo $response;
        $app->close();
    }
}
