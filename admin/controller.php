<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Default controller
 *
 * @package        Crowdfundingfinance
 * @subpackage     Components
 */
class CrowdfundingfinanceController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        $viewName = $this->input->getCmd('view', 'dashboard');
        $this->input->set('view', $viewName);

        $doc = JFactory::getDocument();
        $doc->addStyleSheet('../media/com_crowdfundingfinance/css/backend.style.css');
        JHtml::_('Prism.ui.backendStyles');
        JHtml::_('Prism.ui.styles');

        parent::display($cachable, $urlparams);

        return $this;
    }
}
