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
class EShopViewCategory extends EShopView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$Itemid = JRequest::getInt('Itemid', 0);
		$category = $this->get('Category');
		if (!is_object($category))
		{
			// Requested category does not existed.
			$session = JFactory::getSession();
			$session->set('warning', JText::_('ESHOP_CATEGORY_DOES_NOT_EXIST'));
			$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else 
		{
			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::base().'components/com_eshop/assets/colorbox/colorbox.css');
			// Update hits for category
			EshopHelper::updateHits($category->id, 'categories');
			// Resized image
			$imageSizeFunction = EshopHelper::getConfigValue('image_size_function', 'resizeImage');
			if ($category->category_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$category->category_image))
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($category->category_image, JPATH_ROOT . '/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
			}
			else
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
			}
			$category->image = JURI::base() . 'media/com_eshop/categories/resized/' . $image;
			// Set title of the page
			$document->setTitle($category->category_name);
			// Set metakey and metadesc
			$metaKey = $category->meta_key;
			$metaDesc = $category->meta_desc;
			if ($metaKey)
			{
				$document->setMetaData('keywords', $metaKey);
			}
			if ($metaDesc)
			{
				$document->setMetaData('description', $metaDesc);
			}
			$subCategories = $this->get('SubCategories');
			for ($i = 0; $n = count($subCategories), $i < $n; $i++)
			{
				if ($subCategories[$i]->category_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$subCategories[$i]->category_image))
				{
					$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($subCategories[$i]->category_image, JPATH_ROOT . '/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
				}
				else
				{
					$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
					
				}
				$subCategories[$i]->image = JURI::base() . 'media/com_eshop/categories/resized/' . $image;
			}
			$products = $this->get('Data');
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
		
			//Sort options
			$sortOptions = EshopHelper::getConfigValue('sort_options');
			$sortOptions = explode(',', $sortOptions);
			$sortValues = array (
					'b.product_name-ASC',
					'b.product_name-DESC',
					'a.product_sku-ASC',
					'a.product_sku-DESC',
					'a.product_price-ASC',
					'a.product_price-DESC',
					'a.product_length-ASC',
					'a.product_length-DESC',
					'a.product_width-ASC',
					'a.product_width-DESC',
					'a.product_height-ASC',
					'a.product_height-DESC',
					'a.product_weight-ASC',
					'a.product_weight-DESC',
					'a.product_quantity-ASC',
					'a.product_quantity-DESC',
					'b.product_short_desc-ASC',
					'b.product_short_desc-DESC',
					'b.product_desc-ASC',
					'b.product_desc-DESC'
			);
			$sortTexts = array (
					JText::_('ESHOP_SORTING_PRODUCT_NAME_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_NAME_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_SKU_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_SKU_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_PRICE_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_PRICE_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_LENGTH_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_LENGTH_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_WIDTH_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_WIDTH_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_HEIGHT_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_HEIGHT_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_WEIGHT_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_WEIGHT_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_QUANTITY_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_QUANTITY_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_SHORT_DESC_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_SHORT_DESC_DESC'),
					JText::_('ESHOP_SORTING_PRODUCT_DESC_ASC'),
					JText::_('ESHOP_SORTING_PRODUCT_DESC_DESC')
			);
			$options = array();
			$options[] = JHtml::_('select.option', 'a.id-DESC', JText::_('ESHOP_SORTING_DEFAULT'));
			for ($i = 0; $i< count($sortValues); $i++)
			{
				if (in_array($sortValues[$i], $sortOptions))
				{
					$options[] = JHtml::_('select.option', $sortValues[$i], $sortTexts[$i]);
				}
			}
			if (count($options) > 1)
			{
				$this->sort_options = JHtml::_('select.genericlist', $options, 'sort_options', 'class="input-large" onchange="this.form.submit();" ', 'value', 'text', JRequest::getVar('sort_options',''));
			}
			else 
			{
				$this->sort_options = '';
			}
			
			$pagination = $this->get('Pagination');
			$tax = new EshopTax(EshopHelper::getConfig());
			$currency = new EshopCurrency();
			$this->category = $category;
			$this->subCategories = $subCategories;
			$this->products = $products;
			$this->pagination = $pagination;
			$this->tax = $tax;
			$this->currency = $currency;
			$this->Itemid = $Itemid;
			parent::display($tpl);
		}
	}
}