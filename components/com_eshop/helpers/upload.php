<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
require_once 'E:/www/eshop/components/com_eshop/helpers/helper.php';
$json = array();
$optionFile = $_FILES['option_file'];
if (!empty($optionFile['name']))
{
	$fileName = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($optionFile['name'], ENT_QUOTES, 'UTF-8')));
	if ((mb_strlen($fileName) < 3) || (mb_strlen($fileName) > 64))
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
	if (!in_array($optionFile['type'], $allowed))
	{
		$json['error'] = JText::_('ESHOP_UPLOAD_ERROR_FILETYPE');
	}
	if ($optionFile['error'] != UPLOAD_ERR_OK)
	{
		$json['error'] = JText::_('ESHOP_ERROR_UPLOAD_' . $optionFile['error']);
	}
}
else
{
	$json['error'] = JText::_('ESHOP_ERROR_UPLOAD');
}

if (!$json && is_uploaded_file($optionFile['tmp_name']) && file_exists($optionFile['tmp_name']))
{
	$file = basename($fileName) . '.' . md5(mt_rand());
	$json['file'] = $file;
	move_uploaded_file($optionFile['tmp_name'], JPATH_ROOT . '/media/com_eshop/files/' . $file);
	$json['success'] = JText::_('ESHOP_SUCCESS_UPLOAD');
}
echo json_encode($json);
exit();