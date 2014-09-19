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
$productsPerRow = EshopHelper::getConfigValue('items_per_row', 3);
$span = intval(12 / $productsPerRow);
?>
<script src="<?php echo JURI::base(); ?>components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script src="<?php echo JURI::base(); ?>components/com_eshop/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo JURI::base(); ?>components/com_eshop/assets/js/layout.js" type="text/javascript"></script>
<h2><?php echo JText::_('ESHOP_PRODUCTS'); ?></h2>
<div id="products-list-container" class="products-list-container block list">
	<div class="sortPagiBar row-fluid clearfix">
    	<div class="span3">
            <div class="btn-group hidden-phone">
            	<a rel="list" href="#" class="btn"><i class="icon-th-list"></i></a>
                <a rel="grid" href="#" class="btn"><i class="icon-th"></i></a>
            </div>
      	</div>
    </div>
    <div id="product_list" class="row-fluid clearfix">
		<div class="clearfix">
			<?php
	        if (!count($this->products))
	        {
	            ?>
	            <div class="no-content"><?php echo JText::_('ESHOP_NO_PRODUCTS'); ?></div>
	            <?php
	        }
	        else 
	        {
				$count = 0;
	            foreach ($this->products as $product)
	            {
	                ?>
	                <div class="span<?php echo $span; ?> ajax_block_product spanbox clearfix">
	                    <div class="img_block">                    	
	                        <a href="<?php echo JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id))); ?>">
	                            <img alt="<?php echo $product->product_name; ?>" src="<?php echo $product->image; ?>" />
	                        </a>
	                    </div>
	                    <div class="info_block">
	                    	<h5>
	                        <a href="<?php echo JRoute::_(EshopRoute::getProductRoute($product->id, EshopHelper::getProductCategory($product->id))); ?>">
	                            <?php echo $product->product_name;?>
	                        </a>
	                        </h5>
	                        <p class="product_desc"><?php echo $product->product_short_desc;?></p>
	                        <div class="content_price">
								<?php
	                            if (EshopHelper::showPrice())
	                            {
	                                ?>
	                                <p>
	                                    <?php
	                                    $productPriceArray = EshopHelper::getProductPriceArray($product->id, $product->product_price);
	                                    if ($productPriceArray['salePrice'])
	                                    {
	                                        ?>
	                                        <span class="base-price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
	                                        <span class="sale-price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['salePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
	                                        <?php
	                                    }
	                                    else
	                                    {
	                                        ?>
	                                        <span class="price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['basePrice'], $product->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
	                                        <?php
	                                    }
	                                    if (EshopHelper::getConfigValue('tax'))
	                                    {
	                                        ?>
	                                        <small>
	                                            <?php echo JText::_('ESHOP_EX_TAX'); ?>:
	                                            <?php
	                                            if ($productPriceArray['salePrice'])
	                                            {
	                                                echo $this->currency->format($productPriceArray['salePrice']);
	                                            }
	                                            else
	                                            {
	                                                echo $this->currency->format($productPriceArray['basePrice']);
	                                            }
	                                            ?>
	                                        </small>
	                                        <?php
	                                    }
	                                    ?>
	                                </p>
	                                <?php
	                            } ?>
	                        </div>
	                    </div>
	                    <div class="buttons">                        
	                        <?php if (!EshopHelper::getConfigValue('catalog_mode'))
	                        {
	                            ?>
	                            <p><input id="add-to-cart-<?php echo $product->id; ?>" type="button" class="btn btn-primary" onclick="addToCart(<?php echo $product->id; ?>, 1, '<?php echo JURI::base(); ?>');" value="<?php echo JText::_('ESHOP_ADD_TO_CART'); ?>" /></p>
	                            <p>
	                            <?php
	                            if (EshopHelper::getConfigValue('allow_wishlist'))
	                            {
	                                ?>
	                                <a class="btn button" style="cursor: pointer;" onclick="addToWishList(<?php echo $product->id; ?>)" title="<?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?>"><?php echo JText::_('ESHOP_ADD_TO_WISH_LIST'); ?></a>
	                                <?php
	                            }
	                        }
	                        if (EshopHelper::getConfigValue('allow_compare'))
	                        {
	                            ?>
	                            <a class="btn button" style="cursor: pointer;" onclick="addToCompare(<?php echo $product->id; ?>)" title="<?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?>"><?php echo JText::_('ESHOP_ADD_TO_COMPARE'); ?></a>
	                            </p>
	                            <?php
	                        }
	                        ?>
	                    </div>
	                </div>
	                <?php
					$count++;
					if ($count % $productsPerRow == 0 && $count < count($this->products))
					{
						?>
	                    </div>
						<div class="clearfix">
						<?php
					}
				}
			}
			?>
		</div>
	</div>
</div>