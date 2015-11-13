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

class CrowdfundingFinanceModelProject extends JModelItem
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
    public function getTable($type = 'Project', $prefix = 'CrowdfundingFinanceTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($id)
    {
        $db = $this->getDbo();

        $query = $db->getQuery(true);
        $query
            ->select("a.id, a.title, a.short_desc, a.image, a.goal, a.funded, a.funding_start, a.funding_end, a.created")
            ->from($db->quoteName("#__crowdf_projects", "a"))
            ->where("a.id = " . (int)$id);

        $db->setQuery($query);

        return $db->loadObject();
    }
}
