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

class CrowdfundingFinanceModelPayouts extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  $config An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'category', 'b.title',
                'published', 'a.published',
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        // Load the component parameters.
        $params = JComponentHelper::getParams($this->option);
        $this->setState('params', $params);

        // Load filter search.
        $value = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $value);

        // Load filter state.
        $value = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $value);

        // Load filter approved state.
        $value = $this->getUserStateFromRequest($this->context . '.filter.approved', 'filter_approved', '', 'string');
        $this->setState('filter.approved', $value);

        // Load filter featured state.
        $value = $this->getUserStateFromRequest($this->context . '.filter.featured', 'filter_featured', '', 'string');
        $this->setState('filter.featured', $value);

        // Load filter category.
        $value = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', 0, 'int');
        $this->setState('filter.category_id', $value);

        // Load filter type.
        $value = $this->getUserStateFromRequest($this->context . '.filter.type_id', 'filter_type_id', 0, 'int');
        $this->setState('filter.type_id', $value);

        // List state information.
        parent::populateState('a.created', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string $id A prefix for the store id.
     *
     * @return  string      A store id.
     * @since   1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.approved');
        $id .= ':' . $this->getState('filter.featured');
        $id .= ':' . $this->getState('filter.category_id');
        $id .= ':' . $this->getState('filter.type_id');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     * @since   1.6
     */
    protected function getListQuery()
    {
        $db = $this->getDbo();
        /** @var $db JDatabaseDriver */

        // Create a new query object.
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id, a.title, a.goal, a.funded, a.funding_start, a.funding_end, a.user_id, ' .
                'a.funding_days, a.ordering, a.created, a.catid, ROUND( (a.funded/a.goal) * 100, 1 ) AS funded_percents, ' .
                'a.featured, a.published, a.approved, ' .
                'b.title AS category, ' .
                'c.title AS type, ' .
                'd.name AS username, ' .
                'e.paypal_email, e.paypal_first_name, e.paypal_last_name, e.iban, e.bank_account '
            )
        );
        $query->from($db->quoteName('#__crowdf_projects', 'a'));
        $query->leftJoin($db->quoteName('#__categories', 'b') . ' ON a.catid = b.id');
        $query->leftJoin($db->quoteName('#__crowdf_types', 'c') . ' ON a.type_id = c.id');
        $query->leftJoin($db->quoteName('#__users', 'd') . ' ON a.user_id = d.id');
        $query->leftJoin($db->quoteName('#__cffinance_payouts', 'e') . ' ON a.id = e.id');

        // Filter by category
        $categoryId = $this->getState('filter.category_id');
        if (!empty($categoryId)) {
            $query->where('b.id = ' . (int)$categoryId);
        }

        // Filter by state
        $state = $this->getState('filter.state');
        if (is_numeric($state)) {
            $query->where('a.published = ' . (int)$state);
        } elseif ($state === '') {
            $query->where('(a.published IN (0, 1))');
        }

        // Filter by approved state
        $state = $this->getState('filter.approved');
        if (is_numeric($state)) {
            $query->where('a.approved = ' . (int)$state);
        } elseif ($state === '') {
            $query->where('(a.approved IN (0, 1))');
        }

        // Filter by approved state
        $state = $this->getState('filter.featured');
        if (is_numeric($state)) {
            $query->where('a.featured = ' . (int)$state);
        } elseif ($state === '') {
            $query->where('(a.featured IN (0, 1))');
        }

        // Filter by type
        $typeId = $this->getState('filter.type_id');
        if (!empty($typeId)) {
            $query->where('a.type_id = ' . (int)$typeId);
        }

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } elseif (stripos($search, 'uid:') === 0) {
                $query->where('a.user_id = ' . (int)substr($search, 4));
            } else {
                $escaped = $db->escape($search, true);
                $quoted  = $db->quote("%" . $escaped . "%", false);
                $query->where('a.title LIKE ' . $quoted);
            }
        }

        // Add the list ordering clause.
        $orderString = $this->getOrderString();
        $query->order($db->escape($orderString));

        return $query;
    }

    protected function getOrderString()
    {
        $orderCol  = $this->getState('list.ordering', 'a.created');
        $orderDirn = $this->getState('list.direction', 'asc');
        if ($orderCol == 'a.ordering') {
            $orderCol = 'a.catid ' . $orderDirn . ', a.ordering';
        }

        return $orderCol . ' ' . $orderDirn;
    }
}
