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
<?php if (!empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
        <?php endif; ?>
        <div class="span8">
            <!--  Row 1 -->
            <div class="row-fluid dashboard-stats">
                <?php if (0 <= count($this->latest)) { ?>
                    <div class="span8">
                        <h3 class="latest">
                            <?php echo JText::_("COM_CROWDFUNDINGFINANCE_LATEST_TRANSACTIONS"); ?>
                        </h3>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_PROJECT"); ?></th>
                                <th class="center nowrap"
                                    style="max-width: 50px;"><?php echo JText::_("COM_CROWDFUNDINGFINANCE_AMOUNT"); ?></th>
                                <th class="center nowrap hidden-phone"
                                    style="max-width: 100px;"><?php echo JText::_("COM_CROWDFUNDINGFINANCE_DATE"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 0, $max = count($this->latest); $i < $max; $i++) { ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td>
                                        <a href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=project&id=" . (int)$this->latest[$i]["project_id"]); ?>">
                                            <?php echo JHtmlString::truncate(strip_tags($this->latest[$i]["title"]), 53); ?>
                                        </a>
                                        <a class="help-box"
                                           href="<?php echo JRoute::_("index.php?option=com_crowdfundingfinance&view=transactions&filter_search=id:" . (int)$this->latest[$i]["id"]); ?>">
                                            <?php echo $this->escape($this->latest[$i]["txn_id"]); ?>
                                        </a>
                                    </td>
                                    <td class="center">
                                        <?php echo $this->amount->setValue($this->latest[$i]["txn_amount"])->formatCurrency(); ?>
                                    </td>
                                    <td class="center hidden-phone">
                                        <?php echo JHtml::_('date', $this->latest[$i]["txn_date"], JText::_('DATE_FORMAT_LC2')); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
                <div class="span4">
                    <h3 class="basic-stats">
                        <?php echo JText::_("COM_CROWDFUNDINGFINANCE_BASIC_INFORMATION"); ?>
                    </h3>
                    <table class="table">
                        <tbody>
                        <tr>
                            <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_TOTAL_PROJECTS"); ?></th>
                            <td><?php echo $this->totalProjects; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_TOTAL_TRANSACTIONS"); ?></th>
                            <td><?php echo $this->totalTransactions; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo JText::_("COM_CROWDFUNDINGFINANCE_TOTAL_AMOUNT"); ?></th>
                            <td><?php echo $this->amount->setValue($this->totalAmount)->formatCurrency(); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /Row 1 -->
        </div>

        <div class="span4">
            <a href="http://itprism.com/free-joomla-extensions/ecommerce-gamification/crowdfunding-collective-raising-capital"
               target="_blank"><img src="../media/com_crowdfundingfinance/images/logo.png"
                                    alt="<?php echo JText::_("COM_CROWDFUNDINGFINANCE"); ?>"/></a>
            <a href="http://itprism.com" target="_blank"
               title="<?php echo JText::_("COM_CROWDFUNDINGFINANCE_PRODUCT"); ?>"><img
                    src="../media/com_crowdfunding/images/product_of_itprism.png"
                    alt="<?php echo JText::_("COM_CROWDFUNDINGFINANCE_PRODUCT"); ?>"/></a>

            <p><?php echo JText::_("COM_CROWDFUNDINGFINANCE_YOUR_VOTE"); ?></p>

            <p><?php echo JText::_("COM_CROWDFUNDINGFINANCE_SUBSCRIPTION"); ?></p>
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td><?php echo JText::_("COM_CROWDFUNDINGFINANCE_INSTALLED_VERSION"); ?></td>
                    <td><?php echo $this->version->getShortVersion(); ?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_CROWDFUNDINGFINANCE_RELEASE_DATE"); ?></td>
                    <td><?php echo $this->version->releaseDate ?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_VERSION"); ?></td>
                    <td><?php echo $this->itprismVersion; ?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_CROWDFUNDINGFINANCE_COPYRIGHT"); ?></td>
                    <td><?php echo $this->version->copyright; ?></td>
                </tr>
                <tr>
                    <td><?php echo JText::_("COM_CROWDFUNDINGFINANCE_LICENSE"); ?></td>
                    <td><?php echo $this->version->license; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>