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
class EShopControllerLanguage extends JControllerLegacy
{

	function save()
	{
		$model = $this->getModel('language');
		$data = JRequest::get('post', JREQUEST_ALLOWRAW);
		$model->save($data);
		$lang = $data['lang'];
		$item = $data['item'];
		$url = 'index.php?option=com_eshop&view=language&lang=' . $lang . '&item=' . $item;
		$msg = JText::_('ESHOP_TRANSLATION_SAVED');
		$this->setRedirect($url, $msg);
	}

	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=dashboard');
	}
}