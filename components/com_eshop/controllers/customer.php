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
class EShopControllerCustomer extends JControllerLegacy
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
	
	function downloadInvoice()
	{
		$orderId = JRequest::getInt('order_id');
		EshopHelper::downloadInvoice(array($orderId));
	}
	
	/**
	 * Function to process payment method
	 */
	function processUser()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Customer');
		$json = $model->processUser($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * 
	 * Function to process (add/update) address
	 */
	function processAddress()
	{
		$post = JRequest::get('post', JREQUEST_ALLOWHTML);
		$model = $this->getModel('Customer');
		$json = $model->processAddress($post);
		echo json_encode($json);
		exit();
	}
	
	/**
	 * 
	 * Function to delete address
	 */
	function deleteAddress()
	{
		$model =  $this->getModel('Customer');
		$id = JRequest::getVar('aid') ;
		$json = $model->deleteAddress($id);
		echo json_encode($json);
		exit();
	}
}