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

class modEshopManufacturerHelper
{
	/**
	 * 
	 * Function to get manufacturers
	 * @param object $params
	 * @return manufacturers object list
	 */
    static public function getItems($params)
    {
    	$db =  JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('a.id, a.manufacturer_image, b.manufacturer_name')
			->from('#__eshop_manufacturers AS a')
			->innerJoin('#__eshop_manufacturerdetails AS b ON (a.id = b.manufacturer_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
    		->order('a.id DESC LIMIT 0, ' .  $params->get('manufacturers_total', 8));
    	$db->setQuery($query);
    	return $db->loadObjectList();
    }
}