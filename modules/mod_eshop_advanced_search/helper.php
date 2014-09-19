<?php
/**
 * @version		1.0.4
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class modEshopAdvancedSearchHelper
{
	public static function getManufacturer()
	{
		$language = JFactory::getLanguage();
		$tag = $language->getTag();
		if (!$tag)
		$tag = 'en-GB';
		$language->load('com_eshop', JPATH_ROOT, $tag);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.id, b.manufacturer_id, b.manufacturer_name')
			->from('#__eshop_manufacturers AS a')
			->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
			->where('a.published = 1')
			->where('language = '.$db->quote($tag));
		$db->setQuery($query);
		return  $db->loadObjectList();
	}
}