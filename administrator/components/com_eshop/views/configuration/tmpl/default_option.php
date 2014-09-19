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
<table class="admintable adminform">
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_ITEMS'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_PAGE'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_PAGE_HELP'); ?></span>
			</td>
			<td>
				<input class="input-mini" type="text" name="catalog_limit" id="catalog_limit"  value="<?php echo $this->config->catalog_limit; ?>" />
			</td>
		</tr>
		<tr>
			<td width="50%">
				<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_ROW'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_DEFAULT_ITEMS_PER_ROW_HELP'); ?></span>
			</td>
			<td>
				<input class="input-mini" type="text" name="items_per_row" id="items_per_row"  value="<?php echo $this->config->items_per_row; ?>" />
			</td>
		</tr>
		<tr>
			<td width="50%">
				<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_CATALOG_MODE'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_CATALOG_MODE_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['catalog_mode']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_ADD_CATEGORY_PATH'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ADD_CATEGORY_PATH_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['add_category_path']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<span class="required">*</span><?php echo JText::_('ESHOP_CONFIG_DEFAULT_MENU_ITEM'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_DEFAULT_MENU_ITEM_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['default_menu_item']; ?>
			</td>
		</tr>
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_PRODUCTS'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_CATEGORY_PRODUCT_COUNT'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_CATEGORY_PRODUCT_COUNT_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['product_count']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ALLOW_REVIEWS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ALLOW_REVIEWS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['allow_reviews']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ALLOW_WISHLIST'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ALLOW_WISHLIST_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['allow_wishlist']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ALLOW_COMPARE'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ALLOW_COMPARE_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['allow_compare']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_DYNAMIC_PRICE'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_DYNAMIC_PRICE_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['dynamic_price']; ?>
			</td>
		</tr>
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_TAXES'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_DISPLAY_PRICES_TAX'); ?>:
			</td>
			<td>
				<?php echo $this->lists['tax']; ?>
			</td>
		</tr>		
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_USE_STORE_TAX_ADDRESS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_USE_STORE_TAX_ADDRESS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['tax_default']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_USE_CUSTOMER_TAX_ADDRESS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_USE_CUSTOMER_TAX_ADDRESS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['tax_customer']; ?>
			</td>
		</tr>
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_ACCOUNT'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_LOGIN_DISPLAY_PRICES'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_LOGIN_DISPLAY_PRICES_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['customer_price']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo  JText::_('ESHOP_CONFIG_CUSTOMER_GROUP'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_CUSTOMER_GROUP_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['customergroup_id']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo  JText::_('ESHOP_CONFIG_CUSTOMER_GROUPS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_CUSTOMER_GROUPS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['customer_group_display']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ACCOUNT_TERMS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ACCOUNT_TERMS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['account_terms']; ?>
			</td>
		</tr>	
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_CHECKOUT'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ALLOW_COUPON'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ALLOW_COUPON_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['allow_coupon']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_DISPLAY_WEIGHT_ON_CART_PAGE'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_DISPLAY_WEIGHT_ON_CART_PAGE_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['cart_weight']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_GUEST_CHECKOUT'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_GUEST_CHECKOUT_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['guest_checkout']; ?>
			</td>
		</tr>	
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_CHECKOUT_TERMS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_CHECKOUT_TERMS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['checkout_terms']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ORDER_EDITING'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ORDER_EDITING_HELP'); ?></span>
			</td>
			<td>
				<input class="text_area" type="text" name="order_edit" id="order_edit" size="3" value="<?php echo $this->config->order_edit; ?>" />
			</td>
		</tr>	
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_INVOICE_PREFIX'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_INVOICE_PREFIX_HELP'); ?></span>
			</td>
			<td>
				<input class="text_area" type="text" name="invoice_prefix" id="invoice_prefix" size="15" value="<?php echo $this->config->invoice_prefix; ?>" />
			</td>
		</tr>	
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_ORDER_STATUS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_ORDER_STATUS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['order_status_id']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_COMPLETE_ORDER_STATUS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_COMPLETE_ORDER_STATUS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['complete_status_id']; ?>
			</td>
		</tr>
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_STOCK'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_DISPLAY_STOCK'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_DISPLAY_STOCK_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['stock_display']; ?>
			</td>
		</tr>	
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_STOCK_WARNING'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_STOCK_WARNING_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['stock_warning']; ?>
			</td>
		</tr>	
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_STOCK_CHECKOUT'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_STOCK_CHECKOUT_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['stock_checkout']; ?>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_STOCK_STATUS'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_STOCK_STATUS_HELP'); ?></span>
			</td>
			<td>
				<?php echo $this->lists['stock_status_id']; ?>
			</td>
		</tr>
		<tr>
			<td class="key" colspan="2">
				<h2><?php echo JText::_('ESHOP_CONFIG_FILE'); ?></h2>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_FILE_EXTENSIONS_ALLOWED'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_FILE_EXTENSIONS_ALLOWED_HELP'); ?></span>
			</td>
			<td>
				<textarea name="file_extensions_allowed" id="file_extensions_allowed" rows="5" cols="40"><?php echo $this->config->file_extensions_allowed; ?></textarea>
			</td>
		</tr>
		<tr>
			<td width="50%">
				<?php echo JText::_('ESHOP_CONFIG_FILE_MIME_TYPES_ALLOWED'); ?>:<br>
				<span class="help"><?php echo JText::_('ESHOP_CONFIG_FILE_MIME_TYPES_ALLOWED_HELP'); ?></span>
			</td>
			<td>
				<textarea name="file_mime_types_allowed" id="file_mime_types_allowed" rows="5" cols="60" style="width: 400px;"><?php echo $this->config->file_mime_types_allowed; ?></textarea>
			</td>
		</tr>
</table>