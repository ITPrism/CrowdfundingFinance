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
            <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=payout&layout=edit&id=" . (int)$item->id); ?>">
                <?php echo $this->escape($item->title); ?>
            </a>

            <div class="small">
                <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=transactions&filter_search=pid:" . (int)$item->id); ?>">
                    <?php echo JText::sprintf("COM_CROWDFUNDINGFINANCE_TRANSACTIONS_N", $numberOfTransactions); ?>
                </a>
            </div>
            <div class="small">
                <?php echo JText::_("COM_CROWDFUNDINGFINANCE_CATEGORY"); ?>:
                <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=payouts&filter_category_id=" . (int)$item->catid); ?>">
                    <?php echo $this->escape($item->category); ?>
                </a>
            </div>
        </td>
        <td class="hidden-phone">
            <div class="cf-goal"><?php echo JText::sprintf("COM_CROWDFUNDINGFINANCE_GOAL_S", $this->amount->setValue($item->goal)->formatCurrency()); ?></div>
            <div class="cf-funded"><?php echo JText::sprintf("COM_CROWDFUNDINGFINANCE_FUNDED_S", $this->amount->setValue($item->funded)->formatCurrency()); ?></div>
            <div class="cf-percent"><?php echo JText::sprintf("COM_CROWDFUNDINGFINANCE_PERCENT_S", JHtml::_("crowdfunding.percent", $item->funded_percents)); ?></div>
        </td>
        <td class="hidden-phone">
            <div>
                <strong><?php echo JText::_("COM_CROWDFUNDINGFINANCE_START_DATE"); ?></strong> :
                <?php echo JHtml::_("crowdfunding.date", $item->funding_start, JText::_('DATE_FORMAT_LC3')); ?>
            </div>
            <div>
                <strong><?php echo JText::_("COM_CROWDFUNDINGFINANCE_END_DATE"); ?></strong> :
                <?php echo JHtml::_("crowdfunding.duration", $item->funding_end, $item->funding_days, JText::_('DATE_FORMAT_LC3')); ?>
            </div>
            <div>
                <strong><?php echo JText::_("COM_CROWDFUNDINGFINANCE_CREATED"); ?></strong> :
                <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3')); ?>
            </div>
        </td>
        <td class="hidden-phone">
            <?php echo JHtml::_("crowdfundingfinancebackend.paypal", $item->paypal_email, $item->paypal_first_name, $item->paypal_last_name, $item->id); ?>
        </td>
        <td class="hidden-phone">
            <?php echo JHtml::_("crowdfundingfinancebackend.iban", $item->iban, $item->bank_account, $item->id); ?>
        </td>
        <td class="center hidden-phone"><?php echo $item->id; ?></td>
    </tr>
<?php } ?>
	  