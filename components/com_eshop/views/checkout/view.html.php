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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewCheckout extends EShopView
{		
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$Itemid = JRequest::getInt('Itemid', 0);
		if (EshopHelper::getConfigValue('catalog_mode'))
		{
			$session = JFactory::getSession();
			$session->set('warning', JText::_('ESHOP_CATALOG_MODE_ON'));
			$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('categories')));
		}
		else 
		{
			if ($this->getLayout() == 'complete')
			{
				$this->_displayComplete($tpl);
			}
			elseif ($this->getLayout() == 'cancel')
			{
				$this->_displayCancel($tpl);
			}
			else
			{
				$cart = new EshopCart();
				// Check if cart has products or not
				if (!$cart->hasProducts())
				{
					$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('cart')));
				}
				// Check stock condition
				$stock = true;
				if (!EshopHelper::getConfigValue('stock_checkout'))
				{
					$stock = $cart->hasStock();
				}
				if (!$stock)
				{
					$mainframe->redirect(JRoute::_(EshopRoute::getViewRoute('cart')));
				}
				$document = JFactory::getDocument();
				$document->addStyleSheet(JURI::base().'components/com_eshop/assets/colorbox/colorbox.css');
				$document->setTitle(JText::_('ESHOP_CHECKOUT'));
				$session = JFactory::getSession();
				$user = JFactory::getUser();
				$this->user = $user;
				$this->shipping_required = $cart->hasShipping();
				parent::display($tpl);
			}	
		}
	}
	
	/**
	 * 
	 * Function to display complete layout
	 * @param string $tpl
	 */
	function _displayComplete($tpl)
	{
		$cart = new EshopCart();
		$session = JFactory::getSession();
		$this->order_id = $session->get('order_id');
		// Clear cart and session
		if ($session->get('order_id'))
		{
			$cart->clear();
			$session->clear('shipping_method');
			$session->clear('shipping_methods');
			$session->clear('payment_method');
			$session->clear('guest');
			$session->clear('comment');
			$session->clear('order_id');
			$session->clear('coupon_code');
		}
		parent::display($tpl);
	}
	
	
	/**
	 *
	 * Function to display cancel layout
	 * @param string $tpl
	 */
	function _displayCancel($tpl)
	{
		parent::display($tpl);
	}
}