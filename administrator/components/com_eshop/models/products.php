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
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelProducts extends EShopModelList
{
	/**
	 *
	 * Constructor
	 * 
	 * @param array $config        	
	 */
	function __construct($config)
	{
		$config['search_fields'] = array('b.product_name', 'b.product_short_desc', 'b.product_desc');
		$config['translatable'] = true;
		$config['translatable_fields'] = array(
			'product_name', 
			'product_alias', 
			'product_desc', 
			'product_short_desc', 
			'meta_key', 
			'meta_desc',
			'product_tag');
		parent::__construct($config);
	}
}