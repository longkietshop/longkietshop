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

class EShopModelWishlist extends EShopModel
{

	/**
	 * 
	 * Constructor
	 * @since 1.5
	 */
	public function __construct($config = array())
	{
		parent::__construct();
	}
	
	function add($productId)
	{
		$json = array();
		$user = JFactory::getUser();
		$session = JFactory::getSession();
		$wishlist = $session->get('wishlist');
		if (!$wishlist)
		{
			$wishlist = array();
		}
		$productInfo = EshopHelper::getProduct($productId, JFactory::getLanguage()->getTag());
		if (is_object($productInfo))
		{
			if (!in_array($productId, $wishlist))
			{
				$wishlist[] = $productId;
				$session->set('wishlist', $wishlist);
			}
			$viewProductLink = JRoute::_(EshopRoute::getProductRoute($productId, EshopHelper::getProductCategory($productId)));
			$viewWishListLink = JRoute::_(EshopRoute::getViewRoute('wishlist'));
			if ($user->get('id'))
			{
				$message = '<div>' . sprintf(JText::_('ESHOP_ADD_TO_WISHLIST_SUCCESS_MESSAGE_USER'), $viewProductLink, $productInfo->product_name, $viewWishListLink) . '</div>';
			}
			else
			{
				$loginLink = JRoute::_('index.php?option=com_users&view=login');
				$registerLink = JRoute::_('index.php?option=com_users&view=register');
				$message = '<div>' . sprintf(JText::_('ESHOP_ADD_TO_WISHLIST_SUCCESS_MESSAGE_GUEST'), $loginLink, $registerLink, $viewProductLink, $productInfo->product_name, $viewWishListLink) . '</div>';
			}
			$json['success']['message'] = $message;
		}
		return $json;
	}
}