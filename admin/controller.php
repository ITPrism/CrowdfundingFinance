<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Default controller
 *
 * @package        CrowdfundingFinance
 * @subpackage     Components
 */
class CrowdfundingFinanceController extends JControllerLegacy
{
    protected $option;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }

    public function display($cachable = false, $urlparams = array())
    {
        $viewName = $this->input->getCmd('view', 'dashboard');
        $this->input->set("view", $viewName);

        $doc = JFactory::getDocument();
        $doc->addStyleSheet("../media/" . $this->option . '/css/backend.style.css');

        parent::display($cachable, $urlparams);

        return $this;

    }
}
