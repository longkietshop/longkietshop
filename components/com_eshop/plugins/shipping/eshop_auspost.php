<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop - Auspost Shipping
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_auspost extends eshop_shipping
{
	
	/**
	 * 
	 * Constructor function
	 */
	function eshop_auspost()
	{
		parent::setName('eshop_auspost');
		parent::eshop_shipping();
	}

	/**
	 * 
	 * Function tet get quote for auspost shipping
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
		$error = '';
		$methodData = array();
		$quoteData = array();
		if ($status)
		{
			$cart = new EshopCart();
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$weight = new EshopWeight();
			$cartWeight = $weight->convert($cart->getWeight(), EshopHelper::getConfigValue('weight_id'), $params->get('weight_id'));
			if ($params->get('standard_postage') && $addressData['iso_code_2'] == 'AU')
			{
				$curl = curl_init();
			
				curl_setopt($curl, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . urlencode($params->get('postcode')) . '&destination_postcode=' . urlencode($addressData['postcode']) . '&height=70&width=70&length=70&country=AU&service_type=standard&quantity=1&weight=' . urlencode($cartWeight));
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
				$response = curl_exec($curl);
			
				curl_close($curl);
			
				if ($response)
				{
					$responseInfo = array();
					$parts = explode("\n", trim($response));
					foreach ($parts as $part)
					{
						list($key, $value) = explode('=', $part);
						$responseInfo[$key] = $value;
					}
						
					if ($responseInfo['err_msg'] != 'OK')
					{
						$error = $responseInfo['err_msg'];
					}
					else
					{
						$title = JText::_('ESHOP_AUSPOST_STANDARD');
						if ($params->get('auspost_display_time'))
						{
							$title .= ' (' . $responseInfo['days'] . ' ' . JText::_('ESHOP_AUSPOST_ETA') . ')';
						}

						$quoteData['standard'] = array (
							'name'			=> 'eshop_auspost.standard',
							'title'			=> $title,
							'cost'			=> $currency->convert($responseInfo['charge'], 'AUD', EshopHelper::getConfigValue('default_currency_code')),
							'taxclass_id' 	=> $params->get('taxclass_id'),
							'text'			=> $currency->format($tax->calculate($currency->convert($responseInfo['charge'], 'AUD', $currency->getCurrencyCode()), $params->get('taxclass_id'), EshopHelper::getConfigValue('tax')), $currency->getCurrencyCode(), 1.0000000)
						);
					}
				}
			}
			
			if ($params->get('express_postage') && $addressData['iso_code_2'] == 'AU')
			{
				$curl = curl_init();
			
				curl_setopt($curl, CURLOPT_URL, 'http://drc.edeliver.com.au/ratecalc.asp?pickup_postcode=' . urlencode($params->get('postcode')) . '&destination_postcode=' . urlencode($addressData['postcode']) . '&height=70&width=70&length=70&country=AU&service_type=express&quantity=1&weight=' . urlencode($cartWeight));
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
				$response = curl_exec($curl);
			
				curl_close($curl);
			
				if ($response)
				{
					$responseInfo = array();
					$parts = explode("\n", trim($response));
					foreach ($parts as $part)
					{
						list($key, $value) = explode('=', $part);
						$responseInfo[$key] = $value;
					}
					if ($responseInfo['err_msg'] != 'OK')
					{
						$error = $responseInfo['err_msg'];
					}
					else
					{
						$title = JText::_('ESHOP_AUSPOST_EXPRESS');
						if ($params->get('display_delivery_time'))
						{
							$title .= ' (' . $responseInfo['days'] . ' ' . JText::_('ESHOP_AUSPOST_ETA') . ')';
						}
						$quoteData['express'] = array (
							'name'			=> 'eshop_auspost.express',
							'title'			=> $title,
							'cost'			=> $currency->convert($responseInfo['charge'], 'AUD', EshopHelper::getConfigValue('default_currency_code')),
							'taxclass_id' 	=> $params->get('taxclass_id'),
							'text'			=> $currency->format($tax->calculate($currency->convert($responseInfo['charge'], 'AUD', $currency->getCurrencyCode()), $params->get('taxclass_id'), EshopHelper::getConfigValue('tax')), $currency->getCurrencyCode(), 1.0000000)
						);
					}
				}
			}
		}
		if ($quoteData)
		{
			$query->clear();
			$query->select('*')
				->from('#__eshop_shippings')
				->where('name = "eshop_auspost"');
			$db->setQuery($query);
			$row = $db->loadObject();
			$methodData = array (
				'name'		=> 'eshop_auspost',
				'title'		=> JText::_('PLG_ESHOP_AUSPOST_TITLE'),
				'quote'		=> $quoteData,
				'ordering'	=> $row->ordering,
				'error'		=> $error);
		}
		return $methodData;
	}
}