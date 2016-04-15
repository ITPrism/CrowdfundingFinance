<?php
/**
 * @package      Crowdfunding
 * @subpackage   Plugins
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('Crowdfunding.init');
jimport('Crowdfundingfinance.init');

/**
 * This plugin validates data.
 * It works only on front-end.
 *
 * @package      Crowdfunding
 * @subpackage   Plugins
 */
class plgContentCrowdfundingFraudPrevention extends JPlugin
{
    protected $autoloadLanguage = true;

    /**
     * @var JApplicationSite
     */
    protected $app;

    /**
     * This method validates project data before a user launch its campaign.
     * It works only on front-end.
     *
     * @param string $context
     * @param stdClass $item
     * @param Joomla\Registry\Registry $params
     * @param int $state
     *
     * @return null|array
     */
    public function onContentValidateChangeState($context, &$item, &$params, $state)
    {
        if (strcmp('com_crowdfunding.projects.changestate', $context) !== 0) {
            return null;
        }

        // This validation have to be processed when the state is for launching (publishing) campaign.
        if ((int)$state !== 1) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        $result = array(
            'success' => false,
            'message' => ''
        );

        // Get user ID.
        $userId  = JFactory::getUser()->get('id');

        // Get component parameters
        $componentParams = JComponentHelper::getParams('com_crowdfundingfinance');
        /** @var  $componentParams Joomla\Registry\Registry */

        // Verify the number of campaigns per user at one time.
        $allowedActiveCampaigns = (int)$componentParams->get('protection_active_projects');
        if ($allowedActiveCampaigns > 0) {
            // Get the number of active projects for a user.
            $userStatistics  = new Crowdfunding\Statistics\User(JFactory::getDbo(), $userId);
            $activeCampaigns = (int)$userStatistics->getNumberOfActiveCampaigns();

            // Validate number of active campaigns per user.
            if ($activeCampaigns >= $allowedActiveCampaigns) {
                $result['message'] = JText::sprintf('PLG_CONTENT_CROWDFUNDINGFRAUDPREVENTION_ERROR_ACTIVE_PROJECTS_D', $allowedActiveCampaigns);
                return $result;
            }
        }

        // Verify the number of campaigns per user per year.

        $allowedCampaignsPerYear = (int)$componentParams->get('protection_projects_per_year');
        if ($allowedCampaignsPerYear > 0) {
            // Get the number of active projects for a user.
            $userStatistics = new Crowdfunding\Statistics\User(JFactory::getDbo(), $userId);
            $numberOfCampaigns = (int)$userStatistics->getNumberOfCampaignsInPeriod();

            // Validate number of campaigns per year.
            if ($numberOfCampaigns >= $allowedCampaignsPerYear) {
                $result['message'] = JText::sprintf('PLG_CONTENT_CROWDFUNDINGFRAUDPREVENTION_ERROR_PROJECTS_YEAR_D', $allowedCampaignsPerYear);
                return $result;
            }
        }

        // Validation completed successfully.
        $result = array('success' => true);

        return $result;
    }
}
