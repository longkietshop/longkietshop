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
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span8">
			<?php
			if ($translatable) {
				?>
				<ul class="nav nav-tabs">
					<?php
						$i = 0;
						foreach ($this->languages as $language) {
							$langCode = $language->lang_code;
							?>
							<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#message-value--<?php echo $langCode; ?>" data-toggle="tab"><?php echo $this->languageData['title'][$langCode]; ?>
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
						<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="message-value--<?php echo $langCode; ?>">													
							<table class="admintable adminform" style="width: 100%;">
								<tr>
									<td class="key">
										<?php echo $this->item->message_title; ?>
									</td>
									<td>
										<?php
										if ($this->item->message_type == 'textbox')
										{
											?>
											<input class="input-xlarge" type="text" name="message_value_<?php echo $langCode; ?>" id="message_value_<?php echo $langId; ?>" size="" maxlength="255" value="<?php echo isset($this->item->{'message_value_'.$langCode}) ? $this->item->{'message_value_'.$langCode} : ''; ?>" />
											<?php
										}
										else 
										{
											echo $editor->display( 'message_value_'.$langCode,  isset($this->item->{'message_value_'.$langCode}) ? $this->item->{'message_value_'.$langCode} : '' , '100%', '250', '75', '10' );
										}
										?>
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
							<?php echo $this->item->message_title; ?>
						</td>
						<td>
							<?php
							if ($this->item->message_type == 'textbox')
							{
								?>
								<input class="input-xlarge" type="text" name="message_value" id="message_value" maxlength="255" value="<?php echo $this->item->message_value; ?>" />
								<?php
							}
							else
							{
								echo $editor->display( 'message_value',  $this->item->message_value , '100%', '250', '75', '10' );
							}
							?>
						</td>
					</tr>
				</table>
				<?php
			}
			?>
		</div>
	</div>
	<div class="clearfix"></div>
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