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
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'attribute.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			//Validate the entered data before submitting
			<?php
			if ($translatable)
			{
				foreach ($this->languages as $language)
				{
					$langId = $language->lang_id;
					?>
					if (document.getElementById('attribute_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('attribute_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.attribute_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.attribute_name.focus();
					return;
				}
				<?php
			}
			?>
			if (form.attributegroup_id.value == '0')
			{
				alert("<?php echo JText::_('ESHOP_SELECT_ATTRIBUTE_GROUP'); ?>");
				form.attribute_name.focus();
				return;
			}
			
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span6">
			<table class="admintable adminform" style="width: 100%;">
				<tr>
					<td class="key">
						<span class="required">*</span>
						<?php echo  JText::_('ESHOP_NAME'); ?>
					</td>
					<td>
						<?php
						if ($translatable)
						{
							foreach ($this->languages as $language)
							{
								$langId = $language->lang_id;
								$langCode = $language->lang_code;
								?>
								<input class="input-xlarge" type="text" name="attribute_name_<?php echo $langCode; ?>" id="attribute_name_<?php echo $langId; ?>" size="" maxlength="255" value="<?php echo isset($this->item->{'attribute_name_'.$langCode}) ? $this->item->{'attribute_name_'.$langCode} : ''; ?>" />
								<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
								<br />
								<?php
							}
						}
						else 
						{
							?>
							<input class="input-xlarge" type="text" name="attribute_name" id="attribute_name" maxlength="255" value="<?php echo $this->item->attribute_name; ?>" />
							<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_ATTRIBUTEGROUP'); ?>
					</td>
					<td>
						<?php echo $this->lists['attributegroups']; ?>
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
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<?php
	if ($translatable)
	{
		foreach ($this->languages as $language)
		{
			$langCode = $language->lang_code;
			?>
			<input type="hidden" name="details_id_<?php echo $langCode; ?>" value="<?php echo isset($this->item->{'details_id_' . $langCode}) ? $this->item->{'details_id_' . $langCode} : ''; ?>" />
			<?php
		}
	}
	elseif ($this->translatable)
	{
	?>
		<input type="hidden" name="details_id" value="<?php echo isset($this->item->{'details_id'}) ? $this->item->{'details_id'} : ''; ?>" />
		<?php
	}
	?>
	<input type="hidden" name="task" value="" />
</form>