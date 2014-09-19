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

class EShopModelFrontpage extends EShopModelList
{
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
		$this->language = JFactory::getLanguage()->getTag();
	}
	
	/**
	 * 
	 * Function to get categories
	 * @return categories object list
	 */
	function getCategories()
	{
		$app	= JFactory::getApplication('site');
		$params = $app->getParams();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.category_name, b.category_alias, b.category_desc, b.meta_key, b.meta_desc')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('a.published = 1')
			->where('b.language = "' . $this->language . '"')
			->order('a.ordering LIMIT 0, ' . $params->get('num_categories', 9));
		$db->setQuery($query);
		return $db->loadObjectList();	
	}
	
	/**
	 * 
	 * Function to get products
	 * @return products object list
	 */
	function getProducts()
	{
		$app	= JFactory::getApplication('site');
		$params = $app->getParams();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_tag, b.meta_key, b.meta_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.language = "' . $this->language . '"')
			->order('a.ordering LIMIT 0, ' . $params->get('num_products', 9));
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}