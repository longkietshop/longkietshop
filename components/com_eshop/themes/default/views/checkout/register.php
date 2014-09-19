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
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".colorbox").colorbox();
	});
</script>
<div class="row-fluid clearfix">
		<div class="span6 no_margin_left">
		<legend><?php echo JText::_('ESHOP_YOUR_PERSONAL_DETAILS'); ?></legend>
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
			<label class="control-label" for="email"><span class="required">*</span><?php echo JText::_('ESHOP_EMAIL'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="email" name="email" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="telephone"><span class="required">*</span><?php echo JText::_('ESHOP_TELEPHONE'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="telephone" name="telephone" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="fax"><?php echo JText::_('ESHOP_FAX'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="fax" name="fax" />
			</div>
		</div>
		<legend><?php echo JText::_('ESHOP_USER_DETAILS'); ?></legend>
		<div class="control-group">
			<label class="control-label" for="username"><span class="required">*</span><?php echo JText::_('ESHOP_USERNAME');?></label>
			<div class="controls docs-input-sizes">
				<input type="text" id="username" name="username" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password1"><span class="required">*</span><?php echo JText::_('ESHOP_PASSWORD'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="password" id="password1" name="password1" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password2"><span class="required">*</span><?php echo JText::_('ESHOP_CONFIRM_PASSWORD'); ?></label>
			<div class="controls docs-input-sizes">
				<input type="password" id="password2" name="password2" />
			</div>
		</div>
	</div>
	<div class="span5">
		<legend><?php echo JText::_('ESHOP_YOUR_ADDRESS'); ?></legend>
		<?php
		if (isset($this->lists['customergroup_id']))
		{
			?>
			<div class="control-group">
				<label class="control-label" for="customergroup_id"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></label>
				<div class="controls docs-input-sizes">
					<?php echo $this->lists['customergroup_id']; ?>
				</div>
			</div>
			<?php
		}
		?>
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
			<label class="control-label" for="postcode"><span id="payment-postcode-required" class="required"></span><?php echo JText::_('ESHOP_POST_CODE'); ?></label>
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
</div>
<?php
if ($this->shipping_required)
{
	?>
	<div class="no_margin_right">
		<label class="checkbox"><input type="checkbox" value="1" name="shipping_address"><?php echo JText::_('ESHOP_SHIPPING_ADDRESS_SAME'); ?></label>
	</div>
	<?php
}
?>
<div class="no_margin_left">
	<?php
	if (isset($this->accountTermsLink) && $this->accountTermsLink != '')
	{
		?>
        <span class="privacy">
			<input type="checkbox" value="1" name="account_terms_agree" />
			&nbsp;<?php echo JText::_('ESHOP_ACCOUNT_TERMS_AGREE'); ?>&nbsp;<a class="colorbox cboxElement" href="<?php echo $this->accountTermsLink; ?>"><?php echo JText::_('ESHOP_ACCOUNT_TERMS_AGREE_TITLE'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
		<?php
	}
	?>
	<input type="button" class="btn btn-primary pull-right" id="button-register" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</div>	
<script type="text/javascript"><!--
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
	