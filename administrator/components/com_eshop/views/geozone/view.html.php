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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewGeozone extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, country_name AS name')
			->from('#__eshop_countries')
			->where('published=1');
		$db->setQuery($query);
		$countryOptions = $db->loadObjectList();
		$query->clear();
		$query->select('zone_id,country_id')
			->from('#__eshop_geozonezones')
			->where('geozone_id = ' . (int) $item->id);
		$db->setQuery($query);
		$zoneToGeozones = $db->loadObjectList();
		$config = EshopHelper::getConfig();
		JFactory::getDocument()->addScript(JURI::root() . 'administrator/components/com_eshop/assets/js/eshop.js')
			->addScriptDeclaration(EshopHtmlHelper::getZonesArrayJs())
			->addScriptDeclaration(EshopHtmlHelper::getCountriesOptionsJs());
		$this->countryId = $config->country_id;
		$this->countryOptions = $countryOptions;
		$this->zoneToGeozones = $zoneToGeozones;
	}
}