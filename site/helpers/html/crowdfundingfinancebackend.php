<?php
/**
 * @package      Crowdfunding
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Crowdfunding Finance HTML Helper
 *
 * @package        Crowdfunding
 * @subpackage     Component
 */
abstract class JHtmlCrowdfundingFinanceBackend
{
    public static function approved($value)
    {
        JHtml::_('bootstrap.tooltip');

        if (!$value) { // Disapproved
            $title = 'COM_CROWDFUNDINGFINANCE_DISAPPROVED';
            $class = 'ban-circle';
        } else {
            $title = 'COM_CROWDFUNDINGFINANCE_APPROVED';
            $class = 'ok';
        }

        $html[] = '<a class="btn btn-micro hasTooltip" ';
        $html[] = ' href="javascript:void(0);" ';
        $html[] = ' title="' . addslashes(htmlspecialchars(JText::_($title), ENT_COMPAT, 'UTF-8')) . '">';
        $html[] = '<i class="icon-' . $class . '"></i>';
        $html[] = '</a>';

        return implode($html);
    }


    /**
     * Display an icon that indicates featured item.
     *
     * @param   int $value
     *
     * @return string
     */
    public static function featured($value = 0)
    {
        JHtml::_('bootstrap.tooltip');

        // Array of image, task, title, action
        $states = array(
            0 => array('unfeatured', 'COM_CROWDFUNDINGFINANCE_UNFEATURED'),
            1 => array('featured', 'COM_CROWDFUNDINGFINANCE_FEATURED'),
        );

        $state = Joomla\Utilities\ArrayHelper::getValue($states, (int)$value, $states[1]);
        $icon  = $state[0];
        $html  = '<a href="javascript: void(0);" class="btn btn-micro hasTooltip' . ((int)$value === 1 ? ' active' : '') . '" title="' . JText::_($state[1]) . '"><i class="icon-' . $icon . '"></i></a>';

        return $html;
    }

    /**
     * Returns a published state on a grid.
     *
     * @param   integer $value The state value.
     *
     * @return  string
     */
    public static function published($value)
    {
        $state = (!$value) ? 'unpublish' : 'publish';
        $title = (!$value) ? 'JUNPUBLISHED' : 'JPUBLISHED';

        $html[] = '<a class="btn btn-micro hasTooltip"';
        $html[] = ' href="javascript:void(0);" title="' . JText::_($title) . '" >';
        $html[] = '<i class="icon-' . $state . '"></i>';
        $html[] = '</a>';

        return implode("\n", $html);
    }

    /**
     * Returns IBAN and a link for a popup that displays information about bank account.
     *
     * @param   string $iban  IBAN number
     * @param   string $bankAccount   Information about a bank account.
     * @param   integer $projectId    Project ID
     *
     * @return  string
     */
    public static function iban($iban, $bankAccount, $projectId)
    {
        $html = array();

        $iban = JString::trim($iban);
        $bankAccount = JString::trim($bankAccount);

        if ($iban !== '' or $bankAccount !== '') {
            if ($iban !== '') {
                $html[] = htmlentities($iban, ENT_QUOTES, 'UTF-8');
                $html[] = '<br />';
            }

            if ($bankAccount !== '') {
                $html[] = '<a class="btn btn-mini js-cf-additionalinfo" href="javascript:void(0);" data-pid="'.$projectId.'" data-type="banktransfer" data-title="Bank Transfer">';
                $html[] = '<i class="icon-eye"></i>';
                $html[] = JText::_('COM_CROWDFUNDINGFINANCE_ADDITIONAL_INFORMATION');
                $html[] = '</a>';
            }

        } else {
            $html[] = '---';
        }

        return implode("\n", $html);
    }

    /**
     * Generates information about transaction amount.
     *
     * @param array $data
     * @param string $status
     * @param Crowdfunding\Amount $amount
     *
     * @return string
     */
    public static function transactionStatisticAmount($data, $status, $amount)
    {
        // Get the data from the aggregated list.
        $data = Joomla\Utilities\ArrayHelper::getValue($data, $status, array(), 'array');

        $transactionAmount = Joomla\Utilities\ArrayHelper::getValue($data, 'amount', 0, 'float');
        $transactions = Joomla\Utilities\ArrayHelper::getValue($data, 'transactions', 0, 'int');
        $projectId = Joomla\Utilities\ArrayHelper::getValue($data, 'project_id', 0, 'int');

        $html[] = $amount->setValue($transactionAmount)->formatCurrency();

        $html[] .= '<a href="'.JRoute::_('index.php?option=com_crowdfundingfinance&view=transactions&filter_search=pid:'.$projectId.'&filter_payment_status='.htmlentities($status, ENT_QUOTES, 'UTF-8')).'">';
        $html[] .= '( '.$transactions.' )';
        $html[] .= '</a>';

        return implode("\n", $html);
    }

    /**
     * Returns information about user's PayPal account.
     *
     * @param   string $email
     * @param   string $firstName
     * @param   string $lastName
     * @param   int $projectId
     *
     * @return  string
     */
    public static function paypal($email, $firstName, $lastName, $projectId)
    {
        $html = array();

        if ($email !== '') {
            $html[] = htmlentities($email, ENT_QUOTES, "UTF-8");

            if ($firstName !== '' or $lastName !== '') {
                $html[] = '<a class="btn btn-mini js-cf-additionalinfo" href="javascript:void(0);" data-pid="'.$projectId.'" data-type="paypal" data-title="PayPal">';
                $html[] = '<i class="icon-eye"></i>';
                $html[] = JText::_('COM_CROWDFUNDINGFINANCE_ADDITIONAL_INFORMATION');
                $html[] = '</a>';
            }

        } else {
            $html[] = "---";
        }

        return implode("\n", $html);
    }

