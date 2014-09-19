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
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelCustomer extends EShopModel
{
	function __construct($config)
	{
		parent::__construct($config);
	}
	
	/**
	 * Load the data
	 *
	 */
	public function _loadData()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.name, b.username')
			->from($this->_tableName . ' AS a')
			->leftJoin('#__users AS b ON (a.customer_id = b.id)')
			->where('a.id = ' . intval($this->_id));
		$db->setQuery($query);
		$row = $db->loadObject();
		$this->_data = $row;
	}
	
	/**
	 * Init Category data
	 *
	 */
	public function _initData()
	{
		$db = $this->getDbo();
		$row = new EShopTable($this->_tableName, 'id', $db);
		$this->_data = $row;
	}
	
	/**
	 * Function to store product
	 * @see EShopModel::store()
	 */
	function store(&$data)
	{
		if (!$data['id'] && $data['username'] && $data['password'])
		{
			// Store this account into the system
			jimport('joomla.user.helper');
			$params = JComponentHelper::getParams('com_users');
			$newUserType = $params->get('new_usertype', 2);

			$data['groups'] = array();
			$data['groups'][] = $newUserType;
			$data['block'] = 0;
			$data['name'] = $data['firstname'] . ' ' . $data['lastname'];
			$data['password1'] = $data['password2'] = $data['password'];
			$data['email1'] = $data['email2'] = $data['email'];
			$user = new JUser();
			$user->bind($data);
			if (!$user->save())
			{
				JFactory::getApplication()->redirect('index.php?option=com_eshop&view=customers', $user->getError(), 'error');
			}
			$data['customer_id'] = $user->id;
		}
		// Check and store address
		$address = $data['address'];
		$addressId = 0;
		for ($i = 1; $n = count($address), $i <= $n; $i++)
		{
			$row = JTable::getInstance('Eshop', 'Address');
			$row->bind($address[$i]);
			$row->customer_id = $data['customer_id'];
			$row->created_date = JFactory::getDate()->toSql();
			$row->modified_date = JFactory::getDate()->toSql();
			$row->store();
			if ($i == 1)
			{
				$addressId = $row->id; 
			}
		}
		$data['address_id'] = $addressId;
		parent::store($data);
		return true;
	}
}