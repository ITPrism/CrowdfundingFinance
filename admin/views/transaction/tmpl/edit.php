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

            <fieldset>
                <?php echo $this->form->renderField('txn_amount'); ?>
                <?php echo $this->form->renderField('txn_currency'); ?>
                <?php echo $this->form->renderField('service_provider'); ?>
                <?php echo $this->form->renderField('txn_status'); ?>
                <?php echo $this->form->renderField('txn_id'); ?>
                <?php echo $this->form->renderField('parent_txn_id'); ?>
                <?php echo $this->form->renderField('id'); ?>
                <?php echo $this->form->renderField('investor_id'); ?>
            </fieldset>

            <input type="hidden" name="task" value=""/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>

    <div class="span6">
        <?php
        if (is_array($this->extraData) and count($this->extraData) > 0) {
            $layout = new JLayoutFile('transaction_info', $this->layoutsBasePath);
            echo $layout->render($this->extraData);
        }
        ?>
    </div>
</div>
