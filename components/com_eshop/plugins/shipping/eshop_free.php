<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop - Free Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_free extends eshop_shipping
{
	
	/**
	 *
	 * Constructor function
	 */
	function eshop_free()
	{
		parent::setName('eshop_free');
		parent::eshop_shipping();
	}

	/**
	 * 
	 * Function tet get quote for free shipping
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
		
		//Check min sub total
		$cart = new EshopCart();
		$subTotal = $cart->getSubTotal();
		$minSubTotal = $params->get('min_sub_total', 0);
		if ($subTotal < $minSubTotal)
		{
			$status = false;
		}
		$methodData = array();
		if ($status)
		{
			$currency = new EshopCurrency();
			$quoteData = array();
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_free"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$quoteData['free'] = array (
				'name'			=> 'eshop_free.free', 
				'title'			=> JText::_('PLG_ESHOP_FREE_DESC'), 
				'cost'			=> 0.00, 
				'taxclass_id' 	=> $params->get('taxclass_id'),
				'text'			=> $currency->format(0.00));
			
			$methodData = array (
				'name'		=> 'eshop_free',
				'title'		=> JText::_('PLG_ESHOP_FREE_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> false);
		}
		return $methodData;
	}
}