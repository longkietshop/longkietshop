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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerProduct extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * 
	 * Function to write review
	 */
	function writeReview()
	{
		$post = JRequest::get('post');
		$model = $this->getModel('Product');
		$json = $model->writeReview($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * 
	 * Function to upload file
	 */
	function uploadFile()
	{
		jimport('joomla.filesystem.file');
		$json = array();
		$file = $_FILES['file'];
		if (!empty($file['name']))
		{
			$fileName = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));
			if ((utf8_strlen($fileName) < 3) || (utf8_strlen($fileName) > 64))
			{
				$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILENAME');
			}
			//Allowed file extension types
			$allowed = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_extensions_allowed'));
			foreach ($fileTypes as $fileType)
			{
				$allowed[] = trim($fileType);
			}
			if (!in_array(substr(strrchr($fileName, '.'), 1), $allowed))
			{
				$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
			}
			// Allowed file mime types
			$allowed = array();
			$fileTypes = explode("\n", EshopHelper::getConfigValue('file_mime_types_allowed'));
			foreach ($fileTypes as $fileType)
			{
				$allowed[] = trim($fileType);
			}
			if (!in_array($file['type'], $allowed))
			{
				$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
			}
			if ($file['error'] != UPLOAD_ERR_OK)
			{
				$json['error'] = JText::_('ESHOP_ERROR_UPLOAD_' . $file['error']);
			}
		}
		else
		{
			$json['error'] = JText::_('ESHOP_ERROR_UPLOAD');
		}
		
		if (!$json && is_uploaded_file($file['tmp_name']) && file_exists($file['tmp_name']))
		{
			if (JFile::exists(JPATH_ROOT . '/media/com_eshop/files/' . $fileName))
				$fileName = uniqid('file_') . '_' . $fileName;
			$json['file'] = $fileName;
			JFile::upload($file['tmp_name'], JPATH_ROOT . '/media/com_eshop/files/' . $fileName);
			$json['success'] = JText::_('ESHOP_SUCCESS_UPLOAD');
		}
		echo json_encode($json);
		exit();
	}
}