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
 * Script file of the component
 */
class pkg_crowdfundingfinanceInstallerScript
{
    /**
     * Method to install the component.
     *
     * @param string $parent
     *
     * @return void
     */
    public function install($parent)
    {
    }

    /**
     * Method to uninstall the component.
     *
     * @param string $parent
     *
     * @return void
     */
    public function uninstall($parent)
    {
    }

    /**
     * Method to update the component.
     *
     * @param string $parent
     *
     * @return void
     */
    public function update($parent)
    {
    }

    /**
     * Method to run before an install/update/uninstall method.
     *
     * @param string $type
     * @param string $parent
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
    }

    /**
     * Method to run after an install/update/uninstall method.
     *
     * @param string $type
     * @param string $parent
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        if (!defined('COM_CROWDFUNDINGFINANCE_PATH_COMPONENT_ADMINISTRATOR')) {
            define('COM_CROWDFUNDINGFINANCE_PATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_crowdfundingfinance');
        }

        jimport('Prism.init');
        jimport('Crowdfundingfinance.init');

        // Register Component helpers
        JLoader::register(
            'CrowdfundingfinanceInstallHelper',
            COM_CROWDFUNDINGFINANCE_PATH_COMPONENT_ADMINISTRATOR . '/helpers/install.php'
        );

        // Start table with the information
        CrowdfundingfinanceInstallHelper::startTable();

        // Requirements
        CrowdfundingfinanceInstallHelper::addRowHeading(JText::_('COM_CROWDFUNDINGFINANCE_MINIMUM_REQUIREMENTS'));

        // Display result about verification for GD library
        $title = JText::_('COM_CROWDFUNDINGFINANCE_GD_LIBRARY');
        $info  = '';
        if (!extension_loaded('gd') and !function_exists('gd_info')) {
            $result = array('type' => 'important', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_WARNING'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JON'));
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Display result about verification for cURL library
        $title = JText::_('COM_CROWDFUNDINGFINANCE_CURL_LIBRARY');
        $info  = '';
        if (!extension_loaded('curl')) {
            $info   = JText::_('COM_CROWDFUNDINGFINANCE_CURL_INFO');
            $result = array('type' => 'important', 'text' => JText::_('JOFF'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JON'));
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Display result about verification Magic Quotes
        $title = JText::_('COM_CROWDFUNDINGFINANCE_MAGIC_QUOTES');
        $info  = '';
        if (get_magic_quotes_gpc()) {
            $info   = JText::_('COM_CROWDFUNDINGFINANCE_MAGIC_QUOTES_INFO');
            $result = array('type' => 'important', 'text' => JText::_('JON'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JOFF'));
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Display result about verification PHP Intl
        $title = JText::_('COM_CROWDFUNDINGFINANCE_PHPINTL');
        $info  = '';
        if (!extension_loaded('intl')) {
            $info   = JText::_('COM_CROWDFUNDINGFINANCE_PHPINTL_INFO');
            $result = array('type' => 'important', 'text' => JText::_('JOFF'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JON'));
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Display result about PHP version.
        $title = JText::_('COM_CROWDFUNDINGFINANCE_PHP_VERSION');
        $info  = '';
        if (version_compare(PHP_VERSION, '5.5.0') < 0) {
            $result = array('type' => 'important', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_WARNING'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JYES'));
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Display result about MySQL Version.
        $title = JText::_('COM_CROWDFUNDINGFINANCE_MYSQL_VERSION');
        $info  = '';
        $dbVersion = JFactory::getDbo()->getVersion();
        if (version_compare($dbVersion, '5.5.3', '<')) {
            $result = array('type' => 'important', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_WARNING'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JYES'));
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Display result about verification of installed Prism Library
        $info  = '';
        if (!class_exists('Prism\\Version')) {
            $title  = JText::_('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY');
            $info   = JText::_('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_DOWNLOAD');
            $result = array('type' => 'important', 'text' => JText::_('JNO'));
        } else {
            $prismVersion   = new Prism\Version();
            $text           = JText::sprintf('COM_CROWDFUNDINGFINANCE_CURRENT_V_S', $prismVersion->getShortVersion());

            if (class_exists('Crowdfundingfinance\\Version')) {
                $componentVersion = new Crowdfundingfinance\Version();
                $title            = JText::sprintf('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_S', $componentVersion->requiredPrismVersion);

                if (version_compare($prismVersion->getShortVersion(), $componentVersion->requiredPrismVersion, '<')) {
                    $info   = JText::_('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY_DOWNLOAD');
                    $result = array('type' => 'warning', 'text' => $text);
                }

            } else {
                $title  = JText::_('COM_CROWDFUNDINGFINANCE_PRISM_LIBRARY');
                $result = array('type' => 'success', 'text' => $text);
            }
        }
        CrowdfundingfinanceInstallHelper::addRow($title, $result, $info);

        // Installed extensions

        CrowdfundingfinanceInstallHelper::addRowHeading(JText::_('COM_CROWDFUNDINGFINANCE_INSTALLED_EXTENSIONS'));

        // Crowdfundingfinance Library
        $result = array('type' => 'success', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_INSTALLED'));
        CrowdfundingfinanceInstallHelper::addRow(JText::_('COM_CROWDFUNDINGFINANCE_CROWDFUNDINGFINANCE_LIBRARY'), $result, JText::_('COM_CROWDFUNDINGFINANCE_LIBRARY'));

        // Crowdfunding - Payout Options
        $result = array('type' => 'success', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_INSTALLED'));
        CrowdfundingfinanceInstallHelper::addRow(JText::_('COM_CROWDFUNDINGFINANCE_CROWDFUNDING_PAYOUT_OPTIONS'), $result, JText::_('COM_CROWDFUNDINGFINANCE_PLUGIN'));

        // Content - Crowdfunding Fraud Prevention
        $result = array('type' => 'success', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_INSTALLED'));
        CrowdfundingfinanceInstallHelper::addRow(JText::_('COM_CROWDFUNDINGFINANCE_CONTENT_CROUDFUNDINGFRAUDPREVENTION'), $result, JText::_('COM_CROWDFUNDINGFINANCE_PLUGIN'));

        // CrowdfundingPayment - Fraud Prevention
        $result = array('type' => 'success', 'text' => JText::_('COM_CROWDFUNDINGFINANCE_INSTALLED'));
        CrowdfundingfinanceInstallHelper::addRow(JText::_('COM_CROWDFUNDINGFINANCE_CROWDFUNDINGPAYMENT_FRAUDPREVENTION'), $result, JText::_('COM_CROWDFUNDINGFINANCE_PLUGIN'));

        // End table
        CrowdfundingfinanceInstallHelper::endTable();

        echo JText::sprintf('COM_CROWDFUNDINGFINANCE_MESSAGE_REVIEW_SAVE_SETTINGS', JRoute::_('index.php?option=com_crowdfundingfinance'));

        if (!class_exists('Prism\\Version')) {
            echo JText::_('COM_CROWDFUNDINGFINANCE_MESSAGE_INSTALL_PRISM_LIBRARY');
        } else {
            if (class_exists('Crowdfundingfinance\\Version')) {
                $prismVersion     = new Prism\Version();
                $componentVersion = new Crowdfundingfinance\Version();
                if (version_compare($prismVersion->getShortVersion(), $componentVersion->requiredPrismVersion, '<')) {
                    echo JText::_('COM_CROWDFUNDINGFINANCE_MESSAGE_INSTALL_PRISM_LIBRARY');
                }
            }
        }
    }
}
