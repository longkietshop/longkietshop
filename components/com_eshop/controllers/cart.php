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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerCart extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	/**
	 * 
	 * Function to add a product to the cart
	 */
	function add()
	{
		$cart = new EshopCart();
		$json = array();
		$productId = JRequest::getInt('id');
		$quantity = JRequest::getInt('quantity') > 0 ? JRequest::getInt('quantity') : 1;
		if (JRequest::getVar('options'))
		{
			$options = array_filter(JRequest::getVar('options'));
		}
		else
		{
			$options = array();
		}
		//Validate options first
		$productOptions = EshopHelper::getProductOptions($productId, JFactory::getLanguage()->getTag());
		for ($i = 0; $n = count($productOptions), $i < $n; $i++)
		{
			$productOption = $productOptions[$i];
			if ($productOption->required && empty($options[$productOption->product_option_id]))
			{
				$json['error']['option'][$productOption->product_option_id] = $productOption->option_name . ' ' . JText::_('ESHOP_REQUIRED');
			}
		}
		if (!$json)
		{
			$product = EshopHelper::getProduct($productId, JFactory::getLanguage()->getTag());
			$cart->add($productId, $quantity, $options);
			$viewProductLink = JRoute::_(EshopRoute::getProductRoute($productId, EshopHelper::getProductCategory($productId)));
			$viewCartLink = JRoute::_(EshopRoute::getViewRoute('cart'));
			$viewCheckoutLink = JRoute::_(EshopRoute::getViewRoute('checkout'));
			$message = '<div>' . sprintf(JText::_('ESHOP_ADD_TO_CART_SUCCESS_MESSAGE'), $viewProductLink, $product->product_name, $viewCartLink, 'jQuery.colorbox.close();', $viewCheckoutLink) . '</div>';
			$json['success']['message'] = $message;
		}
		else
		{
			$json['redirect'] = JRoute::_(EshopRoute::getProductRoute($productId, EshopHelper::getProductCategory($productId)));
		}
		echo json_encode($json);
		exit();
	}
	
	/**
	 * 
	 * Function to update quantity of a product in the cart
	 */
	function update()
	{
		$session = JFactory::getSession();
		$session->set('success', JText::_('ESHOP_CART_UPDATE_MESSAGE'));
		$key = JRequest::getVar('key');
		$quantity = JRequest::getInt('quantity');
		$cart = new EshopCart();
		$cart->update($key, $quantity);
	}
	
	/**
	 * 
	 * Function to remove a product from the cart
	 */
	function remove()
	{
		$session = JFactory::getSession();
		$key = JRequest::getVar('key');
		$cart = new EshopCart();
		$cart->remove($key);
		if (JRequest::getInt('redirect'))
		{
			$session->set('success', JText::_('ESHOP_CART_REMOVED_MESSAGE'));
		}
	}
	
	/**
	 * 
	 * Function to apply coupon to the cart
	 */
	function applyCoupon()
	{
		$session = JFactory::getSession();
		$couponCode = JRequest::getVar('coupon_code');
		$coupon = new EshopCoupon();
		$couponData = $coupon->getCouponData($couponCode);
		if (!count($couponData))
		{
			$session->set('warning', JText::_('ESHOP_COUPON_APPLY_ERROR'));
		}
		else
		{
			$session->set('coupon_code', $couponCode);
			$session->set('success', JText::_('ESHOP_COUPON_APPLY_SUCCESS'));
		}
	}
	
	/**
	 * 
	 * Function to apply shipping to the cart
	 */
	function applyShipping()
	{
		$shippingMethod = explode('.', Jrequest::getVar('shipping_method'));
		$session = JFactory::getSession();
		$shippingMethods = $session->get('shipping_methods');
		if (isset($shippingMethods) && isset($shippingMethods[$shippingMethod[0]]))
		{
			$session->set('shipping_method', $shippingMethods[$shippingMethod[0]]['quote'][$shippingMethod[1]]);
			$session->set('success', JText::_('ESHOP_SHIPPING_APPLY_SUCCESS'));
		}
		else
		{
			$session->set('warning', JText::_('ESHOP_SHIPPING_APPLY_ERROR'));
		}
	}
	
	/**
	 * 
	 * Function to get Quote
	 */
	function getQuote()
	{
		$json = array();
		$cart = new EshopCart();
		$countryId = JRequest::getInt('country_id');
		$zoneId = JRequest::getInt('zone_id');
		$postcode = JRequest::getVar('postcode');
		if (!$cart->hasProducts())
		{
			$json['error']['warning'] = JText::_('ESHOP_ERROR_HAS_PRODUCTS');
		}

		if (!$cart->hasShipping())
		{
			$json['error']['warning'] = JText::_('ESHOP_ERROR_HAS_SHIPPING');
		}
		if (!$countryId)
		{
			$json['error']['country'] = JText::_('ESHOP_ERROR_COUNTRY');
		}
		if (!$zoneId)
		{
			$json['error']['zone'] = JText::_('ESHOP_ERROR_ZONE');
		}
		$countryInfo = EshopHelper::getCountry($countryId);
		if (is_object($countryInfo) && $countryInfo->postcode_required && ((utf8_strlen($postcode) < 2) || (utf8_strlen($postcode) > 8)))
		{
			$json['error']['postcode'] = JText::_('ESHOP_ERROR_POSTCODE');
		}
		if (!$json) {
			$session = JFactory::getSession();
			$tax = new EshopTax(EshopHelper::getConfig());
			$tax->setShippingAddress($countryId, $zoneId);
			$session->set('shipping_country_id', $countryId);
			$session->set('shipping_zone_id', $zoneId);
			$session->set('shipping_postcode', $postcode);
			if (is_object($countryInfo))
			{
				$countryName = $countryInfo->country_name;
				$isoCode2 = $countryInfo->iso_code_2;
				$isoCode3 = $countryInfo->iso_code_3;
			}
			else
			{
				$countryName = '';
				$isoCode2 = '';
				$isoCode3 = '';
			}
			$zoneInfo = EshopHelper::getZone($zoneId);
			if (is_object($zoneInfo))
			{
				$zoneName = $zoneInfo->zone_name;
				$zoneCode = $zoneInfo->zone_code;
			}
			else
			{
				$zoneName = '';
				$zoneCode = '';
			}
			$addressData = array(
				'firstname'			=> '',
				'lastname'			=> '',
				'company'			=> '',
				'address_1'			=> '',
				'address_2'			=> '',
				'postcode'			=> $postcode,
				'city'				=> '',
				'zone_id'			=> $zoneId,
				'zone_name'			=> $zoneName,
				'zone_code'			=> $zoneCode,
				'country_id'		=> $countryId,
				'country_name'		=> $countryName,	
				'iso_code_2'		=> $isoCode2,
				'iso_code_3'		=> $isoCode3
			);
			$quoteData = array();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__eshop_shippings')
				->where('published = 1')
				->order('ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			for ($i = 0; $n = count($rows), $i < $n; $i++)
			{
				$shippingName = $rows[$i]->name;
				$params = new JRegistry($rows[$i]->params);
				require_once JPATH_COMPONENT . '/plugins/shipping/' . $shippingName . '.php';
				$shippingClass = new $shippingName();
				$quote = $shippingClass->getQuote($addressData, $params);
				if ($quote)
				{
					$quoteData[$shippingName] = array(
						'title'			=> $quote['title'],
						'quote'			=> $quote['quote'],
						'ordering'		=> $quote['ordering'],
						'error'			=> $quote['error']
					);
				}
			}
			$session->set('shipping_methods', $quoteData);
			if ($session->get('shipping_methods'))
			{
				$json['shipping_methods'] = $session->get('shipping_methods');
			}
			else
			{
				$json['error']['warning'] = JText::_('ESHOP_NO_SHIPPING_METHODS');
			}
		}
		echo json_encode($json);
		exit();
	}
	
	/**
	 * 
	 * Function to get Zones for a specific Country
	 */
	function getZones()
	{
		$json = array();
		$countryId = JRequest::getInt('country_id');
		$countryInfo = EshopHelper::getCountry($countryId);
		if (is_object($countryInfo))
		{
			$json = array(
				'country_id'			=> $countryInfo->id,
				'country_name'			=> $countryInfo->country_name,
				'iso_code_2'			=> $countryInfo->iso_code_2,
				'iso_code_3'			=> $countryInfo->iso_code_3,
				'postcode_required'		=> $countryInfo->postcode_required,
				'zones'					=> EshopHelper::getCountryZones($countryId)
			);
		}
		echo json_encode($json);
		exit();
	}
}