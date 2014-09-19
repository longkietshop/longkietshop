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
 *
 * @package Joomla
 * @subpackage EShop
 * @since 1.5
 */
class EShopViewProduct extends EShopView
{

	function display($tpl = null)
	{
		$item = $this->get('Data');
		$tax = new EshopTax(EshopHelper::getConfig());
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		if (JRequest::getVar('options'))
		{
			$options = array_filter(JRequest::getVar('options'));
		}
		else
		{
			$options = array();
		}
		$optionPrice = 0;
		foreach ($options as $productOptionId => $optionValue)
		{
			$query->clear();
			$query->select('po.id, po.option_id, o.option_type')
				->from('#__eshop_productoptions AS po')
				->innerJoin('#__eshop_options AS o ON (po.option_id = o.id)')
				->where('po.id = ' . intval($productOptionId))
				->where('po.product_id = ' . intval($item->id));
			$db->setQuery($query);
			$optionRow = $db->loadObject();
			if (is_object($optionRow))
			{
				if ($optionRow->option_type == 'Select' || $optionRow->option_type == 'Radio')
				{
					$query->clear();
					$query->select('price, price_sign')
						->from('#__eshop_productoptionvalues')
						->where('id = ' . intval($optionValue))
						->where('product_option_id = ' . intval($productOptionId));
					$db->setQuery($query);
					$optionValueRow = $db->loadObject();
					if (is_object($optionValueRow))
					{
						//Calculate option price
						if ($optionValueRow->price_sign == '+')
						{
							$optionPrice += $optionValueRow->price;
						}
						elseif ($optionValueRow->price_sign == '-')
						{
							$optionPrice -= $optionValueRow->price;
						}
					}
				}
				elseif ($optionRow->option_type == 'Checkbox')
				{
					foreach ($optionValue as $productOptionValueId)
					{
						$query->clear();
						$query->select('price, price_sign')
							->from('#__eshop_productoptionvalues')
							->where('product_option_id = ' . intval($productOptionId))
							->where('id = ' . intval($productOptionValueId));
						$db->setQuery($query);
						$optionValueRow = $db->loadObject();
						if (is_object($optionValueRow))
						{
							//Calculate option price
							if ($optionValueRow->price_sign == '+')
							{
								$optionPrice += $optionValueRow->price;
							}
							elseif ($optionValueRow->price_sign == '-')
							{
								$optionPrice -= $optionValueRow->price;
							}
						}
					}
				}
			}
		}
		$item->product_price = $item->product_price + $optionPrice;
		
		$this->tax = $tax;
		$this->item = $item;
		$this->option_price = $optionPrice;
		$this->currency = new EshopCurrency();
		
		parent::display($tpl);
	}
}