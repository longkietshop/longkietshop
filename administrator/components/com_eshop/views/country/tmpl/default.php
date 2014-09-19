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
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'country.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			//Validate the entered data before submitting
			if (form.country_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
				form.country_name.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<table class="admintable adminform" style="width: 100%;">
				<tr>
					<td class="key">
						<span class="required">*</span>
						<?php echo  JText::_('ESHOP_NAME'); ?>
					</td>
					<td>
						<input class="input-xlarge" type="text" name="country_name" id="country_name" size="40" maxlength="250" value="<?php echo $this->item->country_name; ?>" />
					</td>
				</tr>				
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_ISO_CODE2'); ?>
					</td>
					<td>
						<input class="input-mini" type="text" name="iso_code_2" id="iso_code_2" maxlength="2" value="<?php echo $this->item->iso_code_2; ?>" />
					</td>
				</tr>	
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_ISO_CODE3'); ?>
					</td>
					<td>
						<input class="input-mini" type="text" name="iso_code_3" id="iso_code_3" maxlength="3" value="<?php echo $this->item->iso_code_3; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo JText::_('ESHOP_POSTCODE_REQUIRED'); ?>
					</td>
					<td>
						<?php echo $this->lists['postcode_required']; ?>
					</td>
				</tr>					
				<tr>
					<td class="key">
						<?php echo JText::_('ESHOP_PUBLISHED'); ?>
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>