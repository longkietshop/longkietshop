<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author		Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EShopModelCustomer extends EShopModel
{

	public function __construct($config = array())
	{
		parent::__construct();
	}
	
	/**
	 * 
	 * Function to get User
	 * @return user object
	 */
	function getUser()
	{
		$user = JFactory::getUser();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.password')
			  ->from('#__eshop_customers AS a')
			  ->innerJoin('#__users AS b on a.customer_id = b.id')
			  ->where('a.customer_id = '.(int)$user->id);
		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 * 
	 * Get list orders of current user
	 * @return orders object list
	 */
	function getOrders()
	{
		$user = JFactory::getUser();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.firstname, b.lastname')
			  ->from('#__eshop_orders AS a')
			  ->leftJoin('#__eshop_customers AS b ON (a.customer_id = b.customer_id)')
			  ->where('a.customer_id = '.(int)$user->id);
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 * 
	 * Function to process user
	 * @param array $data
	 * @return json array
	 */
	function processUser($data)
	{
		$session = JFactory::getSession();
		$json = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);	
		// Firstname validate
		if (utf8_strlen($data['firstname']) < 1 || utf8_strlen($data['firstname']) > 32)
		{
			$json['error']['firstname'] = JText::_('ESHOP_ERROR_FIRSTNAME');
		}
		else 
		{
			$query->select('COUNT(*)')
				->from('#__users')
				->where('username = "' . $data['firstname'] . '"');
			$db->setQuery($query);
			if ($db->loadResult())
			{
				$json['error']['username_existed'] = JText::_('ESHOP_ERROR_USERNAME_EXISTED');
			}				
		}
		
		// Lastname validate
		if (utf8_strlen($data['lastname']) < 1 || utf8_strlen($data['lastname']) > 32)
		{
			$json['error']['lastname'] = JText::_('ESHOP_ERROR_LASTNAME');
		}
		// Password validate
		if ($data['password1'] != '' || $data['password2'] != '')
		{
			// Confirm password validate
			if ($data['password1'] != $data['password2'])
			{
				$json['error']['confirm'] = JText::_('ESHOP_ERROR_CONFIRM_PASSWORD');
			}
		}
		// Email validate
		if ((utf8_strlen($data['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $data['email']))
		{
			$json['error']['email'] = JText::_('ESHOP_ERROR_EMAIL');
		}
		// Telephone validate
		if ((utf8_strlen($data['telephone']) < 3) || (utf8_strlen($data['telephone']) > 32))
		{
			$json['error']['telephone'] = JText::_('ESHOP_ERROR_TELEPHONE');
		}
		if (!$json)
		{
			//update user customer
			$row = JTable::getInstance('Eshop', 'Customer');
			if ($data['id']) 
			{		
				$row->load($data['id']);
			}						
			if (!$row->bind($data)) 
			{
				$json['error']['warning'] = JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $this->setError($this->_db->getErrorMsg()));
			}
			//update user 
			$query->clear();
			$password = $data['password1'];
			$query->update('#__users')
				  ->set('name='.$db->quote($data['firstname'].' '.$data['lastname']))
				  ->set('email='.$db->quote($data['email']));
			if($password != '')
			{
				$salt = JUserHelper::genRandomPassword(32);
				$crypt = JUserHelper::getCryptedPassword($password,$salt);
				$password = $crypt . ':' . $salt;
				$query->set('password='.$db->quote($password));
			}
			$query->where('id='.(int)JFactory::getUser()->id);
			$db->setQuery($query);
			$db->query();
			
			$row->customer_id = JFactory::getUser()->id;
			$row->address_id = 0;
			$row->published = 1;
			$row->created_date = JFactory::getDate()->toUnix();
			$row->modified_date = JFactory::getDate()->toUnix();
			if ($row->store()) {
				$session->set('success', JText::_('ESHOP_SAVE_USER_SUCCESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer'));
			}
		}
		return $json;
	}
	
	
	/**
	 * 
	 * Function to get addresses object list for user
	 * @return object list
	 */
	function getAddresses()
	{
		$user = JFactory::getUser();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.country_name, c.zone_name')
			->from('#__eshop_addresses AS a')
			->leftJoin('#__eshop_countries AS b ON (a.country_id = b.id)')
			->leftJoin('#__eshop_zones AS c ON (a.zone_id = c.id)')
			->where('a.customer_id = ' . (int)$user->get('id'));
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 * 
	 * Function to get address detail
	 * @return address object
	 */
	function getAddress()
	{
		$user = JFactory::getUser();
		$id   =  JRequest::getInt('aid');
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_addresses')
			->where('customer_id = ' . (int)$user->get('id'))
			->where('id='.(int)$id);
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	/**
	 *
	 * Function to process address
	 * @param array $data
	 * @return json array
	 */
	function processAddress($data)
	{
		$session = JFactory::getSession();
		$json = array();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Firstname validate
		if (utf8_strlen($data['firstname']) < 1 || utf8_strlen($data['firstname']) > 32)
		{
			$json['error']['firstname'] = JText::_('ESHOP_ERROR_FIRSTNAME');
		}
	
		// Lastname validate
		if (utf8_strlen($data['lastname']) < 1 || utf8_strlen($data['lastname']) > 32)
		{
			$json['error']['lastname'] = JText::_('ESHOP_ERROR_LASTNAME');
		}
		// Address validate
		if (utf8_strlen($data['address_1']) < 3 || utf8_strlen($data['address_1']) > 128)
		{
			$json['error']['address_1'] = JText::_('ESHOP_ERROR_ADDRESS_1');
		}
		// City validate
		if (utf8_strlen($data['city']) < 2 || utf8_strlen($data['city']) > 128)
		{
			$json['error']['city'] = JText::_('ESHOP_ERROR_CITY');
		}
		// Country validate
		if (!$data['country_id'])
		{
			$json['error']['country_id'] = JText::_('ESHOP_ERROR_COUNTRY');
		}
		// Zone validate
		if (!$data['zone_id'])
		{
			$json['error']['zone_id'] = JText::_('ESHOP_ERROR_ZONE');
		}
	
		if (!$json)
		{
			$user = JFactory::getUser();
			//update user customer
			$row = JTable::getInstance('Eshop', 'Address');
			$row->bind($data);
			$row->customer_id = $user->get('id');
			$row->created_date = JFactory::getDate()->toSql();
			$row->modified_date = JFactory::getDate()->toSql();
			if (!$row->bind($data))
			{
				$json['error']['warning'] = JText::sprintf('ESHOP_ADDRESS_BIND_FAILED', $this->setError($this->_db->getErrorMsg()));
			}
				
			if ($row->store())
			{
				$addressId = $row->id;
				if($data['default_address'] != 0)
				{
					(!$data['id']) ? $addressId : $data['id'];
					$query->update('#__eshop_customers')
					->set('address_id='.(int)$addressId)
					->set('firstname='.$db->quote($data['firstname']))
					->set('lastname='.$db->quote($data['lastname']))
					;
					$db->setQuery($query);
					$db->query();
				}
	
				if(EshopHelper::countAddress() == 0)
				{
					// Store customer
					$row = JTable::getInstance('Eshop', 'Customer');
					$row->bind($data);
					$row->customer_id = $user->get('id');
					$customerGroupId = EshopHelper::getConfigValue('customergroup_id');
					$customerGroupDisplay = EshopHelper::getConfigValue('customer_group_display');
					if ($customerGroupDisplay != '')
					{
						$customerGroupDisplay = explode(',', $customerGroupDisplay);
						if (JRequest::getVar('customergroup_id') && in_array(JRequest::getVar('customergroup_id'), $customerGroupDisplay))
						{
							$customerGroupId = JRequest::getVar('customergroup_id');
						}
					}
					$row->customergroup_id = $customerGroupId;
					$row->address_id = $addressId;
					$row->published = 1;
					$row->created_date = JFactory::getDate()->toSql();
					$row->modified_date = JFactory::getDate()->toSql();
					$row->store();
				}
	
				$session->set('success', JText::_('ESHOP_SAVE_ADDRESS_SUCCESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses');
			}
		}
		return $json;
	}
	
	/**
	 * 
	 * Function to delete an address
	 * @param int $id
	 * @return json array
	 */
	function deleteAddress($id)
	{
		$session = JFactory::getSession();
		$json = array();
		if ($id)
		{
			if(EshopHelper::getDefaultAddressId($id) == $id)
			{
				$session->set('warning', JText::_('ESHOP_CAN_NOT_REMOVE_ADDRESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses');
			}
			else
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				$query->delete('#__eshop_addresses')
					->where('id='.(int)$id);
				$db->setQuery($query);
				$db->query();
				$session->set('success', JText::_('ESHOP_REMOVE_ADDRESS_SUCCESS'));
				$json['return'] = JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses');
			}
		}
		return $json;
	}
	
}