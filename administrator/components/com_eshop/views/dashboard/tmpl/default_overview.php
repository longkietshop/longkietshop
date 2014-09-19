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
?>
<table class="adminlist">
	<tr>
		<td><b><?php echo JText::_('ESHOP_TOTAL_SALES'); ?>:</b></td>
		<td class="text_right"><?php echo $this->overviewData['totalSales']; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('ESHOP_TOTAL_ORDERS'); ?>:</b></td>
		<td class="text_right"><?php echo $this->overviewData['totalOrders']; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('ESHOP_TOTAL_CUSTOMERS'); ?>:</b></td>
		<td class="text_right"><?php echo $this->overviewData['totalCustomers']; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('ESHOP_TOTAL_CATEGORIES'); ?>:</b></td>
		<td class="text_right"><?php echo $this->overviewData['totalCategories']; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('ESHOP_TOTAL_PRODUCTS'); ?>:</b></td>
		<td class="text_right"><?php echo $this->overviewData['totalProducts']; ?></td>
	</tr>
	<tr>
		<td><b><?php echo JText::_('ESHOP_TOTAL_MANUFACTURERS'); ?>:</b></td>
		<td class="text_right"><?php echo $this->overviewData['totalManufacturers']; ?></td>
	</tr>
</table>