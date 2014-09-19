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
class EShopModelTaxclass extends EShopModel
{

	function store(&$data)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if ($data['id'])
		{
			$query->delete('#__eshop_taxrules')
				->where('taxclass_id = ' . (int) $data['id']);
			$db->setQuery($query);
			$db->query();
		}
		parent::store($data);
		//save new data
		if (isset($data['tax_id']))
		{
			$taxGroupId = $data['id'];
			$taxIds = $data['tax_id'];
			$baseonIds = $data['based_on'];
			$priorityIds = $data['priority'];
			$query->clear();
			$query->insert('#__eshop_taxrules')->columns('taxclass_id, tax_id, based_on, priority');
			foreach ($taxIds as $key => $taxId)
			{
				$baseonId = $db->quote($baseonIds[$key]);
				$priorityId = $db->quote($priorityIds[$key]);
				$query->values("$taxGroupId, $taxId, $baseonId, $priorityId");
			}
			$db->setQuery($query);
			$db->query();
		}
		
		return true;
	}

}