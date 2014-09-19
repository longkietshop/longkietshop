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
class EShopControllerCheckout extends JControllerLegacy
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
	 * Function to login user
	 */	
	function login()
	{
		$model = $this->getModel('Checkout');
		$json = $model->login();
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to register user
	 */
	function register()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Checkout');
		$json = $model->register($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to guest
	 */
	function guest()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Checkout');
		$json = $model->guest($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to process guest shipping
	 */
	function processGuestShipping()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Checkout');
		$json = $model->processGuestShipping($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to process payment address
	 */
	function processPaymentAddress()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Checkout');
		$json = $model->processPaymentAddress($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to process shipping address
	 */
	function processShippingAddress()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Checkout');
		$json = $model->processShippingAddress($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to process shipping method
	 */
	function processShippingMethod()
	{
		$model = $this->getModel('Checkout');
		$json = $model->processShippingMethod();
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to process payment method
	 */
	function processPaymentMethod()
	{
		$model = $this->getModel('Checkout');
		$json = $model->processPaymentMethod();
		echo json_encode($json);
		exit();
	}
	
	/**
	 * Function to process order
	 */
	function processOrder()
	{
		$model = $this->getModel('Checkout');
		$cart = new EshopCart();
		$user = JFactory::getUser();
		$session = JFactory::getSession();
		$return = '';
		if ($cart->hasShipping())
		{
			// Validate if shipping address is set
			if ($user->get('id') && $session->get('shipping_address_id'))
			{
				$shippingAddress = EshopHelper::getAddress($session->get('shipping_address_id'));
			}
			else
			{
				$guest = $session->get('guest');
				$shippingAddress = isset($guest['shipping']) ? $guest['shipping'] : '';
			}
			if (empty($shippingAddress))
			{
				$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
			}
			// Validate if shipping method is set
			if (!$session->get('shipping_method'))
			{
				$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
			}
		}
		else
		{
			$session->clear('shipping_method');
			$session->clear('shipping_methods');
		}
		
		// Validate if payment address has been set.
		if ($user->get('id') && $session->get('payment_address_id'))
		{
			$paymentAddress = EshopHelper::getAddress($session->get('payment_address_id'));
		}
		else
		{
			$guest = $session->get('guest');
			$paymentAddress = isset($guest['payment']) ? $guest['payment'] : '';
		}
		if (empty($paymentAddress))
		{
			$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
		}
		
		// Validate if payment method has been set
		if (!$session->get('payment_method'))
		{
			$return = JRoute::_(EshopRoute::getViewRoute('checkout'));
		}
		
		// Validate if cart has products
		if (!$cart->hasProducts())
		{
			$return = JRoute::_(EshopRoute::getViewRoute('cart'));
		}
		
		if (!$return)
		{
			// Get information for the order
			$cartData = $model->getCartData();
			$model->getCosts();
			$totalData = $model->getTotalData();
			$total = $model->getTotal();
			$taxes = $model->getTaxes();
				
			$data = JRequest::get('post');
			// Prepare customer data
			if ($user->get('id'))
			{
				$data['customer_id'] = $user->get('id');
				$data['email'] = $user->get('email');
				$customer = EshopHelper::getCustomer($user->get('id'));
				if (is_object($customer))
				{
					$data['customergroup_id'] = $customer->customergroup_id;
					$data['firstname'] = $customer->firstname;
					$data['lastname'] = $customer->lastname;
					$data['telephone'] = $customer->telephone;
					$data['fax'] = $customer->fax;
				}
				else
				{
					$data['customergroup_id'] = '';
					$data['firstname'] = '';
					$data['lastname'] = '';
					$data['telephone'] = '';
					$data['fax'] = '';
				}
				$paymentAddress = EshopHelper::getAddress($session->get('payment_address_id'));
			}
			else
			{
				$data['customer_id'] = 0;
				$data['customergroup_id'] = $guest['customergroup_id'];
				$data['firstname'] = $guest['firstname'];
				$data['lastname'] = $guest['lastname'];
				$data['email'] = $guest['email'];
				$data['telephone'] = $guest['telephone'];
				$data['fax'] = $guest['fax'];
		
				$guest = $session->get('guest');
				$paymentAddress = $guest['payment'];
			}
		
			// Prepare payment data
			$data['payment_firstname'] = $paymentAddress['firstname'];
			$data['payment_lastname'] = $paymentAddress['lastname'];
			$data['payment_company'] = $paymentAddress['company'];
			$data['payment_company_id'] = $paymentAddress['company_id'];
			$data['payment_address_1'] = $paymentAddress['address_1'];
			$data['payment_address_2'] = $paymentAddress['address_2'];
			$data['payment_city'] = $paymentAddress['city'];
			$data['payment_postcode'] = $paymentAddress['postcode'];
			$data['payment_zone_name'] = $paymentAddress['zone_name'];
			$data['payment_zone_id'] = $paymentAddress['zone_id'];
			$data['payment_country_name'] = $paymentAddress['country_name'];
			$data['payment_country_id'] = $paymentAddress['country_id'];
			$data['payment_method'] = $session->get('payment_method');
			$data['payment_method_title'] = EshopHelper::getPaymentTitle($data['payment_method']);
				
			// Prepare shipping data
			if ($cart->hasShipping())
			{
				if ($user->get('id')) {
					$shippingAddress = EshopHelper::getAddress($session->get('shipping_address_id'));
				}
				else
				{
					$guest = $session->get('guest');
					$shippingAddress = $guest['shipping'];
				}
				$data['shipping_firstname'] = $shippingAddress['firstname'];
				$data['shipping_lastname'] = $shippingAddress['lastname'];
				$data['shipping_company'] = $shippingAddress['company'];
				$data['shipping_company_id'] = $shippingAddress['company_id'];
				$data['shipping_address_1'] = $shippingAddress['address_1'];
				$data['shipping_address_2'] = $shippingAddress['address_2'];
				$data['shipping_city'] = $shippingAddress['city'];
				$data['shipping_postcode'] = $shippingAddress['postcode'];
				$data['shipping_zone_name'] = $shippingAddress['zone_name'];
				$data['shipping_zone_id'] = $shippingAddress['zone_id'];
				$data['shipping_country_name'] = $shippingAddress['country_name'];
				$data['shipping_country_id'] = $shippingAddress['country_id'];
				$shippingMethod = $session->get('shipping_method');
				if (is_array($shippingMethod))
				{
					$data['shipping_method'] = $shippingMethod['name'];
					$data['shipping_method_title'] = $shippingMethod['title'];
				}
				else
				{
					$data['shipping_method'] = '';
					$data['shipping_method_title'] = '';
				}
			}
			else
			{
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';
				$data['shipping_company'] = '';
				$data['shipping_company_id'] = '';
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone_name'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country_name'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_method'] = '';
				$data['shipping_method_title'] = '';
			}
			$data['totals'] = $totalData;
			$data['comment'] = $session->get('comment');
			$data['order_status_id'] = EshopHelper::getConfigValue('order_status_id');
			$data['language'] = JFactory::getLanguage()->getTag();
			$currency = new EshopCurrency();
			$data['currency_id'] = $currency->getCurrencyId();
			$data['currency_code'] = $currency->getCurrencyCode();
			$data['currency_exchanged_value'] = $currency->getExchangedValue();
			$data['total'] = $total;
			
			$model->processOrder($data);
		}
	}
	
	/**
	 * Function to verify payment
	 */
	function verifyPayment()
	{
		$model = $this->getModel('Checkout');
		$model->verifyPayment();
	}
}