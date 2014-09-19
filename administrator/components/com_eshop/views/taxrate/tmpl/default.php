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
$editor = JFactory::getEditor(); 	
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'taxrate.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.tax_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_TAX_NAME'); ?>");
				form.tax_name.focus();
				return;
			}
			if (form.tax_rate.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_TAX_RATE'); ?>");
				form.tax_rate.focus();
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
					<td width="220" class="key">
						<span class="required">*</span>
						<?php echo  JText::_('ESHOP_TAX_NAME'); ?>
					</td>
					<td>
						<input class="text-large" type="text" name="tax_name" id="tax_name" maxlength="255" value="<?php echo $this->item->tax_name; ?>" />
					</td>
				</tr>	
				<tr>
					<td class="key">
						<span class="required">*</span>
						<?php echo  JText::_('ESHOP_TAX_RATE'); ?>
					</td>
					<td>
						<input class="text-large" type="text" name="tax_rate" id="tax_rate" maxlength="255" value="<?php echo $this->item->tax_rate; ?>" />
					</td>				
				</tr>	
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_TAX_TYPE'); ?>
					</td>
					<td>
						<?php echo $this->lists['tax_type']; ?>
					</td>				
				</tr>	
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_CUSTOMERGROUPS'); ?>
					</td>
					<td>
						<?php echo $this->lists['customergroup_id']; ?>
					</td>				
				</tr>						
				<tr>
					<td valign="top" class="key">
						<?php echo  JText::_('ESHOP_GEO_ZONE'); ?>
					</td>
					<td >
						<?php echo $this->lists['geozone_id']; ?>
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