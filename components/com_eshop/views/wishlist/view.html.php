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

/**
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewWishlist extends EShopView
{		
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$Itemid = JRequest::getInt('Itemid', 0);
		if (EshopHelper::getConfigValue('catalog_mode'))
		{
			$session = JFactory::getSession();
			$session->set('warning', JText::_('ESHOP_CATALOG_MODE_ON'));
			$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else 
		{
			$user = JFactory::getUser();
			if (!$user->get('id'))
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_users&view=login'));
			}
			else
			{
				$document = JFactory::getDocument();
				$document->addStyleSheet(JURI::base().'components/com_eshop/assets/colorbox/colorbox.css');
				$session = JFactory::getSession();
				$wishlist = ($session->get('wishlist') ? $session->get('wishlist') : array());
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('a.*, b.product_name')
					->from('#__eshop_products AS a')
					->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
					->where('a.id IN (' . (count($wishlist) ? implode(',', $wishlist) : '""') . ')')
					->where('a.published = 1')
					->where('b.language = "' . JFactory::getLanguage()->getTag() . '"');
				$db->setQuery($query);
				$products = $db->loadObjectList();
				for ($i = 0; $n = count($products), $i < $n; $i++)
				{
					// Resize wishlist images
					$imageSizeFunction = EshopHelper::getConfigValue('image_size_function', 'resizeImage');
					if ($products[$i]->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/'.$products[$i]->product_image))
					{
						$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($products[$i]->product_image, JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_wishlist_width'), EshopHelper::getConfigValue('image_wishlist_height')));
					}
					else
					{
						$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', EshopHelper::getConfigValue('image_wishlist_width'), EshopHelper::getConfigValue('image_wishlist_height')));
					}
					$products[$i]->image = JURI::base() . 'media/com_eshop/products/resized/' . $image;
					// Product availability
					if ($products[$i]->product_quantity <= 0)
					{
						$availability = EshopHelper::getStockStatusName(EshopHelper::getConfigValue('stock_status_id'), JFactory::getLanguage()->getTag());
					}
					elseif (EshopHelper::getConfigValue('stock_display'))
					{
						$availability = $products[$i]->product_quantity;
					}
					else
					{
						$availability = JText::_('ESHOP_IN_STOCK');
					}
					$products[$i]->availability = $availability;
				}
				if ($session->get('success'))
				{
				$this->success = $session->get('success');
				$session->clear('success');
				}
				$this->products = $products;
				$this->tax = new EshopTax(EshopHelper::getConfig());
				$this->currency = new EshopCurrency();
			}
			parent::display($tpl);
		}
	}
}