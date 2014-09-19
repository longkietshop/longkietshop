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
	<thead>
		<tr>
			<th class="text_left">
				<?php echo JText::_('ESHOP_PRODUCT'); ?>
			</th>
			<th>
				<?php echo JText::_('ESHOP_NUMBER_RATES'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (count($this->topRatesData))
		{
			for ($i = 0, $n = count($this->topRatesData); $i < $n; $i++)
			{
				$row = $this->topRatesData[$i];
				$link		= JRoute::_('index.php?option=com_eshop&task=product.edit&cid[]=' . $row->id);
				?>
				<tr>
					<td class="text_left">
						<a href="<?php echo $link; ?>">
							<?php echo $this->escape($row->product_name); ?>
						</a>
					</td>
					<td class="text_center">
						<?php echo (int) $row->rates; ?>
					</td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td colspan="2">
					<?php echo JText::_('ESHOP_NO_PRODUCTS'); ?>
				</td>
			</tr>
			<?php
		}
		?>
	</tbody>
</table>