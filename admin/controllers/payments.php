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

// jimport('joomla.application.component.controller');
JLoader::register("CrowdfundingControllerPayments", CROWDFUNDING_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . "payments.php");

/**
 * This controller provides functionality
 * that helps to payment plugins to prepare their payment data.
 *
 * @package        CrowdfundingFinance
 * @subpackage     Payments
 *
 */
class CrowdfundingFinanceControllerPayments extends CrowdfundingControllerPayments
{
    protected $text_prefix = "COM_CROWDFUNDINGFINANCE";

    /**
     * Method to get a model object, loading it if required.
     *
     * @param    string $name   The model name. Optional.
     * @param    string $prefix The class prefix. Optional.
     * @param    array  $config Configuration array for model. Optional.
     *
     * @return    object    The model.
     * @since    1.5
     */
    public function getModel($name = 'Payments', $prefix = '', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}
