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
EshopHelper::chosen();
$editor = JFactory::getEditor(); 	
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'review.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.author.value.length < 3 || form.author.value.length > 25) {
				alert("<?php echo JText::_('ESHOP_ERROR_AUTHOR'); ?>");
				form.author.focus();
				return;
			}
			if (form.review.value.length < 3 || form.review.value.length > 1000) {
				alert("<?php echo JText::_('ESHOP_ERROR_REVIEW'); ?>");
				form.review.focus();
				return;
			}
			for (var i = 0; i < 5; i++) {
				if (form.rating[i].checked) {
					break;
				}
			}
			if (i == 5) {
				alert("<?php echo JText::_('ESHOP_ERROR_RATING'); ?>");
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
						<?php echo  JText::_('ESHOP_AUTHOR'); ?>
					</td>
					<td>
						<input class="text-xlarge" type="text" name="author" id="author" maxlength="128" value="<?php echo $this->item->author; ?>" />
					</td>
				</tr>				
				<tr>
					<td class="key">
						<?php echo JText::_('ESHOP_PRODUCT'); ?>
					</td>
					<td >
						<?php echo $this->lists['products']; ?>
					</td>
				</tr>				
				<tr>
					<td class="key">
						<span class="required">*</span>
						<?php echo JText::_('ESHOP_REVIEW'); ?>
					</td>
					<td>
						<textarea name="review" cols="40" rows="5"><?php echo $this->item->review; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span class="required">*</span>
						<?php echo JText::_('ESHOP_RATING'); ?>
					</td>
					<td>
						<?php echo $this->lists['rating']; ?>
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
</fieldset>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>