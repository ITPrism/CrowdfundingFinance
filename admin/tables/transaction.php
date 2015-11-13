<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register("CrowdfundingTableTransaction", CROWDFUNDING_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "tables" . DIRECTORY_SEPARATOR . "transaction.php");

class CrowdfundingFinanceTableTransaction extends CrowdfundingTableTransaction
{

}
