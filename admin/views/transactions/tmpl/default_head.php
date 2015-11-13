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
<tr>
    <th width="1%" class="hidden-phone">
        <?php echo JHtml::_('grid.checkall'); ?>
    </th>
    <th class="title">
        <?php echo JText::_('COM_CROWDFUNDINGFINANCE_TXN_ID'); ?>
    </th>
    <th width="10%" class="nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_SENDER', 'e.name', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_BENEFICIARY', 'b.name', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_PROJECT', 'c.title', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="nowrap">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_AMOUNT', 'a.txn_amount', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_DATE', 'a.txn_date', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_PAYMENT_GETAWAY', 'a.service_provider', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="nowrap hidden-phone">
        <?php echo JText::_('JSTATUS'); ?>
    </th>
    <th width="10%" class="center nowrap hidden-phone">
        <?php echo JText::_('COM_CROWDFUNDINGFINANCE_REWARD'); ?>
    </th>
    <th width="3%"
        class="center nowrap hidden-phone"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $this->listDirn, $this->listOrder); ?></th>
</tr>
	  