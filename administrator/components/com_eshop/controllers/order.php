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
class EShopControllerOrder extends EShopController
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
	 * Function to download attached file for order
	 */
	public function downloadFile()
	{
    	$id = JRequest::getInt('id');
    	$model = $this->getModel('Order');
    	$model->downloadFile($id);
    }
    
    /**
     * 
     * Function to download invoice for an order
     */
    public function downloadInvoice()
    {
    	$fromExports = JRequest::getVar('from_exports');
    	if ($fromExports)
    	{
    		$dateStart = JRequest::getVar('date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01')));
    		$dateEnd = JRequest::getVar('date_end', date('Y-m-d'));
    		$groupBy = JRequest::getVar('group_by', 'week');
    		$orderStatusId = JRequest::getVar('order_status_id', 0);
    		$db = JFactory::getDbo();
    		$query = $db->getQuery(true);
    		$query->select('id')
    			->from('#__eshop_orders');
    		if ($orderStatusId)
    			$query->where('order_status_id = ' . (int)$orderStatusId);
    		if (!empty($dateStart))
    			$query->where('created_date >= "' . $dateStart . '"');
    		if (!empty($dateEnd))
    			$query->where('created_date <= "' . $dateEnd . '"');
    		$db->setQuery($query);
    		$cid = $db->loadColumn();
    		if (!count($cid))
    		{
    			$mainframe = JFactory::getApplication();
    			$mainframe->redirect('index.php?option=com_eshop&view=reports&layout=orders&date_start=' . $dateStart . '&date_end=' . $dateEnd . '&group_by=' . $groupBy . '&order_status_id=' . $orderStatusId, JText::_('ESHOP_NO_DATA_TO_DOWNLOAD_INVOICE'));
    		}
    	}
    	else 
    	{
    		$cid = JRequest::getVar('cid', array(0), '', 'array');
    	}
    	EshopHelper::downloadInvoice($cid);
    }
}