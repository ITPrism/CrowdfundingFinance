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

abstract class CrowdfundingFinanceHelper
{
    protected static $extension = 'com_crowdfundingfinance';

    /**
     * Configure the Linkbar.
     *
     * @param    string $vName The name of the active view.
     *
     * @since    1.6
     */
    public static function addSubmenu($vName = 'dashboard')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_CROWDFUNDINGFINANCE_DASHBOARD'),
            'index.php?option=' . self::$extension . '&view=dashboard',
            $vName === 'dashboard'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CROWDFUNDINGFINANCE_PROJECTS'),
            'index.php?option=' . self::$extension . '&view=projects',
            $vName === 'projects'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CROWDFUNDINGFINANCE_TRANSACTIONS'),
            'index.php?option=' . self::$extension . '&view=transactions',
            $vName === 'transactions'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_CROWDFUNDINGFINANCE_PAYOUTS'),
            'index.php?option=' . self::$extension . '&view=payouts',
            $vName === 'payouts'
        );

        /* JHtmlSidebar::addEntry(
            JText::_('COM_CROWDFUNDINGFINANCE_PLUGINS'),
            'index.php?option=com_plugins&view=plugins&filter_search='.rawurlencode('crowdfunding finance'),
            $vName == 'plugins'
        ); */
    }
}
