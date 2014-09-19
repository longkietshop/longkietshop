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
 * EShop Component Configuration Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelDashboard extends JModelLegacy
{

	/**
	 * Containing overview data
	 * 
	 * @var array
	 */
	var $_overviewData = null;
	
	/**
	 * Containing top sales data
	 *
	 * @var object list
	 */
	var $_topSalesData = null;
	
	/**
	 * Containing top hits data
	 *
	 * @var object list
	 */
	var $_topHitsData = null;
	
	/**
	 * Containing top rates data
	 *
	 * @var object list
	 */
	var $_topRatesData = null;
	
	/**
	 * Containing top reviews data
	 *
	 * @var object list
	 */
	var $_topReviewsData = null;

	function __construct()
	{
		parent::__construct();
	}
	
	function getOverviewData()
	{
		if (!$this->_overviewData)
		{
			$overviewData = array();
			$db = $this->getDbo();
			$currency = new EshopCurrency();
			//Total sales
			$query = $db->getQuery(true);
			$query->select('total, currency_code, currency_exchanged_value')
				->from('#__eshop_orders')
				->where('order_status_id = ' . (int) EshopHelper::getConfigValue('complete_status_id'));
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			$totalSales = 0;
			foreach ($rows as $row)
			{
				$totalSales += $row->total;
			}
			$totalSales = $currency->format($totalSales, EshopHelper::getConfigValue('default_currency_code'));
			$overviewData['totalSales'] = $totalSales;
			//Total orders
			$query->clear();
			$query->select('COUNT(*)')
				->from('#__eshop_orders')
				->where('order_status_id = ' . (int) EshopHelper::getConfigValue('complete_status_id'));
			$db->setQuery($query);
			$overviewData['totalOrders'] = $db->loadResult();
			//Total customers
			$query->clear();
			$query->select('COUNT(*)')
				->from('#__eshop_customers')
				->where('published = 1');
			$db->setQuery($query);
			$overviewData['totalCustomers'] = $db->loadResult();
			//Total categories
			$query->clear();
			$query->select('COUNT(*)')
				->from('#__eshop_categories')
				->where('published = 1');
			$db->setQuery($query);
			$overviewData['totalCategories'] = $db->loadResult();
			//Total products
			$query->clear();
			$query->select('COUNT(*)')
				->from('#__eshop_products')
				->where('published = 1');
			$db->setQuery($query);
			$overviewData['totalProducts'] = $db->loadResult();
			//Total manufacturers
			$query->clear();
			$query->select('COUNT(*)')
				->from('#__eshop_manufacturers')
				->where('published = 1');
			$db->setQuery($query);
			$overviewData['totalManufacturers'] = $db->loadResult();
			$this->_overviewData = $overviewData;
		}
		return $this->_overviewData;
	}
	
	/**
	 *
	 * Function to get top sales products
	 * @return products opject list
	 */
	function getTopSalesData()
	{
		if (!$this->_topSalesData)
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id, b.product_name, SUM(quantity) AS sales')
				->from('#__eshop_orderproducts AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.product_id = b.product_id)')
				->innerJoin('#__eshop_orders AS c ON (a.order_id = c.id)')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
				->where('c.order_status_id = ' . (int) EshopHelper::getConfigValue('complete_status_id'))
				->group('a.product_id')
				->order('sales DESC LIMIT 0, 10');
			$db->setQuery($query);
			$this->_topSalesData = $db->loadObjectList();
		}
		return $this->_topSalesData;
	}
	
	/**
	 * 
	 * Function to get top hits products
	 * @return products opject list
	 */
	function getTopHitsData()
	{
		if (!$this->_topHitsData)
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id, a.hits, b.product_name')
				->from('#__eshop_products AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
				->where('a.published = 1')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
				->order('a.hits DESC LIMIT 0, 10');
			$db->setQuery($query);
			$this->_topHitsData = $db->loadObjectList();
		}
		return $this->_topHitsData;
	}
	
	/**
	 *
	 * Function to get top rates products
	 * @return products opject list
	 */
	function getTopRatesData()
	{
		if (!$this->_topRatesData)
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id, b.product_name, AVG(rating) AS rates')
				->from('#__eshop_reviews AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.product_id = b.product_id)')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
				->group('a.product_id')
				->order('rates DESC LIMIT 0, 10');
			$db->setQuery($query);
			$this->_topRatesData = $db->loadObjectList();
		}
		return $this->_topRatesData;
	}
	
	/**
	 *
	 * Function to get top reviews products
	 * @return products opject list
	 */
	function getTopReviewsData()
	{
		if (!$this->_topReviewsData)
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id, b.product_name, COUNT(*) AS reviews')
				->from('#__eshop_reviews AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.product_id = b.product_id)')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
				->group('a.product_id')
				->order('reviews DESC LIMIT 0, 10');
			$db->setQuery($query);
			$this->_topReviewsData = $db->loadObjectList();
		}
		return $this->_topReviewsData;
	}
}