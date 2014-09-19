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
//EshopHelper::chosen();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'order.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			Joomla.submitform(pressbutton, form);
		}
	}
	function paymentCountry(element, zoneId) {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=customer.country&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				jQuery('select[name=\'payment_country_id\']').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('.wait').remove();
			},
			success: function(json) {
				html = '<option value="0"><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
				if (json['zones'] != '') {
					for (i = 0; i < json['zones'].length; i++) {
	        			html += '<option value="' + json['zones'][i]['id'] + '"';
						if (json['zones'][i]['id'] == zoneId) {
		      				html += ' selected="selected"';
		    			}
		    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
					}
				}
				jQuery('select[name=\'payment_zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	function shippingCountry(element, zoneId) {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=customer.country&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				jQuery('select[name=\'shipping_country_id\']').after('<span class="wait">&nbsp;<img src="<?php echo JURI::root(); ?>administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('.wait').remove();
			},
			success: function(json) {
				html = '<option value="0"><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
				if (json['zones'] != '') {
					for (i = 0; i < json['zones'].length; i++) {
	        			html += '<option value="' + json['zones'][i]['id'] + '"';
						if (json['zones'][i]['id'] == zoneId) {
		      				html += ' selected="selected"';
		    			}
		    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
					}
				}
				jQuery('select[name=\'shipping_zone_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_GENERAL'); ?></a></li>
			<li><a href="#customer-details-page" data-toggle="tab"><?php echo JText::_('ESHOP_ORDER_CUSTOMER_DETAILS'); ?></a></li>
			<li><a href="#payment-details-page" data-toggle="tab"><?php echo JText::_('ESHOP_ORDER_PAYMENT_DETAILS'); ?></a></li>
			<li><a href="#shipping-details-page" data-toggle="tab"><?php echo JText::_('ESHOP_ORDER_SHIPPING_DETAILS'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general-page">
				<div class="span12">
					<table class="adminlist table table-bordered" style="text-align: center;">
						<thead>
							<tr>
								<th class="text_left"><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></th>
								<th class="text_left"><?php echo JText::_('ESHOP_MODEL'); ?></th>
								<th class="text_right"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
								<th class="text_right"><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
								<th class="text_right"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach ($this->lists['order_products'] as $product)
						{
							$options = $product->options;
							?>
							<tr>
								<td class="text_left">
									<?php
									echo '<b>' . $product->product_name . '</b>';
									for ($i = 0; $n = count($options), $i < $n; $i++)
									{
										if ($options[$i]->option_type == 'File' && $options[$i]->option_value != '')
										{
											echo '<br />- ' . $options[$i]->option_name . ': <a href="index.php?option=com_eshop&task=order.downloadFile&id=' . $options[$i]->id . '">' . $options[$i]->option_value . '</a>';
										}
										else
										{
											echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value;
										}
									}
									?>
								</td>
								<td class="text_left"><?php echo $product->product_sku; ?></td>
								<td class="text_right"><?php echo $product->quantity; ?></td>
								<td class="text_right">
									<?php echo $this->currency->format($product->price, $this->item->currency_code, $this->item->currency_exchanged_value); ?>
								</td>
								<td class="text_right">
									<?php echo $this->currency->format($product->total_price, $this->item->currency_code, $this->item->currency_exchanged_value); ?>
								</td>
							</tr>
							<?php
						}
						foreach ($this->lists['order_totals'] as $total)
						{
							?>
							<tr>
								<td colspan="4" class="text_right"><?php echo $total->title; ?>:</td>
								<td class="text_right"><?php echo $total->text; ?></td>
							</tr>
							<?php	
						}
						?>
						</tbody>
					</table>
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ORDER_PAYMENT_METHOD'); ?>
							</td>
							<td>
								<?php echo $this->item->payment_method_title; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ORDER_SHIPPING_METHOD'); ?>
							</td>
							<td>
								<?php echo $this->item->shipping_method_title; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ORDER_STATUS'); ?>
							</td>
							<td>
								<?php echo $this->lists['order_status_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_COMMENT'); ?>
							</td>
							<td>
								<textarea name="comment" cols="30" rows="5"><?php echo $this->item->comment; ?></textarea>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="customer-details-page">
				<div class="span6">
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_CUSTOMER'); ?>
							</td>
							<td>
								<?php echo $this->lists['customer_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_CUSTOMERGROUP'); ?>
							</td>
							<td>
								<?php echo $this->lists['customergroup_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_FIRST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="firstname" id="firstname" maxlength="32" value="<?php echo $this->item->firstname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_LAST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="lastname" id="lastname" maxlength="32" value="<?php echo $this->item->lastname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_EMAIL'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="email" id="email" maxlength="96" value="<?php echo $this->item->email; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_TELEPHONE'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="telephone" id="telephone" maxlength="32" value="<?php echo $this->item->telephone; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_FAX'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="fax" id="fax" maxlength="32" value="<?php echo $this->item->fax; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="payment-details-page">
				<div class="span6">
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_FIRST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_firstname" id="payment_firstname" maxlength="32" value="<?php echo $this->item->payment_firstname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_LAST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_lastname" id="payment_lastname" maxlength="32" value="<?php echo $this->item->payment_lastname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_COMPANY'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_company" id="payment_company" maxlength="32" value="<?php echo $this->item->payment_company; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_COMPANY_ID'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_company_id" id="payment_company_id" maxlength="32" value="<?php echo $this->item->payment_company_id; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_ADDRESS_1'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_address_1" id="payment_address_1" maxlength="32" value="<?php echo $this->item->payment_address_1; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ADDRESS_2'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_address_2" id="payment_address_2" maxlength="32" value="<?php echo $this->item->payment_address_2; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_CITY'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_city" id="payment_city" maxlength="32" value="<?php echo $this->item->payment_city; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_POST_CODE'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="payment_postcode" id="payment_postcode" maxlength="32" value="<?php echo $this->item->payment_postcode; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_COUNTRY'); ?>
							</td>
							<td>
								<?php echo $this->lists['payment_country_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_REGION_STATE'); ?>
							</td>
							<td>
								<?php echo $this->lists['payment_zone_id']; ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="shipping-details-page">
				<div class="span6">
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_FIRST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_firstname" id="shipping_firstname" maxlength="32" value="<?php echo $this->item->shipping_firstname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_LAST_NAME'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_lastname" id="shipping_lastname" maxlength="32" value="<?php echo $this->item->shipping_lastname; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_COMPANY'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_company" id="shipping_company" maxlength="32" value="<?php echo $this->item->shipping_company; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_COMPANY_ID'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_company_id" id="shipping_company_id" maxlength="32" value="<?php echo $this->item->shipping_company_id; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_ADDRESS_1'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_address_1" id="shipping_address_1" maxlength="32" value="<?php echo $this->item->shipping_address_1; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_ADDRESS_2'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_address_2" id="shipping_address_2" maxlength="32" value="<?php echo $this->item->shipping_address_2; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_CITY'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_city" id="shipping_city" maxlength="32" value="<?php echo $this->item->shipping_city; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_POST_CODE'); ?>
							</td>
							<td>
								<input class="input-memdium" type="text" name="shipping_postcode" id="shipping_postcode" maxlength="32" value="<?php echo $this->item->shipping_postcode; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_COUNTRY'); ?>
							</td>
							<td>
								<?php echo $this->lists['shipping_country_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_REGION_STATE'); ?>
							</td>
							<td>
								<?php echo $this->lists['shipping_zone_id']; ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>