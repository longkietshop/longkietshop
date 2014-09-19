<?php
/**
 * @version		1.0.4
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="eshop-product<?php echo $params->get( 'moduleclass_sfx' ); ?>">
	<?php if($headerText){?>
		<div class="eshopheader"><?php echo $headerText; ?></div>
	<?php }?>
		<ul>
		<?php
			foreach ($items as $key => $product)
			{
				$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id)));
				// Image
				if ($product->product_image && JFile::exists(JPATH_ROOT.'/media/com_eshop/products/' . $product->product_image))
				{
					$image = EshopHelper::resizeImage($product->product_image, JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
				}
				else
				{
					$image = EshopHelper::resizeImage('no-image.png', JPATH_ROOT . '/media/com_eshop/products/', $thumbnailWidth, $thumbnailHeight);
				}
				$image = JURI::base() . 'media/com_eshop/products/resized/' . $image;
				if ($key%$numberProductinrow == 0) echo '<div class="clear"></div>';
		 ?>
			<li class="eshop_product" style="width: <?php echo (int) 100/$numberProductinrow - 1; ?>%">
				<a href="<?php echo $viewProductUrl; ?>">
					<img class="img-polaroid" alt="<?php echo $product->product_name; ?>" src="<?php echo $image; ?>">
				</a>
				<div class="clear"></div>
				<a href="<?php echo $viewProductUrl; ?>" class="link" data-original-title="<?php echo $product->product_short_desc; ?>">
					<?php echo $product->product_name; ?>
				</a>
				<div class="clear"></div>
				<?php
				if ($showPrice == 1 && EshopHelper::showPrice())
				{
					$productPriceArray = EshopHelper::getProductPriceArray($product->id, $product->product_price); 
					if ($productPriceArray['salePrice'])
					{
						?>
						<span class="base-price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id)); ?></span>&nbsp;
						<span class="sale-price"><?php echo $currency->format($tax->calculate($productPriceArray['salePrice'], $product->product_taxclass_id)); ?></span>
						<?php
					}
					else
					{
						?>
						<span class="price"><?php echo $currency->format($tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id)); ?></span>
						<?php
					}
				}
				?>
				<div class="clear"></div>
				<?php
					if($showAddcart == 1 && !EshopHelper::getConfigValue('catalog_mode'))
					{ 
				?>
					<button id="add-to-cart-<?php echo $product->id; ?>" class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo JURI::base(); ?>'); "><?php echo JText::_('ESHOP_ADD_TO_CART'); ?></button>
				<?php 
					}//end if show add to cart
				?>
				<div class="clear"></div>
			</li>
		<?php
			} //end foreach
		?>
		</ul>
	<?php if($footerText){ ?>
		<div class="eshopfooter"><?php echo $footerText; ?></div>
	<?php }?>
</div>
<div class="clear"></div>
<style>
	.eshop-product ul li{ float: left; list-style: none; padding: 0 0 20px 0; text-align: center;}
	.clear{clear: both;}
</style>
