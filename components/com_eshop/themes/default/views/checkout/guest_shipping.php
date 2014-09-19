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
<div class="span12 no_margin_left">
	<div class="control-group">
		<label class="control-label" for="firstname"><span class="required">*</span><?php echo JText::_('ESHOP_FIRST_NAME');?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="firstname" name="firstname" value="<?php echo isset($this->firstname) ? $this->firstname : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lastname"><span class="required">*</span><?php echo JText::_('ESHOP_LAST_NAME'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="lastname" name="lastname" value="<?php echo isset($this->lastname) ? $this->lastname : ''; ?>" />
		</div>
	 </div>
	 <div class="control-group">
		<label class="control-label" for="company"><?php echo JText::_('ESHOP_COMPANY'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="company" name="company" value="<?php echo isset($this->company) ? $this->company : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
			<label class="control-label" for="company_id"><?php echo JText::_('ESHOP_COMPANY_ID'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="company_id" name="company_id" value="<?php echo isset($this->company_id) ? $this->company_id : ''; ?>" />
			</div>
	</div>
	<div class="control-group">
			<label class="control-label" for="address_1"><span class="required">*</span><?php echo JText::_('ESHOP_ADDRESS_1'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="address_1" name="address_1" value="<?php echo isset($this->address_1) ? $this->address_1 : ''; ?>" />
			</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="address_2"><?php echo JText::_('ESHOP_ADDRESS_2'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="address_2" name="address_2" value="<?php echo isset($this->address_2) ? $this->address_2 : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="city"><span class="required">*</span><?php echo JText::_('ESHOP_CITY'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="city" name="city" value="<?php echo isset($this->city) ? $this->city : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="postcode"><span id="shipping-postcode-required" class="required">*</span><?php echo JText::_('ESHOP_POST_CODE'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="postcode" name="postcode" value="<?php echo isset($this->postcode) ? $this->postcode : ''; ?>" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="country_id"><span class="required">*</span><?php echo JText::_('ESHOP_COUNTRY'); ?></label>
		<div class="controls docs-input-sizes">
			<?php echo $this->lists['country_id']; ?>
		</div>
	 </div>
	 <div class="control-group">
		<label class="control-label" for="zone_id"><span class="required">*</span><?php echo JText::_('ESHOP_REGION_STATE'); ?></label>
		<div class="controls docs-input-sizes">
			<?php echo $this->lists['zone_id']; ?>
		</div>
	 </div>
</div>
<div class="no_margin_left">
	<input type="button" class="btn btn-primary pull-right" id="button-guest-shipping" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>
<script type="text/javascript"><!--
	jQuery('#shipping-address input[name=\'shipping_address\']').live('change', function() {
		if (this.value == 'new') {
			jQuery('#shipping-existing').hide();
			jQuery('#shipping-new').show();
		} else {
			jQuery('#shipping-existing').show();
			jQuery('#shipping-new').hide();
		}
	});
	jQuery('#shipping-address select[name=\'country_id\']').bind('change', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=cart.getZones&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				jQuery('#shipping-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('.wait').remove();
			},
			success: function(json) {
				if (json['postcode_required'] == '1')
				{
					jQuery('#shipping-postcode-required').show();
				}
				else
				{
					jQuery('#shipping-postcode-required').hide();
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
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
//--></script>