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
class EshopViewCoupon extends EShopViewForm
{
	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$nullDate = $db->getNullDate();
		$options = array();
		$options[] = JHtml::_('select.option', 'P', JText::_('ESHOP_PERCENTAGE'));
		$options[] = JHtml::_('select.option', 'F', JText::_('ESHOP_FIXED_AMOUNT'));
		$lists['coupon_type'] = JHtml::_('select.genericlist', $options, 'coupon_type', ' class="inputbox" ', 'value', 'text', $item->coupon_type);
		//Get multiple products
		$query->select('a.id AS value, b.product_name AS text')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.published = 1')
			->order('b.product_name');
		$db->setQuery($query);
		$products = $db->loadObjectList();
		$query->clear();
		$query->select('product_id')
			->from('#__eshop_couponproducts')
			->where('coupon_id = ' . intval($item->id));
		$db->setQuery($query);
		$productIds = $db->loadObjectList();
		$productArr = array();
		for ($i = 0; $i < count($productIds); $i++)
		{
			$productArr[] = $productIds[$i]->product_id;
		}
		$lists['product_id'] = JHtml::_('select.genericlist', $products, 'procuct_id[]', 'multiple', 'value', 'text', $productArr);
		$lists['coupon_shipping'] = JHtml::_('select.booleanlist', 'coupon_shipping', ' class="inputbox" ', $item->coupon_shipping);
		//Get history coupon
		$query->clear();
		$query->select('*')
			->from('#__eshop_couponhistory')
			->where('coupon_id = ' . intval($item->id));
		$db->setQuery($query);
		$couponHistories = $db->loadObjectList();
		$this->lists = $lists;
		$this->couponHistories = $couponHistories;
		$this->nullDate = $nullDate;
	}
}