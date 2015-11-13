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
<form action="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<div class="row-fluid">
    <div class="span3">
        <ul class="thumbnails">
            <li class="span12">
                <div class="thumbnail">
                    <img src="<?php echo $this->imagesUrl . "/" . $this->item->image; ?>"
                         alt="<?php echo $this->escape($this->item->title); ?>"/>

                    <h3><?php echo $this->escape($this->item->title); ?></h3>

                    <p><?php echo $this->escape($this->item->short_desc); ?></p>
                </div>
            </li>
        </ul>

        <div class="row-fluid">
            <div class="span12" id="funded-piechart">
            </div>
        </div>

    </div>
    <div class="span3">
        <?php echo $this->loadTemplate("basic"); ?>
    </div>

    <div class="span3">
        <?php echo $this->loadTemplate("payout"); ?>
    </div>
</div>

<div class="row-fluid">
    <div class="span12" id="amount-days-lines">
    </div>
</div>