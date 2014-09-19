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
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopViewTheme extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$registry = new JRegistry();
		$registry->loadString($item->params);
		$data = new stdClass();
		$data->params = $registry->toArray();
		$form = JForm::getInstance('eshop', JPATH_ROOT . '/components/com_eshop/themes/' . $item->name . '/' . $item->name . '.xml', array(), false, '//config');
		$form->bind($data);
		$this->form = $form;
		return true;
	}
}