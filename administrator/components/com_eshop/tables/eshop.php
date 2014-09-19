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
 * Config Table class
 */
class ConfigEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_configs', 'id', $db);
	}
}

/**
 * Address Table class
 */
class AddressEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_addresses', 'id', $db);
	}
}

/**
 * Customer Table class
 */
class CustomerEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_customers', 'id', $db);
	}
}

/**
 * Order Table class
 */
class OrderEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_orders', 'id', $db);
	}
}

/**
 * Order Products Table class
 */
class OrderproductsEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_orderproducts', 'id', $db);
	}
}

/**
 * Order Options Table class
 */
class OrderoptionsEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_orderoptions', 'id', $db);
	}
}

/**
 * Order Totals Table class
 */
class OrdertotalsEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_ordertotals', 'id', $db);
	}
}

/**
 * Coupon History Table class
 */
class CouponhistoryEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_couponhistory', 'id', $db);
	}
}

/**
 * Review Table class
 */
class ReviewEshop extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *        	object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db)
	{
		parent::__construct('#__eshop_reviews', 'id', $db);
	}
}