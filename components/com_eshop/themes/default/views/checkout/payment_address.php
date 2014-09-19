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

if (isset($this->lists['address_id']))
{
	?>
	<label class="radio">
		<input type="radio" value="existing" name="payment_address" checked="checked"> <?php echo JText::_('ESHOP_EXISTING_ADDRESS'); ?>
	</label>
	<div id="payment-existing">
		<?php echo $this->lists['address_id']; ?>
	</div>
	<label class="radio">
		<input type="radio" value="new" name="payment_address"> <?php echo JText::_('ESHOP_NEW_ADDRESS'); ?>
	</label>
	<?php
}
else 
{
	?>
	<input type="hidden" name="payment_address" value="new" />
	<?php
}
?>
<div id="payment-new" style="display: <?php echo (isset($this->lists['address_id']) ? 'none' : 'block'); ?>;">
	<div class="control-group">
		<label class="control-label" for="firstname"><span class="required">*</span><?php echo JText::_('ESHOP_FIRST_NAME');?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="firstname" name="firstname" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lastname"><span class="required">*</span><?php echo JText::_('ESHOP_LAST_NAME'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="lastname" name="lastname" />
		</div>
	 </div>
	 <div class="control-group">
		<label class="control-label" for="company"><?php echo JText::_('ESHOP_COMPANY'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="company" name="company" />
		</div>
	</div>
	<div class="control-group">
			<label class="control-label" for="company_id"><?php echo JText::_('ESHOP_COMPANY_ID'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="company_id" name="company_id" />
			</div>
	</div>
	<div class="control-group">
			<label class="control-label" for="address_1"><span class="required">*</span><?php echo JText::_('ESHOP_ADDRESS_1'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="address_1" name="address_1" />
			</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="address_2"><?php echo JText::_('ESHOP_ADDRESS_2'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="address_2" name="address_2" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="city"><span class="required">*</span><?php echo JText::_('ESHOP_CITY'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="city" name="city" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="postcode"><span id="payment-postcode-required" class="required">*</span><?php echo JText::_('ESHOP_POST_CODE'); ?></label>
		<div class="controls docs-input-sizes">
			<input type="text" id="postcode" name="postcode" />
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
	<input type="button" class="btn btn-primary pull-right" id="button-payment-address" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>
<script type="text/javascript"><!--
	jQuery('#payment-address input[name=\'payment_address\']').live('change', function() {
		if (this.value == 'new') {
			jQuery('#payment-existing').hide();
			jQuery('#payment-new').show();
		} else {
			jQuery('#payment-existing').show();
			jQuery('#payment-new').hide();
		}
	});
	jQuery('#payment-address select[name=\'country_id\']').bind('change', function() {
		jQuery.ajax({
			url: 'index.php?option=com_eshop&task=cart.getZones&country_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				jQuery('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
			},
			complete: function() {
				jQuery('.wait').remove();
			},
			success: function(json) {
				if (json['postcode_required'] == '1')
				{
					jQuery('#payment-postcode-required').show();
				}
				else
				{
					jQuery('#payment-postcode-required').hide();
				}
				html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
				if (json['zones'] != '')
				{
					for (var i = 0; i < json['zones'].length; i++)
					{
	        			html += '<option value="' + json['zones'][i]['id'] + '"';
						if (json['zones'][i]['id'] == '<?php $this->payment_zone_id; ?>')
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
	