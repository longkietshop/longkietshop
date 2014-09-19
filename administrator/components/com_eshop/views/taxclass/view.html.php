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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewTaxclass extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		if ($item->id)
		{
			$query = $db->getQuery(true);
			$query->select("tax_id, based_on, priority")
				->from("#__eshop_taxrules")
				->where("taxclass_id=" . (int) $item->id);
			$db->setQuery($query);
			$taxrateIds = $db->loadObjectList();
			$query->clear();
			$query->select("id, tax_name AS name")
				->from("#__eshop_taxes")
				->where("published = 1");
			$db->setQuery($query);
			$taxrates = array();
			$taxrates = array_merge($taxrates, $db->loadObjectList());
			$baseonOptions = array();
			$baseonOptions[] = JHtml::_('select.option', 'shipping', JText::_('ESHOP_SHIPPING_ADDRESS'));
			$baseonOptions[] = JHtml::_('select.option', 'payment', JText::_('ESHOP_PAYMENT_ADDRESS'));
			$baseonOptions[] = JHtml::_('select.option', 'store', JText::_('ESHOP_STORE_ADDRESS'));
			$this->baseonOptions = $baseonOptions;
			$this->taxrates = $taxrates;
			$this->taxrateIds = $taxrateIds;
		}
		JFactory::getDocument()->addScriptDeclaration(EshopHtmlHelper::getTaxrateOptionsJs())->addScriptDeclaration(
			EshopHtmlHelper::getBaseonOptionsJs());
		EshopHelper::chosen();
	}
}