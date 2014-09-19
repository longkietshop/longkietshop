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
<script src="<?php echo JURI::base(); ?>components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<?php
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_PRODUCT_COMPARE'); ?></h1><br />
<?php
if (!count($this->products))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_COMPARE_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th colspan="<?php echo count($this->products) + 1; ?>"><?php echo JText::_('ESHOP_COMPARE_PRODUCT_DETAILS'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="20%" style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_PRODUCT'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
					?>
					<td>
						<a href="<?php echo $viewProductUrl; ?>">
							<?php echo $product['product_name']; ?>
						</a>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_IMAGE'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td style="text-align:center;">
						<img class="img-polaroid" src="<?php echo $product['image']; ?>" />
					</td>
					<?php
				}
				?>
			</tr>
			<?php
			if (EshopHelper::showPrice())
			{
				?>
				<tr>
					<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_PRICE'); ?></b></td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td>
							<?php
							if ($product['sale_price'])
							{
								?>
								<span class="base-price"><?php echo $product['base_price']; ?></span>&nbsp;
								<span class="sale-price"><?php echo $product['sale_price']; ?></span>
								<?php
							}
							else 
							{
								?>
								<span class="price"><?php echo $product['base_price']; ?></span>
								<?php
							}
							?>
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			?>	
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_MODEL'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['product_sku']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_BRAND'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['manufacturer']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_AVAILABILITY'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['availability']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_RATING'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<img src="components/com_eshop/assets/images/stars-<?php echo round($product['rating']); ?>.png" /><br />
						<?php echo sprintf(JText::_('ESHOP_COMPARE_NUM_REVIEWS'), $product['num_reviews']); ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_SHORT_DESCRIPTION'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['product_short_desc']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_DESCRIPTION'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['product_desc']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_WEIGHT'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['weight']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<tr>
				<td style="text-align:right;"><b><?php echo JText::_('ESHOP_COMPARE_DIMENSIONS'); ?></b></td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td>
						<?php echo $product['length'] . ' x ' . $product['width'] . ' x ' . $product['height']; ?>
					</td>
					<?php
				}
				?>
			</tr>
			<?php
			if (count($this->visibleAttributeGroups))
			{
				foreach ($this->visibleAttributeGroups as $key => $visibleAttributeGroup)
				{
					?>
					<tr>
						<th style="text-align: left;" colspan="<?php echo count($this->products) + 1; ?>"><?php echo $visibleAttributeGroup['attributegroup_name']; ?></th>
					</tr>
					<?php
					foreach ($visibleAttributeGroup['attribute_name'] as $attributeName)
					{
						?>
						<tr>
							<td style="text-align:right;"><?php echo $attributeName; ?></td>
							<?php
							foreach ($this->products as $product)
							{
								?>
								<td>
									<?php
										if (isset($product['attributes'][$visibleAttributeGroup['id']]['value'][$attributeName]))
										{
											echo $product['attributes'][$visibleAttributeGroup['id']]['value'][$attributeName];
										}
									?>
								</td>
								<?php
							}
							?>
						</tr>
						<?php
					}
				}
			}
			if (!EshopHelper::getConfigValue('catalog_mode'))
			{
				?>
				<tr>
					<td>&nbsp;</td>
					<?php
					foreach ($this->products as $product)
					{
						?>
						<td style="text-align:center;">
							<input id="add-to-cart-<?php echo $product['product_id']; ?>" type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product['product_id']; ?>, 1, '<?php echo JURI::base(); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" />
						</td>
						<?php
					}
					?>
				</tr>
				<?php
			}
			?>
			<tr>
				<td>&nbsp;</td>
				<?php
				foreach ($this->products as $product)
				{
					?>
					<td style="text-align:center;">
						<input type="button" class="btn btn-primary" onclick="removeFromCompare(<?php echo $product['product_id']; ?>);" value="<?php echo JText::_('ESHOP_REMOVE'); ?>" />
					</td>
					<?php
				}
				?>
			</tr>
		</tbody>
	</table>	
	<?php
}