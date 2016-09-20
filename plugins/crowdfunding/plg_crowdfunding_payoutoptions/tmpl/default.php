<?php
/**
 * @package      CrowdfundingPayoutOptions
 * @subpackage   Plugins
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Load the script that initializes the select element with banks.
$doc->addScript('plugins/crowdfunding/payoutoptions/js/script.js?v=' . rawurlencode($this->version));
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_PAYOUT_OPTIONS');?></h4>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <?php if($this->params->get('display_paypal', 0)) { ?>
            <li role="presentation" <?php echo ('paypal' === $activeTab) ? 'class="active"' : '';?> >
                <a href="#paypal" aria-controls="paypal" role="tab" data-toggle="tab">
                    <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_PAYPAL'); ?>
                </a>
            </li>
            <?php } ?>

            <?php if($this->params->get('display_banktransfer', 0)) { ?>
            <li role="presentation" <?php echo ('banktransfer' === $activeTab) ? 'class="active"' : '';?>>
                <a href="#banktransfer" aria-controls="banktransfer" role="tab" data-toggle="tab">
                    <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_BANKTRANSFER'); ?>
                </a>
            </li>
            <?php } ?>

            <?php if($this->params->get('display_stripe', 0)) { ?>
                <li role="presentation" <?php echo ('stripe' === $activeTab) ? 'class="active"' : '';?>>
                    <a href="#stripe" aria-controls="stripe" role="tab" data-toggle="tab">
                        <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_STRIPE'); ?>
                    </a>
                </li>
            <?php } ?>
        </ul>

        <form action="<?php echo JRoute::_('index.php?option=com_crowdfundingfinance'); ?>" method="post" id="js-cfpayoutoptions-form" autocomplete="off">
            <div class="tab-content">
            <?php if($this->params->get('display_paypal', 0)) { ?>
                <div role="tabpanel" class="tab-pane <?php echo ('paypal' === $activeTab) ? 'active' : '';?>" id="paypal">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="cf-payoutoptions-paypal-first-name"><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_PAYPAL_FIRST_NAME');?></label>
                            <input type="text" name="paypal_first_name" id="cf-payoutoptions-paypal-first-name" value="<?php echo $payout->getPaypalFirstName(); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="cf-payoutoptions-paypal-last-name"><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_PAYPAL_LAST_NAME');?></label>
                            <input type="text" name="paypal_last_name" id="cf-payoutoptions-paypal-last-name" value="<?php echo $payout->getPaypalLastName(); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="cf-payoutoptions-paypal-email"><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_PAYPAL_EMAIL');?></label>
                        <input type="text" name="paypal_email" id="cf-payoutoptions-paypal-email" value="<?php echo $payout->getPaypalEmail(); ?>" class="form-control">
                    </div>

                </div>
                <?php if($this->params->get('display_paypal_info', 1)) { ?>
                <div class="col-md-4 bg-info mt-5">
                    <h5>
                        <span class="fa fa-info-circle"></span>
                        <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_PAYPAL_INFORMATION');?>
                    </h5>

                    <p><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_PAYPAL_ACCOUNT');?></p>

                    <?php if(!$this->params->get('paypal_requirements_link')) { ?>
                    <p><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_PAYPAL_REQUIREMENTS');?></p>
                    <?php } else { ?>
                    <p><?php echo JText::sprintf('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_PAYPAL_REQUIREMENTS_S', $this->params->get('paypal_requirements_link'));?></p>
                    <?php } ?>

                    <?php if($this->params->get('paypal_additional_information')) { ?>
                        <p><?php echo htmlentities($this->params->get('paypal_additional_information'), ENT_QUOTES, 'UTF-8');?></p>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
                </div>
            <?php } ?>

            <?php if($this->params->get('display_banktransfer', 0)) { ?>
                <div role="tabpanel" class="tab-pane <?php echo ('banktransfer' === $activeTab) ? 'active' : '';?>" id="banktransfer">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="cf-payoutoptions-banktransfer-iban"><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_IBAN');?></label>
                                <input type="text" name="iban" id="cf-payoutoptions-banktransfer-iban" class="form-control" value="<?php echo $payout->getIban(); ?>">
                            </div>

                            <div class="form-group">
                                <label for="cf-payoutoptions-banktransfer-bank_account"><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_ADDITIONAL_INFORMATION');?></label>
                                    <textarea name="bank_account" id="cf-payoutoptions-banktransfer-bank_account" class="form-control" rows="6"><?php echo $payout->getBankAccount(); ?></textarea>
                            </div>

                        </div>
                        <?php if($this->params->get('display_banktransfer_info', 1)) { ?>
                            <div class="col-md-4 bg-info mt-5">
                                <h5>
                                    <span class="fa fa-info-circle"></span>
                                    <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_BANKTRANSFER_INFORMATION');?>
                                </h5>

                                <p><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_BANKTRANSFER_ACCOUNT');?></p>

                                <?php if (!$this->params->get('banktransfer_requirements_link')) { ?>
                                    <p><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_BANKTRANSFER_REQUIREMENTS');?></p>
                                <?php } else { ?>
                                    <p><?php echo JText::sprintf('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_BANKTRANSFER_REQUIREMENTS_S', $this->params->get('banktransfer_requirements_link'));?></p>
                                <?php } ?>

                                <?php if ($this->params->get('banktransfer_additional_information')) { ?>
                                    <p><?php echo htmlentities($this->params->get('banktransfer_additional_information'), ENT_QUOTES, 'UTF-8');?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if($this->params->get('display_stripe', 0)) { ?>
                <div role="tabpanel" class="tab-pane <?php echo ('stripe' === $activeTab) ? 'active' : '';?>" id="stripe">
                    <div class="row">
                        <div class="col-md-8">
                            <?php if ($stripeWarning !== null) { ?>
                            <div class="alert alert-warning" role="alert"><span class="fa fa-warning"></span> <?php echo $stripeWarning; ?></div>
                            <?php } else {
                                echo implode("\n", $stripeButton);
                            } ?>
                        </div>
                        <?php if($this->params->get('display_stripe_info', 1)) { ?>
                            <div class="col-md-4 bg-info mt-5">
                                <h5>
                                    <span class="fa fa-info-circle"></span>
                                    <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_STRIPE_INFORMATION');?>
                                </h5>

                                <p><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_STRIPE_ACCOUNT');?></p>

                                <?php if (!$this->params->get('stripe_requirements_link')) { ?>
                                    <p><?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_STRIPE_REQUIREMENTS');?></p>
                                <?php } else { ?>
                                    <p><?php echo JText::sprintf('PLG_CROWDFUNDING_PAYOUTOPTIONS_NOTE_STRIPE_REQUIREMENTS_S', $this->params->get('stripe_requirements_link'));?></p>
                                <?php } ?>

                                <?php if ($this->params->get('stripe_additional_information')) { ?>
                                    <p><?php echo htmlentities($this->params->get('stripe_additional_information'), ENT_QUOTES, 'UTF-8');?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            </div>

            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-primary" type="submit">
                        <span class="fa fa-floppy-o"></span>
                        <?php echo JText::_('PLG_CROWDFUNDING_PAYOUTOPTIONS_SAVE');?>
                    </button>
                    <img src="/media/com_crowdfunding/images/ajax-loader.gif" width="16" height="16" id="js-cfpayoutoptions-ajax-loader" class="hide" />
                </div>
            </div>

            <input type="hidden" name="task" value="payout.save"/>
            <input type="hidden" name="format" value="raw"/>
            <input type="hidden" name="project_id" value="<?php echo (int)$item->id; ?>"/>
        </form>
    </div>
</div>
