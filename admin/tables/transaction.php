<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register("CrowdfundingTableTransaction", CROWDFUNDING_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "tables" . DIRECTORY_SEPARATOR . "transaction.php");

class CrowdfundingfinanceTableTransaction extends CrowdfundingTableTransaction
{

}
