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
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelManufacturer extends EShopModel
{

	public function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('manufacturer_name', 'manufacturer_alias', 'manufacturer_desc');
		
		parent::__construct($config);
	}

	function store(&$data)
	{
		jimport('joomla.filesystem.file');
		$imagePath = JPATH_ROOT . '/media/com_eshop/manufacturers/';
		if (JRequest::getInt('remove_image') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_manufacturers', 'id', $this->getDbo());
			$row->load($data['id']);
			
			if (JFile::exists($imagePath . $row->manufacturer_image))
				JFile::delete($imagePath . $row->manufacturer_image);
			
			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->manufacturer_image).'-100x100.'.JFile::getExt($row->manufacturer_image)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->manufacturer_image).'-100x100.'.JFile::getExt($row->manufacturer_image));
			$data['manufacturer_image'] = '';
		}
		
		$manufacturerImage = $_FILES['manufacturer_image'];		
		if (is_uploaded_file($manufacturerImage['tmp_name']))
		{
			if ($data['id'])
			{
				// Delete the old image
				$row = new EShopTable('#__eshop_manufacturers', 'id', $this->getDbo());
				$row->load($data['id']);
				
				if (JFile::exists($imagePath . $row->manufacturer_image))
					JFile::delete($imagePath . $row->manufacturer_image);
				
				if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->manufacturer_image).'-100x100.'.JFile::getExt($row->manufacturer_image)))
					JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->manufacturer_image).'-100x100.'.JFile::getExt($row->manufacturer_image));
			}
			if (JFile::exists($imagePath . $manufacturerImage['name']))
			{
				$imageFileName = uniqid('image_') . '_' . $manufacturerImage['name'];
			}
			else
			{
				$imageFileName = $manufacturerImage['name'];
			}
			JFile::upload($manufacturerImage['tmp_name'], $imagePath . $imageFileName);
			// Resize images
			
			$data['manufacturer_image'] = $imageFileName;
			EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/manufacturers/', 100, 100);
		}
		
		parent::store($data);
		return true;
	}
	
	/**
	 * Method to remove manufacturers
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
				->from('#__eshop_manufacturers')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(manufacturer_id) FROM #__eshop_products)');
			$db->setQuery($query);
			$manufacturers = $db->loadColumn();
			if (count($manufacturers))
			{
				$query->clear();
				$query->delete('#__eshop_manufacturers')
					->where('id IN (' . implode(',', $manufacturers) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				$numItemsDeleted = $db->getAffectedRows();
				//Delete details records
				$query->clear();
				$query->delete('#__eshop_manufacturerdetails')
					->where('manufacturer_id IN (' . implode(',', $manufacturers) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Remove SEF urls for categories
				for ($i = 0; $n = count($manufacturers), $i < $n; $i++)
				{
					$query->clear();
					$query->delete('#__eshop_urls')
						->where('query LIKE "view=manufacturer&id=' . $manufacturers[$i] . '"');
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