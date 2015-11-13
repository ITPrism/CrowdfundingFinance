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

JLoader::register("CrowdfundingControllerTransactions", CROWDFUNDING_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "transactions.php");

/**
 * Crowdfunding Finance transactions controller class
 *
 * @package      CrowdfundingFinance
 * @subpackage   Components
 */
class CrowdfundingFinanceControllerTransactions extends CrowdfundingControllerTransactions
{
    public function getModel($name = 'Transaction', $prefix = 'CrowdfundingFinanceModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}
