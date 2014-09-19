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
 * EShop Component Category Model
 *
 * @package Joomla
 * @subpackage EShop
 * @since 1.5
 */
class EShopModelCategory extends EShopModel
{

	public function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('category_name', 'category_alias', 'category_desc', 'meta_key', 'meta_desc');
		
		parent::__construct($config);
	}

	function store(&$data)
	{
		jimport('joomla.filesystem.file');
		$imagePath = JPATH_ROOT . '/media/com_eshop/categories/';
		if (JRequest::getInt('remove_image') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_categories', 'id', $this->getDbo());
			$row->load($data['id']);
			if (JFile::exists($imagePath . $row->category_image))
				JFile::delete($imagePath . $row->category_image);
				
			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->category_image).'-100x100.'.JFile::getExt($row->category_image)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->category_image).'-100x100.'.JFile::getExt($row->category_image));
			$data['category_image'] = '';
		}
		
		$categoryImage = $_FILES['category_image'];
		if (is_uploaded_file($categoryImage['tmp_name']))
		{
			if ($data['id'])
			{
				// Delete the old image
				$row = new EShopTable('#__eshop_categories', 'id', $this->getDbo());
				$row->load($data['id']);
				if (JFile::exists($imagePath . $row->category_image))
					JFile::delete($imagePath . $row->category_image);
			}
			if (JFile::exists($imagePath . $categoryImage['name']))
			{
				$imageFileName = uniqid('image_') . '_' . $categoryImage['name'];
			}
			else
			{
				$imageFileName = $categoryImage['name'];
			}
			JFile::upload($categoryImage['tmp_name'], $imagePath . $imageFileName);
			// Resize image
			EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/categories/', 100, 100);
			$data['category_image'] = $imageFileName;
		}
		parent::store($data);
		return true;
	}
	
	/**
	 * Method to remove categories
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__eshop_categories')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(category_id) FROM #__eshop_productcategories)')
				->where('id NOT IN (SELECT DISTINCT(category_parent_id) FROM #__eshop_categories WHERE category_parent_id > 0)');
			$db->setQuery($query);
			$categories = $db->loadColumn();
			if (count($categories))
			{
				$query->clear();
				$query->delete('#__eshop_categories')
					->where('id IN (' . implode(',', $categories) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				$numItemsDeleted = $db->getAffectedRows();
				//Delete details records
				$query->clear();
				$query->delete('#__eshop_categorydetails')
					->where('category_id IN (' . implode(',', $categories) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Remove SEF urls for categories
				for ($i = 0; $n = count($categories), $i < $n; $i++)
				{
					$query->clear();
					$query->delete('#__eshop_urls')
						->where('query LIKE "view=category&id=' . $categories[$i] . '"');
					$db->setQuery($query);
					$db->query();
				}
				if ($numItemsDeleted < count($cid))
				{
					//Removed warning
					return 2;
				}
			}
			else 
			{
				return 2;
			}
		}
		//Removed success
		return 1;
	}
}