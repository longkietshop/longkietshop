<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop - Weight Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_weight extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	function eshop_weight()
	{
		parent::setName('eshop_weight');
		parent::eshop_shipping();
	}
	
	/**
	 * 
	 * Function tet get quote for weight shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$cart = new EshopCart();
		$tax = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();
		$weight = new EshopWeight();
		$cartWeight = $cart->getWeight();
		$rates = explode("\r\n", $params->get('rates'));
		$quoteData = array();
		for ($i = 0; $n = count($rates), $i < $n; $i++)
		{
			$status = false;
			$rate = explode("|", $rates[$i]);
			$geozoneId = $rate[0];
			if ($geozoneId)
			{
				$query->clear();
				$query->select('COUNT(*)')
					->from('#__eshop_geozonezones')
					->where('geozone_id = ' . intval($geozoneId))
					->where('country_id = ' . intval($addressData['country_id']))
					->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
				$db->setQuery($query);
				if ($db->loadResult())
				{
					$status = true;
				}
			}
			$cost = 0;
			if ($status)
			{
				for ($j = 1; $m = count($rate), $j < $m; $j++)
				{
					$data = explode(";", $rate[$j]);
					if (isset($data[0]) && $data[0] >= $cartWeight)
					{
						if (isset($data[1]))
						{
							$cost = $data[1];
							break;
						}
					}
				}
			}
			if ($cost)
			{
				$geozone = EshopHelper::getGeozone($geozoneId);
				$quoteData['weight_' . $geozoneId] = array (
					'name'			=> 'eshop_weight.weight_' . $geozoneId,
					'title'			=> $geozone->geozone_name . ' (' . JText::_('PLG_ESHOP_WEIGHT_WEIGHT') . ': ' . $weight->format($cartWeight, EshopHelper::getConfigValue('weight_id')) . ')',
					'cost'			=> $cost,
					'taxclass_id' 	=> $params->get('taxclass_id'),
					'text'			=> $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax'))));
			}
		}
		$methodData = array();
		if ($quoteData)
		{
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_weight"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$methodData = array (
				'name'		=> 'eshop_weight',
				'title'		=> JText::_('PLG_ESHOP_WEIGHT_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}