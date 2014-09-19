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
defined( '_JEXEC' ) or die;

class pkg_eshopInstallerScript
{
	
	public static $languageFiles = array('en-GB.com_eshop.ini');
	
	/**
	 * 
	 * Function to run before installing the component	 
	 */
	public function preflight($type, $parent)
	{
		jimport('joomla.filesystem.file');
		//Backup the old language file
		foreach (self::$languageFiles as $languageFile)
		{
			if (JFile::exists(JPATH_ROOT.'/language/en-GB/'.$languageFile))
			{
				JFile::copy(JPATH_ROOT.'/language/en-GB/'.$languageFile, JPATH_ROOT.'/language/en-GB/bak.'.$languageFile);
			}
		}
	}

	/**
	 *
	 * Function to run when installing the component
	 * @return void
	 */
	public function install($parent)
	{
		$this->updateDatabaseSchema(false);
		$this->displayEshopWelcome(false);
	}
	
	/**
	 * 
	 * Function to run when updating the component
	 * @return void
	 */
	function update($parent)
	{
		$this->updateDatabaseSchema(true);
		$this->displayEshopWelcome(true);
	}
	
	/**
	 * 
	 * Function to update database schema
	 */
	public function updateDatabaseSchema($update)
	{
		jimport('joomla.filesystem.file');
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(*)')
			->from('#__eshop_configs');
		$db->setQuery($query);
		$total = $db->loadResult();
		if (!$total)
		{
			$configSql = JPATH_ADMINISTRATOR.'/components/com_eshop/sql/config.eshop.sql';
			$query = JFile::read($configSql);
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
		}
		// Update database
		// Update to #__eshop_orders table
		$sql = 'ALTER TABLE `#__eshop_orders` CHANGE `payment_method` `payment_method` VARCHAR(100) DEFAULT NULL';
		$db->setQuery($sql);
		$db->query();
		
		$sql = 'ALTER TABLE `#__eshop_orders` CHANGE `shipping_method` `shipping_method` VARCHAR(100) DEFAULT NULL';
		$db->setQuery($sql);
		$db->query();
		
		$fields = array_keys($db->getTableColumns('#__eshop_orders'));
		if (!in_array('payment_method_title', $fields))
		{
			$sql = 'ALTER TABLE `#__eshop_orders` ADD `payment_method_title` VARCHAR(100) DEFAULT NULL AFTER `payment_method`';
			$db->setQuery($sql);
			$db->query();
		}
		
		if (!in_array('shipping_method_title', $fields))
		{
			$sql = 'ALTER TABLE `#__eshop_orders` ADD `shipping_method_title` VARCHAR(100) DEFAULT NULL AFTER `shipping_method`';
			$db->setQuery($sql);
			$db->query();
		}
		// Update to #__eshop_payments table
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('MAX(ordering)')
			->from('#__eshop_payments');
		$db->setQuery($query);
		$ordering = $db->loadResult();
		$query->clear();
		$query->select('id')
			->from('#__eshop_payments')
			->where('name = "os_authnet"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$ordering++;
			$query->clear();
			$query->insert('#__eshop_payments')
				->values('"", "os_authnet", "Authorize.net", "Giang Dinh Truong", "0000-00-00 00:00:00", "Copyright 2010-2013 Ossolution Team", "http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2", "contact@joomdonation.com", "www.joomdonation.com", "1.0", "Authorize.net Payment Plugin for EShop", NULL, ' . $ordering . ', 0');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_payments')
			->where('name = "os_eway"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$ordering++;
			$query->clear();
			$query->insert('#__eshop_payments')
				->values('"", "os_eway", "Eway", "Giang Dinh Truong", "0000-00-00 00:00:00", "Copyright 2010-2013 Ossolution Team", "http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2", "contact@joomdonation.com", "www.joomdonation.com", "1.0", "Eway Payment Plugin for EShop", NULL, ' . $ordering . ', 0');
			$db->setQuery($query);
			$db->query();
		}
		
		// Update to #__eshop_options table
		$fields = array_keys($db->getTableColumns('#__eshop_options'));
		if (!in_array('option_image', $fields))
		{
			$sql = 'ALTER TABLE `#__eshop_options` ADD `option_image` VARCHAR(255) DEFAULT NULL AFTER `option_type`';
			$db->setQuery($sql);
			$db->query();
		}
		
		// Update to #__eshop_optiondetails table
		$fields = array_keys($db->getTableColumns('#__eshop_optiondetails'));
		if (!in_array('option_desc', $fields))
		{
			$sql = 'ALTER TABLE `#__eshop_optiondetails` ADD `option_desc` TEXT DEFAULT NULL AFTER `option_name`';
			$db->setQuery($sql);
			$db->query();
		}
		
		// Check and add 2 more shippings methods
		$query->clear();
		$query->select('MAX(ordering)')
			->from('#__eshop_shippings');
		$db->setQuery($query);
		$ordering = $db->loadResult();
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_shippings')
			->where('name = "eshop_auspost"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$ordering++;
			$query->clear();
			$query->insert('#__eshop_shippings')
				->values('"", "eshop_auspost", "Australia Post Shipping", "Giang Dinh Truong", "0000-00-00 00:00:00", "Copyright 2013 Ossolution Team", "http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2", "contact@joomdonation.com", "www.joomdonation.com", "1.0", "This is Australia Post Shipping method for Eshop", "{\"postcode\":\"4215\",\"standard_postage\":\"1\",\"express_postage\":\"1\",\"display_delivery_time\":\"1\",\"weight_id\":\"1\",\"taxclass_id\":\"0\",\"geozone_id\":\"0\"}", ' . $ordering . ', 0');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_shippings')
			->where('name = "eshop_ups"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$ordering++;
			$query->clear();
			$query->insert('#__eshop_shippings')
				->values('"", "eshop_ups", "UPS", "Giang Dinh Truong", "0000-00-00 00:00:00", "Copyright 2013 Ossolution Team", "http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2", "contact@joomdonation.com", "www.joomdonation.com", "1.0.6", "This is UPS Shipping method for Eshop", NULL, ' . $ordering . ', 0');
			$db->setQuery($query);
			$db->query();
		}
		
		//Check and add 2 menus
		$query->clear();
		$query->select('id')
			->from('#__eshop_menus')
			->where('menu_name = "ESHOP_TRANSLATION"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_menus')
				->values('"", "ESHOP_TRANSLATION", "15", "language", NULL, 1, 13');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_menus')
			->where('menu_name = "ESHOP_EXPORTS"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_menus')
				->values('"", "ESHOP_EXPORTS", "15", "exports", NULL, 1, 14');
			$db->setQuery($query);
			$db->query();
		}
		
		//Check and add messages
		$query->clear();
		$query->select('id')
			->from('#__eshop_messages')
			->where('message_name = "admin_notification_email"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_messages')
				->values('"", "Admin Notification Email", "admin_notification_email", "textarea"');
			$db->setQuery($query);
			$db->query();
			$messageId = $db->insertid();
			$query->clear();
			$query->insert('#__eshop_messagedetails')
				->values('\'\', ' . $messageId . ', \'<div style="width: 680px;">\r\n<p style="margin-top: 0px; margin-bottom: 20px;">You have received an order.</p>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Order Details</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Order ID:</strong> [ORDER_ID]<br /> <strong>Date Added:</strong> [DATE_ADDED]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Payment Method:</strong> [PAYMENT_METHOD]<br /> <strong>Shipping Method:</strong> [SHIPPING_METHOD]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Comment</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[COMMENT]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Payment Address</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Shipping Address</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[PAYMENT_ADDRESS]<br /> [PAYMENT_EMAIL]<br /> [PAYMENT_TELEPHONE]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[SHIPPING_ADDRESS]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n[PRODUCTS_LIST]</div>\', \'en-GB\'');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_messages')
			->where('message_name = "customer_notification_email"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_messages')
				->values('"", "Customer Notification Email", "customer_notification_email", "textarea"');
			$db->setQuery($query);
			$db->query();
			$messageId = $db->insertid();
			$query->clear();
			$query->insert('#__eshop_messagedetails')
				->values('\'\', ' . $messageId . ', \'<div style="width: 680px;">\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Thank you for your interest in [STORE_NAME] products. Your order has been received and will be processed once payment has been confirmed.</p>\r\n<p style="margin-top: 0px; margin-bottom: 20px;">To view your order click on the link below:</p>\r\n<p style="margin-top: 0px; margin-bottom: 20px;"><a href="[ORDER_LINK]"> [ORDER_LINK] </a></p>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Order Details</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Order ID:</strong> [ORDER_ID]<br /> <strong>Date Added:</strong> [DATE_ADDED]<br /> <strong>Payment Method:</strong> [PAYMENT_METHOD]<br /> <strong>Shipping Method:</strong> [SHIPPING_METHOD]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Email:</strong> [CUSTOMER_EMAIL]<br /> <strong>Telephone:</strong> [CUSTOMER_TELEPHONE]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Comment</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[COMMENT]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Payment Address</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Shipping Address</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[PAYMENT_ADDRESS]<br /> [PAYMENT_EMAIL]<br /> [PAYMENT_TELEPHONE]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[SHIPPING_ADDRESS]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n[PRODUCTS_LIST]\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Please reply to this email if you have any questions.</p>\r\n</div>\', \'en-GB\'');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_messages')
			->where('message_name = "offline_payment_customer_notification_email"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_messages')
				->values('"", "Offline Payment Customer Notification Email", "offline_payment_customer_notification_email", "textarea"');
			$db->setQuery($query);
			$db->query();
			$messageId = $db->insertid();
			$query->clear();
			$query->insert('#__eshop_messagedetails')
				->values('\'\', ' . $messageId . ', \'<div style="width: 680px;">\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Thank you for your interest in [STORE_NAME] products. Your order has been received and will be processed once payment has been confirmed.</p>\r\n<p style="margin-top: 0px; margin-bottom: 20px;">To view your order click on the link below:</p>\r\n<p style="margin-top: 0px; margin-bottom: 20px;"><a href="[ORDER_LINK]"> [ORDER_LINK] </a></p>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Order Details</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Order ID:</strong> [ORDER_ID]<br /> <strong>Date Added:</strong> [DATE_ADDED]<br /> <strong>Payment Method:</strong> [PAYMENT_METHOD]<br /> <strong>Shipping Method:</strong> [SHIPPING_METHOD]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Email:</strong> [CUSTOMER_EMAIL]<br /> <strong>Telephone:</strong> [CUSTOMER_TELEPHONE]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Comment</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[COMMENT]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Payment Address</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Shipping Address</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[PAYMENT_ADDRESS]<br /> [PAYMENT_EMAIL]<br /> [PAYMENT_TELEPHONE]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[SHIPPING_ADDRESS]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n[PRODUCTS_LIST]\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Please send the offline payment to our bank account:<br /> Enter your bank information here</p>\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Please reply to this email if you have any questions.</p>\r\n</div>\', \'en-GB\'');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_messages')
			->where('message_name = "guest_notification_email"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_messages')
				->values('"", "Guest Notification Email", "guest_notification_email", "textarea"');
			$db->setQuery($query);
			$db->query();
			$messageId = $db->insertid();
			$query->clear();
			$query->insert('#__eshop_messagedetails')
				->values('\'\', ' . $messageId . ', \'<div style="width: 680px;">\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Thank you for your interest in [STORE_NAME] products. Your order has been received and will be processed once payment has been confirmed.</p>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Order Details</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Order ID:</strong> [ORDER_ID]<br /> <strong>Date Added:</strong> [DATE_ADDED]<br /> <strong>Payment Method:</strong> [PAYMENT_METHOD]<br /> <strong>Shipping Method:</strong> [SHIPPING_METHOD]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Email:</strong> [CUSTOMER_EMAIL]<br /> <strong>Telephone:</strong> [CUSTOMER_TELEPHONE]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Comment</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[COMMENT]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Payment Address</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Shipping Address</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[PAYMENT_ADDRESS]<br /> [PAYMENT_EMAIL]<br /> [PAYMENT_TELEPHONE]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[SHIPPING_ADDRESS]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n[PRODUCTS_LIST]\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Please reply to this email if you have any questions.</p>\r\n</div>\', \'en-GB\'');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_messages')
			->where('message_name = "offline_payment_guest_notification_email"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_messages')
				->values('"", "Offline Payment Guest Notification Email", "offline_payment_guest_notification_email", "textarea"');
			$db->setQuery($query);
			$db->query();
			$messageId = $db->insertid();
			$query->clear();
			$query->insert('#__eshop_messagedetails')
				->values('\'\', ' . $messageId . ', \'<div style="width: 680px;">\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Thank you for your interest in [STORE_NAME] products. Your order has been received and will be processed once payment has been confirmed.</p>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Order Details</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Order ID:</strong> [ORDER_ID]<br /> <strong>Date Added:</strong> [DATE_ADDED]<br /> <strong>Payment Method:</strong> [PAYMENT_METHOD]<br /> <strong>Shipping Method:</strong> [SHIPPING_METHOD]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><strong>Email:</strong> [CUSTOMER_EMAIL]<br /> <strong>Telephone:</strong> [CUSTOMER_TELEPHONE]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Comment</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[COMMENT]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">\r\n<thead>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Payment Address</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Shipping Address</td>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[PAYMENT_ADDRESS]<br /> [PAYMENT_EMAIL]<br /> [PAYMENT_TELEPHONE]</td>\r\n<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">[SHIPPING_ADDRESS]</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n[PRODUCTS_LIST]\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Please send the offline payment to our bank account:<br /> Enter your bank information here</p>\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Please reply to this email if you have any questions.</p>\r\n</div>\', \'en-GB\'');
			$db->setQuery($query);
			$db->query();
		}
		
		$query->clear();
		$query->select('id')
			->from('#__eshop_messages')
			->where('message_name = "manufacturer_notification_email"');
		$db->setQuery($query);
		if (!$db->loadResult())
		{
			$query->clear();
			$query->insert('#__eshop_messages')
				->values('"", "Manufacturer Notification Email", "manufacturer_notification_email", "textarea"');
			$db->setQuery($query);
			$db->query();
			$messageId = $db->insertid();
			$query->clear();
			$query->insert('#__eshop_messagedetails')
				->values('\'\', ' . $messageId . ', \'<div style="width: 680px;">\r\n<p style="margin-top: 0px; margin-bottom: 20px;">Hello [MANUFACTURER_NAME],<br /> You are receiving this email because following your product(s) are ordered at [STORE_NAME]:</p>\r\n[PRODUCTS_LIST]</div>\', \'en-GB\'');
			$db->setQuery($query);
			$db->query();
		}
		
	}
	
