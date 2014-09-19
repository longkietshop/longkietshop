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
JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'theme.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting													
			Joomla.submitform(pressbutton, form);
		}								
	}		
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="adminform">
				<legend><?php echo JText::_('ESHOP_THEME_DETAILS'); ?></legend>
					<table class="admintable adminform">
						<tr>
							<td width="100" class="key">
								<?php echo  JText::_('ESHOP_NAME'); ?>
							</td>
							<td>
								<?php echo $this->item->name; ?>
							</td>
						</tr>
						<tr>
							<td width="100" class="key">
								<?php echo  JText::_('ESHOP_TITLE'); ?>
							</td>
							<td>
								<input class="text_area" type="text" name="title" id="title" size="40" maxlength="250" value="<?php echo $this->item->title;?>" />
							</td>
						</tr>					
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_AUTHOR'); ?>
							</td>
							<td>
								<input class="text_area" type="text" name="author" id="author" size="40" maxlength="250" value="<?php echo $this->item->author;?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_CREATION_DATE'); ?>
							</td>
							<td>
								<?php echo $this->item->creation_date; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_COPYRIGHT'); ?>
							</td>
							<td>
								<?php echo $this->item->copyright; ?>
							</td>
						</tr>	
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_LICENSE'); ?>
							</td>
							<td>
								<?php echo $this->item->license; ?>
							</td>
						</tr>							
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_AUTHOR_EMAIL'); ?>
							</td>
							<td>
								<?php echo $this->item->author_email; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_AUTHOR_URL'); ?>
							</td>
							<td>
								<?php echo $this->item->author_url; ?>
							</td>
						</tr>				
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_VERSION'); ?>
							</td>
							<td>
								<?php echo $this->item->version; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
							</td>
							<td>
								<?php echo $this->item->description; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PUBLISHED'); ?>
							</td>
							<td>
								<?php					
									echo $this->lists['published'];					
								?>						
							</td>
						</tr>
				</table>
			</fieldset>				
		</div>						
		<div class="span6">
			<fieldset class="adminform">
				<legend><?php echo JText::_('ESHOP_THEME_PARAMETERS'); ?></legend>
				<?php
					foreach ($this->form->getFieldset('basic') as $field)
					{
					?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label;?>
							</div>					
							<div class="controls">
								<?php echo  $field->input; ?>
							</div>
						</div>	
				<?php
					}
				?>
			</fieldset>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>