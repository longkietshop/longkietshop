<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop - Item Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_item extends eshop_shipping
{

	/**
	 *
	 * Constructor function
	 */
	function eshop_item()
	{
		parent::setName('eshop_item');
		parent::eshop_shipping();
	}
	
	/**
	 * 
	 * Function tet get quote for item shipping
	 * @param array $addressData
	 * @param object $params
	 */
	function getQuote($addressData, $params)
	{
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
		$methodData = array();
		if ($status)
		{
			$cart = new EshopCart();
			$packageFee = $params->get('package_fee', 0);
			$cost = 0;
			foreach ($cart->getCartData() as $product)
			{
				if ($product['product_shipping'])
				{
					$cost += $product['product_shipping_cost'] * $product['quantity'];
				}
			}
			$cost = $cost + $packageFee;
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$quoteData = array();
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_item"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$quoteData['item'] = array (
				'name'			=> 'eshop_item.item', 
				'title'			=> JText::_('PLG_ESHOP_ITEM_DESC'), 
				'cost'			=> $cost, 
				'taxclass_id' 	=> $params->get('taxclass_id'), 
				'text'			=> $currency->format($tax->calculate($cost, $params->get('taxclass_id'), EshopHelper::getConfigValue('tax'))));
			
			$methodData = array (
				'name'		=> 'eshop_item',
				'title'		=> JText::_('PLG_ESHOP_ITEM_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}