<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EshopShipping
{

	/**
	 * 
	 * Function to get Costs, passed by reference to update
	 * @param  array $totalData
	 * @param  float $total
	 * @param  array $taxes
	 */
	public function getCosts(&$totalData, &$total, &$taxes)
	{
		$session = JFactory::getSession();
		$currency = new EshopCurrency();
		$shippingMethod = $session->get('shipping_method');
		if (is_array($shippingMethod))
		{
			$totalData[] = array(
				'name'		=> 'shipping',
				'title'		=> $shippingMethod['title'],
				'text'		=> $currency->format($shippingMethod['cost']),
				'value'		=> $shippingMethod['cost']);
			
			if (!empty($shippingMethod['taxclass_id']))
			{
				$tax = new EshopTax(EshopHelper::getConfig());
				$taxRates = $tax->getTaxRates($shippingMethod['cost'], $shippingMethod['taxclass_id']);
				foreach ($taxRates as $taxRate)
				{
					if (!isset($taxes[$taxRate['tax_rate_id']]))
					{
						$taxes[$taxRate['tax_rate_id']] = $taxRate['amount'];
					}
					else
					{
						$taxes[$taxRate['tax_rate_id']] += $taxRate['amount'];
					}
				}
			}
			$total += $shippingMethod['cost'];
		}
	}
}