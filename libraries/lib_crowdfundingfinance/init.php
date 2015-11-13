<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Libraries
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('JPATH_PLATFORM') or die;

if (!defined('CROWDFUNDINGFINANCE_PATH_COMPONENT_ADMINISTRATOR')) {
    define('CROWDFUNDINGFINANCE_PATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_crowdfundingfinance');
}

if (!defined('CROWDFUNDINGFINANCE_PATH_COMPONENT_SITE')) {
    define('CROWDFUNDINGFINANCE_PATH_COMPONENT_SITE', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_crowdfundingfinance');
}

if (!defined('CROWDFUNDINGFINANCE_PATH_LIBRARY')) {
    define('CROWDFUNDINGFINANCE_PATH_LIBRARY', JPATH_LIBRARIES . DIRECTORY_SEPARATOR . 'crowdfundingfinance');
}

JLoader::registerNamespace('CrowdfundingFinance', JPATH_LIBRARIES);

// Register some helpers
JLoader::register(
    'CrowdfundingFinanceHelper',
    CROWDFUNDINGFINANCE_PATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'crowdfundingfinance.php'
);
JLoader::register('CrowdfundingFinanceHelperRoute', CROWDFUNDINGFINANCE_PATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'route.php');

// Include HTML helpers path
JHtml::addIncludePath(CROWDFUNDINGFINANCE_PATH_COMPONENT_SITE . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
