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
class EShopControllerWishlist extends JControllerLegacy
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
	 * Function to add a product into the wishlist
	 */
	function add()
	{
		$productId = JRequest::getInt('product_id');
		$model = $this->getModel('Wishlist');
		$json = $model->add($productId);
		echo json_encode($json);
		exit();
	}
	
	/**
	 *
	 * Function to remove a product from the wishlist
	 */
	function remove()
	{
		$session = JFactory::getSession();
		$wishlist = $session->get('wishlist');
		$productId = JRequest::getInt('product_id');
		$key = array_search($productId, $wishlist);
		if ($key !== false)
		{
			unset($wishlist[$key]);
		}
		$session->set('success', JText::_('ESHOP_WISHLIST_REMOVED_MESSAGE'));
		$session->set('wishlist', $wishlist);
		$json['redirect'] = JRoute::_(EshopRoute::getViewRoute('wishlist'));
		echo json_encode($json);
		exit();
	}
}