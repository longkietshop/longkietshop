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
if($this->rowUser)
{
	$names = explode(' ', $this->rowUser->name);
	$firstName = $names[0];
	if(count($names)>1)
	{
		$lastName  = $names[1];
	}
	else 
	{
		$lastName = '';
	}
}
?>
<div class="row-fluid">
	<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_eshop&task=customer.processUser'); ?>" method="post">
		<div id="process-user">
			<div class="control-group">
				<label for="firstname" class="control-label"><span class="required">*</span><?php echo JText::_('ESHOP_FIRST_NAME'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" name="firstname" id="firstname" value="<?php echo isset($this->user->firstname) ? $this->user->firstname : $firstName; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="lastname" class="control-label"><span class="required">*</span><?php echo JText::_('ESHOP_LAST_NAME'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" name="lastname" id="lastname" value="<?php echo isset($this->user->lastname) ? $this->user->lastname : $lastName; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="lastname" class="control-label"><?php echo JText::_('ESHOP_PASSWORD'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="password" name="password1" id="password1" value="">
				</div>
			</div>
			<div class="control-group">
				<label for="lastname" class="control-label"><?php echo JText::_('ESHOP_CONFIRM_PASSWORD'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="password" name="password2" id="password2" value="">
				</div>
			</div>
			<div class="control-group">
				<label for="email" class="control-label"><span class="required">*</span><?php echo JText::_('ESHOP_EMAIL'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" name="email" id="email" value="<?php echo isset($this->user->email) ? $this->user->email : $this->rowUser->email; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="telephone" class="control-label"><span class="required">*</span><?php echo JText::_('ESHOP_TELEPHONE'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" name="telephone" id="telephone" value="<?php echo isset($this->user->telephone) ? $this->user->telephone : ''; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="fax" class="control-label"><?php echo JText::_('ESHOP_FAX'); ?></label>
				<div class="controls docs-input-sizes">
					<input type="text" name="fax" id="fax" value="<?php echo isset($this->user->fax) ? $this->user->fax : ''; ?>">
				</div>
			</div>
			<div class="control-group">
				<label for="fax" class="control-label"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></label>
				<div class="controls docs-input-sizes">
					<?php echo $this->customergroup;?>
				</div>
			</div>
		</div>
		<div class="span2">
			<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-back-user-infor" class="btn btn-primary pull-left">
			<input type="button" value="<?php echo JText::_('ESHOP_SAVE'); ?>" id="button-user-infor" class="btn btn-primary pull-right">
		</div>
		<input type="hidden" name="customer_id" value="<?php echo isset($this->user->customer_id) ? $this->user->customer_id : ''; ?>">
		<input type="hidden" name="id" value="<?php echo isset($this->user->id) ? $this->user->id : ''; ?>">
	</form>
</div>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('#button-back-user-infor').click(function(){
				var url = '<?php echo JRoute::_('index.php?option=com_eshop&view=customer'); ?>';
				$(location).attr('href',url);
			});
		})
	
		//process user
		$('#button-user-infor').live('click', function() {
			$.ajax({
				url: 'index.php?option=com_eshop&task=customer.processUser',
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
							$('#process-user input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
						}
						//Lastname error
						if (json['error']['lastname']) {
							$('#process-user input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
						}
						//Password error
						if (json['error']['password']) {
							jQuery('#process-user input[name=\'password1\']').after('<span class="error">' + json['error']['password'] + '</span>');
						}
						//Confirm password error
						if (json['error']['confirm']) {
							$('#process-user input[name=\'password2\']').after('<span class="error">' + json['error']['confirm'] + '</span>');
						}
						//Email validate
						if (json['error']['email']) {
							$('#process-user input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
						}
						//Telephone validate
						if (json['error']['telephone']) {
							$('#process-user input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
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

		
	})(jQuery);
</script>