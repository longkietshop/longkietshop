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
class EShopViewCategories extends EShopView
{
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication('site');
		jimport('joomla.filesystem.file');
		$pathway = $mainframe->getPathway();
		$document = JFactory::getDocument();
		$config = EshopHelper::getConfig();
		$Itemid = JRequest::getInt('Itemid', 0);
		$categoryId = JRequest::getInt('id', 0);
		$items = $this->get('Data');
		// Resize category image
		$imageSizeFunction = EshopHelper::getConfigValue('image_size_function', 'resizeImage');
		for ($i = 0; $n = count($items), $i < $n; $i++)
		{
			if ($items[$i]->category_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$items[$i]->category_image))
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($items[$i]->category_image, JPATH_ROOT.'/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
			}
			else 
			{
				$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT.'/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
			}
			$items[$i]->image = JURI::base() . 'media/com_eshop/categories/resized/' . $image;
		}
		$pagination = $this->get('Pagination');
		$parentCategories = EshopHelper::getParentCategories($categoryId, JFactory::getLanguage()->getTag());
		for ($i = count($parentCategories) - 1; $i > 0; $i--)
		{
			$parentCategory = $parentCategories[$i];
			if ($parentCategory->total_children)
				$pathUrl = JRoute::_('index.php?option=com_eshop&view=categories&id=' . $parentCategory->id . '&Itemid=' . $Itemid);
			else
			{
				$pathUrl = JRoute::_('index.php?option=com_eshop&view=category&id=' . $parentCategory->id . '&Itemid=' . $Itemid);
			}
			$pathway->addItem($parentCategory->category_name, $pathUrl);
		}
		if ($categoryId)
		{
			$pathway->addItem($parentCategories[0]->category_name);
			$document->setTitle($parentCategories[0]->category_name);
			// Update hits for category
			EshopHelper::updateHits($categoryId, 'categories');
		}
		if ($categoryId)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('a.*, b.category_name, b.category_desc')
				->from('#__eshop_categories AS a')
				->innerJoin('#__eshop_categorydetails AS b ON (a.id = b.category_id)')
				->where('a.id = ' . intval($categoryId))
				->where('a.published = 1')
				->where('b.language = "' . JFactory::getLanguage()->getTag() . '"');
			$db->setQuery($query);
			$category = $db->loadObject();
			if (is_object($category))
			{
				if ($category->category_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$category->category_image))
				{
					$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array($category->category_image, JPATH_ROOT.'/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
				}
				else
				{
					$image = call_user_func_array(array('EshopHelper', $imageSizeFunction), array('no-image.png', JPATH_ROOT.'/media/com_eshop/categories/', EshopHelper::getConfigValue('image_category_width'), EshopHelper::getConfigValue('image_category_height')));
				}
				$category->image = JURI::base() . 'media/com_eshop/categories/resized/' . $image;
				$this->category = $category;
			}
			else 
			{
				$session = JFactory::getSession();
				$session->set('warning', JText::_('ESHOP_CATEGORY_DOES_NOT_EXIST'));
				$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
			}
		}
		// Warning message
		$session = JFactory::getSession();
		if ($session->get('warning'))
		{
			$this->warning = $session->get('warning');
			$session->clear('warning');
		}
		$this->categoryId = $categoryId;
		$this->config = $config;
		$this->items = $items;
		$this->pagination = $pagination;
		$this->Itemid = $Itemid;
		$app	= JFactory::getApplication('site');
		$this->params = $app->getParams();
		parent::display($tpl);
	}
}