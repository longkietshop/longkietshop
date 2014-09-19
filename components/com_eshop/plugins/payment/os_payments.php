<?php
/**
 * @version		1.0.3
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class os_payments
{
    public static $methods;
	/**
	 * Get list of payment methods
	 *
	 * @return array
	 */
	public static function getPaymentMethods()
	{
		if (self::$methods == null)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__eshop_payments')
				->where('published = 1')
                ->order('ordering');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
			foreach ($rows as $row)
			{
				if (file_exists(JPATH_ROOT . '/components/com_eshop/plugins/payment/' . $row->name . '.php'))
				{
                    require_once JPATH_ROOT . '/components/com_eshop/plugins/payment/' . $row->name . '.php';
					$method = new $row->name(new JRegistry($row->params));
					$method->title = JText::_($row->title);
					self::$methods[] = $method;
				}
			}
		}

		return self::$methods;
	}

	/**
	 * Load information about the payment method
	 *
	 * @param string $name
	 * Name of the payment method
	 */
	public static function loadPaymentMethod($name)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from('#__eshop_payments')
			->where('name = "' . $name . '"');
		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 * Get default payment gateway
	 *
	 * @return string
	 */
	public static function getDefautPaymentMethod()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('name')
			->from('#__eshop_payments')
			->where('published = 1')
			->order('ordering');
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

	/**
	 * Get the payment method object based on it's name
	 *
	 * @param string $name        	
	 * @return object
	 */
	public static function getPaymentMethod($name)
	{
		$methods = self::getPaymentMethods();
		foreach ($methods as $method)
		{
			if ($method->getName() == $name)
			{
				return $method;
			}
		}
		return null;
	}
}
?>