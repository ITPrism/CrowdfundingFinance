<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use \Crowdfunding\Container\Helper\Money as MoneyHelper;
use \Crowdfunding\Currency\Gateway\JoomlaGateway as CurrencyGateway;

// no direct access
defined('_JEXEC') or die;

class CrowdfundingfinanceViewTransactions extends JViewLegacy
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

    protected $projectTitle = '';
    protected $currencies;
    protected $currency;
    protected $moneyFormatter;

    protected $option;
    protected $listOrder;
    protected $listDirn;
    protected $saveOrder;

    protected $saveOrderingUrl;
    protected $enabledSpecificPlugins;
    protected $paymentStatuses;

    protected $sortFields;

    protected $sidebar;

    /**
     * Payment plugins, which provides capture and void functionality.
     *
     * @var array
     */
    protected $specificPlugins = array();

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->specificPlugins = array('paypalexpress', 'paypaladaptive', 'stripeconnect');
    }

    public function display($tpl = null)
    {
        $this->option     = JFactory::getApplication()->input->get('option');

        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        $currencyGateway  = new CurrencyGateway(JFactory::getDbo());

        // Get parameters of com_crowdfunding.
        $this->cfParams = JComponentHelper::getParams('com_crowdfunding');

        // Get currencies
        $currencyCodes= array();
        foreach ($this->items as $item) {
            $currencyCodes[] = $item->txn_currency;
        }
        $currencyCodes = array_filter(array_unique($currencyCodes));

        if (count($currencyCodes) > 0) {
            $mapper             = new \Crowdfunding\Currency\Mapper($currencyGateway);
            $repository         = new \Crowdfunding\Currency\Repository($mapper);

            $databaseRequest    = new \Prism\Database\Request\Request;
            $databaseRequest->addSpecificCondition(
                'codes',
                new \Prism\Database\Request\Condition([
                    'column'   => 'code',
                    'value'    => $currencyCodes,
                    'operator' => 'IN',
                    'table'    => 'a'
                ])
            );

            $this->currencies   = $repository->fetchCollection($databaseRequest);
        }

        $locale               = JFactory::getLanguage()->getTag();

        $container            = Prism\Container::getContainer();

        $containerHelper      = new MoneyHelper($container);
        $this->currency       = $containerHelper->getCurrency($this->cfParams->get('project_currency'), $currencyGateway);
        $this->moneyFormatter = $containerHelper->getFormatter($locale);

        // Get project title.
        $search = (string)$this->state->get('filter.search');
        if ($search !== '' and strpos($search, 'pid') === 0) {
            $projectId          = (int)substr($search, 4);
            $this->projectTitle = CrowdfundingHelper::getProjectTitle($projectId);
        }

        // Get enabled specific plugins.
        $extensions                   = new Prism\Extensions(JFactory::getDbo(), $this->specificPlugins);
        $this->enabledSpecificPlugins = $extensions->getEnabled();

        // Prepare sorting data
        $this->prepareSorting();

        // Prepare actions
        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();

        // Include HTML helper
        JLoader::register('JHtmlString', JPATH_LIBRARIES . '/joomla/html/html/string.php');

        parent::display($tpl);
    }

    /**
     * Prepare sortable fields, sort values and filters.
     *
     * @throws \Exception
     */
    protected function prepareSorting()
    {
        // Prepare filters
        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn  = $this->escape($this->state->get('list.direction'));
        $this->saveOrder = (strcmp($this->listOrder, 'a.ordering') === 0);

        if ($this->saveOrder) {
            $this->saveOrderingUrl = 'index.php?option=' . $this->option . '&task=' . $this->getName() . '.saveOrderAjax&format=raw';
            JHtml::_('sortablelist.sortable', $this->getName() . 'List', 'adminForm', strtolower($this->listDirn), $this->saveOrderingUrl);
        }

        $this->sortFields = array(
            'b.name'             => JText::_('COM_CROWDFUNDINGFINANCE_BENEFICIARY'),
            'e.name'             => JText::_('COM_CROWDFUNDINGFINANCE_SENDER'),
            'c.title'            => JText::_('COM_CROWDFUNDINGFINANCE_PROJECT'),
            'a.txn_amount'       => JText::_('COM_CROWDFUNDINGFINANCE_AMOUNT'),
            'a.txn_date'         => JText::_('COM_CROWDFUNDINGFINANCE_DATE'),
            'a.service_provider' => JText::_('COM_CROWDFUNDINGFINANCE_PAYMENT_GETAWAY'),
            'a.id'               => JText::_('JGRID_HEADING_ID')
        );
    }

    /**
     * Add a menu on the sidebar of page
     */
    protected function addSidebar()
    {
        CrowdfundingfinanceHelper::addSubmenu($this->getName());
        
        // Create object Filters and load some filters options.
        $filters = Crowdfunding\Filters::getInstance(JFactory::getDbo());

        // Get payment services.
        $paymentServices = $filters->getPaymentServices();
        JHtmlSidebar::addFilter(
            JText::_('COM_CROWDFUNDINGFINANCE_SELECT_PAYMENT_SERVICES'),
            'filter_payment_service',
            JHtml::_('select.options', $paymentServices, 'value', 'text', $this->state->get('filter.payment_service'), true)
        );

        // Get payment statuses.
        $this->paymentStatuses = $filters->getPaymentStatuses();
        JHtmlSidebar::addFilter(
            JText::_('COM_CROWDFUNDINGFINANCE_SELECT_PAYMENT_STATUS'),
            'filter_payment_status',
            JHtml::_('select.options', $this->paymentStatuses, 'value', 'text', $this->state->get('filter.payment_status'), true)
        );

        // Get reward states.
        $rewardDistributionStatuses = $filters->getRewardDistributionStatuses();
        JHtmlSidebar::addFilter(
            JText::_('COM_CROWDFUNDINGFINANCE_SELECT_REWARD_STATUS'),
            'filter_reward_state',
            JHtml::_('select.options', $rewardDistributionStatuses, 'value', 'text', $this->state->get('filter.reward_state'), true)
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
        if ($this->projectTitle) {
            JToolbarHelper::title(JText::sprintf('COM_CROWDFUNDINGFINANCE_TRANSACTIONS_MANAGER_PROJECT_TITLE', $this->projectTitle));
        } else {
            JToolbarHelper::title(JText::_('COM_CROWDFUNDINGFINANCE_TRANSACTIONS_MANAGER'));
        }

        JToolbarHelper::editList('transaction.edit');

        // Add actions used for specific payment plugins.
        if (count($this->enabledSpecificPlugins) > 0) {
            JToolbarHelper::divider();

            // Add custom buttons
            $bar = JToolbar::getInstance();
            $bar->appendButton('Confirm', JText::_('COM_CROWDFUNDINGFINANCE_QUESTION_CAPTURE'), 'checkin', JText::_('COM_CROWDFUNDINGFINANCE_CAPTURE'), 'payments.doCapture', true);
            $bar->appendButton('Confirm', JText::_('COM_CROWDFUNDINGFINANCE_QUESTION_VOID'), 'cancel-circle', JText::_('COM_CROWDFUNDINGFINANCE_VOID'), 'payments.doVoid', true);
        }

        JToolbarHelper::divider();
        JToolbarHelper::deleteList(JText::_('COM_CROWDFUNDINGFINANCE_DELETE_ITEMS_QUESTION'), 'transactions.delete');

        JToolbarHelper::divider();
        JToolbarHelper::custom('transactions.backToDashboard', 'dashboard', '', JText::_('COM_CROWDFUNDINGFINANCE_DASHBOARD'), false);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        if ($this->projectTitle) {
            $this->document->setTitle(JText::sprintf('COM_CROWDFUNDINGFINANCE_TRANSACTIONS_MANAGER_PROJECT_TITLE', $this->projectTitle));
        } else {
            $this->document->setTitle(JText::_('COM_CROWDFUNDINGFINANCE_TRANSACTIONS_MANAGER'));
        }

        // Scripts
        JHtml::_('behavior.multiselect');

        JHtml::_('bootstrap.tooltip');
        JHtml::_('formbehavior.chosen', 'select');

        JHtml::_('Prism.ui.joomlaList');
        JHtml::_('Prism.ui.pnotify');
        JHtml::_('Prism.ui.joomlaHelper');

        $this->document->addScript('../media/' . $this->option . '/js/admin/' . strtolower($this->getName()) . '.js');
    }
}