    /**
     * Calculate the fee that the site owner is going to receive.
     *
     * @param array $data
     * @param Crowdfunding\Amount $amount
     * @param string $title
     *
     * @return string
     */
    public static function earnedFees($data, $amount, $title)
    {
        // Get the data from the aggregated list.
        $completed = Joomla\Utilities\ArrayHelper::getValue($data, 'completed', array(), 'array');
        $pending = Joomla\Utilities\ArrayHelper::getValue($data, 'pending', array(), 'array');

        $completedAmount = Joomla\Utilities\ArrayHelper::getValue($completed, 'fee_amount', 0, 'float');
        $pendingAmount = Joomla\Utilities\ArrayHelper::getValue($pending, 'fee_amount', 0, 'float');

        $html[] = $amount->setValue($completedAmount + $pendingAmount)->formatCurrency();

        $html[] = '<a class="btn btn-mini hasTooltip" href="javascript:void(0);" title="'.htmlentities($title, ENT_QUOTES, 'UTF-8').'">';
        $html[] = '<i class="icon-info"></i>';
        $html[] = '</a>';

        return implode("\n", $html);
    }

    /**
     * Calculate the fee that the site owner will not be able to receive.
     *
     * @param array $data
     * @param Crowdfunding\Amount $amount
     * @param string $title
     *
     * @return string
     */
    public static function missedFees($data, $amount, $title)
    {
        // Get the data from the aggregated list.
        $canceled = Joomla\Utilities\ArrayHelper::getValue($data, 'canceled', array(), 'array');
        $failed = Joomla\Utilities\ArrayHelper::getValue($data, 'failed', array(), 'array');
        $refunded = Joomla\Utilities\ArrayHelper::getValue($data, 'refunded', array(), 'array');

        $canceledAmount = Joomla\Utilities\ArrayHelper::getValue($canceled, 'fee_amount', 0, 'float');
        $failedAmount = Joomla\Utilities\ArrayHelper::getValue($failed, 'fee_amount', 0, 'float');
        $refundedAmount = Joomla\Utilities\ArrayHelper::getValue($refunded, 'fee_amount', 0, 'float');

        $html[] = $amount->setValue($canceledAmount + $failedAmount + $refundedAmount)->formatCurrency();

        $html[] = '<a class="btn btn-mini hasTooltip" href="javascript:void(0);" title="'.htmlentities($title, ENT_QUOTES, 'UTF-8').'">';
        $html[] = '<i class="icon-info"></i>';
        $html[] = '</a>';

        return implode("\n", $html);
    }

    /**
     * Calculate the fee that the site owner is going to receive.
     *
     * @param array $data
     * @param Crowdfunding\Amount $amount
     * @param string $title
     *
     * @return string
     */
    public static function ownerEarnedAmount($data, $amount, $title)
    {
        // Get the data from the aggregated list.
        $completed = Joomla\Utilities\ArrayHelper::getValue($data, 'completed', array(), 'array');
        $pending = Joomla\Utilities\ArrayHelper::getValue($data, 'pending', array(), 'array');

        $completedAmount = Joomla\Utilities\ArrayHelper::getValue($completed, 'amount', 0, 'float');
        $pendingAmount = Joomla\Utilities\ArrayHelper::getValue($pending, 'amount', 0, 'float');

        $html[] = $amount->setValue($completedAmount + $pendingAmount)->formatCurrency();

        $html[] = '<a class="btn btn-mini hasTooltip" href="javascript:void(0);" title="'.htmlentities($title, ENT_QUOTES, 'UTF-8').'">';
        $html[] = '<i class="icon-info"></i>';
        $html[] = '</a>';

        return implode("\n", $html);
    }

    /**
     * Calculate the fee that the site owner is going to receive.
     *
     * @param array $data
     * @param Crowdfunding\Amount $amount
     * @param string $title
     *
     * @return string
     */
    public static function ownerMissedAmount($data, $amount, $title)
    {
        // Get the data from the aggregated list.
        $canceled = Joomla\Utilities\ArrayHelper::getValue($data, 'canceled', array(), 'array');
        $failed = Joomla\Utilities\ArrayHelper::getValue($data, 'failed', array(), 'array');
        $refunded = Joomla\Utilities\ArrayHelper::getValue($data, 'refunded', array(), 'array');

        $canceledAmount = Joomla\Utilities\ArrayHelper::getValue($canceled, 'amount', 0, 'float');
        $failedAmount = Joomla\Utilities\ArrayHelper::getValue($failed, 'amount', 0, 'float');
        $refundedAmount = Joomla\Utilities\ArrayHelper::getValue($refunded, 'amount', 0, 'float');

        $html[] = $amount->setValue($canceledAmount + $failedAmount + $refundedAmount)->formatCurrency();

        $html[] = '<a class="btn btn-mini hasTooltip" href="javascript:void(0);" title="'.htmlentities($title, ENT_QUOTES, 'UTF-8').'">';
        $html[] = '<i class="icon-info"></i>';
        $html[] = '</a>';

        return implode("\n", $html);
    }
}
