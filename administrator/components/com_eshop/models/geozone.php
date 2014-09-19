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
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelGeozone extends EShopModel
{

	function store(&$data)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if ($data['id'])
		{
			$query->delete('#__eshop_geozonezones')
				->where('geozone_id = ' . (int) $data['id']);
			$db->setQuery($query);
			$db->query();
		}
		parent::store($data);
		//save new data
		if (isset($data['country_id']))
		{
			$geozoneId = $data['id'];
			$countryIds = $data['country_id'];
			$zoneIds = $data['zone'];
			$query->clear();
			$query->insert('#__eshop_geozonezones')->columns('geozone_id, zone_id, country_id');
			foreach ($countryIds as $key => $countryId)
			{
				$zoneId = $db->quote($zoneIds[$key]);
				$query->values("$geozoneId, $zoneId, $countryId");
			}
			$db->setQuery($query);
			$db->query();
		}
		
		return true;
	}
	
	/**
	 * Method to remove geozones
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->delete('#__eshop_geozones')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(geozone_id) FROM #__eshop_geozonezones)');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			$numItemsDeleted = $db->getAffectedRows();
			if ($numItemsDeleted < count($cid))
			{
				//Removed warning
				return 2;
			}
		}
		//Removed success
		return 1;
	}

}