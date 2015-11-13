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

class CrowdfundingFinanceViewPayouts extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $cfParams;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $state;

    protected $items;
    protected $pagination;

    protected $amount;
    protected $transactions;

    protected $option;

    protected $listOrder;
    protected $listDirn;
    protected $saveOrder;
    protected $saveOrderingUrl;

    protected $sortFields;

    protected $sidebar;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }

    public function display($tpl = null)
    {
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Get parameters of com_crowdfunding.
        /** @var  $cParams Joomla\Registry\Registry */
        $cParams        = JComponentHelper::getParams("com_crowdfunding");
        $this->cfParams = $cParams;

        $currency = Crowdfunding\Currency::getInstance(JFactory::getDbo(), $this->cfParams->get("project_currency"));

        $this->amount = new Crowdfunding\Amount($this->cfParams);
        $this->amount->setCurrency($currency);

        // Get transactions number.
        $projectsIds = array();
        foreach ($this->items as $item) {
            $projectsIds[] = $item->id;
        }

        $projects           = new Crowdfunding\Projects(JFactory::getDbo());
        $this->transactions = $projects->getTransactionsNumber($projectsIds);

        // Add submenu
        CrowdfundingFinanceHelper::addSubmenu($this->getName());

        // Prepare sorting data
        $this->prepareSorting();

        // Prepare actions
        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Prepare sortable fields, sort values and filters.
     */
    protected function prepareSorting()
    {
        // Prepare filters
        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn  = $this->escape($this->state->get('list.direction'));

        $this->sortFields = array(
            'a.published'     => JText::_('JSTATUS'),
            'a.title'         => JText::_('COM_CROWDFUNDINGFINANCE_TITLE'),
            'b.title'         => JText::_('COM_CROWDFUNDINGFINANCE_CATEGORY'),
            'a.id'            => JText::_('JGRID_HEADING_ID')

        );

    }

    /**
     * Add a menu on the sidebar of page
     */
    protected function addSidebar()
    {
        // Prepare options
        $approvedOptions = array(
            JHtml::_("select.option", 1, JText::_("COM_CROWDFUNDINGFINANCE_APPROVED")),
            JHtml::_("select.option", 0, JText::_("COM_CROWDFUNDINGFINANCE_DISAPPROVED")),
        );

        $featuredOptions = array(
            JHtml::_("select.option", 1, JText::_("COM_CROWDFUNDINGFINANCE_FEATURED")),
            JHtml::_("select.option", 0, JText::_("COM_CROWDFUNDINGFINANCE_NOT_FEATURED")),
        );

        JHtmlSidebar::setAction('index.php?option=' . $this->option . '&view=' . $this->getName());

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'filter_state',
            JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
        );

        JHtmlSidebar::addFilter(
            JText::_('COM_CROWDFUNDINGFINANCE_SELECT_APPROVED_STATUS'),
            'filter_approved',
            JHtml::_('select.options', $approvedOptions, 'value', 'text', $this->state->get('filter.approved'), true)
        );

        JHtmlSidebar::addFilter(
            JText::_('COM_CROWDFUNDINGFINANCE_SELECT_FEATURED_STATUS'),
            'filter_featured',
            JHtml::_('select.options', $featuredOptions, 'value', 'text', $this->state->get('filter.featured'), true)
        );

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_CATEGORY'),
            'filter_category_id',
            JHtml::_('select.options', JHtml::_('category.options', 'com_crowdfunding'), 'value', 'text', $this->state->get('filter.category_id'))
        );

        $this->sidebar = JHtmlSidebar::render();
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        // Set toolbar items for the page
        JToolbarHelper::title(JText::_('COM_CROWDFUNDINGFINANCE_PAYOUTS'));

        JToolbarHelper::divider();
        JToolbarHelper::custom('payouts.backToDashboard', "dashboard", "", JText::_("COM_CROWDFUNDINGFINANCE_DASHBOARD"), false);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_PAYOUTS'));

        // Scripts
        JHtml::_('behavior.multiselect');
        JHtml::_('bootstrap.tooltip');

        JHtml::_('formbehavior.chosen', 'select');

        JHtml::_('prism.ui.joomlaList');

        $this->document->addScript('../media/' . $this->option . '/js/admin/' . JString::strtolower($this->getName()) . '.js');
    }
}
