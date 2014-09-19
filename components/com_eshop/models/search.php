<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author		Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EShopModelSearch extends EShopModel
{

	public function __construct($config = array())
	{
		parent::__construct();
	}
	
	/**
	 * 
	 * Function to get products
	 * @return products object list
	 */
	function getProducts()
	{
		jimport('joomla.html.parameter');
		jimport('joomla.filesystem.file');
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$keyword = JRequest::getVar('keyword','');
		$keyword = $db->quote('%'.$keyword.'%');
		$language = JFactory::getLanguage();
		$currency = new EshopCurrency();
		$tag = $language->getTag();
		if (!$tag)
			$tag = 'en-GB';
		$language->load('com_eshop', JPATH_ROOT, $tag);
		$width = JRequest::getVar('width');
		$height	= JRequest::getVar('height');
		$categoryIds   = JRequest::getVar('category_id');
		$numberProduct = JRequest::getInt('number_product');
		$minPrice	   = JRequest::getVar('min_price');
		$maxPrice	   = JRequest::getVar('max_price');
		$manufacturers   = JRequest::getVar('manufacturers');
		
		$query->select('a.*,c.product_name,c.product_short_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productcategories AS b ON a.id = b.product_id')
			->innerJoin('#__eshop_productdetails AS c ON a.id = c.product_id')
			->where('a.published = 1')
			->where('c.language = ' . $db->quote($tag))
			->where('(c.product_name LIKE ' . $keyword . ' OR c.product_short_desc LIKE ' . $keyword . ')')
			->group('b.product_id');
		
		if ($manufacturers != '')
		{
			$query->where('a.manufacturer_id IN ('.$manufacturers.')');
		}
		
		if($minPrice != '')
		{
			if ($currency->getCurrencyCode() != EshopHelper::getConfigValue('default_currency_code'))
			{
				$minPrice = $currency->convert($minPrice, $currency->getCurrencyCode(), EshopHelper::getConfigValue('default_currency_code'));	
			}
			$query->where('a.product_price >= ' . $minPrice);
		}
		if($maxPrice != '')
		{
			if ($currency->getCurrencyCode() != EshopHelper::getConfigValue('default_currency_code'))
			{
				$maxPrice = $currency->convert($maxPrice, $currency->getCurrencyCode(), EshopHelper::getConfigValue('default_currency_code'));	
			}
			$query->where('a.product_price <= ' . $maxPrice);
		}
		//check limit product
		if($numberProduct)
		{
			$query->order('a.id DESC LIMIT 0, ' . $numberProduct );
		}
		if ($categoryIds != '')
		{
			$query->where('b.category_id IN (' . $categoryIds . ')');
		}
		
		$db->setQuery($query);
		$products = $db->loadObjectList();
		if(!count($products)) return array();
		
		foreach ($products as $product)
		{
			// Image
			$imageSizeFunction = EshopHelper::getConfigValue('image_size_function', 'resizeImage');
			if ($product->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $product->product_image))
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($product->product_image, JPATH_ROOT . '/media/com_eshop/products/', $width, $height));
			}
			else
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', $width, $height));
			}
			$image = JURI::base() . 'media/com_eshop/products/resized/' . $image;
			$product->image = $image;
		}
		return $products;
	}
}