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

class EshopHtmlHelper
{

	/**
	 * 
	 * Function to get Zones Javascript Array
	 * @return string
	 */
	public static function getZonesArrayJs()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('country_id, CONCAT(id, ":", zone_name) AS name')
			->from('#__eshop_zones')
			->where('published = 1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$states = array();
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			$states[$row->country_id][] = $row->name;
		}
		$stateString = " var stateList = new Array();\n";
		foreach ($states as $countryId => $stateArray)
		{
			$stateString .= " stateList[$countryId] = \"0:".JText::_('ESHOP_ALL_ZONES')."," . implode(',', $stateArray) . "\";\n";
		}
		return $stateString;
	}

	/**
	 * 
	 * Function to get Countries Options Javascript
	 * @return string
	 */
	public static function getCountriesOptionsJs()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, country_name AS name')
			->from('#__eshop_countries')
			->where('published=1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = "\nvar countriesOptions = '';";
		foreach ($rows as $row)
		{
			$options .= "\ncountriesOptions += \"<option value='$row->id'>$row->name</option>\";";
		}
		$options .= "\n";
		
		return $options;
	}

	/**
	 * 
	 * Function to get Zones Options Javascript
	 * @param int $countryId
	 * @return string
	 */
	public static function getZonesOptionsJs($countryId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, zone_name')
			->from('#__eshop_zones')
			->where('country_id=' . intval($countryId))
			->where('published=1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = "\nvar zonesOptions = '';";
		foreach ($rows as $row)
		{
			$options .= "\nzonesOptions += \"<option value='$row->id'>$row->zone_name</option>\";";
		}
		$options .= "\n";
		
		return $options;
	}

	/**
	 * 
	 * Function to get Taxrate Options Javascript
	 * @return string
	 */
	public static function getTaxrateOptionsJs()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, tax_name')
			->from('#__eshop_taxes')
			->where('published=1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = "\nvar taxrateOptions = '';";
		foreach ($rows as $row)
		{
			$options .= "\ntaxrateOptions += \"<option value='$row->id'>$row->tax_name</option>\";";
		}
		$options .= "\n";
		
		return $options;
	}

	/**
	 * 
	 * Function to get Baseon Options Javascript
	 * @return string
	 */
	public static function getBaseonOptionsJs()
	{
		$options = "\nvar BaseonOptions = '';";
		$options .= "\nBaseonOptions += \"<option value='shipping'>Shipping Address</option>\";";
		$options .= "\nBaseonOptions += \"<option value='payment'>Payment Address</option>\";";
		$options .= "\nBaseonOptions += \"<option value='store'>Store Address</option>\";";
		$options .= "\n";
		
		return $options;
	}
}
