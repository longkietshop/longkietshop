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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerExports extends JControllerLegacy
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	
	}

	/**
	 * Save the category
	 *
	 */
	function process()
	{
		$exportType = JRequest::getVar('export_type', 'products');
		switch ($exportType)
		{
			case 'products':
				$this->_exportProducts();
				break;
			case 'categories':
				$this->_exportCategories();
				break;
			case 'orders':
				$this->_exportOrders();
				break;	
		}
	}
	
	/**
	 * 
	 * Function to export products
	 */
	function _exportProducts()
	{
		$fieldDelimiter = JRequest::getVar('field_delimiter', ',');
		$imageSeparator = JRequest::getVar('image_separator', ';');
		$language = JRequest::getVar('langauge', 'en-GB');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_tag, b.meta_key, b.meta_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('b.language = "' . $language . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			$resultsArr = array();
			$resultsArr[] = 'language';
			$resultsArr[] = 'product_sku';
			$resultsArr[] = 'product_name';
			$resultsArr[] = 'product_alias';
			$resultsArr[] = 'product_desc';
			$resultsArr[] = 'product_short_desc';
			$resultsArr[] = 'product_tag';
			$resultsArr[] = 'product_meta_key';
			$resultsArr[] = 'product_meta_desc';
			$resultsArr[] = 'product_weight';
			$resultsArr[] = 'product_weight_id';
			$resultsArr[] = 'product_length';
			$resultsArr[] = 'product_width';
			$resultsArr[] = 'product_height';
			$resultsArr[] = 'product_length_id';
			$resultsArr[] = 'product_price';
			$resultsArr[] = 'product_taxclass_id';
			$resultsArr[] = 'product_quantity';
			$resultsArr[] = 'product_shipping';
			$resultsArr[] = 'product_shipping_cost';
			$resultsArr[] = 'product_image';
			$resultsArr[] = 'product_available_date';
			$resultsArr[] = 'product_featured';
			$resultsArr[] = 'product_published';
			$resultsArr[] = 'product_hits';
			$resultsArr[] = 'product_additional_images';
			$resultsArr[] = 'manufacturer_name';
			$resultsArr[] = 'category_name';
			$csvOutput[] = $resultsArr;
			
			foreach ($rows as $row)
			{
				$resultsArr = array();
				$resultsArr[] = $language;
				$resultsArr[] = $row->product_sku;
				$resultsArr[] = $row->product_name;
				$resultsArr[] = $row->product_alias;
				$resultsArr[] = $row->product_desc;
				$resultsArr[] = $row->product_short_desc;
				$resultsArr[] = $row->product_tag;
				$resultsArr[] = $row->meta_key;
				$resultsArr[] = $row->meta_desc;
				$resultsArr[] = $row->product_weight;
				$resultsArr[] = $row->product_weight_id;
				$resultsArr[] = $row->product_length;
				$resultsArr[] = $row->product_width;
				$resultsArr[] = $row->product_height;
				$resultsArr[] = $row->product_length_id;
				$resultsArr[] = $row->product_price;
				$resultsArr[] = $row->product_taxclass_id;
				$resultsArr[] = $row->product_quantity;
				$resultsArr[] = $row->product_shipping;
				$resultsArr[] = $row->product_shipping_cost;
				$resultsArr[] = $row->product_image;
				$resultsArr[] = $row->product_available_date;
				$resultsArr[] = $row->product_featured;
				$resultsArr[] = $row->published;
				$resultsArr[] = $row->hits;
				
				//Get additional images for product
				$query->clear();
				$query->select('image')
					->from('#__eshop_productimages')
					->where('product_id = ' . $row->id);
				$db->setQuery($query);
				$images = $db->loadColumn();
				if (count($images))
					$resultsArr[] = implode($imageSeparator, $images);
				else
					$resultsArr[] = '';
				
				//Get product manufacturer
				$manufacturer = EshopHelper::getProductManufacturer($row->id, $language);
				if (is_object($manufacturer))
					$resultsArr[] = $manufacturer->manufacturer_name;
				else 
					$resultsArr[] = '';
				
				//Get product categories
				$productCategories = EshopHelper::getProductCategories($row->id, $language);
				$categories = array();
				if (count($productCategories))
				{
					foreach ($productCategories as $category)
					{
						$categories[] = implode('/', EshopHelper::getCategoryNamePath($category->id));
					}
					$resultsArr[] = implode(';', $categories);
				}
				else
				{
					$resultsArr[] = '';
				}
				
				$csvOutput[] = $resultsArr;
			}
			
			$filename = 'products_'.date('YmdHis').'.csv';
			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect('index.php?option=com_eshop&view=exports', JText::_('ESHOP_NO_DATA_TO_EXPORT'));
		}
	}
	
	/**
	 *
	 * Function to export categories
	 */
	function _exportCategories()
	{
		$fieldDelimiter = JRequest::getVar('field_delimiter', ',');
		$language = JRequest::getVar('langauge', 'en-GB');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.category_name, b.category_alias, b.category_desc, b.meta_key, b.meta_desc')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('b.language = "' . $language . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			$resultsArr = array();
			$resultsArr[] = 'language';
			$resultsArr[] = 'category_name';
			$resultsArr[] = 'category_alias';
			$resultsArr[] = 'category_desc';
			$resultsArr[] = 'category_image';
			$resultsArr[] = 'products_per_page';
			$resultsArr[] = 'products_per_row';
			$resultsArr[] = 'category_published';
			$resultsArr[] = 'category_meta_key';
			$resultsArr[] = 'category_meta_desc';
			$csvOutput[] = $resultsArr;
				
			foreach ($rows as $row)
			{
				$resultsArr = array();
				$resultsArr[] = $language;
				$resultsArr[] = $row->category_name;
				$resultsArr[] = $row->category_alias;
				$resultsArr[] = $row->category_desc;
				$resultsArr[] = $row->category_image;
				$resultsArr[] = $row->products_per_page;
				$resultsArr[] = $row->products_per_row;
				$resultsArr[] = $row->published;
				$resultsArr[] = $row->meta_key;
				$resultsArr[] = $row->meta_desc;
				$csvOutput[] = $resultsArr;
			}
				
			$filename = 'categories_'.date('YmdHis').'.csv';
			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
		}
		else
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect('index.php?option=com_eshop&view=exports', JText::_('ESHOP_NO_DATA_TO_EXPORT'));
		}
	}
	
	/**
	 * 
	 * Function to export orders
	 */
	function _exportOrders()
	{
		$currency = new EshopCurrency();
		$dateStart = JRequest::getVar('date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01')));
		$dateEnd = JRequest::getVar('date_end', date('Y-m-d'));
		$groupBy = JRequest::getVar('group_by', 'week');
		$orderStatusId = JRequest::getVar('order_status_id', 0);
		$fieldDelimiter = ',';
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_orders');
		if ($orderStatusId)
			$query->where('order_status_id = ' . (int)$orderStatusId);
		if (!empty($dateStart))
			$query->where('created_date >= "' . $dateStart . '"');
		if (!empty($dateEnd))
			$query->where('created_date <= "' . $dateEnd . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$csvOutput = array();
		if (count($rows))
		{
			$resultsArr = array();
			$resultsArr[] = 'customer_firstname';
			$resultsArr[] = 'customer_lastname';
			$resultsArr[] = 'customer_email';
			$resultsArr[] = 'customer_telephone';
			$resultsArr[] = 'customer_fax';
			$resultsArr[] = 'payment_firstname';
			$resultsArr[] = 'payment_lastname';
			$resultsArr[] = 'payment_company';
			$resultsArr[] = 'payment_company_id';
			$resultsArr[] = 'payment_address_1';
			$resultsArr[] = 'payment_address_2';
			$resultsArr[] = 'payment_city';
			$resultsArr[] = 'payment_postcode';
			$resultsArr[] = 'payment_country_name';
			$resultsArr[] = 'payment_zone_name';
			$resultsArr[] = 'payment_method';
			$resultsArr[] = 'shipping_firstname';
			$resultsArr[] = 'shipping_lastname';
			$resultsArr[] = 'shipping_company';
			$resultsArr[] = 'shipping_company_id';
			$resultsArr[] = 'shipping_address_1';
			$resultsArr[] = 'shipping_address_2';
			$resultsArr[] = 'shipping_city';
			$resultsArr[] = 'shipping_postcode';
			$resultsArr[] = 'shipping_country_name';
			$resultsArr[] = 'shipping_zone_name';
			$resultsArr[] = 'shipping_method';
			$resultsArr[] = 'total';
			$resultsArr[] = 'comment';
			$resultsArr[] = 'order_status';
			
			$csvOutput[] = $resultsArr;
			
			$orderId = '';
			foreach ($rows as $row)
			{
				$orderId .= '_' . $row->id;
				$resultsArr = array();
				$resultsArr[] = $row->firstname;
				$resultsArr[] = $row->lastname;
				$resultsArr[] = $row->email;
				$resultsArr[] = $row->telephone;
				$resultsArr[] = $row->fax;
				$resultsArr[] = $row->payment_firstname;
				$resultsArr[] = $row->payment_lastname;
				$resultsArr[] = $row->payment_company;
				$resultsArr[] = $row->payment_company_id;
				$resultsArr[] = $row->payment_address_1;
				$resultsArr[] = $row->payment_address_2;
				$resultsArr[] = $row->payment_city;
				$resultsArr[] = $row->payment_postcode;
				$resultsArr[] = $row->payment_country_name;
				$resultsArr[] = $row->payment_zone_name;
				$resultsArr[] = $row->payment_method_title;
				$resultsArr[] = $row->shipping_firstname;
				$resultsArr[] = $row->shipping_lastname;
				$resultsArr[] = $row->shipping_company;
				$resultsArr[] = $row->shipping_company_id;
				$resultsArr[] = $row->shipping_address_1;
				$resultsArr[] = $row->shipping_address_2;
				$resultsArr[] = $row->shipping_city;
				$resultsArr[] = $row->shipping_postcode;
				$resultsArr[] = $row->shipping_country_name;
				$resultsArr[] = $row->shipping_zone_name;
				$resultsArr[] = $row->shipping_method_title;
				$resultsArr[] = $currency->format($row->total, $row->currency_code, $row->currency_exchanged_value);
				$resultsArr[] = $row->comment;
				$resultsArr[] = EshopHelper::getOrderStatusName($orderStatusId, JComponentHelper::getParams('com_languages')->get('site', 'en-GB'));
				
				$csvOutput[] = $resultsArr;
			}
			
			$filename = 'orders' . $orderId . '.csv';
			$fp = fopen(JPATH_ROOT.'/media/com_eshop/files/'.$filename, 'w');
			foreach ($csvOutput as $output)
			{
				fputcsv($fp, $output, $fieldDelimiter);
			}
			fclose($fp);
			EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, true);
		}
		else 
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect('index.php?option=com_eshop&view=reports&layout=orders&date_start=' . $dateStart . '&date_end=' . $dateEnd . '&group_by=' . $groupBy . '&order_status_id=' . $orderStatusId, JText::_('ESHOP_NO_DATA_TO_EXPORT'));
		}
	}
	
	/**
	 * Cancel the exports
	 *
	 */
	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=dashboard');
	}
}