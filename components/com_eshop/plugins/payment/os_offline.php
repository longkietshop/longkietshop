<?php
/**
 * @version		1.0.3
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class os_offline extends os_payment
{
	/**
	 * Constructor functions, init some parameter
	 *
	 * @param object $params
	 */
	public function __construct($params)
	{
        $config = array(
            'type' => 0,
            'show_card_type' => false,
            'show_card_holder_name' => false
        );

        parent::__construct($params, $config);
	}
	
	/**
	 * Process payment 
	 *
	 */
	public function processPayment($data)
	{
		$row = JTable::getInstance('Eshop', 'Order');
		$id = $data['order_id'];
		$row->load($id);
		//Send confirmation email here
		if (EshopHelper::getConfigValue('order_alert_mail'))
		{
			EshopHelper::sendEmails($row);
		}
        JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_eshop&view=checkout&layout=complete'));
	}
}