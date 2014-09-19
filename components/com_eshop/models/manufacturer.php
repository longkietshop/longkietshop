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

class EShopModelManufacturer extends EShopModelList
{

	/**
	 * ID of the current category
	 * 
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Current active language
	 * 
	 * @var string
	 */
	protected $language = null;

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct($config)
	{
		parent::__construct($config);
		$mainframe = JFactory::getApplication();
		$this->id = JRequest::getInt('id');
		$this->language = JFactory::getLanguage()->getTag();
		$listLength = EshopHelper::getConfigValue('catalog_limit');
		if (!$listLength)
			$listLength = $mainframe->getCfg('list_limit');
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $listLength, 'int' );
		$limitstart = JRequest::getInt('limitstart', JRequest::getInt('start'));
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		$app		= JFactory::getApplication();
		$app->setUserState('limit', $limit);
	}
	
	/**
	 *
	 * Get manufacturer
	 */
	function getManufacturer()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.manufacturer_name, b.manufacturer_alias, b.manufacturer_desc')
		->from('#__eshop_manufacturers AS a')
		->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
		->where('a.id = ' . intval($this->id))
		->where('b.language = "' . $this->language . '"');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 * Get total entities
	 *
	 * @return int
	 */
	public function getTotal()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$db = $this->getDbo();
			$where = $this->_buildContentWhereArray();
			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__eshop_products AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
				->innerJoin('#__eshop_manufacturers AS pc ON (a.manufacturer_id = pc.id)');
			if (count($where))
				$query->where($where);
			$db->setQuery($query);
			$this->_total = $db->loadResult();
		}
		return $this->_total;
	}

	/**
	 * 
	 * Function to build query
	 * @see EShopModelList::_buildQuery()
	 */
	public function _buildQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_tag, b.meta_key, b.meta_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->innerJoin('#__eshop_manufacturers AS pc ON (a.manufacturer_id = pc.id)');
		$where = $this->_buildContentWhereArray();
		if (count($where))
			$query->where($where);
			
		$order = $this->_buildContentOrderBy();
		if(count($order))
		$query->order($order);
					
		$query->order('a.ordering');
		return $query;
	}

	/**
	 * Function to build where array
	 * @see EShopModelList::_buildContentWhereArray()
	 */
	function _buildContentWhereArray()
	{
		$where = array();
		$where[] = 'a.published = 1';
		$where[] = 'b.language = "' . $this->language . '"';
		$where[] = 'pc.id = ' . intval($this->id);
		return $where;
	}
	
	/**
	 * Function to build order By
	 */
	function _buildContentOrderBy()
	{
		$orderby = array();
		$soft = JRequest::getVar('soft_options');
		if($soft == 'Name(A-Z)')
		{
			$orderby[] = 'b.product_name ASC';
		}
		if($soft == 'Name(Z-A)')
		{
			$orderby[] = 'b.product_name DESC';
		}
		if($soft == 'Price(Low>High)')
		{
			$orderby[] = 'a.product_price ASC';
		}	
		if($soft == 'Price(High>Low)')
		{
			$orderby[] = 'a.product_price DESC';
		}
		if($soft == 'Model(A-Z)')
		{
			$orderby[] = 'a.product_sku ASC';
		}
		if($soft == 'Model(Z-A)')
		{
			$orderby[] = 'a.product_sku DESC';
		}
		return $orderby;
	}
}