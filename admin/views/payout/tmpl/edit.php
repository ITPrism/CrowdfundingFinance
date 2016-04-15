<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="row-fluid">
    <div class="span6 form-horizontal">
        <form action="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

            <?php echo $this->form->renderField('paypal_first_name'); ?>
            <?php echo $this->form->renderField('paypal_last_name'); ?>
            <?php echo $this->form->renderField('paypal_email'); ?>
            <?php echo $this->form->renderField('iban'); ?>
            <?php echo $this->form->renderField('bank_account'); ?>
            <?php echo $this->form->renderField('id'); ?>

            <input type="hidden" name="task" value=""/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>