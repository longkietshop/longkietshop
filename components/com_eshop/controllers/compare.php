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
class EShopControllerCompare extends JControllerLegacy
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
	 * Function to add a product into the compare
	 */
	function add()
	{
		$productId = JRequest::getInt('product_id');
		$model = $this->getModel('Compare');
		$json = $model->add($productId);
		echo json_encode($json);
		exit();
	}
	
	/**
	 *
	 * Function to remove a product from the compare
	 */
	function remove()
	{
		$session = JFactory::getSession();
		$compare = $session->get('compare');
		$productId = JRequest::getInt('product_id');
		$key = array_search($productId, $compare);
		if ($key !== false)
		{
			unset($compare[$key]);
		}
		$session->set('compare', $compare);
		$session->set('success', JText::_('ESHOP_COMPARE_REMOVED_MESSAGE'));
		$json = array();
		$json['redirect'] = JRoute::_(EshopRoute::getViewRoute('compare'));
		echo json_encode($json);
		exit();
	}
}