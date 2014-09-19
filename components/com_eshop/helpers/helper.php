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
//defined('_JEXEC') or die();

class EshopHelper
{
	
	/**
	 *
	 * Function to get configuration object
	 */
	public static function getConfig()
	{
		static $config;
		if (is_null($config))
		{
			$config = new stdClass();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('config_key, config_value')
				->from('#__eshop_configs');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			foreach ($rows as $row)
			{
				$config->{$row->config_key} = $row->config_value;
			}
		}
		return $config;
	}
	
	/**
	 *
	 * Function to get value of configuration variable
	 * @param string $configKey
	 * @param string $default
	 * @return string
	 */
	public static function getConfigValue($configKey, $default = null)
	{
		static $configValues;
		if (!isset($configValues["$configKey"]))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('config_value')
				->from('#__eshop_configs')
				->where('config_key = "' . $configKey . '"');
			$db->setQuery($query);
			$configValues[$configKey] = $db->loadResult();
		}
		return $configValues[$configKey] ? $configValues[$configKey] : $default;
	}
	
	/**
	 * 
	 * Function to update currencies
	 * @param boolean $force
	 * @param int $timePeriod
	 * @param string $timeUnit
	 */
	public static function updateCurrencies($force = false, $timePeriod = 1, $timeUnit = 'day')
	{
		if (extension_loaded('curl'))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			if ($force)
			{
				$query->select('*')
					->from('#__eshop_currencies')
					->where('currency_code != "' . $db->escape(EshopHelper::getConfigValue('default_currency_code')) . '"');
			}
			else
			{
				$query->select('*')
					->from('#__eshop_currencies')
					->where('currency_code != "' . $db->escape(EshopHelper::getConfigValue('default_currency_code')) . '"')
					->where('modified_date <= "' . $db->escape(date('Y-m-d H:i:s', strtotime('-' . (int)$timePeriod .' ' . $timeUnit))) . '"');
			}
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				$data = array();
				foreach ($rows as $row)
				{
					$data[] = EshopHelper::getConfigValue('default_currency_code') . $row->currency_code . '=X';
				}
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, 'http://download.finance.yahoo.com/d/quotes.csv?s=' . implode(',', $data) . '&f=sl1&e=.csv');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec($curl);
				curl_close($curl);
				$lines = explode("\n", trim($content));
				foreach ($lines as $line)
				{
					$currency = substr($line, 4, 3);
					$value = substr($line, 11, 6);
					if ((float)$value)
					{
						$query->clear();
						$query->update('#__eshop_currencies')
						->set('exchanged_value = ' . (float)$value)
						->set('modified_date = "' . $db->escape(date('Y-m-d H:i:s')) . '"')
						->where('currency_code = "' . $db->escape($currency) . '"');
						$db->setQuery($query);
						$db->query();
					}
				}
			}
			$query->clear();
			$query->update('#__eshop_currencies')
				->set('exchanged_value = 1.00000')
				->set('modified_date = "' . $db->escape(date('Y-m-d H:i:s')) . '"')
				->where('currency_code = "' . EshopHelper::getConfigValue('default_currency_code') . '"');
			$db->setQuery($query);
			$db->query();
		}
	}
	
	/**
	 * 
	 * Function to update hits for category/manufacturer/product
	 * @param int $id
	 * @param string $element
	 */
	public static function updateHits($id, $element)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__eshop_' . $element)
			->set('hits = hits + 1')
			->where('id = ' . intval($id));
		$db->setQuery($query);
		$db->query();
	} 
	
	/**
	 * 
	 * Function to get name of a specific stock status
	 * @param int $stockStatusId
	 * @param string $langCode
	 * @return string
	 */
	public static function getStockStatusName($stockStatusId, $langCode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('stockstatus_name')
			->from('#__eshop_stockstatusdetails')
			->where('stockstatus_id = ' . intval($stockStatusId))
			->where('language = "' . $langCode . '"');
		$db->setQuery($query);
		return $db->loadResult();		
	}
	
	/**
	 *
	 * Function to get name of a specific order status
	 * @param int $orderStatusId
	 * @param string $langCode
	 * @return string
	 */
	public static function getOrderStatusName($orderStatusId, $langCode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('orderstatus_name')
			->from('#__eshop_orderstatusdetails')
			->where('orderstatus_id = ' . intval($orderStatusId))
			->where('language = "' . $langCode . '"');
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 *
	 * Function to get unit of a specific length
	 * @param int $lengthId
	 * @param string $langCode
	 * @return string
	 */
	public static function getLengthUnit($lengthId, $langCode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('length_unit')
			->from('#__eshop_lengthdetails')
			->where('length_id = ' . intval($lengthId))
			->where('language = "' . $langCode . '"');
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 *
	 * Function to get unit of a specific weight
	 * @param int $weightId
	 * @param string $langCode
	 * @return string
	 */
	public static function getWeightUnit($weightId, $langCode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('weight_unit')
			->from('#__eshop_weightdetails')
			->where('weight_id = ' . intval($weightId))
			->where('language = "' . $langCode . '"');
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * 
	 * Function to get payment title
	 * @param string $paymentName
	 * @return string
	 */
	public static function getPaymentTitle($paymentName)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title')
			->from('#__eshop_payments')
			->where('name = "' . $paymentName . '"');
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 *
	 * Function to get shipping title
	 * @param string $shippingName
	 * @return string
	 */
	public static function getShippingTitle($shippingName)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title')
			->from('#__eshop_shippings')
			->where('name = "' . $shippingName . '"');
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * 
	 * Function to get all available languages
	 * @return languages object list
	 */
	public static function getLanguages()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('lang_id, lang_code, title')
			->from('#__languages')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);
		$languages = $db->loadObjectList();
		return $languages;
	}
	
	/**
	 *
	 * Function to get flags for languages
	 */
	public static function getLanguageData()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$languageData = array();
		$query->select('image, lang_code, title')
			->from('#__languages')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i = 0; $n = count($rows), $i < $n; $i++)
		{
			$languageData['flag'][$rows[$i]->lang_code] = $rows[$i]->image . '.png';
			$languageData['title'][$rows[$i]->lang_code] = $rows[$i]->title;
		}
		return $languageData;
	}
	
	/**
	 *
	 * Function to get attribute groups
	 * @return attribute groups object list
	 */
	public static function getAttributeGroups($langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, b.attributegroup_name')
			->from('#__eshop_attributegroups AS a')
			->innerJoin('#__eshop_attributegroupdetails AS b ON (a.id = b.attributegroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . $langCode . '"')
			->order('a.ordering');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 *
	 * Function to get attributes for a specific products
	 * @param int $productId
	 * @param int $attributeGroupId
	 * @return attribute object list
	 */
	public static function getAttributes($productId, $attributeGroupId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('ad.attribute_name, pad.value')
			->from('#__eshop_attributes AS a')
			->innerJoin('#__eshop_attributedetails AS ad ON (a.id = ad.attribute_id)')
			->innerJoin('#__eshop_productattributes AS pa ON (a.id = pa.attribute_id)')
			->innerJoin('#__eshop_productattributedetails AS pad ON (pa.id = pad.productattribute_id)')
			->where('a.attributegroup_id = ' . intval($attributeGroupId))
			->where('a.published = 1')
			->where('pa.published = 1')
			->where('pa.product_id = ' . intval($productId))
			->where('ad.language = "' . $langCode . '"')
			->where('pad.language = "' . $langCode . '"')
			->order('a.ordering');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 *
	 * Function to get attribute group for a specific attribute
	 * @param unknown $attributeId
	 * @return Ambigous <mixed, NULL>
	 */
	public static function getAttributeAttributeGroup($attributeId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.attributegroup_id, b.attributegroup_name')
			->from('#__eshop_attributes AS a')
			->innerJoin('#__eshop_attributegroupdetails AS b ON (a.attributegroup_id = b.attributegroup_id)')
			->where('a.id = ' . intval($attributeId))
			->where('b.language = "' . $langCode . '"');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 * 
	 * Function to get Categories
	 * @param int $categoryId
	 * @return categories object list
	 */
	public static function getCategories($categoryId = 0, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.category_parent_id, b.category_name')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('a.category_parent_id = ' . intval($categoryId))
			->where('a.published = 1')
			->where('b.language = "' . $langCode . '"')
			->order('a.ordering');
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	
	/**
	 * 
	 * Function to get number products for a specific category
	 * @param int $categoryId
	 * @return int
	 */
	public static function getNumCategoryProducts($categoryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productcategories AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->where('b.category_id = ' . intval($categoryId));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 *
	 * Function to get list of parent categories
	 * @param int $categoryId
	 * @return array of object
	 */
	public static function getParentCategories($categoryId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$parentCategories = array();
		while (true)
		{
			$query->clear();
			$query->select('a.id, a.category_parent_id, b.category_name')
				->from('#__eshop_categories AS a')
				->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
				->where('a.id = ' . intval($categoryId))
				->where('a.published = 1')
				->where('b.language = "' . $langCode . '"');
			$db->setQuery($query);
			$row = $db->loadObject();
			if ($row)
			{
				$query->clear();
				$query->select('COUNT(*)')
					->from('#__eshop_categories')
					->where('category_parent_id = ' . intval($row->id));
				$db->setQuery($query);
				$total = $db->loadResult();
				$row->total_children = $total;
				$parentCategories[] = $row;
				$categoryId = $row->category_parent_id;
			}
			else
			{
				break;
			}
		}
		return $parentCategories;
	}
	
	/**
	 *
	 * Function to get values for a specific option
	 * @param int $optionId
	 */
	public static function getOptionValues($optionId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$languages = EshopHelper::getLanguages();
		if (JLanguageMultilang::isEnabled() && count($languages) > 1)
		{
			$query->select('*')
				->from('#__eshop_optionvalues')
				->where('option_id = ' . intval($optionId))
				->order('ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				for ($i = 0; $n = count($rows), $i < $n; $i++)
				{
					$query->clear();
					$query->select('*')
						->from('#__eshop_optionvaluedetails')
						->where('option_id = ' . intval($optionId))
						->where('optionvalue_id = ' . intval($rows[$i]->id));
						$db->setQuery($query);
						$detailsRows = $db->loadObjectList('language');
						if (count($detailsRows))
						{
							foreach ($detailsRows as $language => $detailsRow)
							{
								$rows[$i]->{'optionvaluedetails_id_' . $language} = $detailsRow->id;
								$rows[$i]->{'value_' . $language} = $detailsRow->value;
							}
						}
					}
			}
		}
		else
		{
			$query->select('ov.*, ovd.id AS optionvaluedetails_id, ovd.value, ovd.language')
				->from('#__eshop_optionvalues AS ov')
				->innerJoin('#__eshop_optionvaluedetails AS ovd ON (ov.id = ovd.optionvalue_id)')
				->where('ov.option_id = ' . intval($optionId))
				->where('ovd.language = "' . $langCode . '"')
				->order('ov.ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}
		return $rows;
	}
	
	/**
	 *
	 * Function to get information for a specific product
	 * @param int $productId
	 */
	public static function getProduct($productId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_tag, b.meta_key, b.meta_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('b.language = "' . $langCode . '"')
			->where('a.id = ' . intval($productId));
		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 * 
	 * Function to get categories for a specific product
	 * @param int $productId        	
	 */
	public static function getProductCategories($productId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('c.id, cd.category_name')
			->from('#__eshop_categories AS c')
			->innerJoin('#__eshop_categorydetails AS cd ON (c.id = cd.category_id)')
			->innerJoin('#__eshop_productcategories AS pc ON (c.id = pc.category_id)')
			->where('pc.product_id = ' . intval($productId))
			->where('cd.language = "' . $langCode . '"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	/**
	 * 
	 * Function to get category id for a specific product
	 * @param int $productId
	 * @return int
	 */
	public static function getProductCategory($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.category_id')
			->from('#__eshop_productcategories AS a')
			->innerJoin('#__eshop_categories AS b ON (a.category_id = b.id)')
			->where('a.product_id = ' . intval($productId))
			->where('b.published = 1');
		$db->setQuery($query);
		return $db->loadResult();
	}

	/**
	 *
	 * Function to get manufacturer for a specific product
	 * @param int $productId
	 * @return manufacturer object
	 */
	public static function getProductManufacturer($productId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('m.id, m.manufacturer_email, md.manufacturer_name')
			->from('#__eshop_products AS p')
			->innerJoin('#__eshop_manufacturers AS m ON (p.manufacturer_id = m.id)')
			->innerJoin('#__eshop_manufacturerdetails AS md ON (m.id = md.manufacturer_id)')
			->where('p.id = ' . intval($productId))
			->where('md.language = "' . $langCode . '"');
		$db->setQuery($query);
		$row = $db->loadObject();
		return $row;
	}

	/**
	 *
	 * Function to get related products for a specific product
	 * @param int $productId
	 */
	public static function getProductRelations($productId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('p.*, pd.product_name, pd.product_alias, pd.product_desc, pd.product_short_desc, pd.product_tag, pd.meta_key, pd.meta_desc')
			->from('#__eshop_products AS p')
			->innerJoin('#__eshop_productdetails AS pd ON (p.id = pd.product_id)')
			->innerJoin('#__eshop_productrelations AS pr ON (p.id = pr.related_product_id)')
			->where('pr.product_id = ' . intval($productId))
			->where('pd.language = "' . $langCode . '"')
			->order('p.ordering');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	/**
	 * 
	 * Function to reviews for a specific product
	 * @param int $productId
	 * @return reviews object list
	 */
	public static function getProductReviews($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_reviews')
			->where('product_id = ' . intval($productId))
			->where('published = 1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	/**
	 * 
	 * Function to get average rating for a specific product 
	 * @param int $productId
	 * @return average rating
	 */
	public static function getProductRating($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('AVG(rating) as rating')
			->from('#__eshop_reviews')
			->where('product_id = ' . intval($productId))
			->where('published = 1');
		$db->setQuery($query);
		$rating = $db->loadResult();
		return $rating;
	}

	/**
	 *
	 * Function to get attributes for a specific product
	 * @param int $productId
	 */
	public static function getProductAttributes($productId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$languages = EshopHelper::getLanguages();
		if (JLanguageMultilang::isEnabled() && count($languages) > 1)
		{
			$query->select('a.id, pa.id AS productattribute_id, pa.published')
				->from('#__eshop_attributes AS a')
				->innerJoin('#__eshop_productattributes AS pa ON (a.id = pa.attribute_id)')
				->where('pa.product_id = ' . intval($productId))
				->order('a.ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				for ($i = 0; $n = count($rows), $i < $n; $i++)
				{
					$query->clear();
					$query->select('*')
						->from('#__eshop_productattributedetails')
						->where('productattribute_id = ' . intval($rows[$i]->productattribute_id));
					$db->setQuery($query);
					$detailsRows = $db->loadObjectList('language');
					if (count($detailsRows))
					{
						foreach ($detailsRows as $language => $detailsRow)
						{
							$rows[$i]->{'productattributedetails_id_' . $language} = $detailsRow->id;
							$rows[$i]->{'value_' . $language} = $detailsRow->value;
						}
					}
				}
			}
		}
		else
		{
			$query->select('a.id, pa.id AS productattribute_id, pa.published, pad.id AS productattributedetails_id ,pad.value')
				->from('#__eshop_attributes AS a')
				->innerJoin('#__eshop_productattributes AS pa ON (a.id = pa.attribute_id)')
				->innerJoin('#__eshop_productattributedetails AS pad ON (pa.id = pad.productattribute_id)')
				->where('pa.product_id = ' . intval($productId))
				->where('pad.language = "' . $langCode . '"')
				->order('a.ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		}
		return $rows;
	}

	/**
	 *
	 * Function to get options for a specific product
	 * @param int $productId
	 */
	public static function getProductOptions($productId, $langCode = '')
	{
		if ($langCode == '')
		{
			$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('o.id, o.option_type, o.option_image, od.option_name, od.option_desc, po.required, po.id AS product_option_id')
			->from('#__eshop_options AS o')
			->innerJoin('#__eshop_optiondetails AS od ON (o.id = od.option_id)')
			->innerJoin('#__eshop_productoptions AS po ON (o.id = po.option_id)')
			->where('po.product_id = ' . intval($productId))
			->where('od.language = "' . $langCode . '"')
			->order('o.ordering');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	/**
	 * 
	 * Function to get option values
	 * @param int $productId
	 * @param int $optionId
	 * @return option value object list
	 */
	public static function getProductOptionValues($productId, $optionId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pov.*')
			->from('#__eshop_productoptionvalues AS pov')
			->where('product_id = ' . intval($productId))
			->where('option_id = ' . intval($optionId))
			->order('id');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	/**
	 *
	 * Function to get images for a specific product
	 * @param int $productId
	 */
	public static function getProductImages($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pi.*')
			->from('#__eshop_productimages AS pi')
			->where('product_id = ' . intval($productId))
			->order('pi.ordering');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}
	
	/**
	 *  
	 * Function to resize image 
	 * @param string $filename
	 * @param string $imagePath
	 * @param int $width
	 * @param int $height
	 * @return void|string
	 */
	public static function resizeImage($filename, $imagePath, $width, $height)
	{
		if (!file_exists($imagePath . $filename) || !is_file($imagePath . $filename))
		{
			return;
		}
		$info = pathinfo($filename);
		$extension = $info['extension'];
		$oldImage = $filename;
		$newImage = substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		if (!file_exists($imagePath . '/resized/' . $newImage) || (filemtime($imagePath . $oldImage) > filemtime($imagePath . '/resized/' . $newImage))) 
		{
			list($width_orig, $height_orig) = getimagesize($imagePath . $oldImage);
			if ($width_orig != $width || $height_orig != $height)
			{
				$image = new EshopImage($imagePath . $oldImage);
				$image->resize($width, $height);
				$image->save($imagePath . '/resized/' . $newImage);
			}
			else
			{
				copy($imagePath . $oldImage, $imagePath . '/resized/' . $newImage);
			}
		}
		return $newImage;
	}
	
	/**
	 *
	 * Function to cropsize image
	 * @param string $filename
	 * @param string $imagePath
	 * @param int $width
	 * @param int $height
	 * @return void|string
	 */
	public static function cropsizeImage($filename, $imagePath, $width, $height)
	{
		if (!file_exists($imagePath . $filename) || !is_file($imagePath . $filename))
		{
			return;
		}
		$info = pathinfo($filename);
		$extension = $info['extension'];
		$oldImage = $filename;
		$newImage = substr($filename, 0, strrpos($filename, '.')) . '-cr-' . $width . 'x' . $height . '.' . $extension;
		if (!file_exists($imagePath . '/resized/' . $newImage) || (filemtime($imagePath . $oldImage) > filemtime($imagePath . '/resized/' . $newImage)))
		{
			list($width_orig, $height_orig) = getimagesize($imagePath . $oldImage);
			if ($width_orig != $width || $height_orig != $height)
			{
				$image = new EshopImage($imagePath . $oldImage);
				$image->cropsize($width, $height);
				$image->save($imagePath . '/resized/' . $newImage);
			}
			else
			{
				copy($imagePath . $oldImage, $imagePath . '/resized/' . $newImage);
			}
		}
		return $newImage;
	}
	
	/**
	 *
	 * Function to max size image
	 * @param string $filename
	 * @param string $imagePath
	 * @param int $width
	 * @param int $height
	 * @return void|string
	 */
	public static function maxsizeImage($filename, $imagePath, $width, $height)
	{
		$maxsize = ($width > $height) ? $width : $height;
		if (!file_exists($imagePath . $filename) || !is_file($imagePath . $filename))
		{
			return;
		}
		$info = pathinfo($filename);
		$extension = $info['extension'];
		$oldImage = $filename;
		$newImage = substr($filename, 0, strrpos($filename, '.')) . '-max-' . $width . 'x' . $height . '.' . $extension;
		if (!file_exists($imagePath . '/resized/' . $newImage) || (filemtime($imagePath . $oldImage) > filemtime($imagePath . '/resized/' . $newImage)))
		{
			$image = new EshopImage($imagePath . $oldImage);
			$image->maxsize($maxsize);
			$image->save($imagePath . '/resized/' . $newImage);
		}
		return $newImage;
	}

	/**
	 *
	 * Function to get discount for a specific product
	 * @param int $productId        	
	 */
	public static function getProductDiscounts($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('pd.*')
			->from('#__eshop_productdiscounts AS pd')
			->innerJoin('#__eshop_customergroups AS cg ON (pd.customergroup_id = cg.id)')
			->where('pd.product_id = ' . intval($productId))
			->order('pd.priority');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}

	/**
	 *
	 * Function to get special for a specific product
	 * @param int $productId        	
	 */
	public static function getProductSpecials($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('ps.*')
			->from('#__eshop_productspecials AS ps')
			->innerJoin('#__eshop_customergroups AS cg ON (ps.customergroup_id = cg.id)')
			->where('ps.product_id = ' . intval($productId))
			->order('ps.priority');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		return $rows;
	}	

	/**
	 * 
	 * Function to get discount price for a specific product
	 * @param int $productId
	 * @return price
	 */
	public static function getDiscountPrice($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('price')
			->from('#__eshop_productdiscounts')
			->where('product_id = ' . intval($productId))
			->where('published = 1')
			->where('date_start <= "' . date('Y-m-d H:i:s') . '"')
			->where('date_end >= "' . date('Y-m-d H:i:s') . '"')
			->where('quantity = 1')
			->order('priority');
		$db->setQuery($query);
		$discountPrice = $db->loadResult();
		return $discountPrice;
	}
	
	/**
	 *
	 * Function to get discount prices for a specific product
	 * @param int $productId
	 * @return prices
	 */
	public static function getDiscountPrices($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('quantity, price')
			->from('#__eshop_productdiscounts')
			->where('product_id = ' . intval($productId))
			->where('published = 1')
			->where('date_start <= "' . date('Y-m-d H:i:s') . '"')
			->where('date_end >= "' . date('Y-m-d H:i:s') . '"')
			->where('quantity > 1')
			->order('priority');
		$db->setQuery($query);
		$discountPrices = $db->loadObjectList();
		return $discountPrices;
	}
	
	/**
	 * 
	 * Function to get special price
	 * @param int $productId
	 * @return price
	 */
	public static function getSpecialPrice($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('price')
			->from('#__eshop_productspecials')
			->where('product_id = ' . intval($productId))
			->where('published = 1')
			->where('date_start <= "' . date('Y-m-d H:i:s') . '"')
			->where('date_end >= "' . date('Y-m-d H:i:s') . '"')
			->order('priority');
		$db->setQuery($query, 0, 1);
		$specialPrice = $db->loadResult();
		if (!$specialPrice)
			$specialPrice = 0;
		return $specialPrice;
	}

	/**
	 * 
	 * Function to get product price array
	 * @param int $productId
	 * @param float $productPrice
	 * @return array of price
	 */
	public static function getProductPriceArray($productId, $productPrice)
	{
		$specialPrice = EshopHelper::getSpecialPrice($productId);
		$discountPrice = EshopHelper::getDiscountPrice($productId);
		if ($specialPrice)
		{
			$salePrice = $specialPrice;
			if ($discountPrice)
			{
				$basePrice = $discountPrice;
			}
			else
			{
				$basePrice = $productPrice;
			}
		}
		else
		{
			$basePrice = $productPrice;
			$salePrice = $discountPrice;
		}
		$productPriceArray = array("basePrice" => $basePrice, "salePrice" => $salePrice);
		return $productPriceArray;
	}

	/**
	 *
	 * Function to get currency format for a specific number
	 * @param float $number        	
	 * @param int $currencyId        	
	 */
	public static function getCurrencyFormat($number, $currencyId = '')
	{
		if (!$currencyId)
		{
			// Use default currency
			$currencyId = 4;
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_currencies')
			->where('id = ' . intval($currencyId));
		$db->setQuery($query);
		$row = $db->loadObject();
		$currencyFormat = '';
		$sign = '';
		if ($number < 0)
		{
			$sign = '-';
			$number = abs($number);
		}
		if (is_object($row))
		{
			$currencyFormat = $sign . $row->left_symbol . number_format($number, $row->decimal_place, $row->decimal_symbol, $row->thousands_separator) .
				 $row->right_symbol;
		}
		return $currencyFormat;
	}
	
	/**
	 * 
	 * Function to round out a number
	 * @param float $number
	 * @param int $places
	 * @return float
	 */
	public static function roundOut($number, $places = 0)
	{
		if ($places < 0)
			$places = 0;
		$mult = pow(10, $places);
		return ($number >= 0 ? ceil($number * $mult):floor($number * $mult)) / $mult;
	}
	
	/**
	 * 
	 * Function to round up a number 
	 * @param float $number
	 * @param int $places
	 * @return float
	 */
	public static function roundUp($number, $places=0)
	{
		if ($places < 0)
			$places = 0;
		$mult = pow(10, $places);
		return ceil($number * $mult) / $mult;
	}
	
	/**
	 *
	 * Function to get information for a specific address
	 * @param int $addressId
	 */
	public static function getAddress($addressId)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_addresses AS a')
			->leftJoin('#__eshop_zones AS z ON (a.zone_id = z.id)')
			->leftJoin('#__eshop_countries AS c ON (a.country_id = c.id)')
			->where('a.id = ' . intval($addressId))
			->where('a.customer_id = ' . intval($user->get('id')));
		$db->setQuery($query);
		return $db->loadAssoc();
	}
	
	/**
	 *
	 * Function to get information for a specific customer
	 * @param int $customerId
	 * @return customer object
	 */
	public static function getCustomer($customerId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_customers')
			->where('customer_id = ' . intval($customerId));
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 *
	 * Function to get information for a specific country
	 * @param int $countryId
	 */
	public static function getCountry($countryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_countries')
			->where('id = ' . intval($countryId))
			->where('published = 1');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 *
	 * Function to get Zones for a specific Country
	 * @param int $countryId
	 */
	public static function getCountryZones($countryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, zone_name')
			->from('#__eshop_zones')
			->where('country_id = ' . intval($countryId))
			->where('published = 1');
		$db->setQuery($query);
		return $db->loadAssocList();
	}
	
	/**
	 *
	 * Function to get information for a specific zone
	 * @param int $zoneId
	 */
	public static function getZone($zoneId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_zones')
			->where('id = ' . intval($zoneId))
			->where('published = 1');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 *
	 * Function to get information for a specific geozone
	 * @param int $geozoneId
	 */
	public static function getGeozone($geozoneId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_geozones')
			->where('id = ' . intval($geozoneId))
			->where('published = 1');
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 *
	 * Function to complete an order
	 * @param order object $row
	 */
	public static function completeOrder($row)
	{
		$orderId = intval($row->id);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_orderproducts')
			->where('order_id = ' . intval($orderId));
		$db->setQuery($query);
		$orderProducts = $db->loadObjectList();
		foreach ($orderProducts as $orderProduct)
		{
			//Update product quantity
			$query->clear();
			$query->update('#__eshop_products')
				->set('product_quantity = product_quantity - ' . intval($orderProduct->quantity))
				->where('id = ' . intval($orderProduct->product_id));
			$db->setQuery($query);
			$db->query();
			//Update product options
			$query->clear();
			$query->select('*')
				->from('#__eshop_orderoptions')
				->where('order_id = ' . intval($orderId))
				->where('order_product_id = ' . intval($orderProduct->id));
			$db->setQuery($query);
			$orderOptions = $db->loadObjectList();
			foreach ($orderOptions as $orderOption)
			{
				if ($orderOption->option_type == 'Select' || $orderOption->option_type == 'Radio' || $orderOption->option_type == 'Checkbox')
				{
					$query->clear();
					$query->update('#__eshop_productoptionvalues')
						->set('quantity = quantity - ' . intval($orderProduct->quantity))
						->where('id = ' . intval($orderOption->product_option_value_id));
					$db->setQuery($query);
					$db->query();
				}
			}
			//Add coupon history
			$query->clear();
			$query->select('value')
				->from('#__eshop_ordertotals')
				->where('order_id = ' . intval($orderId))
				->where('name = "coupon"');
			$db->setQuery($query);
			$amount = $db->loadResult();
			if ($amount)
			{
				$session = JFactory::getSession();
				$couponCode = $session->get('coupon_code');
				if ($couponCode != '')
				{
					$query->clear();
					$query->select('id')
						->from('#__eshop_coupons')
						->where('coupon_code = "' . $couponCode . '"');
					$db->setQuery($query);
					$couponId = $db->loadResult();
					if ($couponId)
					{
						$coupon = new EshopCoupon();
						$coupon->addCouponHistory($couponId, $orderId, $row->customer_id, $amount);
					}
				}
			}
		}
	}
	
	/**
	 *
	 * Function to send email
	 * @param order object $row
	 */
	public static function sendEmails($row)
	{
		$jconfig = new JConfig();
		$mailer = JFactory::getMailer();
		$fromName = $jconfig->fromname;
		$fromEmail =  $jconfig->mailfrom;
		
		//Send notification email to admin
		$adminSubject = sprintf(JText::_('ESHOP_ADMIN_EMAIL_SUBJECT'), EshopHelper::getConfigValue('store_name'), $row->id);
		$adminBody = self::getAdminEmailBody($row);
		$adminEmail = EshopHelper::getConfigValue('email') ? trim(EshopHelper::getConfigValue('email')) : $fromEmail;
		$mailer->sendMail($fromEmail, $fromName, $adminEmail, $adminSubject, $adminBody, 1);
		
		//Send notification email to additional emails
		$alertEmails = EshopHelper::getConfigValue('alert_emails');
		$alertEmails = str_replace(' ', '', $alertEmails);
		$alertEmails = explode(',', $alertEmails);
		for ($i = 0; $n = count($alertEmails), $i < $n; $i++)
		{
			if ($alertEmails[$i] != '')
			{
				$mailer->ClearAllRecipients();
				$mailer->sendMail($fromEmail, $fromName, $alertEmails[$i], $adminSubject, $adminBody, 1);
			}
		}
		
		//Send notification email to manufacturer
		$manufacturers = array();
		$orderProducts = self::getOrderProducts($row->id);
		for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
		{
			$product = $orderProducts[$i];
			$manufacturer = self::getProductManufacturer($product->product_id, JFactory::getLanguage()->getTag());
			if (is_object($manufacturer))
			{
				$manufacturer->product = $orderProducts[$i];
				if (!isset($manufacturers[$manufacturer->id]))
				{
					$manufacturers[$manufacturer->id] = array();
				}
				$manufacturers[$manufacturer->id][] = $manufacturer;
			}
		}
		$manufacturerSubject = JText::_('ESHOP_MANUFACTURER_EMAIL_SUBJECT');
		foreach ($manufacturers as $manufacturerId => $manufacturer)
		{
			if ($manufacturer[0]->manufacturer_email != '')
			{
				$manufacturerBody = self::getManufacturerEmailBody($manufacturer);
				$mailer->ClearAllRecipients();
				$mailer->sendMail($fromEmail, $fromName, $manufacturer[0]->manufacturer_email, $manufacturerSubject, $manufacturerBody);
			}
		}
		
		//Send email to customer
		$customerSubject = sprintf(JText::_('ESHOP_CUSTOMER_EMAIL_SUBJECT'), EshopHelper::getConfigValue('store_name'), $row->id);
		$customerBody = self::getCustomerEmailBody($row);
		$mailer->ClearAllRecipients();
		$mailer->sendMail($fromEmail, $fromName, $row->email, $customerSubject, $customerBody, 1);
	}
	
	/**
	 *
	 * Function to get admin email body
	 * @param unknown $row
	 * @return string
	 */
	public static function getAdminEmailBody($row)
	{
		$adminEmailBody = self::getMessageValue('admin_notification_email');
		// Order information
		$replaces = array();
		$replaces['order_id'] = $row->id;
		$replaces['date_added'] = JHtml::date($row->created_date, 'm-d-Y');		
		$replaces['payment_method'] = $row->payment_method_title;
		$replaces['shipping_method'] = $row->shipping_method_title;
		// Comment
		$replaces['comment'] = $row->comment;
		// Payment information
		$paymentAddress = $row->payment_firstname . ' ' . $row->payment_lastname . '<br />';
		$paymentAddress .= $row->payment_address_1;
		if ($row->payment_address_2 != '')
			$paymentAddress .= ', ' . $row->payment_address_2;
		$paymentAddress .= '<br />';
		$paymentAddress .= $row->payment_city;
		$paymentAddress .= ', ' . $row->payment_zone_name;
		$paymentAddress .= ' ' . $row->payment_postcode;
		$replaces['payment_address'] = $paymentAddress;
		$replaces['payment_email'] = $row->email;
		$replaces['payment_telephone'] = $row->telephone;
		// Shipping information
		$shippingAddress = '';
		if ($row->shipping_method != '')
		{
			$shippingAddress .= $row->shipping_firstname . ' ' . $row->shipping_lastname . '<br />';
			$shippingAddress .= $row->shipping_address_1;
			if ($row->shipping_address_2 != '')
				$shippingAddress .= ', ' . $row->shipping_address_2;
			$shippingAddress .= '<br />';
			$shippingAddress .= $row->shipping_city;
			$shippingAddress .= ', ' . $row->shipping_zone_name;
			$shippingAddress .= ' ' . $row->shipping_postcode;
		}
		$replaces['shipping_address'] = $shippingAddress;
		// Products list
		$viewConfig['name'] = 'email';
		$viewConfig['base_path'] = JPATH_ROOT.'/components/com_eshop/emailtemplates';
		$viewConfig['template_path'] = JPATH_ROOT.'/components/com_eshop/emailtemplates';
		$viewConfig['layout'] = 'admin';
		$view =  new JViewLegacy($viewConfig);
		$orderProducts = self::getOrderProducts($row->id);
		$view->assignRef('orderProducts', $orderProducts);
		$view->assignRef('orderTotals', self::getOrderTotals($row->id));
		$view->assignRef('row', $row);
		ob_start();
		$view->display();
		$text = ob_get_contents();
		ob_end_clean();
		$replaces['products_list'] = $text;
		
		foreach ($replaces as $key => $value)
		{
			$key = strtoupper($key);
			$adminEmailBody = str_replace("[$key]", $value, $adminEmailBody);
		}
		return $adminEmailBody;
	}
	
	/**
	 * 
	 * Function to get manufacturer email body
	 * @param array $manufacturer
	 */
	public static function getManufacturerEmailBody($manufacturer)
	{
		$manufacturerEmailBody = self::getMessageValue('manufacturer_notification_email');
		$replaces = array();
		$replaces['manufacturer_name'] = $manufacturer[0]->manufacturer_name;
		$replaces['store_name'] = EshopHelper::getConfigValue('store_name');
		// Products list
		$viewConfig['name'] = 'email';
		$viewConfig['base_path'] = JPATH_ROOT.'/components/com_eshop/emailtemplates';
		$viewConfig['template_path'] = JPATH_ROOT.'/components/com_eshop/emailtemplates';
		$viewConfig['layout'] = 'manufacturer';
		$view =  new JViewLegacy($viewConfig);
		$view->assignRef('manufacturer', $manufacturer);
		ob_start();
		$view->display();
		$text = ob_get_contents();
		ob_end_clean();
		$replaces['products_list'] = $text;
		
		foreach ($replaces as $key => $value)
		{
			$key = strtoupper($key);
			$manufacturerEmailBody = str_replace("[$key]", $value, $manufacturerEmailBody);
		}
		return $manufacturerEmailBody;
	}
	
	/**
	 *
	 * Function to get customer email body
	 * @param order object $row
	 * @return html string
	 */
	public static function getCustomerEmailBody($row)
	{
		if ($row->customer_id)
		{
			if ($row->payment_method == 'os_offline')
			{
				$customerEmailBody = self::getMessageValue('offline_payment_customer_notification_email');
			}
			else
			{
				$customerEmailBody = self::getMessageValue('customer_notification_email');
			}
		}
		else
		{
			if ($row->payment_method == 'os_offline')
			{
				$customerEmailBody = self::getMessageValue('offline_payment_guest_notification_email');
			}
			else
			{
				$customerEmailBody = self::getMessageValue('guest_notification_email');
			}
		}
		// Order information
		$replaces = array();
		$replaces['payment_firstname'] = $row->payment_firstname;
		$replaces['store_name'] = EshopHelper::getConfigValue('store_name');
		$replaces['order_link'] = JRoute::_(JURI::base().'index.php?option=com_eshop&view=customer&layout=order&order_id=' . $row->id);
		$replaces['order_id'] = $row->id;
		$replaces['date_added'] = JHtml::date($row->created_date, 'm-d-Y');
		$replaces['payment_method'] = $row->payment_method_title;
		$replaces['shipping_method'] = $row->shipping_method_title;
		$replaces['customer_email'] = $row->email;
		$replaces['customer_telephone'] = $row->telephone;
		// Comment
		$replaces['comment'] = $row->comment;
		// Payment information
		$paymentAddress = $row->payment_firstname . ' ' . $row->payment_lastname . '<br />';
		$paymentAddress .= $row->payment_address_1;
		if ($row->payment_address_2 != '')
			$paymentAddress .= ', ' . $row->payment_address_2;
		$paymentAddress .= '<br />';
		$paymentAddress .= $row->payment_city;
		$paymentAddress .= ', ' . $row->payment_zone_name;
		$paymentAddress .= ' ' . $row->payment_postcode;
		$replaces['payment_address'] = $paymentAddress;
		$replaces['payment_email'] = $row->email;
		$replaces['payment_telephone'] = $row->telephone;
		// Shipping information
		$shippingAddress = '';
		if ($row->shipping_method != '')
		{
			$shippingAddress .= $row->shipping_firstname . ' ' . $row->shipping_lastname . '<br />';
			$shippingAddress .= $row->shipping_address_1;
			if ($row->shipping_address_2 != '')
				$shippingAddress .= ', ' . $row->shipping_address_2;
			$shippingAddress .= '<br />';
			$shippingAddress .= $row->shipping_city;
			$shippingAddress .= ', ' . $row->shipping_zone_name;
			$shippingAddress .= ' ' . $row->shipping_postcode;
		}
		$replaces['shipping_address'] = $shippingAddress;
		// Products list
		$viewConfig['name'] = 'email';
		$viewConfig['base_path'] = JPATH_ROOT.'/components/com_eshop/emailtemplates';
		$viewConfig['template_path'] = JPATH_ROOT.'/components/com_eshop/emailtemplates';
		$viewConfig['layout'] = 'customer';
		$view =  new JViewLegacy($viewConfig);
		$orderProducts = self::getOrderProducts($row->id);
		$view->assignRef('orderProducts', $orderProducts);
		$view->assignRef('orderTotals', self::getOrderTotals($row->id));
		$view->assignRef('row', $row);
		ob_start();
		$view->display();
		$text = ob_get_contents();
		ob_end_clean();
		$replaces['products_list'] = $text;
		
		foreach ($replaces as $key => $value)
		{
			$key = strtoupper($key);
			$customerEmailBody = str_replace("[$key]", $value, $customerEmailBody);
		}
		return $customerEmailBody;
	}
	
	/**
	 * 
	 * Function to get invoice ouput for a specific order
	 * @param int $orderId
	 * @return string
	 */
	public static function getInvoiceHtml($orderId)
	{
		$viewConfig['name'] = 'invoice';
		$viewConfig['base_path'] = JPATH_ROOT.'/components/com_eshop/invoicetemplates';
		$viewConfig['template_path'] = JPATH_ROOT.'/components/com_eshop/invoicetemplates';
		$viewConfig['layout'] = 'default';
		$view =  new JViewLegacy($viewConfig);
		$view->assignRef('order_id', $orderId);
		ob_start();
		$view->display();
		$text = ob_get_contents();
		ob_end_clean();
		return $text;
	}

	/**
	 *
	 * Function to load jQuery chosen plugin
	 */
	public static function chosen()
	{
		static $chosenLoaded;
		if (!$chosenLoaded)
		{
			$document = JFactory::getDocument();
			if (version_compare(JVERSION, '3.0', 'ge'))
			{
				JHtml::_('formbehavior.chosen', '.chosen');
			}
			else
			{
				$document->addScript(JURI::base() . 'components/com_eshop/assets/chosen/chosen.jquery.js');
				$document->addStyleSheet(JURI::base() . 'components/com_eshop/assets/chosen/chosen.css');
			}
			$document->addScriptDeclaration(
				"jQuery(document).ready(function(){
	                    jQuery(\".chosen\").chosen();
	                });");
			$chosenLoaded = true;
		}
	}
	
	/**
	 *
	 * Function to load bootstrap library
	 */
	public static function loadBootstrap($loadJs = true)
	{
		$document = JFactory::getDocument();
		if ($loadJs)
		{
			$document->addScript(JUri::root() . 'components/com_eshop/assets/bootstrap/js/jquery.min.js');
			$document->addScript(JUri::root() . 'components/com_eshop/assets/bootstrap/js/jquery-noconflict.js');
			$document->addScript(JUri::root() . 'components/com_eshop/assets/bootstrap/js/bootstrap.min.js');
		}
		$document->addStyleSheet(JURI::root() . 'components/com_eshop/assets/bootstrap/css/bootstrap.css');
		$document->addStyleSheet(JURI::root() . 'components/com_eshop/assets/bootstrap/css/bootstrap.min.css');
	}
	
	/**
	 *
	 * Function to load bootstrap css
	 */
	public static function loadBootstrapCss()
	{
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root() . 'components/com_eshop/assets/bootstrap/css/bootstrap.css');
		$document->addStyleSheet(JURI::root() . 'components/com_eshop/assets/bootstrap/css/bootstrap.min.css');
	}
	
	/**
	 *
	 * Function to load bootstrap javascript
	 */
	public static function loadBootstrapJs($loadJs = true)
	{
		$document = JFactory::getDocument();
		$document->addScript(JUri::root() . 'components/com_eshop/assets/bootstrap/js/jquery.min.js');
		$document->addScript(JUri::root() . 'components/com_eshop/assets/bootstrap/js/jquery-noconflict.js');
		$document->addScript(JUri::root() . 'components/com_eshop/assets/bootstrap/js/bootstrap.min.js');
	}
	
	/**
	 * 
	 * Function to load scripts for share product
	 */
	public static function loadShareScripts($product)
	{
		$document = JFactory::getDocument();
		
		//Add script for Twitter
		if (EshopHelper::getConfigValue('show_twitter_button'))
		{
			$script = '!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");';
			$document->addScriptDeclaration($script);
		}
		
		//Add script for PinIt
		if (EshopHelper::getConfigValue('show_pinit_button'))
		{
			$script = '(function() {
				window.PinIt = window.PinIt || { loaded:false };
				if (window.PinIt.loaded) return;
				window.PinIt.loaded = true;
				function async_load(){
					var s = document.createElement("script");
					s.type = "text/javascript";
					s.async = true;
					s.src = "http://assets.pinterest.com/js/pinit.js";
					var x = document.getElementsByTagName("script")[0];
					x.parentNode.insertBefore(s, x);
				}
				if (window.attachEvent)
					window.attachEvent("onload", async_load);
				else
					window.addEventListener("load", async_load, false);
			})();';
			$document->addScriptDeclaration($script);
		}
		
		// Add script for LinkedIn
		if (EshopHelper::getConfigValue('show_linkedin_button'))
			$document->addScript('//platform.linkedin.com/in.js');

		// Add script for Google
		if (EshopHelper::getConfigValue('show_google_button'))
		{
			$script = '(function() {
				var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
				po.src = "https://apis.google.com/js/plusone.js";
				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
			})();';
			$document->addScriptDeclaration($script);
		}
		
		// Add script for Facebook
		if (EshopHelper::getConfigValue('show_facebook_button') || EshopHelper::getConfigValue('show_facebook_comment'))
		{
			$script = '(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/' . EshopHelper::getConfigValue('button_language', 'en_US') . '/all.js#xfbml=1&appId=' . EshopHelper::getConfigValue('app_id', '372958799407679') . '";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, "script","facebook-jssdk"));';
			$document->addScriptDeclaration($script);
			$uri = JURI::getInstance();
			$conf = JFactory::getConfig();
			$document->addCustomTag('<meta property="og:title" content="'.$product->product_name.'"/>');
			$document->addCustomTag('<meta property="og:type" content="product"/>');
			$document->addCustomTag('<meta property="og:image" content="'.$product->thumb_image.'"/>');
			$document->addCustomTag('<meta property="og:url" content="'.$uri->toString().'"/>');
			$document->addCustomTag('<meta property="og:description" content="'.$product->product_name.'"/>');
			$document->addCustomTag('<meta property="og:site_name" content="'.$conf->get('sitename').'"/>');
			$document->addCustomTag('<meta property="fb:admins" content="'.EshopHelper::getConfigValue('app_id', '372958799407679').'"/>');
		}
	}
	
	/**
	 * 
	 * Function to get Itemid of Eshop component
	 * @return int
	 */
	public static function getItemid()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__menu')
			->where('link LIKE "%index.php?option=com_eshop%"')
			->where('published = 1')
			->where('`access` IN ("' . implode(',', $user->getAuthorisedViewLevels()) . '")')
			->order('access');
		$db->setQuery($query);
		$itemId = $db->loadResult();
		if (!$itemId)
		{
			$Itemid = JRequest::getInt('Itemid');
			if ($Itemid == 1)
				$itemId = 999999;
			else
				$itemId = $Itemid;
		}
		return $itemId;
	}
	
	/**
	 *
	 * Function to get a list of the actions that can be performed.
	 * @return JObject
	 * @since 1.6
	 */
	public static function getActions()
	{
		$user = JFactory::getUser();
		$result = new JObject();
		$actions = array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete');
		$assetName = 'com_eshop';
		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}
	
		return $result;
	}

	/**
	 * 
	 * Function to display copy right information
	 */
	public static function displayCopyRight()
	{
		echo '<div class="copyright" style="text-align:center;margin-top: 5px;"><a href="http://joomdonation.com" target="_blank"><strong>EShop</strong></a> version 1.1.8, Copyright (C) 2012-2013 <a href="http://joomdonation.com" target="_blank"><strong>Ossolution Team</strong></a></div>';
	}

	/**
	 *
	 * Function to add dropdown menu
	 * @param string $vName        	
	 */
	public static function renderSubmenu($vName = 'dashboard')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_menus')
			->where('published = 1')
			->where('menu_parent_id = 0')
			->order('ordering');
		$db->setQuery($query);
		$menus = $db->loadObjectList();
		$html = '';
		$html .= '<div id="submenu-box"><div class="m"><ul class="nav nav-pills">';
		for ($i = 0; $n = count($menus), $i < $n; $i++)
		{
			$menu = $menus[$i];
			$query->clear();
			$query->select('*')
				->from('#__eshop_menus')
				->where('published = 1')
				->where('menu_parent_id = ' . intval($menu->id))
				->order('ordering');
			$db->setQuery($query);
			$subMenus = $db->loadObjectList();
			if (!count($subMenus))
			{
				$class = '';
				if ($menu->menu_view == $vName)
				{
					$class = ' class="active"';
				}
				$html .= '<li' . $class . '><a href="index.php?option=com_eshop&view=' . $menu->menu_view . '">' . JText::_($menu->menu_name) . '</a></li>';
			}
			else
			{
				$class = ' class="dropdown"';
				for ($j = 0; $m = count($subMenus), $j < $m; $j++)
				{
					$subMenu = $subMenus[$j];
					$lName = JRequest::getVar('layout');
					if ((!$subMenu->menu_layout && $vName == $subMenu->menu_view ) || ($lName != '' && $lName == $subMenu->menu_layout))
					{
						$class = ' class="dropdown active"';
						break;
					}
				}
				$html .= '<li' . $class . '>';
				$html .= '<a id="drop_' . $menu->id . '" href="#" data-toggle="dropdown" role="button" class="dropdown-toggle">' .
					 JText::_($menu->menu_name) . ' <b class="caret"></b></a>';
				$html .= '<ul aria-labelledby="drop_' . $menu->id . '" role="menu" class="dropdown-menu" id="menu_' . $menu->id . '">';
				for ($j = 0; $m = count($subMenus), $j < $m; $j++)
				{
					$subMenu = $subMenus[$j];
					$layoutLink = '';
					if ($subMenu->menu_layout)
					{
						$layoutLink	= '&layout=' . $subMenu->menu_layout;
					}
					$class = '';
					$lName = JRequest::getVar('layout');
					if ((!$subMenu->menu_layout && $vName == $subMenu->menu_view ) || ($lName != '' && $lName == $subMenu->menu_layout))
					{
						$class = ' class="active"';
					}
					$html .= '<li' . $class . '><a href="index.php?option=com_eshop&view=' .
						 $subMenu->menu_view . $layoutLink . '" tabindex="-1">' . JText::_($subMenu->menu_name) . '</a></li>';
				}
				$html .= '</ul>';
				$html .= '</li>';
			}
		}
		$html .= '</ul></div></div>';
		if (version_compare(JVERSION, '3.0', 'le'))
		{
			JFactory::getDocument()->setBuffer($html, array('type' => 'modules', 'name' => 'submenu'));
		}
		else
		{
			echo $html;
		}
	}
	
	/**
	 * 
	 * Function to get value for a message
	 * @param string $messageName
	 * @return string
	 */
	public static function getMessageValue($messageName)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$language = JFactory::getLanguage();
		$tag = $language->getTag();
		if (!$tag)
			$tag = 'en-GB';
		$language->load('com_eshop', JPATH_ROOT, $tag);
		$query->select('a.message_value')
			->from('#__eshop_messagedetails AS a')
			->innerJoin('#__eshop_messages AS b ON a.message_id = b.id')
			->where('a.language = ' . $db->quote($tag))
			->where('b.message_name = ' . $db->quote($messageName));
		$db->setQuery($query);
		$messageValue = $db->loadResult();
		if (!$messageValue)
		{
			$query->clear();
			$query->select('a.message_value')
				->from('#__eshop_messagedetails AS a')
				->innerJoin('#__eshop_messages AS b ON a.message_id = b.id')
				->where('a.language = "en-GB"')
				->where('b.message_name = ' . $db->quote($messageName));
			$db->setQuery($query);
			$messageValue = $db->loadResult();
		}
		return $messageValue;
	}
		
	/**
	 *
	 * Function to get information for a specific order
	 * @param int $orderId
	 * @return order Object
	 */
	public static function getOrder($orderId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_orders')
			->where('id = ' . (int) $orderId);
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 * 
	 * Function to get products for a specific order
	 * @param int $orderId
	 */
	public static function getOrderProducts($orderId)
	{
		$order = self::getOrder($orderId);
		$currency = new EshopCurrency();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_orderproducts')
			->where('order_id = '.(int)$orderId);
		$db->setQuery($query);
		$orderProducts = $db->loadObjectList();
		for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
		{
			$orderProducts[$i]->orderOptions = self::getOrderOptions($orderProducts[$i]->id);
			$orderProducts[$i]->price = $currency->format($orderProducts[$i]->price + $orderProducts[$i]->tax, $order->currency_code, $order->currency_exchanged_value);
			$orderProducts[$i]->total_price = $currency->format($orderProducts[$i]->total_price + $orderProducts[$i]->tax * $orderProducts[$i]->quantity, $order->currency_code, $order->currency_exchanged_value);
		}
		return $orderProducts;
	}
	
	/**
	 * 
	 * Function to get totals for a specific order
	 * @param int $orderId
	 * @return total object list
	 */
	public static function getOrderTotals($orderId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_ordertotals')
			->where('order_id = '.(int)$orderId);
		$db->setQuery($query);
		return  $db->loadObjectList();
	}
	
	/**
	 * 
	 * Function to get options for a specific order product
	 * @param unknown $orderProductId
	 */
	public static function getOrderOptions($orderProductId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_orderoptions')
			->where('order_product_id = ' . (int) $orderProductId);
		$db->setQuery($query);
		return  $db->loadObjectList();
	}
	
	/**
	 * 
	 * Function to get invoice output for products
	 * @param int $orderId
	 * @return string
	 */
	public static function getInvoiceProducts($orderId)
	{
		$viewConfig['name'] = 'invoice';
		$viewConfig['base_path'] = JPATH_ROOT.'/components/com_eshop/invoicetemplates';
		$viewConfig['template_path'] = JPATH_ROOT.'/components/com_eshop/invoicetemplates';
		$viewConfig['layout'] = 'default';
		$view =  new JViewLegacy($viewConfig);
		$orderProducts = self::getOrderProducts($orderId);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
		{
			$query->clear();
			$query->select('*')
				->from('#__eshop_orderoptions')
				->where('order_product_id = ' . intval($orderProducts[$i]->id));
			$db->setQuery($query);
			$orderProducts[$i]->options = $db->loadObjectList();
		}
		$orderTotals = self::getOrderTotals($orderId);
		$view->assignRef('order_id', $orderId);
		$view->assignRef('order_products', $orderProducts);
		$view->assignRef('order_total',$orderTotals);
		ob_start();
		$view->display();
		$text = ob_get_contents();
		ob_end_clean();
		return $text;
	}
	
	/**
	 * Generate invoice PDF
	 * @param array $cid
	 */
	public static function generateInvoicePDF($cid)
	{
		$mainframe = JFactory::getApplication();
		$sitename = $mainframe->getCfg("sitename");
		require_once JPATH_ROOT . "/components/com_eshop/tcpdf/tcpdf.php";
		require_once JPATH_ROOT . "/components/com_eshop/tcpdf/config/lang/eng.php";
		JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_eshop/tables');
		$invoiceOutputs = '';
		for ($i = 0; $n = count($cid), $i< $n; $i++)
		{
			$id = $cid[$i];
			$row = JTable::getInstance('Eshop', 'Order');
			$row->load($id);
			// Initial pdf object
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor($sitename);
			$pdf->SetTitle('Invoice');
			$pdf->SetSubject('Invoice');
			$pdf->SetKeywords('Invoice');
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			// Set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			// Set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->SetFont('times', '', 8);
			$pdf->AddPage();
			$invoiceOutput = self::getMessageValue('invoice_layout');
			
			// Store information
			$replaces = array();
			$replaces['invoice_heading'] = JText::_('ESHOP_INVOICE_HEADING');
			$replaces['store_name'] = EshopHelper::getConfigValue('store_name');
			$replaces['store_address'] = EshopHelper::getConfigValue('address');
			$replaces['store_telephone'] = EshopHelper::getConfigValue('telephone');
			$replaces['store_fax'] = EshopHelper::getConfigValue('fax');
			$replaces['store_email'] = EshopHelper::getConfigValue('email');
			$replaces['store_url'] = JURI::base();
			$replaces['date_added'] = JHtml::date($row->created_date, 'm-d-Y');
			$replaces['order_id'] = $row->id;
			$replaces['payment_method'] = $row->payment_method_title;
			$replaces['shipping_method'] = $row->shipping_method_title;
				
			// Payment information
			$paymentAddress = $row->payment_firstname . ' ' . $row->payment_lastname . '<br />';
			$paymentAddress .= $row->payment_address_1;
			if ($row->payment_address_2 != '')
				$paymentAddress .= ', ' . $row->payment_address_2;
			$paymentAddress .= '<br />';
			$paymentAddress .= $row->payment_city;
			$paymentAddress .= ', ' . $row->payment_zone_name;
			$paymentAddress .= ' ' . $row->payment_postcode;
			$replaces['payment_address'] = $paymentAddress;
			$replaces['payment_email'] = $row->email;
			$replaces['payment_telephone'] = $row->telephone;
				
			// Shipping information
			$shippingAddress = '';
			if ($row->shipping_method != '')
			{
				$shippingAddress .= $row->shipping_firstname . ' ' . $row->shipping_lastname . '<br />';
				$shippingAddress .= $row->shipping_address_1;
				if ($row->shipping_address_2 != '')
					$shippingAddress .= ', ' . $row->shipping_address_2;
				$shippingAddress .= '<br />';
				$shippingAddress .= $row->shipping_city;
				$shippingAddress .= ', ' . $row->shipping_zone_name;
				$shippingAddress .= ' ' . $row->shipping_postcode;
			}
			$replaces['shipping_address'] = $shippingAddress;
				
			// Products list
			$replaces['products_list'] = self::getInvoiceProducts($row->id);
				
			// Comment
			$replaces['comment'] = $row->comment;
				
			foreach ($replaces as $key => $value)
			{
				$key = strtoupper($key);
				$invoiceOutput = str_replace("[$key]", $value, $invoiceOutput);
			}
			$invoiceOutput = self::convertImgTags($invoiceOutput);
			if ($n > 1 && $i < ($n - 1))
				$invoiceOutput = '<div style="page-break-after: always;">' . $invoiceOutput . '</div>';
			$invoiceOutputs .= $invoiceOutput;
		}
		$v = $pdf->writeHTML($invoiceOutputs, true, false, false, false, '');
	
		// Filename
		if (count($cid) == 1)
			$filename = $cid[0] . '.pdf';
		else
			$filename = implode('-', $cid) . '.pdf';
		if (EshopHelper::getConfigValue('invoice_prefix') != '')
		{
			$filename = EshopHelper::getConfigValue('invoice_prefix') . '-' . $filename;
		}
		$filePath = JPATH_ROOT . '/media/com_eshop/invoices/' . $filename;
		$pdf->Output($filePath, 'F');
	}
	
	/**
	 * 
	 * Function to download invoice
	 * @param array $cid
	 */
	public static function downloadInvoice($cid)
	{
		$invoiceStorePath = JPATH_ROOT . '/media/com_eshop/invoices/';
		if (count($cid) == 1)
			$filename = $cid[0] . '.pdf';
		else
			$filename = implode('-', $cid) . '.pdf';
		if (EshopHelper::getConfigValue('invoice_prefix') != '')
		{
			$filename = EshopHelper::getConfigValue('invoice_prefix') . '-' . $filename;
		}
		if ($row->payment_method == 'os_offline' || !file_exists($invoiceStorePath . $filename))
		{
			self::generateInvoicePDF($cid);
		}
		$invoicePath = $invoiceStorePath . $filename;
		while (@ob_end_clean());
		self::processDownload($invoicePath, $filename, true);
	}

	/**
	 * 
	 * Function to process download
	 * @param string $filePath
	 * @param string $filename
	 * @param boolean $download
	 */
	public static function processDownload($filePath, $filename, $download = false)
	{
		jimport('joomla.filesystem.file') ;						
		$fsize = @filesize($filePath);
		$mod_date = date('r', filemtime($filePath) );		
		if ($download) {
		    $cont_dis ='attachment';   
		} else {
		    $cont_dis ='inline';
		}		
		$ext = JFile::getExt($filename) ;
		$mime = self::getMimeType($ext);
		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression'))  {
			ini_set('zlib.output_compression', 'Off');
		}
	    header("Pragma: public");
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Expires: 0");		
	    header("Content-Transfer-Encoding: binary");
		header('Content-Disposition:' . $cont_dis .';'
			. ' filename="' . JFile::getName($filename) . '";' 
			. ' modification-date="' . $mod_date . '";'
			. ' size=' . $fsize .';'
			); //RFC2183
	    header("Content-Type: "    . $mime );			// MIME type
	    header("Content-Length: "  . $fsize);
	
	    if( ! ini_get('safe_mode') ) { // set_time_limit doesn't work in safe mode
		    @set_time_limit(0);
	    }
	    self::readfile_chunked($filePath);
	}
	
	/**
	 * 
	 * Function to get mimetype of file
	 * @param string $ext
	 * @return string
	 */
	public static function getMimeType($ext)
	{
		require_once JPATH_ROOT . "/components/com_eshop/helpers/mime.mapping.php";
		foreach ($mime_extension_map as $key => $value)
		{
			if ($key == $ext)
			{
				return $value;
			}
		}
		return "";
	}
	
	/**
	 * 
	 * Function to read file
	 * @param string $filename
	 * @param boolean $retbytes
	 * @return boolean|number
	 */
	public static function readfile_chunked($filename, $retbytes = true)
	{
		$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
		$buffer = '';
		$cnt = 0;
		$handle = fopen($filename, 'rb');
		if ($handle === false)
		{
			return false;
		}
		while (!feof($handle))
		{
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			@ob_flush();
			flush();
			if ($retbytes)
			{
				$cnt += strlen($buffer);
			}
		}
		$status = fclose($handle);
		if ($retbytes && $status)
		{
			return $cnt; // return num. bytes delivered like readfile() does.
		}
		return $status;
	}
	
	/**
	 * Convert all img tags to use absolute URL
	 * @param string $html_content
	 */
	public static function convertImgTags($html_content)
	{
		$patterns = array();
		$replacements = array();
		$i = 0;
		$src_exp = "/src=\"(.*?)\"/";
		$link_exp = "[^http:\/\/www\.|^www\.|^https:\/\/|^http:\/\/]";
		$siteURL = JURI::root();
		preg_match_all($src_exp, $html_content, $out, PREG_SET_ORDER);
		foreach ($out as $val)
		{
			$links = preg_match($link_exp, $val[1], $match, PREG_OFFSET_CAPTURE);
			if ($links == '0')
			{
				$patterns[$i] = $val[1];
				$patterns[$i] = "\"$val[1]";
				$replacements[$i] = $siteURL . $val[1];
				$replacements[$i] = "\"$replacements[$i]";
			}
			$i++;
		}
		$mod_html_content = str_replace($patterns, $replacements, $html_content);
	
		return $mod_html_content;
	}
	
	/**
	 *
	 * Function to get order number product
	 * @param int $orderId
	 */
	public static function getNumberProduct($orderId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(id)')
		->from('#__eshop_orderproducts')
		->where('order_id=' . intval($orderId));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * 
	 * Function to get substring
	 * @param string $text
	 * @param int $length
	 * @param string $replacer
	 * @param boolean $isAutoStripsTag
	 * @return string
	 */
	public static function substring($text, $length = 100, $replacer = '...', $isAutoStripsTag = true )
	{
		$string = $isAutoStripsTag ? strip_tags($text) : $text;
		return JString::strlen($string) > $length ? JHtml::_('string.truncate', $string, $length ) : $string;
	}
	
	/**
	 * 
	 * Function to get alement alias
	 * @param int $id
	 * @param string $element
	 * @param string $langCode
	 * @return string
	 */
	public static function getElementAlias($id, $element, $langCode = '')
	{
		if (!$langCode)
			$langCode = JFactory::getLanguage()->getTag();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($element . '_name, ' . $element . '_alias')
			->from('#__eshop_' . $element . 'details')
			->where($element . '_id = ' . (int)$id )
			->where('language = "' . $langCode . '"');
		$db->setQuery($query);
		$row = $db->loadObject();
		if ($row->{$element . '_alias'} != '')
			return $row->{$element . '_alias'};
		else
			return $row->{$element . '_name'};
	}
	
	/**
	 * 
	 * Function to get category id/alias path
	 * @param int $id
	 * @param string $type
	 * @param string $langCode
	 * @param int $parentId
	 * @return array
	 */
	public static function getCategoryPath($id, $type, $langCode = '', $parentId = 0)
	{
		static $categories;
		if (!$langCode)
			$langCode = JFactory::getLanguage()->getTag();
		$db = JFactory::getDbo();
		if (empty($categories))
		{
			$query = $db->getQuery(true);
			$query->select('a.id, a.category_parent_id, b.category_alias')
				->from('#__eshop_categories AS a')
				->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
				->where('b.language = "' . $langCode . '"');
			$db->setQuery($query);
			$categories = $db->loadObjectList('id');
		}
		$alias = array();
		$ids = array();
		do
		{
			$alias[] = $categories[$id]->category_alias;
			$ids[] = $categories[$id]->id;
			$id = $categories[$id]->category_parent_id;
		}
		while ($id != $parentId);
		if ($type == 'id')
			return array_reverse($ids);
		else 
			return array_reverse($alias);
	}
	
	/**
	 * 
	 * Function to get category name path
	 * @param int $id
	 * @param string $langCode
	 * @return string
	 */
	public static function getCategoryNamePath($id, $langCode = '')
	{
		if (!$langCode)
			$langCode = JFactory::getLanguage()->getTag();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, a.category_parent_id, b.category_name')
			->from('#__eshop_categories AS a')
			->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
			->where('b.language = "' . $langCode . '"');
		$db->setQuery($query);
		$categories = $db->loadObjectList('id');
		$names = array();
		do
		{
			$names[] = $categories[$id]->category_name;
			$id = $categories[$id]->category_parent_id;
		}
		while ($id != 0);
		return array_reverse($names);
	}
	
	/**
	 * 
	 * Function to identify if price will be showed or not
	 * @return boolean
	 */
	public static function showPrice()
	{
		$user = JFactory::getUser();
		if ($user->get('id') || (!$user->get('id') && !EshopHelper::getConfigValue('customer_price')))
			$showPrice = true;
		else
			$showPrice = false;
		return $showPrice;
	}

	/**
	 * 
	 * Function to get default address id
	 * @param int $id
	 * @return int
	 */
	public static function getDefaultAddressId($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('address_id')
			->from('#__eshop_customers')
			->where('address_id='.(int)$id);
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * 
	 * Function to count address for current user
	 * @return int
	 */
	public static function countAddress()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(id)')
			->from('#__eshop_addresses')
			->where('customer_id='.(int)$user->get('id'));
		$db->setQuery($query);
		return $db->loadResult();
	}
}