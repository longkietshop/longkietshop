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
 *
 * @package Joomla
 * @subpackage EShop
 * @since 1.5
 */
class EShopViewCheckout extends EShopView
{

	function display($tpl = null)
	{
		$cart = new EshopCart();
		$user = JFactory::getUser();
		$this->user = $user;
		$this->shipping_required = $cart->hasShipping();
		switch ($this->getLayout())
		{
			case 'login':
				$this->_displayLogin($tpl);
				break;
			case 'guest':
				$this->_displayGuest($tpl);
				break;
			case 'register':
				$this->_displayRegister($tpl);
				break;
			case 'payment_address':
				$this->_displayPaymentAddress($tpl);
				break;
			case 'shipping_address':
				$this->_displayShippingAddress($tpl);
				break;
			case 'guest_shipping':
				$this->_displayGuestShipping($tpl);
				break;	
			case 'shipping_method':
				$this->_displayShippingMethod($tpl);
				break;
			case 'payment_method':
				$this->_displayPaymentMethod($tpl);
				break;
			case 'confirm':
				$this->_displayConfirm($tpl);
				break;
			default:
				break;
		}
	}

	/**
	 *
	 * @param string $tpl        	
	 */
	function _displayLogin($tpl = null)
	{
		parent::display($tpl);
	}

