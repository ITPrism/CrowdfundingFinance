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
    $ordering = ($this->listOrder == 'a.ordering');
    ?>
    <tr class="row<?php echo $i % 2; ?>">
        <td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td>
            <a href="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance&view=transaction&layout=edit&id=' . $item->id); ?>"><?php echo $item->txn_id; ?></a>
            <?php if (!empty($item->parent_txn_id)) { ?>
                <div class="small">
                    <?php echo $this->escape($item->parent_txn_id); ?>
                </div>
            <?php } ?>
        </td>
        <td class="hidden-phone"><?php echo JHtml::_('crowdfunding.name', $item->sender); ?></td>
        <td class="hidden-phone"><?php echo $this->escape($item->beneficiary); ?></td>
        <td class="hidden-phone">
            <a href="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance&view=transactions&filter_search=pid:' . $item->project_id); ?>">
                <?php echo JHtmlString::truncate(strip_tags($item->project), 53); ?>
            </a>
        </td>
        <td><?php echo JHtml::_('crowdfundingbackend.transactionAmount', $item, $this->amount, $this->currencies); ?></td>
        <td class="hidden-phone"><?php echo $item->txn_date; ?></td>
        <td class="hidden-phone">
            <?php echo $item->service_provider; ?>
            <div class="small">
                <?php echo JText::sprintf('COM_CROWDFUNDINGFINANCE_ALIAS_S', $this->escape($item->service_alias)); ?>
            </div>
        </td>
        <td class="hidden-phone">
            <?php echo $item->txn_status; ?>
            <?php echo JHtml::_('crowdfundingbackend.reason', $item->status_reason); ?>
        </td>
        <td class="center hidden-phone">
            <?php echo JHtml::_('crowdfundingbackend.reward', $item->reward_id, $item->reward, $item->project_id, $item->reward_state); ?>
        </td>
        <td class="center hidden-phone"><?php echo $item->id; ?></td>
    </tr>
<?php } ?>
	  