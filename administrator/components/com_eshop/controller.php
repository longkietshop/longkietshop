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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopController extends JControllerLegacy
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
	 * Display information
	 *
	 */
	function display($cachable = false, $urlparams = false)
	{				    	
		$task = $this->getTask();
		$view = JRequest::getVar('view', '');
		if (!$view)
		{
			JRequest::setVar('view', 'dashboard');
		}
		EShopHelper::renderSubmenu(JRequest::getVar('view', 'configuration'));
		parent::display();
		EShopHelper::displayCopyRight();
	}
	
	/**
	 * 
	 * Function to install sample data
	 */
	function installSampleData()
	{
		$mainframe = JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		$sampleSql = JPATH_ADMINISTRATOR.'/components/com_eshop/sql/sample.eshop.sql';
		$query = JFile::read($sampleSql);
		$queries = $db->splitSql($query);
		if (count($queries))
		{
			foreach ($queries as $query)
			{
				$query = trim($query);
				if ($query != '' && $query{0} != '#')
				{
					$db->setQuery($query);
					$db->query();
				}
			}
		}
		$mainframe->redirect('index.php?option=com_eshop&view=dashboard', JText::_('ESHOP_INSTALLATION_DONE'));
	}
}