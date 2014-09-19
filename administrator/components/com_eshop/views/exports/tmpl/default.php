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
JToolBarHelper::title(JText::_('ESHOP_EXPORTS'), 'generic.png');
JToolBarHelper::custom('exports.process', 'export', 'export', Jtext::_('ESHOP_PROCESS'), false);
JToolBarHelper::cancel('exports.cancel');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'exports.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			if (form.export_type.value == '') {
				alert('<?php echo JText::_('ESHOP_EXPORT_TYPE_PROMPT'); ?>');
				form.export_type.focus();
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
						<?php echo  JText::_('ESHOP_EXPORT_TYPE'); ?>
					</td>
					<td>
						<?php echo $this->lists['export_type']; ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_FIELD_DELIMITER'); ?>
					</td>
					<td>
						<input class="input-mini" type="text" name="field_delimiter" id="field_delimiter" maxlength="1" value="," />
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_IMAGE_SEPARATOR'); ?>
					</td>
					<td>
						<input class="input-mini" type="text" name="image_separator" id="image_separator" maxlength="1" value=";" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<?php echo  JText::_('ESHOP_LANGUAGE'); ?>
					</td>
					<td>
						<?php echo $this->lists['language']; ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<div class="clearfix"></div>
</form>