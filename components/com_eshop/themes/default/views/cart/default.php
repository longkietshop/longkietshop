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
if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
elseif (!$this->stock)
{
	?>
	<div class="warning"><?php echo JText::_('ESHOP_CART_STOCK_WARNING'); ?></div>
	<?php
}
?>
<h1>
	<?php echo JText::_('ESHOP_SHOPPING_CART'); ?>
	<?php
	if ($this->weight)
	{
		echo ' (' . $this->weight . ')';
	}
	?>
</h1><br />
<?php
if (!count($this->cartData))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_CART_EMPTY'); ?></div>
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
				<th><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
				<th><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></th>
				<th><?php echo JText::_('ESHOP_TOTAL'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($this->cartData as $key => $product)
			{
				$optionData = $product['option_data'];
				$viewProductUrl = JRoute::_(EshopRoute::getProductRoute($product['product_id'], EshopHelper::getProductCategory($product['product_id'])));
				?>
				<tr>
					<td class="muted center_text">
						<a href="<?php echo $viewProductUrl; ?>">
							<img class="img-polaroid" src="<?php echo $product['image']; ?>" />
						</a>
					</td>
					<td>
						<a href="<?php echo $viewProductUrl; ?>">
							<?php echo $product['product_name']; ?>
						</a>
						<?php
						if (!$product['stock'] && !EshopHelper::getConfigValue('stock_checkout'))
						{
							?>
							<span class="stock">***</span>
							<?php
						}
						?>
						<br />	
						<?php
						for ($i = 0; $n = count($optionData), $i < $n; $i++)
						{
							echo '- ' . $optionData[$i]['option_name'] . ': ' . $optionData[$i]['option_value'] . '<br />';
						}
						?>
					</td>
					<td><?php echo $product['product_sku']; ?></td>
					<td>
						<input type="text" class="input-mini" value="<?php echo $product['quantity']; ?>" name="quantity[<?php echo $key; ?>]" id="quantity_<?php echo $key; ?>">
						<input type="image" alt="<?php echo JText::_('ESHOP_UPDATE'); ?>" title="<?php echo JText::_('ESHOP_UPDATE'); ?>" src="<?php echo JURI::base(); ?>components/com_eshop/assets/images/update.png" onclick="updateCart('<?php echo $key; ?>');" />
						<a class="eshop-remove-item-cart" href="#" id="<?php echo $key; ?>">
							<img alt="<?php echo JText::_('ESHOP_REMOVE'); ?>" title="<?php echo JText::_('ESHOP_REMOVE'); ?>" src="<?php echo JURI::base(); ?>components/com_eshop/assets/images/remove.png" />
						</a>
					</td>
					<td>
						<?php
						if (EshopHelper::showPrice())
							echo $this->currency->format($this->tax->calculate($product['price'], $product['product_taxclass_id'], EshopHelper::getConfigValue('tax')));
						?>
					</td>
					<td>
						<?php
						if (EshopHelper::showPrice())
							echo $this->currency->format($this->tax->calculate($product['total_price'], $product['product_taxclass_id'], EshopHelper::getConfigValue('tax')));
						?>
					</td>
				</tr>
				<?php
			}
			if (EshopHelper::showPrice())
			{
				foreach ($this->totalData as $data)
				{
					?>
					<tr>
						<td colspan="5" style="text-align: right;"><?php echo $data['title']; ?>:</td>
						<td><strong><?php echo $data['text']; ?></strong></td>
					</tr>
					<?php	
				}
			}
			?>
		</tbody>
	</table>
	<?php
	if (EshopHelper::getConfigValue('allow_coupon'))
	{
		?>
		<table class="table table-bordered table-striped">
			<tbody>
				<tr>
					<td class="form-horizontal">
						<div class="control-group">
							<label for="coupon_code" class="control-label"><strong><?php echo JText::_('ESHOP_COUPON_TEXT'); ?>: </strong></label>
							<div class="controls">
								<input type="text" id="coupon_code" name="coupond_code" class="input-xlarge" value="<?php echo $this->coupon_code; ?>">
								<button type="button" class="btn btn-small btn-primary" onclick="applyCoupon();"><?php echo JText::_('ESHOP_COUPON_APPLY'); ?></button>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	if ($this->shipping_required)
	{
		?>
		<table class="table table-bordered table-striped">
			<tbody>
				<tr>
					<th><?php echo JText::_('ESHOP_SHIPPING_ESTIMATE_TEXT'); ?></th>
				</tr>
				<tr>
					<td class="form-horizontal">
						<div class="control-group">
							<label for="country_id" class="control-label"><span class="required">*</span><strong><?php echo JText::_('ESHOP_COUNTRY'); ?>:</strong></label>
							<div class="controls">
								<?php echo $this->lists['country_id']; ?>
							</div>
						</div>
						<div class="control-group">
							<label for="zone_id" class="control-label"><span class="required">*</span><strong><?php echo JText::_('ESHOP_REGION_STATE'); ?>:</strong></label>
							<div class="controls">
								<?php echo $this->lists['zone_id']; ?>
							</div>
						</div>
						<div class="control-group">
							<label for="postcode" class="control-label"><span class="required" id="postcode-required" style="display: none;">*</span><strong><?php echo JText::_('ESHOP_POST_CODE'); ?>:</strong></label>
							<div class="controls">
								<input class="input-small" name="postcode" id="postcode" value="<?php echo $this->postcode; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button type="button" id="get-quotes" class="btn btn-small btn-primary"><?php echo JText::_('ESHOP_GET_QUOTES'); ?></button>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	?>
	<a class="btn btn-primary" href="<?php echo JRoute::_(EshopRoute::getViewRoute('frontpage')); ?>"><?php echo JText::_('ESHOP_CONTINUE_SHOPPING'); ?></a>
	<a class="btn btn-primary pull-right" href="<?php echo JRoute::_(EshopRoute::getViewRoute('checkout')); ?>"><?php echo JText::_('ESHOP_CHECKOUT'); ?></a>
	
	<script type="text/javascript">
		//Function to update cart
		function updateCart(key)
		{
			jQuery.ajax({
				type: 'POST',
				url: 'index.php?option=com_eshop&task=cart.update',
				data: 'key=' + key + '&quantity=' + document.getElementById('quantity_' + key).value,
				success: function() {
					window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
			  	}
			});
		}

		(function($) {
			//Ajax remove cart item
			$('.eshop-remove-item-cart').bind('click', function() {
				var id = $(this).attr('id');				
				$.ajax({
					type :'POST',
					url  : 'index.php?option=com_eshop&task=cart.remove&key=' +  id + '&redirect=1',
					success : function() {
						window.location.href = '<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>';
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			});
		})(jQuery)
		<?php
		if (EshopHelper::getConfigValue('allow_coupon'))
		{
			?>
			//Function to apply coupon
			function applyCoupon()
			{
				jQuery.ajax({
					type: 'POST',
					url: 'index.php?option=com_eshop&task=cart.applyCoupon',
					data: 'coupon_code=' + document.getElementById('coupon_code').value,
					success: function() {
						window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
				  	}
				});
			}
			<?php
		}
		if ($this->shipping_required)
		{
			?>
			jQuery('select[name=\'country_id\']').bind('change', function() {
				jQuery.ajax({
					url: 'index.php?option=com_eshop&task=cart.getZones&country_id=' + this.value,
					dataType: 'json',
					beforeSend: function() {
						jQuery('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						jQuery('.wait').remove();
					},
					success: function(json) {
						if (json['postcode_required'] == '1')
						{
							jQuery('#postcode-required').show();
						}
						else
						{
							jQuery('#postcode-required').hide();
						}
						html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
						if (json['zones'] != '')
						{
							for (var i = 0; i < json['zones'].length; i++)
							{
			        			html += '<option value="' + json['zones'][i]['id'] + '"';
								if (json['zones'][i]['id'] == '<?php $this->shipping_zone_id; ?>')
								{
				      				html += ' selected="selected"';
				    			}
				    			html += '>' + json['zones'][i]['zone_name'] + '</option>';
							}
						}
						jQuery('select[name=\'zone_id\']').html(html);
					}
				});
			});
			
			//Function to apply shipping
			function applyShipping()
			{
				var shippingMethod = document.getElementsByName('shipping_method');
				var validated = false;
				var selectedShippingMethod = '';
				for (var i = 0, length = shippingMethod.length; i < length; i++)
				{
					if (shippingMethod[i].checked)
					{
						validated = true;
						selectedShippingMethod = shippingMethod[i].value;
						break;
				    }
				}
				if (!validated)
				{
					alert('<?php echo JText::_('ESHOP_ERROR_SHIPPING_METHOD'); ?>');
					return;
				}
				else
				{
					jQuery.ajax({
						type: 'POST',
						url: 'index.php?option=com_eshop&task=cart.applyShipping',
						data: 'shipping_method=' + selectedShippingMethod,
						success: function() {
							window.location.href = "<?php echo JRoute::_(EshopRoute::getViewRoute('cart')); ?>";
					  	}
					});
				}
			}
	
			//Function to get quotes
			jQuery('#get-quotes').bind('click', function() {
				jQuery.ajax({
					type: 'POST',
					url: 'index.php?option=com_eshop&task=cart.getQuote',
					data: 'country_id=' + jQuery('select[name=\'country_id\']').val() + '&zone_id=' + jQuery('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent(jQuery('input[name=\'postcode\']').val()),
					dataType: 'json',
					success: function(json) {
						jQuery(' .error').remove();
						if (json['error'])
						{
							if (json['error']['warning'])
							{
								jQuery.colorbox({
									overlayClose: true,
									opacity: 0.5,
									width: '400px',
									height: '200px',
									href: false,
									html: json['error']['warning']
								});
							}
							if (json['error']['country'])
							{
								jQuery('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
							}
							if (json['error']['zone'])
							{
								jQuery('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
							}
							if (json['error']['postcode'])
							{
								jQuery('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
							}
						}
						if (json['shipping_methods'])
						{
							//Prepare html for shipping methods list here
							html = '<div>';
							html += '<h2><?php echo JText::_('ESHOP_SHIPPING_METHOD_TITLE'); ?></h2>';
							html += '<form action="" method="post" enctype="multipart/form-data" name="shipping_form">';
							for (i in json['shipping_methods'])
							{
								html += '<div>';
								html += '<strong>' + json['shipping_methods'][i]['title'] + '</strong><br />';
								if (!json['shipping_methods'][i]['error'])
								{
									for (j in json['shipping_methods'][i]['quote'])
									{
										var checkedStr = ' ';
										if (json['shipping_methods'][i]['quote'][j]['name'] == '<?php echo $this->shipping_method; ?>')
										{
											checkedStr = " checked = 'checked' ";
										}
										html += '<label class="radio">';
										html += '<input type="radio" value="' + json['shipping_methods'][i]['quote'][j]['name'] + '" name="shipping_method"' + checkedStr +'/>';
										html += json['shipping_methods'][i]['quote'][j]['title'];
										html += ' (';
										html += json['shipping_methods'][i]['quote'][j]['text'];
										html += ')';
										html += '</label>';
									}
								}
								else
								{
									html += json['shipping_methods'][i]['error'];
								}
								html += '</div>';
							}
							html += '<input class="btn btn-small btn-primary" type="button" onclick="applyShipping();" value="<?php echo JText::_('ESHOP_SHIPPING_APPLY'); ?>">';
							html += '</form>';
							html += '</div>'
							jQuery.colorbox({
								overlayClose: true,
								opacity: 0.5,
								width: '700px',
								height: '400px',
								href: false,
								html: html
							});
						}
				  	}
				});
			});
			<?php
		}
		?>
	</script>
	<?php
}