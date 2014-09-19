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
<h1><?php echo JText::_('ESHOP_MY_WISHLIST'); ?></h1><br />
<?php
if (!count($this->products))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_WISHLIST_EMPTY'); ?></div>
	<?php
}
else
{
	?>
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('ESHOP_IMAGE'); ?></th>
				<th><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
				<th><?php echo JText::_('ESHOP_MODEL'); ?></th>
				<th><?php echo JText::_('ESHOP_AVAILABILITY'); ?></th>
				<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
				<th><?php echo JText::_('ESHOP_ACTION'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->products as $product)
			{
				$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
				?>
				<tr>
					<td class="muted center_text">
						<a href="<?php echo $viewProductUrl; ?>">
							<img class="img-polaroid" src="<?php echo $product->image; ?>" />
						</a>
					</td>
					<td>
						<a href="<?php echo $viewProductUrl; ?>">
							<?php echo $product->product_name; ?>
						</a>
					</td>
					<td><?php echo $product->product_sku; ?></td>
					<td>
						<?php echo $product->availability; ?>
					</td>
					<td><?php echo $this->currency->format($this->tax->calculate($product->product_price, $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></td>
					<td>
						<?php
						if (!EshopHelper::getConfigValue('catalog_mode'))
						{
							?>
							<input id="add-to-cart-<?php echo $product->id; ?>" type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo JURI::base(); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" />
							<?php
						}
						?>
						<input type="button" class="btn btn-primary" onclick="removeFromWishlist(<?php echo $product->id; ?>);" value="<?php echo JText::_('ESHOP_REMOVE'); ?>" />
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>	
	<?php
}