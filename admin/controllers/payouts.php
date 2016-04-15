<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Crowdfunding projects controller
 *
 * @package      Crowdfundingfinance
 * @subpackage   Components
 */
class CrowdfundingfinanceControllerProjects extends Prism\Controller\Admin
{
    public function getModel($name = 'Project', $prefix = 'CrowdfundingfinanceModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}
