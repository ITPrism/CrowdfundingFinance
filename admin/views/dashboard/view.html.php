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

class CrowdfundingfinanceViewDashboard extends JViewLegacy
{
    use Crowdfunding\Helper\MoneyHelper;

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
    protected $money;
    protected $version;
    protected $prismVersion;
    protected $prismVersionLowerMessage;

    protected $sidebar;

    public function display($tpl = null)
    {
        $this->version = new Crowdfundingfinance\Version();

        // Load Prism library version
        if (!class_exists('Prism\\Version')) {
            $this->prismVersion = JText::_('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_DOWNLOAD');
        } else {
            $prismVersion       = new Prism\Version();
            $this->prismVersion = $prismVersion->getShortVersion();

            if (version_compare($this->prismVersion, $this->version->requiredPrismVersion, '<')) {
                $this->prismVersionLowerMessage = JText::_('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_LOWER_VERSION');
            }
        }

        $this->cfParams = JComponentHelper::getParams('com_crowdfunding');
        /** @var  $cfParams Joomla\Registry\Registry */

        // Get latest transactions.
        $this->latest = new Crowdfunding\Statistics\Transactions\Latest(JFactory::getDbo());
        $this->latest->load(array('limit' => 5));

        $basic                   = new Crowdfunding\Statistics\Basic(JFactory::getDbo());
        $this->totalProjects     = $basic->getTotalProjects();
        $this->totalTransactions = $basic->getTotalTransactions();
        $this->totalAmount       = $basic->getTotalAmount();

        $this->money             = $this->getMoneyFormatter($this->cfParams);

        // Add submenu
        CrowdfundingfinanceHelper::addSubmenu($this->getName());

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
        JToolbarHelper::title(JText::_('COM_CROWDFUNDINGFINANCE_DASHBOARD'));

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
