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
class EShopModelOrders extends EShopModelList
{
	function __construct($config)
	{
		$config['search_fields'] = array('firstname', 'lastname', 'email', 'payment_firstname', 'payment_lastname', 'shipping_firstname', 'shipping_lastname');
		//$config['state_vars'] = array('filter_order' => array('a.id', 'int', 1));
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
		$query->select('a.*')
			->from($this->mainTable . ' AS a ');
		$where = $this->_buildContentWhereArray();
		if (count($where))
			$query->where($where);
		$orderby = $this->_buildContentOrderBy();
		if ($orderby != '')
			$query->order($orderby);
		return $query;
	}
}