	/**
	 * 
	 * Function to display Guest layout
	 * @param string $tpl        	
	 */
	function _displayGuest($tpl = null)
	{
		$lists = array();
		$session = JFactory::getSession();
		$guest = $session->get('guest');
		$this->_getCustomerGroupList($lists, isset($guest['customergroup_id']) ? $guest['customergroup_id'] : '');
		$this->_getCountryList($lists, $session->get('payment_country_id'));
		$this->_getZoneList($lists, $session->get('payment_zone_id'), $session->get('payment_country_id'));
		$this->lists = $lists;
		if (is_array($guest))
		{
			$this->firstname = isset($guest['firstname']) ? $guest['firstname'] : '';
			$this->lastname = isset($guest['lastname']) ? $guest['lastname'] : '';
			$this->email = isset($guest['email']) ? $guest['email'] : '';
			$this->telephone = isset($guest['telephone']) ? $guest['telephone'] : '';
			$this->fax = isset($guest['fax']) ? $guest['fax'] : '';
			if (isset($guest['payment']))
			{
				$payment = $guest['payment'];
				$this->company = isset($payment['company']) ? $payment['company'] : '';
				$this->company_id = isset($payment['company_id']) ? $payment['company_id'] : '';
				$this->address_1 = isset($payment['address_1']) ? $payment['address_1'] : '';
				$this->address_2 = isset($payment['address_2']) ? $payment['address_2'] : '';
				$this->city = isset($payment['city']) ? $payment['city'] : '';
				$this->postcode = isset($payment['postcode']) ? $payment['postcode'] : '';
			}
		}
		$this->payment_zone_id = $session->get('payment_zone_id');
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Register layout
	 * @param string $tpl        	
	 */
	function _displayRegister($tpl = null)
	{
		$lists = array();
		$session = JFactory::getSession();
		$this->_getCustomerGroupList($lists);
		$this->_getCountryList($lists, $session->get('payment_country_id'));
		$this->_getZoneList($lists, $session->get('payment_zone_id'), $session->get('payment_country_id'));
		$this->lists = $lists;
		$this->payment_zone_id = $session->get('payment_zone_id');
		$accountTerms = EshopHelper::getConfigValue('account_terms');
		if ($accountTerms)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__content')
				->where('id = ' . intval($accountTerms));
			$db->setQuery($query);
			$article = $db->loadObject();
			if (is_object($article))
			{
				$catId = $article->catid;
				require_once JPATH_ROOT.'/components/com_content/helpers/route.php';
				$accountTermsLink = ContentHelperRoute::getArticleRoute($accountTerms, $catId).'&tmpl=component&format=html';
			}
			else 
			{
				$accountTermsLink = '';
			}
			$this->accountTermsLink = $accountTermsLink;
		}
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Payment Address layout
	 * @param string $tpl
	 */
	function _displayPaymentAddress($tpl = null)
	{
		$lists = array();
		$session = JFactory::getSession();
		$this->_getAddressList($lists, $session->get('payment_address_id'));
		$this->_getCountryList($lists, $session->get('payment_country_id'));
		$this->_getZoneList($lists, $session->get('payment_zone_id'), $session->get('payment_country_id'));
		$this->lists = $lists;
		$this->payment_zone_id = $session->get('payment_zone_id');
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Shipping Address layout
	 * @param string $tpl        	
	 */
	function _displayShippingAddress($tpl = null)
	{
		$lists = array();
		$session = JFactory::getSession();
		$this->_getAddressList($lists, $session->get('shipping_address_id'));
		$this->_getCountryList($lists, $session->get('shipping_country_id'));
		$this->_getZoneList($lists, $session->get('shipping_zone_id'), $session->get('shipping_country_id'));
		$this->lists = $lists;
		$this->shipping_zone_id = $session->get('shipping_zone_id');
		parent::display($tpl);
	}
	
	/**
	 *
	 * Function to display Guest Shipping layout
	 * @param string $tpl
	 */
	function _displayGuestShipping($tpl = null)
	{
		$session = JFactory::getSession();
		$guest = $session->get('guest');
		$lists = array();
		$this->_getCountryList($lists, $session->get('shipping_country_id'));
		$this->_getZoneList($lists, $session->get('shipping_zone_id'), $session->get('shipping_country_id'));
		if (is_array($guest))
		{
			if (isset($guest['shipping']))
			{
				$shipping = $guest['shipping'];
				$this->firstname = isset($shipping['firstname']) ? $shipping['firstname'] : '';
				$this->lastname = isset($shipping['lastname']) ? $shipping['lastname'] : '';
				$this->company = isset($shipping['company']) ? $shipping['company'] : '';
				$this->company_id = isset($shipping['company_id']) ? $shipping['company_id'] : '';
				$this->address_1 = isset($shipping['address_1']) ? $shipping['address_1'] : '';
				$this->address_2 = isset($shipping['address_2']) ? $shipping['address_2'] : '';
				$this->city = isset($shipping['city']) ? $shipping['city'] : '';
				$this->postcode = isset($shipping['postcode']) ? $shipping['postcode'] : '';
			}
		}
		$this->shipping_zone_id = $session->get('shipping_zone_id');
		$this->lists = $lists;
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Shipping Method layout
	 * @param string $tpl        	
	 */
	function _displayShippingMethod($tpl = null)
	{
		$session = JFactory::getSession();
		$user = JFactory::getUser();
		if ($user->get('id') && $session->get('shipping_address_id'))
		{
			//User Shipping
			$addressInfo = EshopHelper::getAddress($session->get('shipping_address_id'));
		}
		else
		{
			//Guest Shipping
			$guest = $session->get('guest');
			$addressInfo = $guest['shipping'];
		}
		$addressData = array(
			'firstname'			=> $addressInfo['firstname'],
			'lastname'			=> $addressInfo['lastname'],
			'company'			=> $addressInfo['company'],
			'address_1'			=> $addressInfo['address_1'],
			'address_2'			=> $addressInfo['address_2'],
			'postcode'			=> $addressInfo['postcode'],
			'city'				=> $addressInfo['city'],
			'zone_id'			=> $addressInfo['zone_id'],
			'zone_name'			=> $addressInfo['zone_name'],
			'zone_code'			=> $addressInfo['zone_code'],
			'country_id'		=> $addressInfo['country_id'],
			'country_name'		=> $addressInfo['country_name'],
			'iso_code_2'		=> $addressInfo['iso_code_2'],
			'iso_code_3'		=> $addressInfo['iso_code_3']
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
			$this->shipping_methods = $session->get('shipping_methods');
		}
		$shippingMethod = $session->get('shipping_method');
		if (is_array($shippingMethod))
		{
			$this->shipping_method = $shippingMethod['name'];
		}
		else
		{
			$this->shipping_method = '';
		}
		$this->comment = $session->get('comment') ? $session->get('comment') : '';
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Payment Method layout
	 * @param string $tpl        	
	 */
	function _displayPaymentMethod($tpl = null)
	{
		$session = JFactory::getSession();
		$paymentMethod = JRequest::getVar('payment_method', os_payments::getDefautPaymentMethod(), 'post');
		if (!$paymentMethod)
			$paymentMethod = os_payments::getDefautPaymentMethod();
		$this->comment = $session->get('comment') ? $session->get('comment') : '';
		$this->methods = os_payments::getPaymentMethods();
		$this->paymentMethod = $paymentMethod;
		$checkoutTerms = EshopHelper::getConfigValue('checkout_terms');
		if ($checkoutTerms)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from('#__content')
			->where('id = ' . intval($checkoutTerms));
			$db->setQuery($query);
			$article = $db->loadObject();
			if (is_object($article))
			{
				$catId = $article->catid;
				require_once JPATH_ROOT.'/components/com_content/helpers/route.php';
				$checkoutTermsLink = ContentHelperRoute::getArticleRoute($checkoutTerms, $catId).'&tmpl=component&format=html';
			}
			else
			{
				$checkoutTermsLink = '';
			}
			$this->checkoutTermsLink = $checkoutTermsLink;
		}
		$this->checkout_terms_agree = $session->get('checkout_terms_agree');
		parent::display($tpl);
	}

	/**
	 *
	 * Function to display Confirm layout
	 * @param string $tpl        	
	 */
	function _displayConfirm($tpl = null)
	{
		// Get information for the order
		$session = JFactory::getSession();
		$tax = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();
		$cartData = $this->get('CartData');
		$model = $this->getModel();
		$model->getCosts();
		$totalData = $model->getTotalData();
		$total = $model->getTotal();
		$taxes = $model->getTaxes();
		$this->cartData = $cartData;
		$this->totalData = $totalData;
		$this->total = $total;
		$this->taxes = $taxes;
		$this->tax = $tax;
		$this->currency = $currency;
		// Payment method
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$paymentMethod = $session->get('payment_method');
		require_once JPATH_COMPONENT . '/plugins/payment/' . $paymentMethod . '.php';
		$query->select('params')
			->from('#__eshop_payments')
			->where('name = "' . $paymentMethod . '"');
		$db->setQuery($query);
		$plugin = $db->loadObject();
		$params = new JRegistry($plugin->params);
		$paymentClass = new $paymentMethod($params);
		$this->paymentClass = $paymentClass;
		parent::display($tpl);
	}

	/**
	 * 
	 * Private method to get Customer Group List
	 * @param array $lists
	 */
	function _getCustomerGroupList(&$lists, $selected = '')
	{
		if (!$selected) {
			$selected = EshopHelper::getConfigValue('customergroup_id');
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$customerGroupDisplay = EshopHelper::getConfigValue('customer_group_display');
		$query->select('a.id, b.customergroup_name AS name')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JFactory::getLanguage()->getTag() . '"');
		if ($customerGroupDisplay != '')
			$query->where('a.id IN (' . $customerGroupDisplay . ')');
		$query->order('b.customergroup_name');
		$db->setQuery($query);
		$lists['customergroup_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'customergroup_id', ' class="inputbox" ', 'id', 'name', $selected);
	}
	
	/**
	 * 
	 * Function to get Address List
	 * @param array $lists
	 * @param int $selected
	 */
	function _getAddressList(&$lists, $selected = '')
	{
		//Get address list
		$user = JFactory::getUser();
		if ($user->get('id')) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('a.id, CONCAT(a.firstname, " ", a.lastname, ", ", a.address_1, ", ", a.city, ", ", z.zone_name, ", ", c.country_name) AS name')
				->from('#__eshop_addresses AS a')
				->leftJoin('#__eshop_zones AS z ON (a.zone_id = z.id)')
				->leftJoin('#__eshop_countries AS c ON (a.country_id = c.id)')
				->where('customer_id = ' . (int) $user->get('id'));
			$db->setQuery($query);
			$addresses = $db->loadObjectList();
			if (count($addresses))
			{
				if (count($addresses) == 1)
					$selected = $addresses[0]->id;
				$lists['address_id'] = JHtml::_('select.genericlist', $addresses, 'address_id', ' style="width: 100%; margin-bottom: 15px;" size="5" ', 'id', 'name', $selected);
			}
		}
	}
	
	/**
	 *
	 * Private method to get Country List
	 * @param array $lists
	 */
	function _getCountryList(&$lists, $selected = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, country_name AS name')
			->from('#__eshop_countries')
			->where('published = 1');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_PLEASE_SELECT'), 'id', 'name');
		$options = array_merge($options, $db->loadObjectList());
		$lists['country_id'] = JHtml::_('select.genericlist', $options, 'country_id', ' class="inputbox" ', 'id', 'name', $selected);
	}
	
	/**
	 *
	 * Private method to get Zone List
	 * @param array $lists
	 */
	function _getZoneList(&$lists, $selected = '', $countryId = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, zone_name')
			->from('#__eshop_zones')
			->where('country_id=' . (int) $countryId)
			->where('published = 1');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_PLEASE_SELECT'), 'id', 'zone_name');
		$options = array_merge($options, $db->loadObjectList());
		$lists['zone_id'] = JHtml::_('select.genericlist', $options, 'zone_id', ' class="inputbox" ', 'id', 'zone_name', $selected);
	}
}