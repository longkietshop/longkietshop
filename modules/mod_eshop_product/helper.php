<?php
/**
 * @version		1.0.4
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

class modEshopProductHelper
{

	static public function getItems($params)
	{
		//get config xml
		$numberProduct  	= $params->get('number_product',6);
		$display            = $params->get('product_group','featured');
		$language = JFactory::getLanguage();
		$tag = $language->getTag();
		if (!$tag)
		$tag = 'en-GB';
		$language->load('com_eshop', JPATH_ROOT, $tag);
		$categoryIds 		= $params->get('category_id');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*,c.product_name,c.product_short_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productcategories AS b ON a.id = b.product_id')
			->innerJoin('#__eshop_productdetails AS c ON a.id = c.product_id')
			->where('a.published=1')
			->where('language='.$db->quote($tag))
			->group('b.product_id');
		if (count($categoryIds) != 0)
		{
			$query->where('b.category_id IN (' . implode($categoryIds, ',') . ')');
		}
		if($display == 'featured')
		{
			$query->where('a.product_featured=1');
		}elseif ($display == 'latest')
		{
			$query->order('a.id DESC');
		}elseif ($display == 'random')
		{
			$query->order('RAND()');
		}elseif ($display == 'topten')
		{
			$query->order('a.hits DESC');
		}
		//check limit product
		if($numberProduct != '')
		{
			$query->order('a.id DESC LIMIT 0, ' . $numberProduct );
		}
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}