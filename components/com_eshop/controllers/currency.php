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
class EShopControllerCurrency extends JControllerLegacy
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
	
	function change()
	{
		$session = JFactory::getSession();
		$currencyCode = JRequest::getVar('currency_code', null, 'POST');
		if (!$session->get('currency_code') || $session->get('currency_code') != $currencyCode)
		{
			$session->set('currency_code', $currencyCode);
		}
		if (!JRequest::getVar('currency_code', '', 'COOKIE') || JRequest::getVar('currency_code', '', 'COOKIE') != $currencyCode)
		{
			setcookie('currency_code', $currencyCode, time() + 60 * 60 * 24 * 30);
			JRequest::setVar('currency_code', $currencyCode, 'COOKIE');
		}
		$return = base64_decode(JRequest::getVar('return'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect($return);
	}
}