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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopController extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		//By adding this code, the system will first find the model from backend, if not exist, it will use the model class defined in the front-end
		$config['model_path'] = JPATH_ADMINISTRATOR.'/components/com_eshop/models';
		parent::__construct($config);
		$this->addModelPath(JPATH_COMPONENT.'/models', $this->model_prefix);
	}
}