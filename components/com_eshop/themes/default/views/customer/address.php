<?php
/**
 * @version		1.0.7
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
$id =JRequest::getInt('aid');
$language = JFactory::getLanguage();
$tag = $language->getTag();
if (!$tag)
	$tag = 'en-GB';
if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
?>
<h1><?php echo ($id) ? JText::_('ESHOP_ADDRESS_EDIT') : JText::_('ESHOP_ADDRESS_NEW') ; ?></h1>
<div class="row-fluid clearfix">
	<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_eshop&task=customer.processAddress'); ?>" method="post">
		<div id="process-address">
			<div class="control-group">
				<label class="control-label" for="firstname"><span class="required">*</span><?php echo JText::_('ESHOP_FIRST_NAME');?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="firstname" name="firstname" value="<?php echo isset($this->address->firstname) ? $this->address->firstname : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="lastname"><span class="required">*</span><?php echo JText::_('ESHOP_LAST_NAME'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="lastname" name="lastname" value="<?php echo isset($this->address->lastname) ? $this->address->lastname : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="company"><?php echo JText::_('ESHOP_COMPANY'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="company" name="company" value="<?php echo isset($this->address->company) ? $this->address->company : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="company_id"><?php echo JText::_('ESHOP_COMPANY_ID'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="company_id" name="company_id" value="<?php echo isset($this->address->company_id) ? $this->address->company_id : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="address_1"><span class="required">*</span><?php echo JText::_('ESHOP_ADDRESS_1'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="address_1" name="address_1" value="<?php echo isset($this->address->address_1) ? $this->address->address_1 : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="address_2"><?php echo JText::_('ESHOP_ADDRESS_2'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="address_2" name="address_2" value="<?php echo isset($this->address->address_2) ? $this->address->address_2 : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="city"><span class="required">*</span><?php echo JText::_('ESHOP_CITY'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="city" name="city" value="<?php echo isset($this->address->city) ? $this->address->city : ''; ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="postcode"><span id="payment-postcode-required" class="required"></span><?php echo JText::_('ESHOP_POST_CODE'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" id="postcode" name="postcode" value="<?php echo isset($this->address->postcode) ? $this->address->postcode : ''; ?>" />
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
			 <div class="control-group">
				<label class="control-label" for="zone_id"><?php echo JText::_('ESHOP_DEFAULT_ADDRESS'); ?></label>
				<div class="controls docs-input-sizes">
					<?php echo $this->lists['default_address']; ?>
				</div>
			 </div>
			<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-back-address" class="btn btn-primary pull-left" />
			<input type="button" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" id="button-continue-address" class="btn btn-primary pull-right" />
			<input type="hidden" name="id" value="<?php echo isset($this->address->id) ? $this->address->id : ''; ?>">
		</div>	 
	 </form>
</div>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('#button-back-address').click(function() {
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=addresses'); ?>';
				$(location).attr('href', url);
			});

			//process user
			$('#button-continue-address').live('click', function() {
				$.ajax({
					url: 'index.php?option=com_eshop&task=customer.processAddress',
					type: 'post',
					data: $("#adminForm").serialize(),
					dataType: 'json',
					success: function(json) {
							$('.warning, .error').remove();
							if (json['return']) {
								window.location.href = json['return'];
							} else if (json['error']) {
							//Firstname error
							if (json['error']['firstname']) {
								$('#process-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
							}
							//Lastname error
							if (json['error']['lastname']) {
								$('#process-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
							}
							//Address error
							if (json['error']['address_1']) {
								$('#process-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
							}
							//City error
							if (json['error']['city']) {
								$('#process-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
							}
							//Country error
							if (json['error']['country_id']) {
								$('#process-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country_id'] + '</span>');
							}
							//Zone error
							if (json['error']['zone_id']) {
								$('#process-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone_id'] + '</span>');
							}
								
						} else {
							$('.error').remove();
							$('.warning, .error').remove();
							
						}	  
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});	
			});

			jQuery('#process-address select[name=\'country_id\']').bind('change', function() {
				jQuery.ajax({
					url: 'index.php?option=com_eshop&task=cart.getZones&country_id=' + this.value,
					dataType: 'json',
					beforeSend: function() {
						jQuery('#process-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="components/com_eshop/assets/images/loading.gif" alt="" /></span>');
					},
					complete: function() {
						jQuery('.wait').remove();
					},
					success: function(json) {
						html = '<option value=""><?php echo JText::_('ESHOP_PLEASE_SELECT'); ?></option>';
						if (json['zones'] != '')
						{
							for (var i = 0; i < json['zones'].length; i++)
							{
			        			html += '<option value="' + json['zones'][i]['id'] + '"';
			        			<?php
			        			if (isset($this->address->zone_id))
								{
			        				?>
			        				if (json['zones'][i]['id'] == '<?php $this->address->zone_id; ?>')
									{
					      				html += ' selected="selected"';
					    			}
			        				<?php	
			        			}
			        			?>
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
		})
	})(jQuery);
</script>
	
