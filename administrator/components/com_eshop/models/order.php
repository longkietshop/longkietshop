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
class EShopModelOrder extends EShopModel
{
	
	function __construct($config)
	{
		parent::__construct($config);
	}
	
	function store(&$data)
	{
		$paymentCountryInfo = EshopHelper::getCountry($data['payment_country_id']);
		if (is_object($paymentCountryInfo))
		{
			$data['payment_country_name'] = $paymentCountryInfo->country_name;
		}
		$paymentZoneInfo = EshopHelper::getZone($data['payment_zone_id']);
		if (is_object($paymentZoneInfo))
		{
			$data['payment_zone_name'] = $paymentZoneInfo->zone_name;
		}
		$shippingCountryInfo = EshopHelper::getCountry($data['shipping_country_id']);
		if (is_object($shippingCountryInfo))
		{
			$data['shipping_country_name'] = $shippingCountryInfo->country_name;
		}
		$shippingZoneInfo = EshopHelper::getZone($data['shipping_zone_id']);
		if (is_object($shippingZoneInfo))
		{
			$data['shipping_zone_name'] = $shippingZoneInfo->zone_name;
		}
		parent::store($data);
		return true;
	}
	
	/**
	 * Method to remove orders
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
			$query->delete('#__eshop_orders')
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			$numItemsDeleted = $db->getAffectedRows();
			//Delete order products
			$query->clear();
			$query->delete('#__eshop_orderproducts')
				->where('order_id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			//Delete order totals
			$query->clear();
			$query->delete('#__eshop_ordertotals')
				->where('order_id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			//Delete order options
			$query->clear();
			$query->delete('#__eshop_orderoptions')
				->where('order_id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			
			if ($numItemsDeleted < count($cid))
			{
				//Removed warning
				return 2;
			}
		}
		//Removed success
		return 1;
	}
	
	/**
	 * 
	 * Function to download file
	 * @param int $id
	 */
	function downloadFile($id, $download = true)
	{
		$app = JFactory::getApplication();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('option_value')
			->from('#__eshop_orderoptions')
			->where('id = ' . intval($id));
		$db->setQuery($query);
		$filename = $db->loadResult();
		while (@ob_end_clean());
		EshopHelper::processDownload(JPATH_ROOT . '/media/com_eshop/files/' . $filename, $filename, $download);
		$app->close(0);
	}
}