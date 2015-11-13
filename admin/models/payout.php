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

class CrowdfundingFinanceModelPayout extends JModelAdmin
{
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string $type   The table type to instantiate
     * @param   string $prefix A prefix for the table class name. Optional.
     * @param   array  $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Payout', $prefix = 'CrowdfundingFinanceTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interrogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.payout', 'payout', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.payout.data', array());
        if (empty($data)) {
            $data = $this->getItem();

            if (!$data->id) {
                // If you create a new payout record, set a project ID.
                $app = JFactory::getApplication();
                $projectId = $app->input->getInt("id");

                $data->id = $projectId;
            }
        }

        return $data;
    }

    /**
     * Save data into the DB
     *
     * @param array $data The data of item
     *
     * @return    int      Item ID
     */
    public function save($data)
    {
        $projectId       = Joomla\Utilities\ArrayHelper::getValue($data, "id", 0, "int");
        $paypalFirstName = Joomla\Utilities\ArrayHelper::getValue($data, "paypal_first_name");
        $paypalLastName  = Joomla\Utilities\ArrayHelper::getValue($data, "paypal_last_name");
        $paypalEmail     = Joomla\Utilities\ArrayHelper::getValue($data, "paypal_email");
        $iban            = Joomla\Utilities\ArrayHelper::getValue($data, "iban");
        $bankAccount     = Joomla\Utilities\ArrayHelper::getValue($data, "bank_account");

        // Check for valid ID.
        if (!$projectId) {
            return 0;
        }

        // Create a record if it does not exist.
        $this->createRecord($projectId);

        if (!$paypalFirstName) { $paypalFirstName = null; }
        if (!$paypalLastName) { $paypalLastName = null; }
        if (!$paypalEmail) { $paypalEmail = null; }
        if (!$iban) { $iban = null; }
        if (!$bankAccount) { $bankAccount = null; }

        // Load a record from the database
        $row = $this->getTable();
        $row->load($projectId);

        $row->set("paypal_first_name", $paypalFirstName);
        $row->set("paypal_last_name", $paypalLastName);
        $row->set("paypal_email", $paypalEmail);
        $row->set("iban", $iban);
        $row->set("bank_account", $bankAccount);

        $row->store(true);

        return $row->get("id");
    }

    protected function createRecord($id)
    {
        $db = $this->getDbo();

        $query = $db->getQuery(true);

        $query
            ->select("COUNT(*)")
            ->from($db->quoteName("#__cffinance_payouts", "a"))
            ->where("a.id =" .(int)$id);

        $db->setQuery($query, 0, 1);
        $result = $db->loadResult();

        if (!$result) {
            $query = $db->getQuery(true);

            $query
                ->insert($db->quoteName("#__cffinance_payouts"))
                ->set($db->quoteName("id") . "=" . (int)$id);

            $db->setQuery($query);
            $db->execute();
        }
    }
}
