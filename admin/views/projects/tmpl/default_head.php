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

    <th width="5%" class="center nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_GOAL', 'a.goal', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="5%" class="center nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_FUNDED', 'a.funded', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="5%" class="center nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_FUNDED_PERCENTS', 'funded_percents', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="center hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_START_DATE', 'a.funding_start', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="center hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_END_DATE', 'a.funding_end', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="center nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'COM_CROWDFUNDINGFINANCE_CREATED', 'a.created', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="center nowrap hidden-phone">
        <?php echo JHtml::_('grid.sort', 'JCATEGORY', 'b.title', $this->listDirn, $this->listOrder); ?>
    </th>
    <th width="10%" class="center hidden-phone"><?php echo JText::_("COM_CROWDFUNDINGFINANCE_TYPE"); ?></th>
    <th width="3%"
        class="center nowrap hidden-phone"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $this->listDirn, $this->listOrder); ?></th>
</tr>
	  