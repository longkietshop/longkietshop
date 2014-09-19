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
class EShopViewExports extends JViewLegacy
{

	function display($tpl = null)
	{
		$lists = array();
		//Export type
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'));
		$options[] = JHtml::_('select.option', 'products', JText::_('ESHOP_PRODUCTS'));
		$options[] = JHtml::_('select.option', 'categories', JText::_('ESHOP_CATEGORIES'));
		$lists['export_type'] = JHtml::_('select.genericlist', $options, 'export_type', ' class="inputbox" ', 'value', 'text', JRequest::getVar('export_type'));
		//Language
		jimport('joomla.filesystem.folder');
		$path = JPATH_ROOT . '/language';
		$folders = JFolder::folders($path);
		$languages = array();
		foreach ($folders as $folder)
		if ($folder != 'pdf_fonts' && $folder != 'overrides')
			$languages[] = $folder;
		$options = array();
		foreach ($languages as $language)
		{
			$options[] = JHtml::_('select.option', $language, $language);
		}
		$lists['language'] = JHtml::_('select.genericlist', $options, 'language', ' class="inputbox" ', 'value', 'text', '');
		
		$this->lists = $lists;
		parent::display($tpl);
	}
}