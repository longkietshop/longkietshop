<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop - Price Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_price extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	function eshop_price()
	{
		parent::setName('eshop_price');
		parent::eshop_shipping();
	}
	
	/**
	 * 
	 * Function tet get quote for price shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
		//Check geozone condition
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if (!$params->get('geozone_id'))
		{
			$status = true;
		}
		else
		{
			$query->select('COUNT(*)')
				->from('#__eshop_geozonezones')
				->where('geozone_id = ' . intval($params->get('geozone_id')))
				->where('country_id = ' . intval($addressData['country_id']))
				->where('(zone_id = 0 OR zone_id = ' . intval($addressData['zone_id']) . ')');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$status = true;
			}
			else
			{
				$status = false;
			}
		}
		//Check min sub total for free condition
		$cart = new EshopCart();
		$subTotal = $cart->getSubTotal();
		$minTotalForFree = $params->get('min_sub_total_for_free', 0);
		if ($minTotalForFree > 0 && $minTotalForFree <= $subTotal)
		{
			$status = false;
		}
		//Check input data condition
		$subTotalRange = $params->get('sub_total_range');
		$subTotalRangeArr = explode(';', $subTotalRange);
		$costRange = $params->get('cost_range');
		$costRangeArr = explode(';', $costRange);
		if (!count($subTotalRangeArr) || !count($costRangeArr))
		{
			$status = false;
		}
		$methodData = array();
		if ($status)
		{
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$quoteData = array();
			$packageFee = $params->get('package_fee', 0);
			$cost = 0;
			for ($i = 0; $n = count($subTotalRangeArr), $i < ($n - 1); $i++)
			{
				if ($subTotal >= $subTotalRangeArr[$i] && $subTotal <= $subTotalRangeArr[$i + 1])
				{
					if (strpos($costRangeArr[$i], '%'))
					{
						$percentageCost = str_replace('%', '', $costRangeArr[$i]);
						$cost = round($subTotal * $percentageCost / 100, 2);
					}
					else
					{
						$cost = $costRangeArr[$i];
					}
					break;
				}
				else
				{
					continue;
				}
			}
			if ($i == ($n - 1))
			{
				if (strpos($costRangeArr[$i], '%'))
				{
					$percentageCost = str_replace('%', '', $costRangeArr[$i]);
					$cost = round($subTotal * $percentageCost / 100, 2);
				}
				else
				{
					$cost = $costRangeArr[$i];
				}
			}
			$cost = $cost + $packageFee;
			
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_price"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$quoteData['price'] = array(
				'name'			=> 'eshop_price.price', 
				'title'			=> JText::_('PLG_ESHOP_PRICE_DESC'), 
				'cost'			=> $cost, 
				'taxclass_id' 	=> $params->get('taxclass_id'), 
				'text'			=> $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax'))));
			
			$methodData = array(
				'name'		=> 'eshop_price',
				'title'		=> JText::_('PLG_ESHOP_PRICE_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}