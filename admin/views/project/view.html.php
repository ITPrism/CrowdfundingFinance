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

class CrowdfundingFinanceViewProject extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $params;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $cfParams;

    protected $item;

    protected $stats;
    protected $transactionStatuses;
    protected $payout;
    protected $amount;
    protected $imagesUrl;

    protected $documentTitle;
    protected $option;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app    = JFactory::getApplication();
        $itemId = $app->input->getUint("id");

        $model        = $this->getModel();
        $this->params = JComponentHelper::getParams("com_crowdfundingfinance");

        $this->item = $model->getItem($itemId);

        $this->stats = new Crowdfunding\Statistics\Project(JFactory::getDbo(), $itemId);

        $this->transactionStatuses = $this->stats->getTransactionsStatusStatistics();
        $this->payout = $this->stats->getPayoutStatistics();

        /** @var  $cParams Joomla\Registry\Registry */
        $cParams        = JComponentHelper::getParams("com_crowdfunding");
        $this->cfParams = $cParams;

        $imagesFolder    = $this->cfParams->get("images_directory", "images/crowdfunding");
        $this->imagesUrl = JUri::root() . $imagesFolder;

        // Get currency.
        $currency = Crowdfunding\Currency::getInstance(JFactory::getDbo(), $this->cfParams->get("project_currency"));

        $this->amount = new Crowdfunding\Amount($this->cfParams);
        $this->amount->setCurrency($currency);

        // Prepare actions, behaviors, scripts and document
        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $this->documentTitle = JText::_('COM_CROWDFUNDINGFINANCE_PROJECT_STATISTICS');

        JToolbarHelper::title($this->documentTitle);

        JToolbarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle($this->documentTitle);

        // Add scripts
        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.keepalive');
        JHtml::_('behavior.formvalidation');

        $d3Cdn = (bool)$this->params->get("d3_cdn", true);
        JHtml::_("prism.ui.d3", $d3Cdn);

        $js = "
            cfProjectId = " . $this->item->id . ";
        ";
        $this->document->addScriptDeclaration($js);
        $this->document->addScript('../media/' . $this->option . '/js/admin/' . JString::strtolower($this->getName()) . '.js');
    }
}
