<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Crowdfunding Finance project controller class.
 *
 * @package        ITPrism Components
 * @subpackage     Crowdfunding
 * @since          1.6
 */
class CrowdfundingFinanceControllerStatistics extends JControllerLegacy
{
    public function getProjectTransactions()
    {
        // Create response object
        $response = new Prism\Response\Json();

        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $itemId = $app->input->getInt('id');

        // Check for errors.
        if (!$itemId) {
            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_PROJECT'))
                ->failure();

            echo $response;
            JFactory::getApplication()->close();
        }

        $data = array();

        try {

            // Get statistics
            $project = new Crowdfunding\Statistics\Project(JFactory::getDbo(), $itemId);
            $data    = $project->getFullPeriodAmounts();

        } catch (Exception $e) {

            JLog::add($e->getMessage());

            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            JFactory::getApplication()->close();

        }

        $response
            ->setData($data)
            ->success();

        echo $response;
        JFactory::getApplication()->close();
    }


    public function getProjectFunds()
    {
        // Create response object
        $response = new Prism\Response\Json();

        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $itemId = $app->input->getInt('id');

        // Check for errors.
        if (!$itemId) {
            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_PROJECT'))
                ->failure();

            echo $response;
            JFactory::getApplication()->close();
        }

        try {

            // Get statistics
            $project = new Crowdfunding\Statistics\Project(JFactory::getDbo(), $itemId);
            $data    = $project->getFundedAmount();

        } catch (Exception $e) {

            JLog::add($e->getMessage());

            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            JFactory::getApplication()->close();

            throw new Exception(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'));

        }

        $response
            ->setData($data)
            ->success();

        echo $response;
        JFactory::getApplication()->close();
    }
}
