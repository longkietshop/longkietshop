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
class EShopViewFrontpage extends EShopView
{		
	function display($tpl = null)
	{
		jimport('joomla.filesystem.file');
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base().'components/com_eshop/assets/colorbox/colorbox.css');
		$app	= JFactory::getApplication('site');
		$params = $app->getParams();
		$title = $params->get('page_title', '');
		if ($title == '')
			$title = JText::_('ESHOP_FRONT_PAGE');
		$document->setTitle($title);
		$categories = $this->get('Categories');
		// Resize categories images
		$imageSizeFunction = EshopHelper::getConfigValue('image_size_function', 'resizeImage');
		for ($i = 0; $n = count($categories), $i < $n; $i++)
		{
			if ($categories[$i]->category_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$categories[$i]->category_image))
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($categories[$i]->category_image, JPATH_ROOT.'/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
			}
			else
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT.'/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
			}
			$categories[$i]->image = JURI::base() . 'media/com_eshop/categories/resized/' . $image;
		}
		$products = $this->get('Products');
		// Resize products images
		for ($i = 0; $n = count($products), $i < $n; $i++)
		{
			if ($products[$i]->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/'.$products[$i]->product_image))
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($products[$i]->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_list_width'), EshopHelper::getConfigValue('image_list_height')));
			}
			else
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_list_width'), EshopHelper::getConfigValue('image_list_height')));
			}
			$products[$i]->image = JURI::base() . 'media/com_eshop/products/resized/' . $image;
		}
		$tax = new EshopTax(EshopHelper::getConfig());
		$currency = new EshopCurrency();
		$this->categories = $categories;
		$this->products = $products;
		$this->tax = $tax;
		$this->currency = $currency;
		parent::display($tpl);
	}
}