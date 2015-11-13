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
    <th width="1%" style="min-width: 55px" class="nowrap center">
        <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $this->listDirn, $this->listOrder); ?>
    </th>
    <th class="title">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_TITLE', 'a.title', $this->listDirn, $this->listOrder); ?>
    </th>

    <th width="10%" class="nowrap hidden-phone">
        <?php echo JText::_("COM_CROWDFUNDINGFINANCE_GOAL"); ?> / <?php echo JText::_("COM_CROWDFUNDINGFINANCE_FUNDED"); ?>
    </th>
    <th width="15%" class="nowrap hidden-phone">
        <?php echo JText::_("COM_CROWDFUNDINGFINANCE_DATE"); ?>
    </th>
    <th width="15%" class="nowrap hidden-phone">
        <?php echo JText::_("COM_CROWDFUNDINGFINANCE_PAYPAL"); ?>
    </th>
    <th width="15%" class="nowrap hidden-phone">
        <?php echo JText::_("COM_CROWDFUNDINGFINANCE_IBAN"); ?>
    </th>
    <th width="3%" class="center nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $this->listDirn, $this->listOrder); ?>
    </th>
</tr>
	  