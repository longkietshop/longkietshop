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
 *
 * @package Joomla
 * @subpackage EShop
 * @since 1.5
 */
class EShopViewCart extends EShopView
{

	function display($tpl = null)
	{
		switch ($this->getLayout())
		{
			case 'mini':
				$this->_displayMini($tpl);
				break;
			default:
				break;
		}
	}

	/**
	 *
	 * @param string $tpl        	
	 */
	function _displayMini($tpl = null)
	{
		//Get cart data
		$cart = new EshopCart();
		$items = $cart->getCartData();
		$countProducts = $cart->countProducts();
		$currency = new EshopCurrency();
		$tax = new EshopTax(EshopHelper::getConfig());
		
		$model = $this->getModel();
		$model->getCosts();
		$totalData = $model->getTotalData();
		$totalPrice = $currency->format($model->getTotal());
		
		$this->items = $items;
		$this->countProducts = $countProducts;
		$this->totalData = $totalData;
		$this->totalPrice = $totalPrice;
		$this->currency = $currency;
		$this->tax = $tax;
		parent::display($tpl);
	}	
}