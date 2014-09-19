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
class EShopViewProducts extends EShopViewList
{
	public function display($tpl = null)
	{
		$this->currency = new EshopCurrency();
		parent::display($tpl);
	}
	/**
	 * Additional grid function for featured toggles
	 *
	 * @return string HTML code to write the toggle button
	 */
	function toggle($field, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix = '')
	{
		$img = $field ? $imgY : $imgX;
		$task = $field ? 'product.unfeatured' : 'product.featured';
		$alt = $field ? JText::_('ESHOP_PRODUCT_FEATURED') : JText::_('ESHOP_PRODUCT_UNFEATURED');
		$action = $field ? JText::_('ESHOP_PRODUCT_UNFEATURED') : JText::_('ESHOP_PRODUCT_FEATURED');
		return ('<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $action . '">' .
			 JHtml::_('image', 'admin/' . $img, $alt, null, true) . '</a>');
	}
}