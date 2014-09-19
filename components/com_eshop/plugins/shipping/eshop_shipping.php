<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class eshop_shipping
{

	/**
	 * Name of shipping plugin
	 *
	 * @var string
	 */
	var $_name = null;
	
	/**
	 * Method to get shipping name
	 *
	 * @return string
	 */
	function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Method to set shipping name
	 *
	 * @param string $value
	 */
	function setName($value)
	{
		$this->_name = $value;
	}
	
	/**
	 * 
	 * Constructor function
	 */
	function eshop_shipping()
	{
		$this->loadLanguage();
	}
	
	/**
	 * Load language file for this payment plugin
	 */
	function loadLanguage()
	{
		$pluginName = $this->getName();
		$lang = JFactory::getLanguage();
		$tag = $lang->getTag();
		if (!$tag)
			$tag = 'en-GB';
		$lang->load('plg_'.$pluginName, JPATH_ROOT, $tag);
	}
}
?>