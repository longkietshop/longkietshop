<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelCustomers extends EShopModelList
{
	function __construct($config)
	{
		$config['state_vars'] = array('filter_order' => array('b.name', 'string', 1));
		$config['search_fields'] = array('b.name');
		parent::__construct($config);
	}
	
	/**
	 * Basic build Query function.
	 * The child class must override it if it is necessary
	 *
	 * @return string
	 */
	public function _buildQuery()
	{
		$db = $this->getDbo();
		$state = $this->getState();
		$query = $db->getQuery(true);
		$query->select('a.*, b.name')
			->innerJoin('#__users AS b ON (a.customer_id = b.id)')
			->from($this->mainTable . ' AS a ');
		$where = $this->_buildContentWhereArray();
		if (count($where))
			$query->where($where);
		return $query;
	}
	
	/**
	 * Build an where clause array
	 *
	 * @return array
	 */
	public function _buildContentWhereArray()
	{
		$db = $this->getDbo();
		$state = $this->getState();
		$where = array();
		if ($state->filter_state == 'P')
			$where[] = ' a.published=1 ';
		elseif ($state->filter_state == 'U')
		$where[] = ' a.published = 0';
	
		if ($state->search)
		{
			$search = $db->Quote('%' . $db->getEscaped($state->search, true) . '%', false);
			if (is_array($this->searchFields))
			{
				$whereOr = array();
				foreach ($this->searchFields as $titleField)
				{
					$whereOr[] = " LOWER($titleField) LIKE " . $search;
				}
				$where[] = ' (' . implode(' OR ', $whereOr) . ') ';
			}
			else
			{
				$where[] = 'LOWER(' . $this->searchFields . ') LIKE ' . $db->Quote('%' . $db->getEscaped($state->search, true) . '%', false);
			}
		}
		return $where;
	}
}