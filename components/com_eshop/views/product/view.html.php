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
class EShopViewProduct extends EShopView
{

	/**
	 * Display function
	 * @see JView::display()
	 */
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$document = JFactory::getDocument();
		$Itemid = JRequest::getInt('Itemid', 0);
		$item = $this->get('Data');
		$this->currency = new EshopCurrency();
		if (!is_object($item))
		{
			// Requested product does not existed.
			$session = JFactory::getSession();
			$session->set('warning', JText::_('ESHOP_PRODUCT_DOES_NOT_EXIST'));
			$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else 
		{
			$document->addStyleSheet(JURI::base().'components/com_eshop/assets/colorbox/colorbox.css');
			$productId = JRequest::getInt('id');
			// Update hits for product
			EshopHelper::updateHits($productId, 'products');
			// Set title of the page
			$document->setTitle($item->product_name);
			$imageSizeFunction = EshopHelper::getConfigValue('image_size_function', 'resizeImage');
			// Main image resize
			if ($item->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $item->product_image))
			{
				$thumbImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($item->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
				$popupImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($item->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
			}
			else
			{
				$thumbImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_thumb_width'), EshopHelper::getConfigValue('image_thumb_height')));
				$popupImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
			}
			$item->thumb_image = JURI::base() . 'media/com_eshop/products/resized/' . $thumbImage;
			$item->popup_image = JURI::base() . 'media/com_eshop/products/resized/' . $popupImage;
			// Set metakey and metadesc
			$metaKey = $item->meta_key;
			$metaDesc = $item->meta_desc;
			if ($metaKey)
			{
				$document->setMetaData('keywords', $metaKey);
			}
			if ($metaDesc)
			{
				$document->setMetaData('description', $metaDesc);
			}
			// Product availability
			if ($item->product_quantity <= 0)
			{
				$availability = EshopHelper::getStockStatusName(EshopHelper::getConfigValue('stock_status_id'), JFactory::getLanguage()->getTag());
			}
			elseif (EshopHelper::getConfigValue('stock_display'))
			{
				$availability = $item->product_quantity;
			}
			else
			{
				$availability = JText::_('ESHOP_IN_STOCK');
			}
			$item->availability = $availability;
			$item->product_desc = JHtml::_('content.prepare', $item->product_desc);
			// Get information related to this current product
			$productImages = EshopHelper::getProductImages($productId);
			// Additional images resize
			for ($i = 0; $n = count($productImages), $i < $n; $i++)
			{
				if ($productImages[$i]->image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $productImages[$i]->image))
				{
					$thumbImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($productImages[$i]->image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_additional_width'), EshopHelper::getConfigValue('image_additional_height')));
					$popupImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($productImages[$i]->image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
				}
				else
				{
					$thumbImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_additional_width'), EshopHelper::getConfigValue('image_additional_height')));
					$popupImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_popup_width'), EshopHelper::getConfigValue('image_popup_height')));
				}
				$productImages[$i]->thumb_image = JURI::base() . 'media/com_eshop/products/resized/' . $thumbImage;
				$productImages[$i]->popup_image = JURI::base() . 'media/com_eshop/products/resized/' . $popupImage;
			}
			$discountPrices = EshopHelper::getDiscountPrices($productId);
			$productOptions = EshopHelper::getProductOptions($productId, JFactory::getLanguage()->getTag());
			$optionValues = array();
			$hasSpecification = false;
			for ($i = 0; $n = count($productOptions), $i < $n; $i++)
			{
				$optionValues[] = EshopHelper::getProductOptionValues($productId, $productOptions[$i]->id);
			}
			$attributeGroups = EshopHelper::getAttributeGroups(JFactory::getLanguage()->getTag());
			$productAttributes = array();
			for ($i = 0; $n = count($attributeGroups), $i < $n; $i++)
			{
				$productAttributes[] = EshopHelper::getAttributes($productId, $attributeGroups[$i]->id, JFactory::getLanguage()->getTag());
				if (count($productAttributes[$i])) $hasSpecification = true;
			}
			$productRelations = EshopHelper::getProductRelations($productId, JFactory::getLanguage()->getTag());
			// Related products images resize
			for ($i = 0; $n = count($productRelations), $i < $n; $i++)
			{
				if ($productRelations[$i]->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $productRelations[$i]->product_image))
				{
					$thumbImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($productRelations[$i]->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_related_width'), EshopHelper::getConfigValue('image_related_height')));
				}
				else
				{
					$thumbImage = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_related_width'), EshopHelper::getConfigValue('image_related_height')));
				}
				$productRelations[$i]->thumb_image = JURI::base() . 'media/com_eshop/products/resized/' . $thumbImage;
			}
			if (EshopHelper::getConfigValue('allow_reviews'))
			{
				$productReviews = EshopHelper::getProductReviews($productId);
				$this->productReviews = $productReviews;
			}
			$tax = new EshopTax(EshopHelper::getConfig());
			if (EshopHelper::getConfigValue('social_enable'))
			{
				EshopHelper::loadShareScripts($item);
			}
			$this->item = $item;
			$this->productImages = $productImages;
			$this->discountPrices = $discountPrices;
			$this->productOptions = $productOptions;
			$this->optionValues = $optionValues;
			$this->hasSpecification = $hasSpecification;
			$this->attributeGroups = $attributeGroups;
			$this->productAttributes = $productAttributes;
			$this->productRelations = $productRelations;
			$this->manufacturer = EshopHelper::getProductManufacturer($productId, JFactory::getLanguage()->getTag());
			$this->tax = $tax;
			// Preparing rating html
			$ratingHtml = '<b>' . JText::_('ESHOP_BAD') . '</b>';
			for ($i = 1; $i <= 5; $i++)
			{
				$ratingHtml .= '<input type="radio" name="rating" value="' . $i . '" style="width: 25px;" />';
			}
			$ratingHtml .= '<b>' . JText::_('ESHOP_EXCELLENT') . '</b>';
			$this->ratingHtml = $ratingHtml;
			parent::display($tpl);
		}
	}
}