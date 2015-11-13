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
?>
<h2><?php echo JText::_("COM_CROWDFUNDINGFINANCE_BASIC_INFORMATION"); ?></h2>
<table class="table table-bordered">
    <tbody>
    <tr>
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_CREATED"); ?></th>
        <td>
            <?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2')); ?>
        </td>
    </tr>
    <tr>
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_GOAL"); ?></th>
        <td>
            <?php echo $this->amount->setValue($this->item->goal)->formatCurrency(); ?>
        </td>
    </tr>
    <tr>
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_FUNDED"); ?></th>
        <td>
            <?php echo $this->amount->setValue($this->item->funded)->formatCurrency(); ?>
        </td>
    </tr>
    <tr>
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_FUNDING_START"); ?></th>
        <td>
            <?php echo JHtml::_('date', $this->item->funding_start, JText::_('DATE_FORMAT_LC3')); ?>
        </td>
    </tr>
    <tr>
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_FUNDING_END"); ?></th>
        <td>
            <?php echo JHtml::_('date', $this->item->funding_end, JText::_('DATE_FORMAT_LC3')); ?>
        </td>
    </tr>
    <tr>
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_TRANSACTIONS"); ?></th>
        <td>
            <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=transactions&filter_search=pid:" . (int)$this->item->id); ?>">
                ( <?php echo $this->stats->getTransactionsNumber(); ?> )
            </a>
        </td>
    </tr>
    </tbody>
</table>