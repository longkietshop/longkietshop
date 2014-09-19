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
$thumbnailWidth 	= $params->get('image_width', 80);
$thumbnailHeight	= $params->get('image_height', 80);
$manufacturersTotal = $params->get('manufacturers_total', 8);
$slideWidth = $params->get('slide_width', 680);
$items = modEshopManufacturerHelper::getItems($params);
//Resize manufacturer images
for ($i = 0; $n = count($items), $i < $n; $i++)
{
	$item = $items[$i];
	// Image
	if ($item->manufacturer_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/manufacturers/' . $item->manufacturer_image))
	{
		$image = EshopHelper::resizeImage($item->manufacturer_image, JPATH_ROOT . '/media/com_eshop/manufacturers/', $thumbnailWidth, $thumbnailHeight);
	}
	else
	{
		$image = EshopHelper::resizeImage('no-image.png', JPATH_ROOT . '/media/com_eshop/manufacturers/', $thumbnailWidth, $thumbnailHeight);
	}
	$items[$i]->image = JURI::base() . 'media/com_eshop/manufacturers/resized/' . $image;
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

$document = JFactory::getDocument();
$document->addScript(JURI::root().'modules/mod_eshop_manufacturer/admin/jquery.jcarousel.min.js');
$document->addStyleSheet(JURI::root().'modules/mod_eshop_manufacturer/admin/skin.css');
require(JModuleHelper::getLayoutPath('mod_eshop_manufacturer'));
?>