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
jimport('joomla.filesystem.file');
EshopHelper::chosen();
$editor = JFactory::getEditor();
$translatable = JLanguageMultilang::isEnabled() && count($this->languages) > 1;
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'product.cancel') {
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
					if (document.getElementById('product_name_<?php echo $langId; ?>').value == '') {
						alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
						document.getElementById('product_name_<?php echo $langId; ?>').focus();
						return;
					}
					<?php
				}
			}
			else
			{
				?>
				if (form.product_name.value == '') {
					alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
					form.product_name.focus();
					return;
				}
				<?php
			}
			?>
			if (form.product_sku.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_PRODUCT_SKU'); ?>");
				form.product_sku.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
	//Add or Remove product images
	var countProductImages = '<?php echo count($this->productImages); ?>';
	function addProductImage() {
		var html = '<tr id="product_image_' + countProductImages + '" style="height: 100px;">'
		//Image column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="file" class="input" size="20" accept="image/*" name="image[]" /></td>';
		//Ordering column
		html += '<td style="text-align: center; vertical-align: middle;"><input class="input-small" type="text" name="image_ordering[]" maxlength="10" value="" /></td>';
		//Published column
		html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox" name="image_published[]">';
		html += '<option selected="selected" value="1"><?php echo JText::_('ESHOP_YES'); ?></option>';
		html += '<option value="0"><?php echo JText::_('ESHOP_NO'); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductImage('+countProductImages+');" /></td>';
		html += '</tr>';
		jQuery('#product_images_area').append(html);
		countProductImages++;
	}
	function removeProductImage(rowIndex) {
		jQuery('#product_image_' + rowIndex).remove();
	}
	//Add or Remove product attributes
	var countProductAttributes = '<?php echo count($this->productAttributes); ?>';
	function addProductAttribute() {
		var html = '<tr id="product_attribute_' + countProductAttributes + '">'
		//Attribute column
		html += '<td style="text-align: center; vertical-align: middle;"><?php echo preg_replace(array('/\r/', '/\n/'), '', $this->lists['attributes']); ?></td>';
		//Value column
		html += '<td style="text-align: center;">';
		<?php
		if ($translatable)
		{
			?>
			
			<?php
			foreach ($this->languages as $language) {
				$langCode = $language->lang_code;
				?>
				html += '<input class="input-large" type="text" name="attribute_value_<?php echo $langCode; ?>[]" maxlength="255" value="" />';
				html += '<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" /><br />';
				<?php
			}	
		}
		else 
		{
			?>
			html += '<input class="input-large" type="text" name="attribute_value[]" maxlength="255" value="" />';
			<?php
		}
		?>
		html += '</td>';
		//Published column
		html += '<td style="text-align: center; vertical-align: middle;"><select class="inputbox" name="attribute_published[]">';
		html += '<option selected="selected" value="1"><?php echo JText::_('ESHOP_YES'); ?></option>';
		html += '<option value="0"><?php echo JText::_('ESHOP_NO'); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center; vertical-align: middle;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductAttribute('+countProductAttributes+');" /></td>';
		html += '</tr>';
		jQuery('#product_attributes_area').append(html);
		countProductAttributes++;
	}
	function removeProductAttribute(rowIndex) {
		jQuery('#product_attribute_' + rowIndex).remove();
	}
	//Options
	<?php
	$addedOptions = array();
	for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
		$addedOptions[] = '"'.$this->productOptions[$i]->id.'"';		
	}
	?>
	var addedOptions = new Array(<?php echo implode($addedOptions, ','); ?>);
	function addProductOption() {
		//Change active tab
  		for (var i = 0; i < addedOptions.length; i++) {
			jQuery('#product_option_'+addedOptions[i]).attr('class', '');
			jQuery('#option-'+addedOptions[i]+'-page').attr('class', 'tab-pane');
  	  	}
		var optionSel = document.getElementById('option_id');
		//Find option type
		var optionTypeSel = document.getElementById('option_type_id');
		var optionType = 'Select';
  		for (var i = 0; i < optionTypeSel.length; i++) {
  	  		if (optionTypeSel.options[i].value == optionSel.value) {
				var optionType = optionTypeSel.options[i].text;
  	  	  	}
  	  	}
		//working
		var htmlTab = '<li id="product_option_'+optionSel.value+'" class="active">';
		htmlTab += '<a data-toggle="tab" href="#option-'+optionSel.value+'-page">'+optionSel.options[optionSel.selectedIndex].text;
		htmlTab += '<img onclick="removeProductOption('+optionSel.value+', \''+optionSel.options[optionSel.selectedIndex].text+'\');" src="<? echo JURI::base(); ?>components/com_eshop/assets/images/remove.png" />';
		htmlTab += '<input type="hidden" value="'+optionSel.value+'" name="productoption_id[]">';
		htmlTab += '</a></li>';
		jQuery('#nav-tabs').append(htmlTab);
		var htmlContent = '<div id="option-'+optionSel.value+'-page" class="tab-pane active">';
		htmlContent += '<table class="adminlist" style="width: 100%;">';
		htmlContent += '<tbody>';
		htmlContent += '<tr>';
		htmlContent += '<td style="width: 150px;"><?php echo JText::_('ESHOP_REQUIRED'); ?></td>';
		htmlContent += '<td><select class="inputbox" name="required_'+optionSel.value+'" id="required">';
		htmlContent += '<option selected="selected" value="1"><?php echo JText::_('ESHOP_YES'); ?></option>';
		htmlContent += '<option value="0"><?php echo JText::_('ESHOP_NO'); ?></option></select></td>';
		htmlContent += '</tr>';
		htmlContent += '</tbody>';
		htmlContent += '</table>';
		if (optionType == 'Select' || optionType == 'Radio' || optionType == 'Checkobx')
		{
			htmlContent += '<table style="text-align: center;" class="adminlist table table-bordered">';
			htmlContent += '<thead>';
			htmlContent += '<tr><th width="" class="title">Option Value</th><th width="" class="title">Quantity</th><th width="" class="title">Price</th><th width="" class="title">Weight</th><th width="" class="title">&nbsp;</th></tr>';
			htmlContent += '</thead>';
			htmlContent += '<tbody id="product_option_'+optionSel.value+'_values_area"></tbody>';
			htmlContent += '<tfoot>';
			htmlContent += '<tr><td colspan="5"><input type="button" onclick="addProductOptionValue('+optionSel.value+');" value="Add" name="btnAdd" class="btn btn-small btn-primary"></td></tr>';
			htmlContent += '</tfoot>';
			htmlContent += '</table>';	
		}
		htmlContent += '</div>';
  		jQuery('#tab-content').append(htmlContent);
  		addedOptions[addedOptions.length] = optionSel.value;
		for (var i = optionSel.length - 1; i>=0; i--) {
			if (optionSel.options[i].selected) {
				optionSel.remove(i);
				break;
    		}
  		}
	}
	function removeProductOption(optionId, optionName) {
		var optionHtml = '<option value="'+optionId+'">'+optionName+'</option>';
		jQuery('#option_id').append(optionHtml);
		jQuery('#product_option_'+optionId).remove();
		jQuery('#option-'+optionId+'-page').remove();
		addedOptions.splice( addedOptions.indexOf(optionId), 1);
	}
	//Option Values
	var countProductOptionValues = new Array();
	<?php
	for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
		$productOption = $this->productOptions[$i];
		?>
		countProductOptionValues['<?php echo $productOption->id ?>'] = '<?php echo count($this->productOptionValues[$i]); ?>';
		<?php
	}
	for ($i = 0; $n = count($this->options), $i < $n; $i++) {
		?>
		if (countProductOptionValues['<?php echo $this->options[$i]->id; ?>'] === undefined) {
			countProductOptionValues['<?php echo $this->options[$i]->id; ?>'] = 0;
		}
		<?php
	}
	?>
	function addProductOptionValue(optionId) {
		var html = '<tr id="product_option_'+optionId+'_value_'+countProductOptionValues[optionId]+'">';
		//Option Value column
		html += '<td style="text-align: center;">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_id[]">';
		html +=	jQuery('#option_values_'+optionId).html();
		html += '</select>'
		html += '</td>';
		//Quantity column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="255" name="optionvalue_'+optionId+'_quantity[]" class="input-small">';
		html += '</td>';
		//Price column
		html += '<td style="text-align: center;">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_price_sign[]">';
		html +=	jQuery('#price_sign').html();
		html += '</select>'
		html += '<input type="text" value="" maxlength="255" name="optionvalue_'+optionId+'_price[]" class="input-small">';
		html += '</td>';
		//Weight column
		html += '<td style="text-align: center;">';
		html += '<select class="inputbox" name="optionvalue_'+optionId+'_weight_sign[]">';
		html +=	jQuery('#weight_sign').html();
		html += '</select>'
		html += '<input type="text" value="" maxlength="255" name="optionvalue_'+optionId+'_weight[]" class="input-small">';
		html += '</td>';
		//Remove button column
		html += '<td style="text-align: center;">';
		html += '<input type="button" onclick="removeProductOptionValue('+optionId+', '+countProductOptionValues[optionId]+');" value="Remove" name="btnRemove" class="btn btn-small btn-primary">';
		html += '</td>';
		html += '</tr>';
		jQuery('#product_option_'+optionId+'_values_area').append(html);
		countProductOptionValues[optionId]++;
	}
	function removeProductOptionValue(optionId, rowIndex) {
		jQuery('#product_option_'+optionId+'_value_'+rowIndex).remove();
	}
	var countProductDiscounts = '<?php echo count($this->productDiscounts); ?>';
	function addProductDiscount() {
		var html = '<tr id="product_discount_' + countProductDiscounts + '">';
		//Customer group column
		html += '<td style="text-align: center;"><?php echo preg_replace(array('/\r/', '/\n/'), '', $this->lists['discount_customer_group']); ?></td>';
		//Quantity column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="discount_quantity[]" class="input-mini" />';
		html += '</td>';
		//Priority column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="discount_priority[]" class="input-small" />';
		html += '</td>';
		//Price column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="discount_price[]" class="input-small" />';
		html += '</td>';
		//Start date column
		html += '<td style="text-align: center;">';
		html += '<input type="text" style="width: 100px; " value="" id="discount_date_start_'+countProductDiscounts+'" name="discount_date_start[]">';
		html += '<img id="discount_date_start_'+countProductDiscounts+'_img" class="calendar" alt="Calendar" src="templates/bluestork/images/system/calendar.png">';
		html += '</td>';
		//End date column
		html += '<td style="text-align: center;">';
		html += '<input type="text" style="width: 100px; " value="" id="discount_date_end_'+countProductDiscounts+'" name="discount_date_end[]">';
		html += '<img id="discount_date_end_'+countProductDiscounts+'_img" class="calendar" alt="Calendar" src="templates/bluestork/images/system/calendar.png">';
		html += '</td>';
		//Published column
		html += '<td style="text-align: center;"><select class="inputbox" name="discount_published[]">';
		html += '<option selected="selected" value="1"><?php echo JText::_('ESHOP_YES'); ?></option>';
		html += '<option value="0"><?php echo JText::_('ESHOP_NO'); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductDiscount('+countProductDiscounts+');" /></td>';
		html += '</tr>';
		jQuery('#product_discounts_area').append(html);
		Calendar.setup({
			// Id of the input field
			inputField: "discount_date_start_"+countProductDiscounts,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "discount_date_start_"+countProductDiscounts+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
			});
		Calendar.setup({
			// Id of the input field
			inputField: "discount_date_end_"+countProductDiscounts,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "discount_date_end_"+countProductDiscounts+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
			});
		countProductDiscounts++;
	}
	function removeProductDiscount(rowIndex) {
		jQuery('#product_discount_' + rowIndex).remove();
	}
	var countProductSpecials = '<?php echo count($this->productSpecials); ?>';
	function addProductSpecial() {
		var html = '<tr id="product_special_' + countProductSpecials + '">';
		//Customer group column
		html += '<td style="text-align: center;"><?php echo preg_replace(array('/\r/', '/\n/'), '', $this->lists['special_customer_group']); ?></td>';
		//Priority column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="special_priority[]" class="input-small" />';
		html += '</td>';
		//Price column
		html += '<td style="text-align: center;">';
		html += '<input type="text" value="" maxlength="10" name="special_price[]" class="input-small" />';
		html += '</td>';
		//Start date column
		html += '<td style="text-align: center;">';
		html += '<input type="text" style="width: 100px; " value="" id="special_date_start_'+countProductSpecials+'" name="special_date_start[]">';
		html += '<img id="special_date_start_'+countProductSpecials+'_img" class="calendar" alt="Calendar" src="templates/bluestork/images/system/calendar.png">';
		html += '</td>';
		//End date column
		html += '<td style="text-align: center;">';
		html += '<input type="text" style="width: 100px; " value="" id="special_date_end_'+countProductSpecials+'" name="special_date_end[]">';
		html += '<img id="special_date_end_'+countProductSpecials+'_img" class="calendar" alt="Calendar" src="templates/bluestork/images/system/calendar.png">';
		html += '</td>';
		//Published column
		html += '<td style="text-align: center;"><select class="inputbox" name="special_published[]">';
		html += '<option selected="selected" value="1"><?php echo JText::_('ESHOP_YES'); ?></option>';
		html += '<option value="0"><?php echo JText::_('ESHOP_NO'); ?></option>';
		html += '</select></td>';
		// Remove button column
		html += '<td style="text-align: center;"><input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductSpecial('+countProductSpecials+');" /></td>';
		html += '</tr>';
		jQuery('#product_specials_area').append(html);
		Calendar.setup({
			// Id of the input field
			inputField: "special_date_start_"+countProductSpecials,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "special_date_start_"+countProductSpecials+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
			});
		Calendar.setup({
			// Id of the input field
			inputField: "special_date_end_"+countProductSpecials,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: "special_date_end_"+countProductSpecials+"_img",
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 0
			});
		countProductSpecials++;
	}
	function removeProductSpecial(rowIndex) {
		jQuery('#product_special_' + rowIndex).remove();
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_GENERAL'); ?></a></li>
			<li><a href="#data-page" data-toggle="tab"><?php echo JText::_('ESHOP_DATA'); ?></a></li>
			<li><a href="#attributes-page" data-toggle="tab"><?php echo JText::_('ESHOP_ATTRIBUTES'); ?></a></li>
			<li><a href="#options-page" data-toggle="tab"><?php echo JText::_('ESHOP_OPTIONS'); ?></a></li>
			<li><a href="#discount-page" data-toggle="tab"><?php echo JText::_('ESHOP_DISCOUNT'); ?></a></li>
			<li><a href="#special-page" data-toggle="tab"><?php echo JText::_('ESHOP_SPECIAL'); ?></a></li>
			<li><a href="#images-page" data-toggle="tab"><?php echo JText::_('ESHOP_IMAGES'); ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general-page">
				<div class="span10">
				<?php
				if ($translatable)
				{
					?>
					<ul class="nav nav-tabs">
						<?php
							$i = 0;
							foreach ($this->languages as $language)
							{
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
											<input class="input-xlarge" type="text" name="product_name_<?php echo $langCode; ?>" id="product_name_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'product_name_'.$langCode}) ? $this->item->{'product_name_'.$langCode} : ''; ?>" />
										</td>								
									</tr>																		
									<tr>
										<td class="key">
											<?php echo  JText::_('ESHOP_ALIAS'); ?>
										</td>
										<td>
											<input class="input-medium" type="text" name="product_alias_<?php echo $langCode; ?>" id="product_alias_<?php echo $langId; ?>" size="" maxlength="250" value="<?php echo isset($this->item->{'product_alias_'.$langCode}) ? $this->item->{'product_alias_'.$langCode} : ''; ?>" />
										</td>								
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('ESHOP_PRODUCT_SHORT_DESCRIPTION'); ?>
										</td>
										<td>
											<textarea rows="5" cols="30" name="product_short_desc_<?php echo $langCode; ?>"><?php echo isset($this->item->{'product_short_desc_'.$langCode}) ? $this->item->{'product_short_desc_'.$langCode} : ''; ?></textarea>
										</td>
									</tr>
									<tr>
										<td class="key">
											<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
										</td>
										<td>
											<?php echo $editor->display( 'product_desc_'.$langCode,  isset($this->item->{'product_desc_'.$langCode}) ? $this->item->{'product_desc_'.$langCode} : '' , '100%', '250', '75', '10' ); ?>
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
									<tr>
										<td class="key">
											<?php echo  JText::_('ESHOP_PRODUCT_TAGS'); ?>
										</td>
										<td>
											<input class="inputbox" type="text" name="product_tag_<?php echo $langCode; ?>" id="product_tag_<?php echo $langCode; ?>" size="50" value="<?php echo isset($this->item->{'product_tag_'.$langCode}) ? $this->item->{'product_tag_'.$langCode} : ''; ?>" />
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
								<input class="input-xlarge" type="text" name="product_name" id="product_name" size="" maxlength="250" value="<?php echo $this->item->product_name; ?>" />
							</td>								
						</tr>																		
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_ALIAS'); ?>
							</td>
							<td>
								<input class="input-medium" type="text" name="product_alias" id="product_alias" size="" maxlength="250" value="<?php echo $this->item->product_alias; ?>" />
							</td>								
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_SHORT_DESCRIPTION'); ?>
							</td>
							<td>
								<textarea rows="5" cols="30" name="product_short_desc"><?php echo $this->item->product_short_desc; ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
							</td>
							<td>
								<?php echo $editor->display( 'product_desc',  $this->item->product_desc , '100%', '250', '75', '10' ); ?>
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
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_PRODUCT_TAGS'); ?>
							</td>
							<td>
								<input class="inputbox" type="text" name="product_tag" id="product_tag" size="50" value="<?php echo $this->item->product_tag; ?>" />
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
								<span class="required">*</span>
								<?php echo JText::_('ESHOP_PRODUCT_SKU'); ?>
							</td>
							<td>
								<input class="input-medium" type="text" name="product_sku" id="product_sku" size="" maxlength="250" value="<?php echo $this->item->product_sku; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_MANUFACTURER'); ?>
							</td>
							<td>
								<?php echo $this->lists['manufacturer']; ?>
							</td>
						</tr>
						<tr>
							<td class="key" valign="top">
								<?php echo  JText::_('ESHOP_IMAGE'); ?>
							</td>
							<td valign="middle">
								<input type="file" class="input-large" accept="image/*" name="product_image" />								
								<?php
								if (JFile::exists(JPATH_ROOT.'/media/com_eshop/products/'.$this->item->product_image))
								{
									$viewImage = JFile::stripExt($this->item->product_image).'-100x100.'.JFile::getExt($this->item->product_image);
									if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/products/resized/'.$viewImage))
									{
										?>
										<img src="<?php echo JURI::root().'media/com_eshop/products/resized/'.$viewImage; ?>" />
										<?php
									}
									else 
									{
										?>
										<img src="<?php echo JURI::root().'media/com_eshop/products/'.$this->item->product_image; ?>" height="100" />
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
								<?php echo JText::_('ESHOP_CATEGORIES'); ?>
							</td>
							<td>
								<?php echo $this->lists['categories']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_PRICE'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_price" id="product_price" size="" maxlength="250" value="<?php echo $this->item->product_price; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_LENGTH'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_length" id="product_length" size="" maxlength="250" value="<?php echo $this->item->product_length; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_WIDTH'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_width" id="product_width" size="" maxlength="250" value="<?php echo $this->item->product_width; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_HEIGHT'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_height" id="product_height" size="" maxlength="250" value="<?php echo $this->item->product_height; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_LENGTH_UNIT'); ?>
							</td>
							<td>
								<?php echo $this->lists['product_length_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_WEIGHT'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_weight" id="product_weight" size="" maxlength="250" value="<?php echo $this->item->product_weight; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_WEIGHT_UNIT'); ?>
							</td>
							<td>
								<?php echo $this->lists['product_weight_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_TAX'); ?>
							</td>
							<td>
								<?php echo $this->lists['taxclasses']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_QUANTITY'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_quantity" id="product_quantity" size="" maxlength="250" value="<?php echo $this->item->product_quantity; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_REQUIRES_SHIPPING'); ?>
							</td>
							<td>
								<?php echo $this->lists['product_shipping']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_SHIPPING_COST'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="product_shipping_cost" id="product_shipping_cost" size="" maxlength="250" value="<?php echo $this->item->product_shipping_cost; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_RELATED_PRODUCTS'); ?>
							</td>
							<td>
								<?php echo $this->lists['related_products']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_AVAILABLE_DATE'); ?>
							</td>
							<td>
								<?php echo JHtml::_('calendar', (($this->item->product_available_date == $this->nullDate) || !$this->item->product_available_date) ? '' : JHtml::_('date', $this->item->product_available_date, 'Y-m-d', null), 'product_available_date', 'product_available_date', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PRODUCT_FEATURED'); ?>
							</td>
							<td>
								<?php echo $this->lists['featured']; ?>
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
			<div class="tab-pane" id="attributes-page">
				<div class="span6">
					<table class="adminlist table table-bordered" style="text-align: center;">
						<thead>
							<tr>
								<th class="title" width="30%"><?php echo JText::_('ESHOP_ATTRIBUTE'); ?></th>
								<th class="title" width="45%"><?php echo JText::_('ESHOP_VALUE'); ?></th>
								<th class="title" width="15%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
								<th class="title" width="10%">&nbsp;</th>
							</tr>
						</thead>
						<tbody id="product_attributes_area">
							<?php
							$options = array();
							$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
							$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
							for ($i = 0; $n = count($this->productAttributes), $i < $n; $i++)
							{
								$productAttribute = $this->productAttributes[$i];
								?>
								<tr id="product_attribute_<?php echo $i; ?>">
									<td style="text-align: center; vertical-align: middle;">
										<?php echo $this->lists['attributes_'.$productAttribute->id]; ?>
									</td>
									<td style="text-align: center; vertical-align: middle;">
										<?php
										if ($translatable)
										{
											foreach ($this->languages as $language)
											{
												$langCode = $language->lang_code;
												?>
												<input class="input-large" type="text" name="attribute_value_<?php echo $langCode; ?>[]" maxlength="255" value="<?php echo isset($productAttribute->{'value_'.$langCode}) ? $productAttribute->{'value_'.$langCode} : ''; ?>" />
												<img src="<?php echo JURI::root(); ?>media/com_eshop/flags/<?php echo $this->languageData['flag'][$langCode]; ?>" />
												<input type="hidden" class="inputbox" name="productattributedetails_id_<?php echo $langCode; ?>[]" value="<?php echo isset($productAttribute->{'productattributedetails_id_'.$langCode}) ? $productAttribute->{'productattributedetails_id_'.$langCode} : ''; ?>" />
												<br />
												<?php
											}
										}
										else
										{
											?>
											<input class="input-medium" type="text" name="attribute_value[]" maxlength="255" value="<?php echo $productAttribute->value; ?>" />
											<input type="hidden" class="inputbox" name="productattributedetails_id[]" value="<?php echo $productAttribute->productattributedetails_id; ?>" />
											<?php
										}
										?>
										<input type="hidden" class="inputbox" name="productattribute_id[]" value="<?php echo $productAttribute->productattribute_id; ?>" />
									</td>
									<td style="text-align: center; vertical-align: middle;">
										<?php echo JHtml::_('select.genericlist', $options, 'attribute_published[]', ' class="inputbox"', 'value', 'text', $productAttribute->published); ?>
									</td>
									<td style="text-align: center; vertical-align: middle;">
										<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductAttribute(<?php echo $i; ?>);" />
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4">
									<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductAttribute();" />
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="options-page">
				<div class="span10">
					<div class="tabbable tabs-left">
						<ul class="nav nav-tabs" id="nav-tabs">
							<li><?php echo $this->lists['options']; ?></li>
							<?php
							echo $this->lists['options_type'];
							for ($i = 0; $n = count($this->options), $i < $n; $i++) {
								echo $this->lists['option_values_'.$this->options[$i]->id];
							}
							echo $this->lists['price_sign'];
							echo $this->lists['weight_sign'];
							?>
							<?php
							for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
								$productOption = $this->productOptions[$i];
								?>
								<li <?php echo ($i == 0) ? 'class="active"' : 0; ?> id="product_option_<?php echo $productOption->id; ?>">
									<a href="#option-<?php echo $productOption->id; ?>-page" data-toggle="tab"><?php echo $productOption->option_name; ?>
									<img src="<?php echo JURI::base(); ?>components/com_eshop/assets/images/remove.png" onclick="removeProductOption(<?php echo $productOption->id; ?>, '<?php echo $productOption->option_name; ?>');" />
									</a>
									<input type="hidden" name="productoption_id[]" value="<?php echo $productOption->id; ?>"/>
								</li>
								<?php
							}
							?>
						</ul>	
						<div class="tab-content" id="tab-content">
							<?php
							for ($i = 0; $n = count($this->productOptions), $i < $n; $i++) {
								$productOption = $this->productOptions[$i];
								?>
								<div class="tab-pane<?php echo ($i == 0) ? ' active' : ''; ?>" id="option-<?php echo $productOption->id; ?>-page">
									<table style="width: 100%;" class="adminlist">
										<tbody>
											<tr>
												<td style="width: 150px;"><?php echo JText::_('ESHOP_REQUIRED'); ?></td>
												<td>
													<?php echo JHtml::_('select.genericlist', $options, 'required_'.$productOption->id, ' class="inputbox"', 'value', 'text', $productOption->required); ?>
												</td>
											</tr>
										</tbody>
									</table>
									<?php
									if ($productOption->option_type == 'Select' || $productOption->option_type == 'Radio' || $productOption->option_type == 'Checkbox')
									{
										?>
										<table class="adminlist table table-bordered" style="text-align: center;">
											<thead>
												<tr>
													<th class="title" width=""><?php echo JText::_('ESHOP_OPTION_VALUE'); ?></th>
													<th class="title" width=""><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
													<th class="title" width=""><?php echo JText::_('ESHOP_PRICE'); ?></th>
													<th class="title" width=""><?php echo JText::_('ESHOP_WEIGHT'); ?></th>
													<th class="title" width="">&nbsp;</th>
												</tr>
											</thead>
											<tbody id="product_option_<?php echo $productOption->id; ?>_values_area">
												<?php
												$options = array();
												$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
												$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
												for ($j = 0; $m = count($this->productOptionValues[$i]), $j < $m; $j++) {
													$productOptionValue = $this->productOptionValues[$i][$j];
													?>
													<tr id="product_option_<?php echo $productOption->id; ?>_value_<?php echo $j; ?>">
														<td style="text-align: center;">
															<?php echo $this->lists['product_option_value_'.$productOptionValue->id]; ?>
														</td>
														<td style="text-align: center;">
															<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_quantity[]" size="10" maxlength="255" value="<?php echo $productOptionValue->quantity; ?>" />
															<input type="hidden" name="productoptionvalue_id" value="<?php echo $productOptionValue->id; ?>" />
														</td>
														<td style="text-align: center;">
															<?php echo $this->lists['price_sign_'.$productOptionValue->id]; ?>
															<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_price[]" size="10" maxlength="255" value="<?php echo $productOptionValue->price; ?>" />
														</td>
														<td style="text-align: center;">
															<?php echo $this->lists['weight_sign_'.$productOptionValue->id]; ?>
															<input class="input-small" type="text" name="optionvalue_<?php echo $productOption->id; ?>_weight[]" size="10" maxlength="255" value="<?php echo $productOptionValue->weight; ?>" />
														</td>
														<td style="text-align: center;">
															<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductOptionValue(<?php echo $productOption->id; ?>, <?php echo $j; ?>);" />
														</td>
													</tr>	
													<?php
												}
												?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="5">
														<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductOptionValue(<?php echo $productOption->id; ?>);" />
														<?php echo $this->lists['option_values_'.$productOption->id]; ?>										
													</td>
												</tr>
											</tfoot>
										</table>
										<?php
									}	
									?>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="discount-page">
				<div class="span12">
					<table class="adminlist table table-bordered" style="text-align: center;">
						<thead>
							<tr>
								<th class="title" width="20%"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_QUANTITY'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_PRIORITY'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_PRICE'); ?></th>
								<th class="title" width="15%"><?php echo JText::_('ESHOP_START_DATE'); ?></th>
								<th class="title" width="15%"><?php echo JText::_('ESHOP_END_DATE'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
								<th class="title" width="10%">&nbsp;</th>
							</tr>
						</thead>
						<tbody id="product_discounts_area">
							<?php
							$options = array();
							$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
							$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
							for ($i = 0; $n = count($this->productDiscounts), $i < $n; $i++) {
								$productDiscount = $this->productDiscounts[$i];
								?>
								<tr id="product_discount_<?php echo $i; ?>">
									<td style="text-align: center;">
										<?php echo $this->lists['discount_customer_group_'.$productDiscount->id]; ?>
										<input type="hidden" class="inputbox" name="productdiscount_id[]" value="<?php echo $productDiscount->id; ?>" />
									</td>
									<td style="text-align: center;">
										<input class="input-mini" type="text" name="discount_quantity[]" maxlength="10" value="<?php echo $productDiscount->quantity; ?>" />
									</td>
									<td style="text-align: center;">
										<input class="input-small" type="text" name="discount_priority[]" maxlength="10" value="<?php echo $productDiscount->priority; ?>" />
									</td>
									<td style="text-align: center;">
										<input class="input-small" type="text" name="discount_price[]" maxlength="10" value="<?php echo $productDiscount->price; ?>" />
									</td>
									<td style="text-align: center;">
										<?php echo JHtml::_('calendar', (($productDiscount->date_start == $this->nullDate) || !$productDiscount->date_start) ? '' : JHtml::_('date', $productDiscount->date_start, 'Y-m-d', null), 'discount_date_start[]', 'discount_date_start_'.$i, '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
									</td>
									<td style="text-align: center;">
										<?php echo JHtml::_('calendar', (($productDiscount->date_end == $this->nullDate) || !$productDiscount->date_end) ? '' : JHtml::_('date', $productDiscount->date_end, 'Y-m-d', null), 'discount_date_end[]', 'discount_date_end_'.$i, '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
									</td>
									<td style="text-align: center;">
										<?php echo JHtml::_('select.genericlist', $options, 'discount_published[]', ' class="inputbox"', 'value', 'text', $productDiscount->published); ?>
									</td>
									<td style="text-align: center;">
										<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductDiscount(<?php echo $i; ?>);" />
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="9">
									<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductDiscount();" />
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="special-page">
				<div class="tab-pane" id="special-page">
				<div class="span10">
					<table class="adminlist table table-bordered" style="text-align: center;">
						<thead>
							<tr>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_CUSTOMER_GROUP'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_PRIORITY'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_PRICE'); ?></th>
								<th class="title" width="15%"><?php echo JText::_('ESHOP_START_DATE'); ?></th>
								<th class="title" width="15%"><?php echo JText::_('ESHOP_END_DATE'); ?></th>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
								<th class="title" width="10%">&nbsp;</th>
							</tr>
						</thead>
						<tbody id="product_specials_area">
							<?php
							$options = array();
							$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
							$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
							for ($i = 0; $n = count($this->productSpecials), $i < $n; $i++) {
								$productSpecial = $this->productSpecials[$i];
								?>
								<tr id="product_special_<?php echo $i; ?>">
									<td style="text-align: center;">
										<?php echo $this->lists['special_customer_group_'.$productSpecial->id]; ?>
									</td>
									<td style="text-align: center;">
										<input class="input-small" type="text" name="special_priority[]" maxlength="10" value="<?php echo $productSpecial->priority; ?>" />
									</td>
									<td style="text-align: center;">
										<input class="input-small" type="text" name="special_price[]" maxlength="10" value="<?php echo $productSpecial->price; ?>" />
									</td>
									<td style="text-align: center;">
										<?php echo JHtml::_('calendar', (($productSpecial->date_start == $this->nullDate) || !$productSpecial->date_start) ? '' : JHtml::_('date', $productSpecial->date_start, 'Y-m-d', null), 'special_date_start[]', 'special_date_start_'.$i, '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
									</td>
									<td style="text-align: center;">
										<?php echo JHtml::_('calendar', (($productSpecial->date_end == $this->nullDate) || !$productSpecial->date_end) ? '' : JHtml::_('date', $productSpecial->date_end, 'Y-m-d', null), 'special_date_end[]', 'special_date_end_'.$i, '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
									</td>
									<td style="text-align: center;">
										<?php echo JHtml::_('select.genericlist', $options, 'special_published[]', ' class="inputbox"', 'value', 'text', $productSpecial->published); ?>
									</td>
									<td style="text-align: center;">
										<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductSpecial(<?php echo $i; ?>);" />
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="7">
									<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductSpecial();" />
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			</div>
			<div class="tab-pane" id="images-page">
				<div class="span6">
					<table class="adminlist table table-bordered" style="text-align: center;">
						<thead>
							<tr>
								<th class="title" width="40%"><?php echo JText::_('ESHOP_IMAGE'); ?></th>
								<th class="title" width="20%"><?php echo JText::_('ESHOP_ORDERING'); ?></th>
								<th class="title" width="20%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
								<th class="title" width="20%">&nbsp;</th>
							</tr>
						</thead>
						<tbody id="product_images_area">
							<?php
							$options = array();
							$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
							$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
							for ($i = 0; $n = count($this->productImages), $i < $n; $i++) {
								$productImage = $this->productImages[$i];
								?>
								<tr id="product_image_<?php echo $i; ?>" style="height: 100px;">
									<td style="text-align: center; vertical-align: middle;">
										<?php
										if (JFile::exists(JPATH_ROOT.'/media/com_eshop/products/'.$productImage->image))
										{
											$viewImage = JFile::stripExt($productImage->image).'-100x100.'.JFile::getExt($productImage->image);
											if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/products/resized/'.$viewImage))
											{
												?>
												<img class="img-polaroid" src="<?php echo JURI::root().'media/com_eshop/products/resized/'.$viewImage; ?>" />
												<?php
											}
										}
										?>
										<input type="hidden" class="inputbox" name="productimage_id[]" value="<?php echo $productImage->id; ?>" />
									</td>
									<td style="text-align: center; vertical-align: middle;">
										<input class="input-small" type="text" name="productimage_ordering[]" size="5" maxlength="10" value="<?php echo $productImage->ordering; ?>" />
									</td>
									<td style="text-align: center; vertical-align: middle;">
										<?php echo JHtml::_('select.genericlist', $options, 'productimage_published[]', ' class="inputbox"', 'value', 'text', $productImage->published); ?>
									</td>
									<td style="text-align: center; vertical-align: middle;">
										<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductImage(<?php echo $i; ?>);" />
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4">
									<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductImage();" />
								</td>
							</tr>
						</tfoot>
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