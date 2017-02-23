<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\Registry\Registry;
use League\Fractal;
use Crowdfunding\Data\Serializer;
use Crowdfunding\Data\Transformer;

// No direct access
defined('_JEXEC') or die;

/**
 * Crowdfunding Finance statistics controller class.
 *
 * @package        Crowdfundingfinance
 * @subpackage     Components
 * @since          1.6
 */
class CrowdfundingfinanceControllerStatistics extends JControllerLegacy
{
    use Crowdfunding\Helper\MoneyHelper;

    public function getDailyFunds()
    {
        // Create response object
        $response = new Prism\Response\Json();

        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $itemId = $app->input->getUint('id');

        // Check for errors.
        if (!$itemId) {
            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_PROJECT'))
                ->failure();

            echo $response;
            $app->close();
        }

        $data = array();

        try {
            $params = \JComponentHelper::getParams('com_crowdfunding');
            /** @var  $params Registry */

            $money   = $this->getMoneyFormatter($params);

            $project = new Crowdfunding\Statistics\Project(JFactory::getDbo(), $itemId);
            $data    = $project->getFullPeriodAmounts();

            $manager = new Fractal\Manager();
            $manager->setSerializer(new Serializer\Chart\DailyFunds());

            // Run all transformers
            $resource = new Fractal\Resource\Collection($data, new Transformer\Chart\DailyFunds($money));
            $data     = $manager->createData($resource)->toArray();

        } catch (Exception $e) {
            JLog::add($e->getMessage());

            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            $app->close();
        }

        $response
            ->setData($data)
            ->success();

        echo $response;
        $app->close();
    }


    public function getProjectFunds()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        // Create response object
        $response = new Prism\Response\Json();

        $itemId = $app->input->getUint('id');

        // Check for errors.
        if (!$itemId) {
            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_INVALID_PROJECT'))
                ->failure();

            echo $response;
            $app->close();
        }

        $data = array();

        try {
            $params = \JComponentHelper::getParams('com_crowdfunding');
            /** @var  $params Registry */

            $money   = $this->getMoneyFormatter($params);

            $project = new Crowdfunding\Statistics\Project(JFactory::getDbo(), $itemId);
            $data    = $project->getFundedAmount();

            $manager = new Fractal\Manager();
            $manager->setSerializer(new Serializer\Chart\ProjectFunds());

            // Run all transformers
            $resource = new Fractal\Resource\Item($data, new Transformer\Chart\ProjectFunds($money));
            $data = $manager->createData($resource)->toArray();

        } catch (Exception $e) {
            JLog::add($e->getMessage());

            $response
                ->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_FAIL'))
                ->setText(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            $app->close();
        }

        $response
            ->setData($data)
            ->success();

        echo $response;
        $app->close();
    }
}
