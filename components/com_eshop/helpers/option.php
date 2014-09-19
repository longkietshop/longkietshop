<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
/**
 * Options helper class
 *
 */
class EshopOption
{
	/**
	 * 
	 * Function to render an option input for a product
	 * @param int $productId
	 * @param int $optionId
	 * @param int $optionType
	 * @param int $taxClassId
	 * @return html code
	 */
	public static function renderOption($productId, $optionId, $optionType, $taxClassId)
	{
		$currency = new EshopCurrency();
		$tax = new EshopTax(EshopHelper::getConfig());
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__eshop_productoptions')
			->where('product_id = ' . intval($productId))
			->where('option_id = ' . intval($optionId));
		$db->setQuery($query);
		$productOptionId = $db->loadResult();
		$query->clear();
		$query->select('ovd.value, pov.id, pov.price, pov.price_sign')
			->from('#__eshop_optionvalues AS ov')
			->innerJoin('#__eshop_optionvaluedetails AS ovd ON (ov.id = ovd.optionvalue_id)')
			->innerJoin('#__eshop_productoptionvalues AS pov ON (ovd.optionvalue_id = pov.option_value_id)')
			->where('pov.product_option_id = ' . intval($productOptionId))
			->where('ovd.language = "' . JFactory::getLanguage()->getTag() . '"')
			->order('ov.ordering');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i = 0; $n = count($rows), $i < $n; $i++)
		{
			if (EshopHelper::showPrice() && $rows[$i]->price > 0)
				$rows[$i]->text = $rows[$i]->value.' ('.$rows[$i]->price_sign.$currency->format($tax->calculate($rows[$i]->price, $taxClassId, EshopHelper::getConfigValue('tax'))).')';
			else 
				$rows[$i]->text = $rows[$i]->value;
			$rows[$i]->value = $rows[$i]->id;
		}
		$optionHtml = '';
		if (!EshopHelper::getConfigValue('catalog_mode'))
		{
			$updatePrice = '';
			if (EshopHelper::getConfigValue('dynamic_price'))
				$updatePrice = ' onchange="updatePrice();"';
			switch ($optionType)
			{
				case 'Select':
					$options[] = JHtml::_('select.option', '', JText::_('ESHOP_PLEASE_SELECT'), 'value', 'text');
					$optionHtml = JHtml::_('select.genericlist', array_merge($options, $rows), 'options['.$productOptionId.']',
						array(
							'option.text.toHtml' => false,
							'option.value' => 'value',
							'option.text' => 'text',
							'list.attr' => ' class="inputbox"' . $updatePrice));
					break;
				case 'Checkbox':
					for ($i = 0; $n = count($rows), $i < $n; $i++)
					{
						$optionHtml .= '<label class="checkbox">';
						$optionHtml .= '<input type="checkbox" name="options['.$productOptionId.'][]" value="'.$rows[$i]->id.'"' . $updatePrice . '> '.$rows[$i]->text;
						$optionHtml .= '</label>';
					}
					break;
				case 'Radio':
					for ($i = 0; $n = count($rows), $i < $n; $i++)
					{
						$optionHtml .= '<label class="radio">';
						$optionHtml .= '<input type="radio" name="options['.$productOptionId.']" value="'.$rows[$i]->id.'"' .$updatePrice . '> '.$rows[$i]->text;
						$optionHtml .= '</label>';
					}
					break;
				case 'Text':
					$optionHtml .= '<input type="text" name="options['.$productOptionId.']" value="" />';
					break;
				case 'Textarea':
					$optionHtml .= '<textarea name="options['.$productOptionId.']" cols="40" rows="5"></textarea>';
					break;
				case 'File':
					$optionHtml .= '<input type="button" value="'.JText::_('ESHOP_UPLOAD_FILE').'" id="button-option-'.$productOptionId.'" class="btn btn-primary">';
					$optionHtml .= '<input type="hidden" name="options['.$productOptionId.']" value="" />';
					break;	
				case 'Date':
					$optionHtml .= JHtml::_('calendar', '', 'options['.$productOptionId.']', 'options['.$productOptionId.']', '%Y-%m-%d');
					break;
				case 'Datetime':
					$optionHtml .= JHtml::_('calendar', '', 'options['.$productOptionId.']', 'options['.$productOptionId.']', '%Y-%m-%d 00:00:00');
					break;
				default:
					break;
			}
		}
		else 
		{
			for ($i = 0; $n = count($rows), $i < $n; $i++)
			{
				echo $rows[$i]->text . '<br />';
			}
		}
		return $optionHtml;
	}
}
?>