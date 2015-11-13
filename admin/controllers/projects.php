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
 * Crowdfunding projects controller
 *
 * @package      CrowdfundingFinance
 * @subpackage   Components
 */
class CrowdfundingFinanceControllerProjects extends Prism\Controller\Admin
{
    public function getModel($name = 'Project', $prefix = 'CrowdfundingFinanceModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
