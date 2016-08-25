<?php
/**
 * @package      Crowdfunding
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="span8">

    <!--  Row 1 -->
    <div class="row-fluid">
        <div class="span8">
            <div class="panel panel-default">
                <div class="panel-heading latest-started">
                    <i class="icon-list"></i>
                    <?php echo JText::_('COM_CROWDFUNDINGFINANCE_LATEST_TRANSACTIONS'); ?>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo JText::_('COM_CROWDFUNDINGFINANCE_PROJECT'); ?></th>
                            <th class="center nowrap"
                                style="max-width: 50px;"><?php echo JText::_('COM_CROWDFUNDINGFINANCE_AMOUNT'); ?></th>
                            <th class="center nowrap hidden-phone"
                                style="max-width: 100px;"><?php echo JText::_('COM_CROWDFUNDINGFINANCE_DATE'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0, $max = count($this->latest); $i < $max; $i++) { ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td>
                                    <a href="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance&view=project&id=' . (int)$this->latest[$i]['project_id']); ?>">
                                        <?php echo JHtmlString::truncate(strip_tags($this->latest[$i]["title"]), 53); ?>
                                    </a>
                                    <a class="help-box"
                                       href="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance&view=transactions&filter_search=id:' . (int)$this->latest[$i]['id']); ?>">
                                        <?php echo $this->escape($this->latest[$i]['txn_id']); ?>
                                    </a>
                                </td>
                                <td class="center">
                                    <?php echo $this->amount->setValue($this->latest[$i]['txn_amount'])->formatCurrency(); ?>
                                </td>
                                <td class="center hidden-phone">
                                    <?php echo JHtml::_('date', $this->latest[$i]['txn_date'], $this->cfParams->get('date_format_views', JText::_('DATE_FORMAT_LC3'))); ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="span4">
            <div class="panel panel-default">
                <div class="panel-heading bgcolor-yellow-light">
                    <i class="icon-list"></i>
                    <?php echo JText::_('COM_CROWDFUNDINGFINANCE_BASIC_INFORMATION'); ?>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th><?php echo JText::_('COM_CROWDFUNDINGFINANCE_TOTAL_PROJECTS'); ?></th>
                            <td><?php echo $this->totalProjects; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_('COM_CROWDFUNDINGFINANCE_TOTAL_TRANSACTIONS'); ?></th>
                            <td><?php echo $this->totalTransactions; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_('COM_CROWDFUNDINGFINANCE_TOTAL_AMOUNT'); ?></th>
                            <td><?php echo $this->amount->setValue($this->totalAmount)->formatCurrency(); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row 1 -->
</div>