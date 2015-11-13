<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Get a list of items
 */
class CrowdfundingFinanceModelPayout extends JModelForm
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
        }

        return $data;
    }

    public function getItem()
    {
        return array();
    }


    /**
     * Save data in the database
     *
     * @param array $data The data of item
     *
     * @return    int      Payout Record ID
     */
    public function save($data)
    {
        // Set the value of the data to NULL if it is empty.
        foreach ($data as &$value) {
            if (empty($value)) {
                $value = null;
            }
        }


        $projectId       = JArrayHelper::getValue($data, "id");
        $paypalEmail     = JArrayHelper::getValue($data, "paypal_email");
        $paypalFirstName = JArrayHelper::getValue($data, "paypal_first_name");
        $paypalLastName  = JArrayHelper::getValue($data, "paypal_last_name");
        $iban            = JArrayHelper::getValue($data, "iban");
        $bankAccount     = JArrayHelper::getValue($data, "bank_account");

        // Load a record from the database
        $row = $this->getTable();
        $row->load($projectId);

        // Create a new record if it does not exist.
        if (!$row->get("id")) {
            $this->createRecord($projectId);
            $row->load($projectId);
        }

        $row->set("paypal_email", $paypalEmail);
        $row->set("paypal_first_name", $paypalFirstName);
        $row->set("paypal_last_name", $paypalLastName);
        $row->set("iban", $iban);
        $row->set("bank_account", $bankAccount);

        $row->store(true);

        return $row->get("id");
    }

    /**
     * Create a new record.
     *
     * @param $projectId
     */
    protected function createRecord($projectId)
    {
        if (!$projectId) {
            throw new InvalidArgumentException(JText::_("COM_CROWDFUNDING_ERROR_INVALID_PROJECT"));
        }

        $db     = $this->getDbo();
        $query  = $db->getQuery(true);

        $query
            ->insert($db->quoteName("#__cffinance_payouts"))
            ->set($db->quoteName("id") ." = ".(int)$projectId);

        $db->setQuery($query);
        $db->execute();
    }
}
