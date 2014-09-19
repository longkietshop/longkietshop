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
<h1><?php echo JText::_('ESHOP_CHECKOUT'); ?></h1><br />
<div class="row-fluid">
	<div id="checkout-options">
		<div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_1'); ?></div>
		<div class="checkout-content"></div>
	</div>
	<div id="payment-address">
		<div class="checkout-heading">
			<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_REGISTER'); ?>
		</div>
		<div class="checkout-content"></div>
	</div>
	<?php
	if ($this->shipping_required)
	{
		?>
		<div id="shipping-address">
			<div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_3'); ?></div>
			<div class="checkout-content"></div>
		</div>
		<div id="shipping-method">
			<div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_4'); ?></div>
			<div class="checkout-content"></div>
		</div>
		<?php
	}
	?>
	<div id="payment-method">
		<div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_5'); ?></div>
		<div class="checkout-content"></div>
	</div>
	<div id="confirm">
		<div class="checkout-heading"><?php echo JText::_('ESHOP_CHECKOUT_STEP_6'); ?></div>
		<div class="checkout-content"></div>
	</div>
</div>
<script type="text/javascript">
	//Script to change Payment Address heading when changing checkout options between Register and Guest
	jQuery('#checkout-options .checkout-content input[name=\'account\']').live('change', function() {
		if (jQuery(this).attr('value') == 'register') {
			jQuery('#payment-address .checkout-heading').html('<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_REGISTER'); ?>');
		} else {
			jQuery('#payment-address .checkout-heading').html('<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_GUEST'); ?>');
		}
	});
	//Script to allow Edit step
	jQuery('.checkout-heading a').live('click', function() {
		jQuery('.checkout-content').slideUp('slow');
		jQuery(this).parent().parent().find('.checkout-content').slideDown('slow');
	});
	//If user is not logged in, then show login layout
	<?php
	if (!$this->user->get('id'))
	{
	?>
	jQuery(document).ready(function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&view=checkout&layout=login&format=raw',
			dataType: 'html',
			success: function(html) {
				jQuery('#checkout-options .checkout-content').html(html);
				jQuery('#checkout-options .checkout-content').slideDown('slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	//Else, show payment address layout
	<?php
	}
	else
	{
		?>
	jQuery('#payment-address .checkout-heading').html('<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_GUEST'); ?>');
	jQuery(document).ready(function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw',
			dataType: 'html',
			success: function(html) {
				jQuery('#payment-address .checkout-content').html(html);
				jQuery('#payment-address .checkout-content').slideDown('slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	<?php
	}
	?>
	
	//Checkout options - will run if user choose Register Account or Guest Checkout
	jQuery('#button-account').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&view=checkout&layout=' + jQuery('input[name=\'account\']:checked').attr('value') + '&format=raw',
			dataType: 'html',
			beforeSend: function() {
				jQuery('#button-account').attr('disabled', true);
				jQuery('#button-account').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('#button-account').attr('disabled', false);
				jQuery('.wait').remove();
			},
			success: function(html) {
				jQuery('#payment-address .checkout-content').html(html);
				jQuery('#checkout-options .checkout-content').slideUp('slow');
				jQuery('#payment-address .checkout-content').slideDown('slow');
				jQuery('.checkout-heading a').remove();
				jQuery('#checkout-options .checkout-heading').append('<a><?php echo Jtext::_('ESHOP_EDIT'); ?></a>');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	//Login - will run if user choose login with an existed account
	jQuery('#button-login').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.login',
			type: 'post',
			data: jQuery('#checkout-options #login :input'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-login').attr('disabled', true);
				jQuery('#button-login').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},	
			complete: function() {
				jQuery('#button-login').attr('disabled', false);
				jQuery('.wait').remove();
			},				
			success: function(json) {
				jQuery('.warning, .error').remove();
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					jQuery('#checkout-options .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
					jQuery('.warning').fadeIn('slow');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	// Register
	jQuery('#button-register').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.register',
			type: 'post',
			data: jQuery('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-register').attr('disabled', true);
				jQuery('#button-register').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('#button-register').attr('disabled', false); 
				jQuery('.wait').remove();
			},			
			success: function(json) {
				jQuery('.warning, .error').remove();
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
					//Firstname error
					if (json['error']['firstname']) {
						jQuery('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
					}
					//Lastname error
					if (json['error']['lastname']) {
						jQuery('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
					}
					//Email error
					if (json['error']['email']) {
						jQuery('#payment-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
					}
					//Telephone error
					if (json['error']['telephone']) {
						jQuery('#payment-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
					}
					//Company ID error	
					if (json['error']['company_id']) {
						jQuery('#payment-address input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
					}
					//Address 1 error														
					if (json['error']['address_1']) {
						jQuery('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
					}
					//City error
					if (json['error']['city']) {
						jQuery('#payment-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
					}	
					//Postcode error
					if (json['error']['postcode']) {
						jQuery('#payment-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
					}	
					//Country error
					if (json['error']['country']) {
						jQuery('#payment-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
					}	
					//Zone error
					if (json['error']['zone']) {
						jQuery('#payment-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
					}
					//Username error
					if (json['error']['username']) {
						jQuery('#payment-address input[name=\'username\']').after('<span class="error">' + json['error']['username'] + '</span>');
					}
					//Existed username error
					if (json['error']['username_existed']) {
						jQuery('#payment-address input[name=\'username\']').after('<span class="error">' + json['error']['username_existed'] + '</span>');
					} 
					//Password error
					if (json['error']['password']) {
						jQuery('#payment-address input[name=\'password1\']').after('<span class="error">' + json['error']['password'] + '</span>');
					}
					//Confirm password error
					if (json['error']['confirm']) {
						$('#payment-address input[name=\'password2\']').after('<span class="error">' + json['error']['confirm'] + '</span>');
					}																																	
				} else {
					<?php
					//If shipping required, then we must considering Step 3: Delivery Details and Step 4: Delivery Method
					if ($this->shipping_required)
					{
					?>				
						var shipping_address = jQuery('#payment-address input[name=\'shipping_address\']:checked').attr('value');
						//If shipping address is same as billing address, then ignore Step 3: Delivery Details, go to Step 4: Delivery Method
						if (shipping_address) {
							jQuery.ajax({
								url: 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw',
								dataType: 'html',
								success: function(html) {
									jQuery('#shipping-method .checkout-content').html(html);
									jQuery('#payment-address .checkout-content').slideUp('slow');
									jQuery('#shipping-method .checkout-content').slideDown('slow');
									jQuery('#checkout-options .checkout-heading a').remove();
									jQuery('#payment-address .checkout-heading a').remove();
									jQuery('#shipping-address .checkout-heading a').remove();
									jQuery('#shipping-method .checkout-heading a').remove();
									jQuery('#payment-method .checkout-heading a').remove();
									jQuery('#shipping-address .checkout-heading').append('<a><?php echo Jtext::_('ESHOP_EDIT'); ?></a>');
									jQuery('#payment-address .checkout-heading').append('<a><?php echo Jtext::_('ESHOP_EDIT'); ?></a>');
									//Update shipping address for Step 3: Delivery Details
									jQuery.ajax({
										url: 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw',
										dataType: 'html',
										success: function(html) {
											jQuery('#shipping-address .checkout-content').html(html);
										},
										error: function(xhr, ajaxOptions, thrownError) {
											alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						} else {
							//Else, show Step 3: Delivery Details
							jQuery.ajax({
								url: 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw',
								dataType: 'html',
								success: function(html) {
									jQuery('#shipping-address .checkout-content').html(html);
									jQuery('#payment-address .checkout-content').slideUp('slow');
									jQuery('#shipping-address .checkout-content').slideDown('slow');
									jQuery('#checkout-options .checkout-heading a').remove();
									jQuery('#payment-address .checkout-heading a').remove();
									jQuery('#shipping-address .checkout-heading a').remove();
									jQuery('#shipping-method .checkout-heading a').remove();
									jQuery('#payment-method .checkout-heading a').remove();
									jQuery('#payment-address .checkout-heading').append('<a><?php echo Jtext::_('ESHOP_EDIT'); ?></a>');	
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});			
						}
					<?php
					}
					else
					{
					//Else, we go to Step 5: Payment Method
					?>
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#payment-method .checkout-content').html(html);
							jQuery('#payment-address .checkout-content').slideUp('slow');
							jQuery('#payment-method .checkout-content').slideDown('slow');
							jQuery('#checkout-options .checkout-heading a').remove();
							jQuery('#payment-address .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});					
					<?php
					}
					?>
					//Finally, we must update payment address
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#payment-address .checkout-content').html(html);
							jQuery('#payment-address .checkout-heading span').html('<?php echo JText::_('ESHOP_CHECKOUT_STEP_2_REGISTER'); ?>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	// Payment Address
	jQuery('#button-payment-address').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.processPaymentAddress',
			type: 'post',
			data: jQuery('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-payment-address').attr('disabled', true);
				jQuery('#button-payment-address').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('#button-payment-address').attr('disabled', false);
				jQuery('.wait').remove();
			},
			success: function(json) {
				jQuery('.warning, .error').remove();
				
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
					//Firstname error
					if (json['error']['firstname']) {
						jQuery('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
					}
					//Lastname error
					if (json['error']['lastname']) {
						jQuery('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
					}
					//Address 1 error
					if (json['error']['address_1']) {
						jQuery('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
					}
					//City error
					if (json['error']['city']) {
						jQuery('#payment-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
					}	
					//Postcode error
					if (json['error']['postcode']) {
						jQuery('#payment-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
					}	
					//Country error
					if (json['error']['country']) {
						jQuery('#payment-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
					}	
					//Zone error
					if (json['error']['zone']) {
						jQuery('#payment-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
					}
				} else {
					<?php
					if ($this->shipping_required)
					{
					?>
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#shipping-address .checkout-content').html(html);
							jQuery('#payment-address .checkout-content').slideUp('slow');
							jQuery('#shipping-address .checkout-content').slideDown('slow');
							jQuery('#payment-address .checkout-heading a').remove();
							jQuery('#shipping-address .checkout-heading a').remove();
							jQuery('#shipping-method .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
					<?php
					}
					else
					{
					?>
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#payment-method .checkout-content').html(html);
							jQuery('#payment-address .checkout-content').slideUp('slow');
							jQuery('#payment-method .checkout-content').slideDown('slow');
							jQuery('#payment-address .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');	
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});	
					<?php
					}
					?>					
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=payment_address&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#payment-address .checkout-content').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});					
				}	  
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});	
	});
	
	// Shipping Address			
	jQuery('#button-shipping-address').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.processShippingAddress',
			type: 'post',
			data: jQuery('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-shipping-address').attr('disabled', true);
				jQuery('#button-shipping-address').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},	
			complete: function() {
				jQuery('#button-shipping-address').attr('disabled', false);
				jQuery('.wait').remove();
			},			
			success: function(json) {
				jQuery('.warning, .error').remove();
				
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
					//Firstname validate			
					if (json['error']['firstname']) {
						jQuery('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
					}
					//Lastname validate
					if (json['error']['lastname']) {
						jQuery('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
					}
					//Address validate																					
					if (json['error']['address_1']) {
						jQuery('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
					}	
					//City validate
					if (json['error']['city']) {
						jQuery('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
					}	
					//Postcode validate
					if (json['error']['postcode']) {
						jQuery('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
					}	
					//Country validate
					if (json['error']['country']) {
						jQuery('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
					}	
					//Zone validate
					if (json['error']['zone']) {
						jQuery('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
					}
				} else {
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#shipping-method .checkout-content').html(html);
							jQuery('#shipping-address .checkout-content').slideUp('slow');
							jQuery('#shipping-method .checkout-content').slideDown('slow');
							jQuery('#shipping-address .checkout-heading a').remove();
							jQuery('#shipping-method .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#shipping-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
							jQuery.ajax({
								url: 'index.php?option=com_eshop&view=checkout&layout=shipping_address&format=raw',
								dataType: 'html',
								success: function(html) {
									jQuery('#shipping-address .checkout-content').html(html);
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});						
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	//Guest
	jQuery('#button-guest').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.guest',
			type: 'post',
			data: jQuery('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address select'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-guest').attr('disabled', true);
				jQuery('#button-guest').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},	
			complete: function() {
				jQuery('#button-guest').attr('disabled', false); 
				jQuery('.wait').remove();
			},			
			success: function(json) {
				jQuery('.warning, .error').remove();
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
					//Firstname validate
					if (json['error']['firstname']) {
						jQuery('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
					}
					//Lastname validate
					if (json['error']['lastname']) {
						jQuery('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
					}	
					//Email validate
					if (json['error']['email']) {
						jQuery('#payment-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
					}
					//Telephone validate
					if (json['error']['telephone']) {
						jQuery('#payment-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
					}	
					//Address validate														
					if (json['error']['address_1']) {
						jQuery('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
					}	
					//City validate
					if (json['error']['city']) {
						jQuery('#payment-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
					}	
					//Postcode validate
					if (json['error']['postcode']) {
						jQuery('#payment-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
					}	
					//Country validate
					if (json['error']['country']) {
						jQuery('#payment-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
					}	
					//Zone validate
					if (json['error']['zone']) {
						jQuery('#payment-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
					}
				} else {
					<?php
					if ($this->shipping_required)
					{
						?>
						var shipping_address = jQuery('#payment-address input[name=\'shipping_address\']:checked').attr('value');
						if (shipping_address) {
							jQuery.ajax({
								url: 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw',
								dataType: 'html',
								success: function(html) {
									jQuery('#shipping-method .checkout-content').html(html);
									jQuery('#payment-address .checkout-content').slideUp('slow');
									jQuery('#shipping-method .checkout-content').slideDown('slow');
									jQuery('#payment-address .checkout-heading a').remove();
									jQuery('#shipping-address .checkout-heading a').remove();
									jQuery('#shipping-method .checkout-heading a').remove();
									jQuery('#payment-method .checkout-heading a').remove();
									jQuery('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									jQuery('#shipping-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
									jQuery.ajax({
										url: 'index.php?option=com_eshop&view=checkout&layout=guest_shipping&format=raw',
										dataType: 'html',
										success: function(html) {
											jQuery('#shipping-address .checkout-content').html(html);
										},
										error: function(xhr, ajaxOptions, thrownError) {
											alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});					
						} else {
							jQuery.ajax({
								url: 'index.php?option=com_eshop&view=checkout&layout=guest_shipping&format=raw',
								dataType: 'html',
								success: function(html) {
									jQuery('#shipping-address .checkout-content').html(html);
									jQuery('#payment-address .checkout-content').slideUp('slow');
									jQuery('#shipping-address .checkout-content').slideDown('slow');
									jQuery('#payment-address .checkout-heading a').remove();
									jQuery('#shipping-address .checkout-heading a').remove();
									jQuery('#shipping-method .checkout-heading a').remove();
									jQuery('#payment-method .checkout-heading a').remove();
									jQuery('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
								},
								error: function(xhr, ajaxOptions, thrownError) {
									alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
								}
							});
						}
					<?php
					}
					else 
					{
					?>				
						jQuery.ajax({
							url: 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw',
							dataType: 'html',
							success: function(html) {
								jQuery('#payment-method .checkout-content').html(html);
								jQuery('#payment-address .checkout-content').slideUp('slow');
								jQuery('#payment-method .checkout-content').slideDown('slow');
								jQuery('#payment-address .checkout-heading a').remove();
								jQuery('#payment-method .checkout-heading a').remove();
								jQuery('#payment-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});				
					<?php
					}
					?>
				}	 
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});	
	});

	// Guest Shipping
	jQuery('#button-guest-shipping').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.processGuestShipping',
			type: 'post',
			data: jQuery('#shipping-address input[type=\'text\'], #shipping-address select'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-guest-shipping').attr('disabled', true);
				jQuery('#button-guest-shipping').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('#button-guest-shipping').attr('disabled', false); 
				jQuery('.wait').remove();
			},
			success: function(json) {
				jQuery('.warning, .error').remove();
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
					// Firstname validate
					if (json['error']['firstname']) {
						jQuery('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
					}
					//Lastname validate
					if (json['error']['lastname']) {
						jQuery('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
					}	
					// Address validate			
					if (json['error']['address_1']) {
						jQuery('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
					}	
					// City validate
					if (json['error']['city']) {
						jQuery('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
					}	
					// Postcode validate
					if (json['error']['postcode']) {
						jQuery('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
					}	
					// Country validate
					if (json['error']['country']) {
						jQuery('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
					}	
					// Zone validate
					if (json['error']['zone']) {
						jQuery('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
					}
				} else {
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=shipping_method&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#shipping-method .checkout-content').html(html);
							jQuery('#shipping-address .checkout-content').slideUp('slow');
							jQuery('#shipping-method .checkout-content').slideDown('slow');
							jQuery('#shipping-address .checkout-heading a').remove();
							jQuery('#shipping-method .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#shipping-address .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	//Shipping Method
	jQuery('#button-shipping-method').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.processShippingMethod',
			type: 'post',
			data: jQuery('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-shipping-method').attr('disabled', true);
				jQuery('#button-shipping-method').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},	
			complete: function() {
				jQuery('#button-shipping-method').attr('disabled', false);
				jQuery('.wait').remove();
			},			
			success: function(json) {
				jQuery('.warning, .error').remove();
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
				} else {
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=payment_method&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#payment-method .checkout-content').html(html);
							jQuery('#shipping-method .checkout-content').slideUp('slow');
							jQuery('#payment-method .checkout-content').slideDown('slow');
							jQuery('#shipping-method .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#shipping-method .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	// Payment Method
	jQuery('#button-payment-method').live('click', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=checkout.processPaymentMethod',
			type: 'post',
			data: jQuery('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
			dataType: 'json',
			beforeSend: function() {
				jQuery('#button-payment-method').attr('disabled', true);
				jQuery('#button-payment-method').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},	
			complete: function() {
				jQuery('#button-payment-method').attr('disabled', false);
				jQuery('.wait').remove();
			},			
			success: function(json) {
				jQuery('.warning, .error').remove();
				
				if (json['return']) {
					window.location.href = json['return'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						jQuery('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
						jQuery('.warning').fadeIn('slow');
					}
				} else {
					jQuery.ajax({
						url: 'index.php?option=com_eshop&view=checkout&layout=confirm&format=raw',
						dataType: 'html',
						success: function(html) {
							jQuery('#confirm .checkout-content').html(html);
							jQuery('#payment-method .checkout-content').slideUp('slow');
							jQuery('#confirm .checkout-content').slideDown('slow');
							jQuery('#payment-method .checkout-heading a').remove();
							jQuery('#payment-method .checkout-heading').append('<a><?php echo JText::_('ESHOP_EDIT'); ?></a>');
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
</script>