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
defined( '_JEXEC' ) or die();

/**
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewCustomer extends EShopView
{
	
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		if (EshopHelper::getConfigValue('catalog_mode'))
		{
			$session = JFactory::getSession();
			$session->set('warning', JText::_('ESHOP_CATALOG_MODE_ON'));
			$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else
		{
			$session = JFactory::getSession();
			$userInfor = $this->get('user');
			$user = JFactory::getUser();
			if($user->id)
			{
				$layout = $this->getLayout();
				if ($layout == 'account')
				{
					$this->_displayAccount($tpl);
					return;
				}
				elseif ($layout == 'orders')
				{
					$this->_displayOrders($tpl);
					return;
				}
				elseif ($layout == 'order')
				{
					$this->_displayOrder($tpl);
					return;
				}
				elseif ($layout == 'addresses')
				{
					$this->_displayAddresses($tpl);
					return ;
				}
				elseif ($layout == 'address')
				{
					$this->_displayAddress($tpl);
					return ;
				}
				else
				{
					$userInfor = $this->get('user');
					// Success message
					if ($session->get('success'))
					{
						$this->success = $session->get('success');
						$session->clear('success');
					}
					$this->user = $userInfor;
					parent::display($tpl);
				}
			}
			else
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_users&view=login'));
			}	
		}
	}

	/**
	 * 
	 * Function to display edit account page
	 * @param string $tpl
	 */
	function _displayAccount($tpl)
	{
		$userInfor = $this->get('user');
		if($userInfor)
		{
			$selected = $userInfor->customergroup_id;
		}
		else 
		{
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
		
		// Get login user infor
		$query->clear();
		$query->select('*')
			  ->from('#__users')
			  ->where('id='.(int)JFactory::getUser()->id)
		;
		$db->setQuery($query);
		$rowUser = $db->loadObject();

		$this->rowUser = $rowUser;
		$this->user = $userInfor;
		$this->customergroup = $lists['customergroup_id'];
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Function to display list orders for user
	 * @param string $tpl
	 */
	function _displayOrders($tpl)
	{
		$tax = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();
		$orders = $this->get('Orders');
		for ($i = 0; $n = count($orders), $i < $n; $i++)
		{
			$orders[$i]->total = $currency->format($orders[$i]->total, $orders[$i]->currency_code, $orders[$i]->currency_exchanged_value);
		}
		$pagination = $this->get('Pagination');
		$this->pagination = $pagination;
		$this->tax		  = $tax;
		$this->orders     = $orders;	
		$this->currency     = $currency;
		// Warning message
		$session = JFactory::getSession();
		if ($session->get('warning'))
		{
			$this->warning = $session->get('warning');
			$session->clear('warning');
		}
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Function to display order information
	 * @param string $tpl
	 */
	function _displayOrder($tpl)
	{
		$orderId =JRequest::getInt('order_id');
		$user = JFactory::getUser();
	
		//Get order infor
		$orderInfor = EshopHelper::getOrder($orderId);
		if (!is_object($orderInfor) || (is_object($orderInfor) && $orderInfor->customer_id != $user->get('id')))
		{
			$mainframe = JFactory::getApplication();
			$session = JFactory::getSession();
			$session->set('warning', JText::_('ESHOP_ORDER_DOES_NOT_EXITS'));
			$mainframe->redirect(EshopRoute::getViewRoute('customer') . '&layout=orders');
		}
		else
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
	
			$orderProducts = EshopHelper::getOrderProducts($orderId);
			for ($i = 0; $n = count($orderProducts), $i < $n; $i++)
			{
				$query->clear();
				$query->select('*')
					->from('#__eshop_orderoptions')
					->where('order_product_id = ' . intval($orderProducts[$i]->id));
				$db->setQuery($query);
				$orderProducts[$i]->options = $db->loadObjectList();
			}
			$orderTotals   = EshopHelper::getOrderTotals($orderId);
	
			$this->orderProducts = $orderProducts;
			$this->orderInfor   = $orderInfor;
			$this->orderTotals   = $orderTotals;
			$this->tax		  = $tax;
			$this->currency     = $currency;
			parent::display($tpl);
		}
	}
	
	/**
	 * 
	 * Function to display addresses for user
	 * @param string $tpl
	 */
	function _displayAddresses($tpl)
	{
		$addresses = $this->get('addresses');
		$this->addresses = $addresses;
		// Warning message
		$session = JFactory::getSession();
		if ($session->get('success'))
		{
			$this->success = $session->get('success');
			$session->clear('success');
		}
		if ($session->get('warning'))
		{
			$this->warning = $session->get('warning');
			$session->clear('warning');
		}
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Function to display address form
	 * @param string $tpl
	 */
	function _displayAddress($tpl)
	{
		$address = $this->get('address');
		$lists = array();
		if (is_object($address))
		{
			$this->_getCountryList($lists, $address->country_id);
			$this->_getZoneList($lists, $address->zone_id, $address->country_id);
			(EshopHelper::getDefaultAddressId($address->id) == $address->id) ? $isDefault = 1 : $isDefault = 0;
		}
		else 
		{
			$this->_getCountryList($lists);
			$this->_getZoneList($lists);
			$isDefault = 0;
		}
		$lists['default_address'] =  JHTML::_('select.booleanlist', 'default_address', ' class="inputbox" ', $isDefault) ;
		
		$this->address = $address;
		$this->lists = $lists;
		parent::display($tpl);
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