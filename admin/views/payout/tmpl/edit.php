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
<div class="row-fluid">
    <div class="span6 form-horizontal">
        <form action="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

            <fieldset>

                <?php echo $this->form->getControlGroup('paypal_first_name'); ?>
                <?php echo $this->form->getControlGroup('paypal_last_name'); ?>
                <?php echo $this->form->getControlGroup('paypal_email'); ?>
                <?php echo $this->form->getControlGroup('iban'); ?>
                <?php echo $this->form->getControlGroup('bank_account'); ?>
                <?php echo $this->form->getControlGroup('id'); ?>

            </fieldset>

            <input type="hidden" name="task" value=""/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>