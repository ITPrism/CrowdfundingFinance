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
 * Crowdfunding Finance payout controller class.
 *
 * @package        ITPrism Components
 * @subpackage     Crowdfunding
 * @since          1.6
 */
class CrowdfundingFinanceControllerPayout extends JControllerLegacy
{
    public function getModel($name = 'Payout', $prefix = 'CrowdfundingFinanceModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function getAdditionalInfo()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $itemId = $app->input->getInt('id');
        $type   = $app->input->getCmd('type');

        // Check for errors.
        if (!$itemId) {
            JFactory::getApplication()->close();
        }

        $result = "";

        try {

            $model = $this->getModel();
            $item  = $model->getItem($itemId);

            switch ($type) {

                case "paypal":
                    $result .= "<div><strong>".JText::_("COM_CROWDFUNDINGFINANCE_EMAIL")     ."</strong> :".htmlentities($item->paypal_email, ENT_QUOTES, "UTF-8") . "</div>";
                    $result .= "<div><strong>".JText::_("COM_CROWDFUNDINGFINANCE_FIRST_NAME")."</strong> :".htmlentities($item->paypal_first_name, ENT_QUOTES, "UTF-8") . "</div>";
                    $result .= "<div><strong>".JText::_("COM_CROWDFUNDINGFINANCE_LAST_NAME") ."</strong> :".htmlentities($item->paypal_last_name, ENT_QUOTES, "UTF-8") . "</div>";
                    break;

                case "banktransfer":
                    $result = nl2br($item->bank_account);
                    break;

            }

        } catch (Exception $e) {

            JLog::add($e->getMessage());
            JFactory::getApplication()->close();

        }

        echo $result;
        JFactory::getApplication()->close();
    }
}
