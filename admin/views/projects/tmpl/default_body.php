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
<?php foreach ($this->items as $i => $item) {
    $numberOfTransactions = (isset($this->transactions[$item->id])) ? $this->transactions[$item->id]["number"] : 0;
    ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="btn-group">
            <?php echo JHtml::_('crowdfundingfinancebackend.published', $i, $item->published, "projects."); ?>
            <?php echo JHtml::_('crowdfundingfinancebackend.featured', $i, $item->featured); ?>
            <?php echo JHtml::_('crowdfundingfinancebackend.approved', $i, $item->approved); ?>
        </td>
        <td>
            <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=project&id=" . (int)$item->id); ?>">
                <?php echo $this->escape($item->title); ?>
            </a>

            <div class="small">
                <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=transactions&filter_search=pid:" . (int)$item->id); ?>">
                    <?php echo JText::sprintf("COM_CROWDFUNDINGFINANCE_TRANSACTIONS_N", $numberOfTransactions); ?>
                </a>
            </div>
        </td>
        <td class="center hidden-phone"><?php echo $this->amount->setValue($item->goal)->formatCurrency(); ?></td>
        <td class="center hidden-phone"><?php echo $this->amount->setValue($item->funded)->formatCurrency(); ?></td>
        <td class="center hidden-phone"><?php echo JHtml::_("crowdfunding.percent", $item->funded_percents); ?></td>
        <td class="center hidden-phone"><?php echo JHtml::_("crowdfunding.date", $item->funding_start, JText::_('DATE_FORMAT_LC3')); ?></td>
        <td class="center hidden-phone"><?php echo JHtml::_("crowdfunding.duration", $item->funding_end, $item->funding_days, JText::_('DATE_FORMAT_LC3')); ?></td>
        <td class="center hidden-phone"><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3')); ?></td>
        <td class="center hidden-phone"><?php echo $item->category; ?></td>
        <td class="center hidden-phone"><?php echo $this->escape($item->type); ?></td>
        <td class="center hidden-phone"><?php echo $item->id; ?></td>
    </tr>
<?php } ?>
	  