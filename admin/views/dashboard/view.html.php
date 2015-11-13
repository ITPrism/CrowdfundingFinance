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

class CrowdfundingFinanceViewDashboard extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $cfParams;

    protected $option;

    protected $latest;
    protected $totalProjects;
    protected $totalTransactions;
    protected $totalAmount;
    protected $amount;
    protected $version;
    protected $itprismVersion;

    protected $sidebar;

    public function display($tpl = null)
    {
        $this->version = new CrowdfundingFinance\Version();

        // Load ITPrism library version
        if (!class_exists("Prism\\Version")) {
            $this->itprismVersion = JText::_("COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_DOWNLOAD");
        } else {
            $itprismVersion       = new Prism\Version();
            $this->itprismVersion = $itprismVersion->getShortVersion();
        }

        /** @var  $cfParams Joomla\Registry\Registry */
        $cfParams       = JComponentHelper::getParams("com_crowdfunding");
        $this->cfParams = $cfParams;

        // Get latest transactions.
        $this->latest = new Crowdfunding\Statistics\Transactions\Latest(JFactory::getDbo());
        $this->latest->load(5);

        $basic                   = new Crowdfunding\Statistics\Basic(JFactory::getDbo());
        $this->totalProjects     = $basic->getTotalProjects();
        $this->totalTransactions = $basic->getTotalTransactions();
        $this->totalAmount       = $basic->getTotalAmount();

        // Get currency.
        $currency = Crowdfunding\Currency::getInstance(JFactory::getDbo(), $this->cfParams->get("project_currency"));

        $this->amount   = new Crowdfunding\Amount($this->cfParams);
        $this->amount->setCurrency($currency);

        // Add submenu
        CrowdfundingFinanceHelper::addSubmenu($this->getName());

        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Add a menu on the sidebar of page
     */
    protected function addSidebar()
    {
        $this->sidebar = JHtmlSidebar::render();
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_("COM_CROWDFUNDINGFINANCE_DASHBOARD"));

        JToolbarHelper::preferences('com_crowdfundingfinance');
        JToolbarHelper::divider();

        // Help button
        $bar = JToolbar::getInstance('toolbar');
        $bar->appendButton('Link', 'help', JText::_('JHELP'), JText::_('COM_CROWDFUNDINGFINANCE_HELP_URL'));
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_DASHBOARD'));

    }
}
