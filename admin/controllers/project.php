<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Crowdfunding Finance project controller class.
 *
 * @package        ITPrism Components
 * @subpackage     Crowdfunding
 * @since          1.6
 */
class CrowdfundingFinanceControllerProject extends Prism\Controller\Form\Backend
{
    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $data   = $app->input->post->get('jform', array(), 'array');
        $itemId = Joomla\Utilities\ArrayHelper::getValue($data, "id");

        $redirectOptions = array(
            "task" => $this->getTask(),
            "id"   => $itemId
        );

        $model = $this->getModel();
        /** @var $model CrowdfundingModelProject */

        $form = $model->getForm($data, false);
        /** @var $form JForm * */

        if (!$form) {
            throw new Exception($model->getError(), 500);
        }

        // Validate the form
        $validData = $model->validate($form, $data);

        // @todo fix this
        $validData["duration_type"] = Joomla\Utilities\ArrayHelper::getValue($data, "funding_duration_type");

        // Check for errors.
        if ($validData === false) {
            $this->displayNotice($form->getErrors(), $redirectOptions);

            return;
        }

        try {

            // Get image
            $files = $app->input->files->get('jform', array(), 'array');
            $image = Joomla\Utilities\ArrayHelper::getValue($files, "image");

            $pitchImage = Joomla\Utilities\ArrayHelper::getValue($files, "pitch_image");

            jimport('joomla.filesystem.folder');
            jimport('joomla.filesystem.file');
            jimport('joomla.filesystem.path');
            jimport('joomla.image.image');

            // Upload image
            if (!empty($image['name'])) {

                $imageNames = $model->uploadImage($image);
                if (!empty($imageNames["image"])) {
                    $validData = array_merge($validData, $imageNames);
                }

            }

            // Upload pitch image
            if (!empty($pitchImage['name'])) {

                $pitchImageName = $model->uploadPitchImage($pitchImage);
                if (!empty($pitchImageName)) {
                    $validData["pitch_image"] = $pitchImageName;
                }

            }

            $model->save($validData);

        } catch (Exception $e) {

            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_CROWDFUNDINGFINANCE_ERROR_SYSTEM'));

        }

        $this->displayMessage(JText::_('COM_CROWDFUNDINGFINANCE_PROJECT_SAVED'), $redirectOptions);
    }
}
