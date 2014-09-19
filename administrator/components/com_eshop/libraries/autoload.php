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
defined( '_JEXEC' ) or die();

$librariesPath = dirname(__FILE__);
$classes = array(
	'EShopViewList'		=> $librariesPath.'/mvc/viewlist.php',
	'EShopViewForm'		=> $librariesPath.'/mvc/viewform.php',
	'EShopModelList'		=> $librariesPath.'/mvc/modellist.php',				
	'EShopModel'			=> $librariesPath.'/mvc/model.php',
	'EShopController'		=> $librariesPath.'/mvc/controller.php',
	'EShopTable'			=> $librariesPath.'/mvc/table.php',
	'EShopView'			=> $librariesPath.'/mvc/view.php',
	'EshopCart'			=> JPATH_ROOT.'/components/com_eshop/helpers/cart.php',
	'EshopCoupon'		=> JPATH_ROOT.'/components/com_eshop/helpers/coupon.php',
	'EshopCurrency'		=> JPATH_ROOT.'/components/com_eshop/helpers/currency.php',
	'EshopCustomer'		=> JPATH_ROOT.'/components/com_eshop/helpers/customer.php',
	'EshopHelper'		=> JPATH_ROOT.'/components/com_eshop/helpers/helper.php',
	'EshopHtmlHelper'	=> JPATH_ROOT.'/components/com_eshop/helpers/html.php',
	'EshopImage'		=> JPATH_ROOT.'/components/com_eshop/helpers/image.php',
	'EshopLength'		=> JPATH_ROOT.'/components/com_eshop/helpers/length.php',
	'EshopOption'		=> JPATH_ROOT.'/components/com_eshop/helpers/option.php',
	'EshopRoute'		=> JPATH_ROOT.'/components/com_eshop/helpers/route.php',
	'EshopShipping'		=> JPATH_ROOT.'/components/com_eshop/helpers/shipping.php',
	'EshopTax'			=> JPATH_ROOT.'/components/com_eshop/helpers/tax.php',
	'EshopWeight'		=> JPATH_ROOT.'/components/com_eshop/helpers/weight.php',
	'os_payments'		=> JPATH_ROOT . '/components/com_eshop/plugins/payment/os_payments.php',
	'os_payment'		=> JPATH_ROOT . '/components/com_eshop/plugins/payment/os_payment.php',
	'eshop_shipping'	=> JPATH_ROOT . '/components/com_eshop/plugins/shipping/eshop_shipping.php'
);

foreach ($classes as $className => $path) {
	JLoader::register($className, $path);
}