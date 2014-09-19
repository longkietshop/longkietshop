<?php
/**
 * @version		1.0.4
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

// Include the helper functions only once
require_once JPATH_ROOT.'/components/com_eshop/helpers/helper.php';
require_once dirname(__FILE__).'/helper.php';
$currencies = modEshopCurrencyHelper::getCurrencies();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_eshop_currency/css/style.css');
$session = JFactory::getSession();
if ($session->get('currency_code'))
{
	$currencyCode = $session->get('currency_code');
}
elseif (JRequest::getVar('currency_code', '', 'COOKIE'))
{
	$currencyCode = JRequest::getVar('currency_code', '', 'COOKIE');
}
else 
{
	$currencyCode = EshopHelper::getConfigValue('default_currency_code');
}
// Load Bootstrap CSS and JS
if (EshopHelper::getConfigValue('load_bootstrap_css'))
{
	EshopHelper::loadBootstrapCss();
}
if (EshopHelper::getConfigValue('load_bootstrap_js'))
{
	EshopHelper::loadBootstrapJs();
}
require JModuleHelper::getLayoutPath('mod_eshop_currency', 'default');