	/**
	 * 
	 * Function to display welcome page after installing
	 */
	public function displayEshopWelcome($update)
	{
		//Add style css
		JFactory::getDocument()->addStyleSheet(JURI::base().'/components/com_eshop/assets/css/style.css');
		//Load Eshop language file
		$lang = JFactory::getLanguage();
		$lang->load('com_eshop', JPATH_ADMINISTRATOR, 'en_GB', true);
		?>
		<table cellspacing="0" cellpadding="0" width="100%">
			<tbody>
				<td valign="top">
					<?php echo JHtml::_('image', 'media/com_eshop/logo_eshop.png', ''); ?><br />
					<h2 class="eshop-welcome-title"><?php echo JText::_('ESHOP_WELCOME_TITLE'); ?></h2><br />
					<p class="eshop-welcome-text"><?php echo JText::_('ESHOP_WELCOME_TEXT'); ?></p>
				</td>
				<td valign="top">
					<h2><?php echo $update ? JText::_('ESHOP_UPDATE_SUCCESSFULLY') : JText::_('ESHOP_INSTALLATION_SUCCESSFULLY'); ?></h2>
					<div id="cpanel">
						<?php
						if (!$update)
						{
							?>
							<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
								<div class="icon">
									<a title="<?php echo JText::_('ESHOP_INSTALL_SAMPLE_DATA'); ?>" href="<?php echo JRoute::_('index.php?option=com_eshop&task=installSampleData'); ?>">
										<?php echo JHtml::_('image', 'administrator/components/com_eshop/assets/icons/icon-48-install.png', JText::_('ESHOP_INSTALL_SAMPLE_DATA')); ?>
										<span><?php echo JText::_('ESHOP_INSTALL_SAMPLE_DATA'); ?></span>
									</a>
								</div>
							</div>	
							<?php
						}
						?>
						<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">	
							<div class="icon">
								<a title="<?php echo JText::_('ESHOP_GO_TO_HOME'); ?>" href="<?php echo JRoute::_('index.php?option=com_eshop&view=dashboard'); ?>">
									<?php echo JHtml::_('image', 'administrator/components/com_eshop/assets/icons/icon-48-home.png', JText::_('ESHOP_GO_TO_HOME')); ?>
									<span><?php echo JText::_('ESHOP_GO_TO_HOME'); ?></span>
								</a>
							</div>
						</div>
					</div>
				</td>
			</tbody>
		</table>
		<?php
	}

	/**
	 * 
	 * Function to run after installing the component	 
	 */
	public function postflight($type, $parent)
	{
		jimport('joomla.filesystem.file');
		//Restore the modified language strings by merging to language files
		$registry	= new JRegistry();							
		foreach (self::$languageFiles as $languageFile)
		{
			$backupFile =  JPATH_ROOT.'/language/en-GB/bak.'.$languageFile;
			$currentFile = JPATH_ROOT.'/language/en-GB/'.$languageFile;
			if (JFile::exists($currentFile) && JFile::exists($backupFile))
			{
				$registry->loadFile($currentFile, 'INI');
				$currentItems = $registry->toArray();
				$registry->loadFile($backupFile, 'INI');
				$backupItems = $registry->toArray();
				$items =  array_merge($currentItems, $backupItems);
				$content = "";
				foreach ($items as $key => $value)
				{
					$content.="$key=\"$value\"\n";
				}
				JFile::write($currentFile, $content);
			}
		}
	}
}