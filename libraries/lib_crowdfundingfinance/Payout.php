<?php
/**
 * @package      CrowdfundingFinance
 * @subpackage   Payouts
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace CrowdfundingFinance;

use Prism\Database\Table;
use Joomla\Registry\Registry;

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality that manage a payout.
 *
 * @package      CrowdfundingFinance
 * @subpackage   Payouts
 */
class Payout extends Table
{
    protected $id;

    protected $paypal_email;
    protected $paypal_first_name;
    protected $paypal_last_name;
    protected $iban;
    protected $bank_account;
    protected $stripe;

    protected $project_id;
    protected $secret_key;

    /**
     * Load a payout data from database.
     *
     * <code>
     * $keys = array(
     *    "project_id" => 1
     * );
     *
     * $payout    = new CrowdfundingFinance\Payout();
     * $payout->setDb(\JFactory::getDbo());
     * $payout->load($keys);
     * </code>
     *
     * @param int|array $keys Project ID or keys
     * @param array $options
     */
    public function load($keys, $options = array())
    {
        if (array_key_exists('secret_key', $options) and $options['secret_key'] !== '') {
            $this->secret_key = $options['secret_key'];
        }

        if (!$this->secret_key) {
            throw new \InvalidArgumentException('It is missing a key used for encryption and decrypting data.');
        }

        $query = $this->db->getQuery(true);

        $query
            ->select(
                'a.id, a.paypal_email, a.paypal_first_name, a.paypal_last_name, a.iban, a.bank_account, a.project_id, ' .
                'AES_DECRYPT(a.stripe, '.$this->db->quote($this->secret_key).') as stripe'
            )
            ->from($this->db->quoteName('#__cffinance_payouts', 'a'));

        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $query->where($this->db->quoteName($key) .' = ' . $this->db->quote($value));
            }
        } else {
            $query->where('a.id = ' . (int)$keys);
        }

        $this->db->setQuery($query);
        $result = (array)$this->db->loadAssoc();

        if (count($result) > 0) {
            $this->bind($result, array('stripe'));

            // Set Stripe data.
            if ($result['stripe'] !== null) {
                $this->stripe = new Registry($result['stripe']);
            }
        }
    }

    /**
     * Return payout ID.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * if (!$payout->getId()) {
     * ...
     * }
     * </code>
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return PayPal e-mail.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * $paypalEmail = $payout->getPaypalEmail();
     * </code>
     *
     * @return string
     */
    public function getPaypalEmail()
    {
        return $this->paypal_email;
    }

    /**
     * Return PayPal First Name.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * $paypalFirstName = $payout->getPayPalFirstName();
     * </code>
     *
     * @return string
     */
    public function getPaypalFirstName()
    {
        return (string)$this->paypal_first_name;
    }

    /**
     * Return PayPal last name.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * $paypalLastName = $payout->getPayPalLastName();
     * </code>
     *
     * @return string
     */
    public function getPaypalLastName()
    {
        return $this->paypal_last_name;
    }

    /**
     * Return the IBAN of the user where the amount should be sent.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * $iban = $payout->getIban();
     * </code>
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Return information about user bank account.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * $bankAccount = $payout->getBankAccount();
     * </code>
     *
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * Return project ID.
     *
     * <code>
     * $id  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * echo $payout->getProjectId();
     * </code>
     *
     * @return int
     */
    public function getProjectId()
    {
        return (int)$this->project_id;
    }

    /**
     * Return project ID.
     *
     * <code>
     * $id  = 1;
     * $projectId = 2;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($id);
     *
     * $payout->setProjectId($projectId);
     * </code>
     *
     * @param $projectId
     *
     * @return self
     */
    public function setProjectId($projectId)
    {
        $this->project_id = (int)$projectId;

        return $this;
    }

    /**
     * Store data to database.
     *
     * <code>
     * $id  = 1;
     * $secretKey = 'sk_asdf1234';
     *
     * $stripe = new Registry;
     * $stripe->set('stripconnect.production.access_token', 'at_asdf1234');
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->setSecretKey($secretKey);
     * $payout->load($id);
     *
     * $payout->setStripe($stripe);
     * $payout->store();
     * </code>
     */
    public function store()
    {
        if (!$this->secret_key) {
            throw new \InvalidArgumentException('It is missing a key used for encryption and decrypting data.');
        }

        if (!$this->id) { // Insert
            $this->insertObject();
        } else { // Update
            $this->updateObject();
        }
    }

    protected function insertObject()
    {
        if (!$this->project_id) {
            throw new \InvalidArgumentException('It is missing project ID.');
        }

        // Prepare data value.
        $stripe = 'NULL';
        if (($this->stripe instanceof Registry) and ($this->stripe->count() > 0)) {
            $stripe = ' AES_ENCRYPT(' . $this->db->quote($this->stripe->toString()) . ', '. $this->db->quote($this->secret_key) . ')';
        }

        $query = $this->db->getQuery(true);

        $query
            ->insert($this->db->quoteName('#__cffinance_payouts'))
            ->set($this->db->quoteName('paypal_email') . ' = ' .$this->db->quote($this->paypal_email))
            ->set($this->db->quoteName('paypal_first_name') . ' = ' .$this->db->quote($this->paypal_first_name))
            ->set($this->db->quoteName('paypal_last_name') . ' = ' .$this->db->quote($this->paypal_last_name))
            ->set($this->db->quoteName('iban') . ' = ' . $this->db->quote($this->paypal_last_name))
            ->set($this->db->quoteName('bank_account') . ' = ' .$this->db->quote($this->bank_account))
            ->set($this->db->quoteName('stripe') . ' = ' . $stripe)
            ->set($this->db->quoteName('project_id') . ' = ' . (int)$this->project_id);

        $this->db->setQuery($query);
        $this->db->execute();

        $this->id = $this->db->insertid();
    }

    protected function updateObject()
    {
        // Prepare data value.
        $stripe = 'NULL';
        if (($this->stripe instanceof Registry) and ($this->stripe->count() > 0)) {
            $stripe = ' AES_ENCRYPT(' . $this->db->quote($this->stripe->toString()) . ', '. $this->db->quote($this->secret_key) . ')';
        }

        $query = $this->db->getQuery(true);

        $query
            ->update($this->db->quoteName('#__cffinance_payouts'))
            ->set($this->db->quoteName('paypal_email') . ' = ' .$this->db->quote($this->paypal_email))
            ->set($this->db->quoteName('paypal_first_name') . ' = ' .$this->db->quote($this->paypal_first_name))
            ->set($this->db->quoteName('paypal_last_name') . ' = ' .$this->db->quote($this->paypal_last_name))
            ->set($this->db->quoteName('iban') . ' = ' . $this->db->quote($this->paypal_last_name))
            ->set($this->db->quoteName('bank_account') . ' = ' .$this->db->quote($this->bank_account))
            ->set($this->db->quoteName('stripe') . ' = ' . $stripe)
            ->where($this->db->quoteName('project_id') . ' = ' . (int)$this->project_id);

        $this->db->setQuery($query);
        $this->db->execute();
    }

    /**
     * Return Stripe data.
     *
     * <code>
     * $payoutId  = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->load($payoutId);
     *
     * $stripe = $payout->getStripe();
     * </code>
     *
     * @return Registry
     */
    public function getStripe()
    {
        return $this->stripe;
    }

    /**
     * Set Stripe data.
     *
     * <code>
     * $payoutId = 1;
     * $stripeData = new Registry;
     *
     * $stripeData->set('stripeconnect.production.access_token', 'tk_asdf1234');
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->setSecretKey('asdf1234');
     * $payout->load($payoutId);
     *
     * $payout->setStripe($stripeData);
     * </code>
     *
     * @param Registry $data
     *
     * @return self
     */
    public function setStripe(Registry $data)
    {
        $this->stripe = $data;

        return $this;
    }

    /**
     * Set key that will be used for encrypting data.
     *
     * <code>
     * $payoutId = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->setSecretKey('asdf1234');
     *
     * $payout->load($payoutId);
     * </code>
     *
     * @param string $key
     *
     * @return self
     */
    public function setSecretKey($key)
    {
        $this->secret_key = $key;

        return $this;
    }

    /**
     * Update Stripe data.
     *
     * <code>
     * $payoutId = 1;
     *
     * $payout    = new CrowdfundingFinance\Payout(\JFactory::getDbo());
     * $payout->setSecretKey('asdf1234');
     *
     * $payout->load($payoutId);
     *
     * $stripeAccessTokens = new Registry();
     *
     * $payout->setStripe($stripeAccessTokens);
     * $payout->storeStripe();
     * </code>
     */
    public function storeStripe()
    {
        if (!$this->secret_key) {
            throw new \InvalidArgumentException('It is missing a key used for encryption and decrypting data.');
        }

        if (!$this->project_id) {
            throw new \InvalidArgumentException('It is missing project ID.');
        }

        // Prepare data value.
        $stripe = 'NULL';
        if (($this->stripe instanceof Registry) and ($this->stripe->count() > 0)) {
            $stripe = ' AES_ENCRYPT(' . $this->db->quote($this->stripe->toString()) . ', '. $this->db->quote($this->secret_key) . ')';
        }

        $query = $this->db->getQuery(true);

        $query
            ->update($this->db->quoteName('#__cffinance_payouts'))
            ->set($this->db->quoteName('stripe') . ' = ' . $stripe)
            ->where($this->db->quoteName('project_id') . ' = ' . (int)$this->project_id);

        $this->db->setQuery($query);
        $this->db->execute();
    }
}
