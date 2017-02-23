<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class CrowdfundingfinanceViewProject extends JViewLegacy
{
    use Crowdfunding\Helper\MoneyHelper;

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
    protected $money;
    protected $imagesUrl;

    protected $documentTitle;
    protected $option;

    protected $fundedData;

    public function display($tpl = null)
    {
        $app          = JFactory::getApplication();
        $this->option = $app->input->get('option');

        $itemId       = $app->input->getUint('id');

        $model        = $this->getModel();
        $this->params = JComponentHelper::getParams('com_crowdfundingfinance');

        $this->item   = $model->getItem($itemId);

        $this->stats  = new Crowdfunding\Statistics\Project(JFactory::getDbo(), $itemId);

        $this->transactionStatuses = $this->stats->getTransactionsStatusStatistics();
        $this->payout              = $this->stats->getPayoutStatistics();

        $this->cfParams  = JComponentHelper::getParams('com_crowdfunding');

        $this->imagesUrl = JUri::root() . CrowdfundingHelper::getImagesFolderUri();

        $this->money     = $this->getMoneyFormatter($this->cfParams);

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

        // Add custom buttons
        $bar = JToolbar::getInstance('toolbar');
        $bar->appendButton('Link', 'cancel', JText::_('JTOOLBAR_CLOSE'), JRoute::_('index.php?option=com_crowdfundingfinance&view=projects'));
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

        JHtml::_('Prism.ui.chartjs');

        JText::script('COM_CROWDFUNDINGFINANCE_DAILY_FUNDS');

        $js = '
        var crowdfundingPlatform = {
            projectId: ' . $this->item->id .'
        }';

        $this->document->addScriptDeclaration($js);
        $this->document->addScript('../media/' . $this->option . '/js/admin/' . strtolower($this->getName()) . '.js');
    }
}
