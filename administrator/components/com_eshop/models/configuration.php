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
 * EShop Component Configuration Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelConfiguration extends JModelLegacy
{

	/**
	 * Containing all config data,  store in an object with key, value
	 *
	 * @var object
	 */
	var $_data = null;

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get configuration data
	 * @return object
	 */
	function getData()
	{
		if (empty($this->_data))
		{
			$config = new stdClass();
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('config_key, config_value')
				->from('#__eshop_configs');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			if (count($rows))
			{
				for ($i = 0, $n = count($rows); $i < $n; $i++)
				{
					$row = $rows[$i];
					$key = $row->config_key;
					$value = $row->config_value;
					$config->$key = stripslashes($value);
				}
			}
			$this->_data = $config;
		}
		
		return $this->_data;
	}

	/**
	 * Store the configuration data
	 *
	 * @param array $post
	 * @return Boolean
	 */
	function store($data)
	{
		$db = $this->getDbo();
		$db->truncateTable('#__eshop_configs');
		$row = & $this->getTable('Eshop', 'Config');
		foreach ($data as $key => $value)
		{
			$row->id = '';
			if (is_array($value))
				$value = implode(',', $value);
			$row->config_key = $key;
			$row->config_value = $value;
			$row->store();
		}
		//Update currencies
		EshopHelper::updateCurrencies(true);
		return true;
	}
}