<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelOption extends EShopModel
{

	public function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('option_name', 'option_desc');
		parent::__construct($config);
	}

	function store(&$data)
	{
		jimport('joomla.filesystem.file');
		$imagePath = JPATH_ROOT . '/media/com_eshop/options/';
		if (JRequest::getInt('remove_image') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_options', 'id', $this->getDbo());
			$row->load($data['id']);
				
			if (JFile::exists($imagePath . $row->option_image))
				JFile::delete($imagePath . $row->option_image);
				
			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->option_image).'-100x100.'.JFile::getExt($row->option_image)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->option_image).'-100x100.'.JFile::getExt($row->option_image));
			$data['option_image'] = '';
		}
		
		$optionImage = $_FILES['option_image'];
		if (is_uploaded_file($optionImage['tmp_name']))
		{
			if ($data['id'])
			{
				// Delete the old image
				$row = new EShopTable('#__eshop_options', 'id', $this->getDbo());
				$row->load($data['id']);
		
				if (JFile::exists($imagePath . $row->option_image))
					JFile::delete($imagePath . $row->option_image);
		
				if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->option_image).'-100x100.'.JFile::getExt($row->option_image)))
					JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->option_image).'-100x100.'.JFile::getExt($row->option_image));
			}
			if (JFile::exists($imagePath . $optionImage['name']))
			{
				$imageFileName = uniqid('image_') . '_' . $optionImage['name'];
			}
			else
			{
				$imageFileName = $optionImage['name'];
			}
			JFile::upload($optionImage['tmp_name'], $imagePath . $imageFileName);
			// Resize images
				
			$data['option_image'] = $imageFileName;
			EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/options/', 100, 100);
		}
		parent::store($data);
		// Store option values
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$optionId = $data['id'];
		$optionValueId = JRequest::getVar('optionvalue_id');
		$published = JRequest::getVar('optionvalue_published');
		$ordering = JRequest::getVar('ordering');
		
		// Delete in option values table first
		$query->clear();
		$query->delete('#__eshop_optionvalues')
			->where('option_id = ' . intval($optionId));
		if (count($optionValueId))
		{
			$query->where('id NOT IN (' . implode(',', $optionValueId) . ')');
		}
		$db->setQuery($query);
		$db->query();
		// Delete in option values details
		$query->clear();
		$query->delete('#__eshop_optionvaluedetails')
			->where('option_id = ' . intval($optionId));
		if (count($optionValueId))
		{
			$query->where('optionvalue_id NOT IN (' . implode(',', $optionValueId) . ')');
		}
		$db->setQuery($query);
		$db->query();
		
		$languages = EshopHelper::getLanguages();
		if (JLanguageMultilang::isEnabled() && count($languages) > 1)
		{
			for ($i = 0; $n = count($ordering), $i < $n; $i++)
			{
				$row = new EShopTable('#__eshop_optionvalues', 'id', $db);
				$row->id = isset($optionValueId[$i]) ? $optionValueId[$i] : '';
				$row->option_id = $optionId;
				$row->published = $published[$i];
				$row->ordering = $ordering[$i];
				$row->store();
				foreach ($languages as $language)
				{
					$langCode = $language->lang_code;
					$optionValueDetailsId = JRequest::getVar('optionvaluedetails_id_' . $langCode);
					$value = JRequest::getVar('value_' . $langCode);
					$detailsRow = new EShopTable('#__eshop_optionvaluedetails', 'id', $db);
					$detailsRow->id = isset($optionValueDetailsId[$i]) ? $optionValueDetailsId[$i] : '';
					$detailsRow->optionvalue_id = $row->id;
					$detailsRow->option_id = $optionId;
					$detailsRow->value = $value[$i];
					$detailsRow->language = $langCode;
					$detailsRow->store();
				}
			}
		}
		else
		{
			$optionValueDetailsId = JRequest::getVar('optionvaluedetails_id');
			$value = JRequest::getVar('value');
			for ($i = 0; $n = count($ordering), $i < $n; $i++)
			{
				$row = new EShopTable('#__eshop_optionvalues', 'id', $db);
				$row->id = isset($optionValueId[$i]) ? $optionValueId[$i] : '';
				$row->option_id = $optionId;
				$row->published = $published[$i];
				$row->ordering = $ordering[$i];
				$row->store();
				$detailsRow = new EShopTable('#__eshop_optionvaluedetails', 'id', $db);
				$detailsRow->id = isset($optionValueDetailsId[$i]) ? $optionValueDetailsId[$i] : '';
				$detailsRow->optionvalue_id = $row->id;
				$detailsRow->option_id = $optionId;
				$detailsRow->value = $value[$i];
				$detailsRow->language = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
				$detailsRow->store();
			}
		}
		return true;
	}
	
	/**
	 * Method to remove options
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
			$query->delete('#__eshop_options')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(option_id) FROM #__eshop_optionvalues)');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			$numItemsDeleted = $db->getAffectedRows();
			//Delete option values records
			$query->clear();
			$query->delete('#__eshop_optiondetails')
				->where('option_id IN (' . $cids . ')')
				->where('option_id NOT IN (SELECT  DISTINCT(option_id) FROM #__eshop_optionvalues)');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			if ($numItemsDeleted < count($cid))
			{
				//Removed warning
				return 2;
			}
		}
		//Removed success
		return 1;
	}
}