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

require_once (dirname(__FILE__).'/helper.php');
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
//get config xml
$headerText			= $params->get('header_text','');
$footerText         = $params->get('footer_text','');
$numberProductinrow = $params->get('number_product_per_row',6);
$showPrice			= $params->get('show_price',1);
$showAddcart        = $params->get('show_addtocart',1);
$layout 			= $params->get('layout','default');
$display_style 		= $params->get( 'display_style', "list" ); // Display Style
$showTooltip		= $params->get('show_tooltip',1);
$thumbnailWidth     = $params->get('image_width',100);
$thumbnailHeight    = $params->get('image_height',100);

$currency = new EshopCurrency();
$tax = new EshopTax(EshopHelper::getConfig());
$document = JFactory::getDocument();
$theme = EshopHelper::getConfigValue('theme');
if (JFile::exists(JPATH_ROOT.'/components/com_eshop/themes/' . $theme . '/css/style.css'))
{
	$document->addStyleSheet(JURI::base().'components/com_eshop/themes/' . $theme . '/css/style.css');
}
else 
{
	$document->addStyleSheet(JURI::base().'components/com_eshop/themes/default/css/style.css');
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
//Load javascript and css
$document->addScript(JURI::root().'components/com_eshop/assets/js/eshop.js');
$document->addScript(JURI::root().'components/com_eshop/assets/colorbox/jquery.colorbox.js');
$document->addStyleSheet(JURI::root().'components/com_eshop/assets/colorbox/colorbox.css');

//Load css module
if($showTooltip == 1){
	$document->addScriptDeclaration("
		(function($){
			$(document).ready(function() {
			    $('.link').tooltip();
			});
		})(jQuery);
	");
}

$items = modEshopProductHelper::getItems($params);
require(JModuleHelper::getLayoutPath('mod_eshop_product',$layout));
?>