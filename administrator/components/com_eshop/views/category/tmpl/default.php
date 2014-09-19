<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	ESshop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
jimport('joomla.filesystem.file');
EshopHelper::chosen();
$editor = JFactory::getEditor();
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'category.cancel') {
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
					if (document.getElementById('category_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('category_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.category_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.category_name.focus();
					return;
				}
				<?php
			}
			?>
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_GENERAL'); ?></a></li>		
			<li><a href="#data-page" data-toggle="tab"><?php echo JText::_('ESHOP_DATA'); ?></a></li>					
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general-page">
				<div class="span10">
				<?php
				if ($translatable) {
					?>
					<ul class="nav nav-tabs">
						<?php
							$i = 0;
							foreach ($this->languages as $language) {
								$langCode = $language->lang_code;
								?>
								<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#general-page-<?php echo $langCode; ?>" data-toggle="tab"><?php echo $this->languageData['title'][$langCode]; ?>
								<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" /></a></li>
								<?php
								$i++;	
							}
						?>																					
					</ul>
					<div class="tab-content">
					<?php
						$i = 0;
						foreach ($this->languages as $language)
						{
							$langId = $language->lang_id;
							$langCode = $language->lang_code;
						?>
							<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="general-page-<?php echo $langCode; ?>">													
								<table class="admintable adminform" style="width: 100%;">
									<tr>
										<td class="key">
											<span class="required">*</span>
											<?php echo  JText::_('ESHOP_NAME'); ?>
										</td>
										<td>
											<input class="input-xlarge" type="text" name="category_name_<?php echo $langCode; ?>" id="category_name_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_name_'.$langCode}) ? $this->item->{'category_name_'.$langCode} : ''; ?>" />
										</td>								
									</tr>																		
									<tr>
										<td class="key">
											<?php echo  JText::_('ESHOP_ALIAS'); ?>
										</td>
										<td>
											<input class="input-medium" type="text" name="category_alias_<?php echo $langCode; ?>" id="category_alias_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'category_alias_'.$langCode}) ? $this->item->{'category_alias_'.$langCode} : ''; ?>" />
										</td>								
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
										</td>
										<td>
											<?php echo $editor->display( 'category_desc_'.$langCode,  isset($this->item->{'category_desc_'.$langCode}) ? $this->item->{'category_desc_'.$langCode} : '' , '100%', '250', '75', '10' ); ?>
										</td>								
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('ESHOP_META_KEYS'); ?>
										</td>
										<td>
											<textarea rows="5" cols="30" name="meta_key_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_key_'.$langCode}; ?></textarea>
										</td>
									</tr>			
									<tr>
										<td class="key">
											<?php echo JText::_('ESHOP_META_DESC'); ?>
										</td>
										<td>
											<textarea rows="5" cols="30" name="meta_desc_<?php echo $langCode; ?>"><?php echo $this->item->{'meta_key_'.$langCode}; ?></textarea>
										</td>
									</tr>
								</table>
							</div>							
							<?php
							$i++;
							}
						?>
					</div>
				<?php
				}
				else
				{
					?>
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo  JText::_('ESHOP_NAME'); ?>
							</td>
							<td>
								<input class="input-xlarge" type="text" name="category_name" id="category_name" size="" maxlength="250" value="<?php echo $this->item->category_name; ?>" />
							</td>								
						</tr>																		
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_ALIAS'); ?>
							</td>
							<td>
								<input class="input-medium" type="text" name="category_alias" id="category_alias" size="" maxlength="250" value="<?php echo $this->item->category_alias; ?>" />
							</td>								
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
							</td>
							<td>
								<?php echo $editor->display( 'category_desc',  $this->item->category_desc , '100%', '250', '75', '10' ); ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_META_KEYS'); ?>
							</td>
							<td>
								<textarea rows="5" cols="30" name="meta_key"><?php echo $this->item->meta_key; ?></textarea>
							</td>
						</tr>			
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_META_DESC'); ?>
							</td>
							<td>
								<textarea rows="5" cols="30" name="meta_desc"><?php echo $this->item->meta_desc; ?></textarea>
							</td>
						</tr>
					</table>
					<?php
				}
				?>
				</div>
			</div>			
			<div class="tab-pane" id="data-page">
				<div class="span8">
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_PARENT_CATEGORY'); ?>
							</td>
							<td>
								<?php echo $this->lists['category_parent_id']; ?>
							</td>							
						</tr>
						<tr>
							<td class="key" valign="top">
								<?php echo  JText::_('ESHOP_CATEGORY_IMAGE'); ?>
							</td>
							<td>
								<input type="file" class="input-large" accept="image/*" name="category_image" />								
								<?php
									if (JFile::exists(JPATH_ROOT.'/media/com_eshop/categories/'.$this->item->category_image))
									{
										$viewImage = JFile::stripExt($this->item->category_image).'-100x100.'.JFile::getExt($this->item->category_image);
										if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/categories/resized/'.$viewImage))
										{
											?>
											<img src="<?php echo JURI::root().'media/com_eshop/categories/resized/'.$viewImage; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo JURI::root().'media/com_eshop/categories/'.$this->item->category_image; ?>" height="100" />
											<?php
										}
										?>
										<label class="checkbox">
											<input type="checkbox" name="remove_image" value="1" />
											<?php echo JText::_('ESHOP_REMOVE_IMAGE'); ?>
										</label>
										<?php
									}
								?>
							</td>							
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_PRODUCTS_PER_PAGE'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="products_per_page" id="products_per_page" size="" maxlength="250" value="<?php echo $this->item->products_per_page; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_PRODUCTS_PER_ROW'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="products_per_row" id="products_per_row" size="" maxlength="250" value="<?php echo $this->item->products_per_row; ?>" />
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