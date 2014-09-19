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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form action="index.php?option=com_eshop&view=products" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo JText::_( 'ESHOP_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_RESET' ); ?></button>		
			</td>
			<td align="right" class="text-right;">
				<?php echo $this->lists['filter_state']; ?>
			</td>	
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th class="text_center" width="2%">
						<?php echo JText::_( 'ESHOP_NUM' ); ?>
					</th>
					<th class="text_center" width="2%">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_NAME'), 'b.product_name', $this->lists['order_Dir'], $this->lists['order'] ); ?>				
					</th>
					<th class="text_center" width="5%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_PRODUCT_SKU'), 'a.product_sku', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="10%">
						<?php echo JText::_('ESHOP_PRODUCT_PRICE'); ?>
					</th>
					<th class="text_center" width="20%">
						<?php echo JText::_('ESHOP_CATEGORY'); ?>
					</th>
					<th class="text_center" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_MANUFACTURER'), 'a.product_sku', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="5%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_FEATURED'), 'a.product_featured', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="5%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_PUBLISHED'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="2%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>													
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=product.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$published 	= JHtml::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'product.' );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $this->pagination->getRowOffset( $i ); ?>
					</td>
					<td class="text_center">
						<?php echo $checked; ?>
					</td>	
					<td class="text_left">																			
						<a href="<?php echo $link; ?>"><?php echo $row->product_name; ?></a>				
					</td>																			
					<td class="text_center">
						<?php echo $row->product_sku; ?>
					</td>
					<td class="text_center">																			
						<?php
						$productPriceArray = EshopHelper::getProductPriceArray($row->id, $row->product_price);
						if ($productPriceArray['salePrice'])
						{
							?>
							<span class="base-price"><?php echo $this->currency->format($productPriceArray['basePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>&nbsp;
							<span class="sale-price"><?php echo $this->currency->format($productPriceArray['salePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>
							<?php
						}
						else
						{
							?>
							<span class="price"><?php echo $this->currency->format($productPriceArray['basePrice'], EshopHelper::getConfigValue('default_currency_code')); ?></span>
							<?php
						}
						?>
					</td>
					<td class="text_center">
						<?php
						$categories = EshopHelper::getProductCategories($row->id);
						for ($j = 0; $m = count($categories), $j < $m; $j++) {
							$category = $categories[$j];
							$editCategoryLink = JRoute::_( 'index.php?option=com_eshop&task=category.edit&cid[]='. $category->id);
							$dividedChar = ($j < ($m - 1)) ? ' | ' : ''; 
							?>
							<a href='<?php echo $editCategoryLink; ?>'><?php echo $category->category_name; ?></a><?php echo $dividedChar; ?>
							<?php
						}
						?>
					</td>
					<td class="text_center">
						<?php
						$manufacturer = EshopHelper::getProductManufacturer($row->id);
						if (is_object($manufacturer))
						{
							$editManufacturerLink = JRoute::_( 'index.php?option=com_eshop&task=manufacturer.edit&cid[]='. $manufacturer->id);
							?>
							<a href='<?php echo $editManufacturerLink; ?>'><?php echo $manufacturer->manufacturer_name; ?></a>
							<?php
						}
						?>
					</td>
					<td class="text_center">
						<?php echo $this->toggle($row->product_featured, $i); ?>
					</td>
					<td class="text_center">
						<?php echo $published; ?>
					</td>
					<td class="text_center">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>
</form>