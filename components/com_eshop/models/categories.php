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

class EShopModelCategories extends EShopModelList
{
	/**
	 * Current active language
	 *
	 * @var string
	 */
	protected $language = null;
	
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array('category_name', 'category_alias', 'category_desc', 'meta_key', 'meta_desc');
		parent::__construct($config);
		$this->language = JFactory::getLanguage()->getTag();
		$mainframe = JFactory::getApplication('site');
		$listLength = EshopHelper::getConfigValue('catalog_limit');
		if (!$listLength)
			$listLength = $mainframe->getCfg('list_limit');
		$limit = $mainframe->getUserStateFromRequest('categorires.list.limit', 'limit', $listLength, 'int');
		$limitstart = JRequest::getInt('limitstart', JRequest::getInt('start'));
		// In case limit has been changed, adjust limitstart accordingly
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		$app		= JFactory::getApplication();
		$app->setUserState('limit', $limit);
	}
	
	/**
	 * Build an where clause array
	 * 
	 * @return array
	 */
	public function _buildContentWhereArray()
	{
		$categoryId = JRequest::getInt('id', 0);
		$where = array();
		$where[] = 'a.published = 1';
		$where[] = 'a.category_parent_id = ' . intval($categoryId);
		$where[] = 'b.language = "' . $this->language . '"';
		return $where;
	}
}