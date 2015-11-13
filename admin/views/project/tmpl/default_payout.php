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
<h2><?php echo JText::_("COM_CROWDFUNDINGFINANCE_PAYOUT_INFORMATION"); ?></h2>

<table class="table table-bordered">
    <thead>
    <tr class="cf-table-header">
        <th colspan="2"><?php echo JText::_("COM_CROWDFUNDINGFINANCE_TRANSACTIONS"); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr class="success">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_COMPLETED"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.transactionStatisticAmount', $this->transactionStatuses, "completed", $this->amount); ?>
        </td>
    </tr>
    <tr class="success">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_PENDING"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.transactionStatisticAmount', $this->transactionStatuses, "pending", $this->amount); ?>
        </td>
    </tr>
    <tr class="error">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_CANCELED"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.transactionStatisticAmount', $this->transactionStatuses, "canceled", $this->amount); ?>
        </td>
    </tr>
    <tr class="error">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_FAILED"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.transactionStatisticAmount', $this->transactionStatuses, "failed", $this->amount); ?>
        </td>
    </tr>
    <tr class="error">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_REFUNDED"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.transactionStatisticAmount', $this->transactionStatuses, "refunded", $this->amount); ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <thead>
    <tr class="cf-table-header">
        <th colspan="2"><?php echo JText::_("COM_CROWDFUNDINGFINANCE_FEES"); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr class="success">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_EARNED_FEES"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.earnedFees', $this->transactionStatuses, $this->amount, JText::_("COM_CROWDFUNDINGFINANCE_SUM_COMPLETED_PENDING_TRANSACTIONS")); ?>
        </td>
    </tr>
    <tr class="error">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_MISSED_FEES"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.missedFees', $this->transactionStatuses, $this->amount, JText::_("COM_CROWDFUNDINGFINANCE_SUM_FAILED_TRANSACTIONS")); ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <thead>
    <tr class="cf-table-header">
        <th colspan="2"><?php echo JText::_("COM_CROWDFUNDINGFINANCE_AMOUNTS"); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr class="success">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_OWNER_EARNED_AMOUNT"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.ownerEarnedAmount', $this->transactionStatuses, $this->amount, JText::_("COM_CROWDFUNDINGFINANCE_SUM_OWNER_RECEIVE")); ?>
        </td>
    </tr>
    <tr class="error">
        <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_OWNER_MISSED_AMOUNT"); ?></th>
        <td>
            <?php echo JHtml::_('crowdfundingfinancebackend.ownerMissedAmount', $this->transactionStatuses, $this->amount, JText::_("COM_CROWDFUNDINGFINANCE_SUM_OWNER_CANNOT_RECEIVE")); ?>
        </td>
    </tr>
    </tbody>
</table>

<p class="alert alert-info">
    <i class="icon-info"></i>
    <?php echo JText::_("COM_CROWDFUNDINGFINANCE_PAYOUT_NOTE"); ?>
</p>