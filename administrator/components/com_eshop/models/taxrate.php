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
 * Eshop Component Taxrate Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelTaxrate extends EShopModel
{

	function __construct($config)
	{		
		$config['table_name'] = '#__eshop_taxes';		
				
		parent::__construct($config);
	}

	function store(&$data)
	{
		parent::store($data);
		$taxRateId = $data['id'];
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__eshop_taxcustomergroups')
			->where('tax_id = ' . intval($taxRateId));
		$db->setQuery($query);
		$db->query();
		if (isset($data['customergroup_id']))
		{
			$query->clear();
			$query->insert('#__eshop_taxcustomergroups')
				->columns('tax_id, customergroup_id');
			foreach ($data['customergroup_id'] as $groupId)
			{
				$query->values("$taxRateId, $groupId");
			}
			$db->setQuery($query);
			$db->query();			
		}
		
		return true;
	}
}