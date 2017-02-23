<?php
/**
 * @package      Crowdfundingfinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="row-fluid">
    <div class="span3">
        <ul class="thumbnails">
            <li class="span12">
                <div class="thumbnail">
                    <img src="<?php echo $this->imagesUrl . '/' . $this->item->image; ?>"
                         alt="<?php echo $this->escape($this->item->title); ?>"/>

                    <h3><?php echo $this->escape($this->item->title); ?></h3>

                    <p><?php echo $this->escape($this->item->short_desc); ?></p>
                </div>
            </li>
        </ul>
    </div>
    <div class="span3">
        <?php echo $this->loadTemplate('basic'); ?>
    </div>

    <div class="span3">
        <?php echo $this->loadTemplate('payout'); ?>
    </div>
    <div class="span3">
        <canvas id="js-funded-chart" width="400" height="400" ></canvas>
    </div>
</div>

<div style="position: relative; height: 50vh" >
    <canvas id="js-transactions-chart"></canvas>
</div>
