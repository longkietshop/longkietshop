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
EshopHelper::chosen(); 	
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'geozone.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.geozone_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
				form.geozone_name.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
	var rowIndex = <?php echo count($this->zoneToGeozones); ?>;
	var defaultCountry = <?php echo $this->countryId; ?>;
	function addGeoZone() {
		var countryId = 'country' + rowIndex;
		var zoneId = 'zone' + rowIndex;	
		html = '<tr id="zone-to-geo-zone-row' + rowIndex + '">';
		html += '<td style="text-align: center;"><select name="country_id[]"  id="country' + rowIndex + '" onchange="Eshop.updateStateList(this.value,\''+ zoneId + '\')">';
		html += countriesOptions;
		html += '</select></td>';
		html += '<td style="text-align: center;"><select name="zone[]" style="width: 220px;" id="zone' + rowIndex + '"><option value="0"><?php echo JText::_('ESHOP_ALL_ZONES'); ?></option></select></td>';
		html += '<td style="text-align: center;"><input type="button" onclick="jQuery(\'#zone-to-geo-zone-row' + rowIndex + '\').remove();" class="btn btn-small btn-primary" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>"></td>';
		html += '</tr>';
		jQuery('#zone-to-geo-zone').append(html);
		jQuery('#country' + rowIndex).attr('value', defaultCountry);
		Eshop.updateStateList(defaultCountry, zoneId);
		rowIndex++;
		jQuery("#"+countryId).chosen();
	}
	<?php
	if (count($this->zoneToGeozones)) {
		$index = 0;
 		?>
		jQuery(document).ready(function() {
 		  	<?php
			foreach ($this->zoneToGeozones as $zoneToGeozone) {
 		  		$zoneSelectTagId = 'zone'.$index;
				?>
				Eshop.updateStateList(<?php echo $zoneToGeozone->country_id; ?>, '<?php echo $zoneSelectTagId ?>');
 		  		jQuery('#<?php echo $zoneSelectTagId; ?>').attr('value', <?php echo $zoneToGeozone->zone_id; ?>);
 		  		<?php
 		  			$index++;
 		  		}
 		  	?>
 		});
	<?php
 	}
	?>
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span5">
			<fieldset class="admintable">
				<legend><?php echo JText::_('ESHOP_GEOZONE_DETAILS'); ?></legend>
				<table class="admintable adminform" style="width: 100%;">
					<tr>
						<td class="key">
							<span class="required">*</span>
							<?php echo  JText::_('ESHOP_NAME'); ?>
						</td>
						<td>
							<input  class="input-xlarge" type="text" name="geozone_name" id="geozone_name" maxlength="250" value="<?php echo $this->item->geozone_name; ?>" />
						</td>
					</tr>
					<tr>
						<td class="key">
							<?php echo  JText::_('ESHOP_DESCRIPTION'); ?>
						</td>
						<td>
							<textarea rows="5" cols="40" name="geozone_desc"><?php echo $this->item->geozone_desc; ?></textarea>
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
			</fieldset>
		</div>
		<div class="span7">
			<fieldset class="admintable">
				<legend><?php echo JText::_('ESHOP_GEOZONE_ZONES'); ?></legend>
				<table class="adminlist table table-bordered" style="text-align: center;">
					<thead>
						<tr>
							<th class="title"><?php echo JText::_('ESHOP_COUNTRY')?></th>
							<th class="title"><?php echo JText::_('ESHOP_ZONE')?></th>
							<th class="title">&nbsp;</th>
						</tr>
					</thead>
					<tbody id="zone-to-geo-zone">
					<?php
					$rowIndex = 0;
					if(count($this->zoneToGeozones)){
						foreach ($this->zoneToGeozones as $zoneToGeozone) {
						?>					
						<tr id="zone-to-geo-zone-row<?php echo $rowIndex?>">
							<td style="text-align: center;">
								<?php echo JHtml::_('select.genericlist', $this->countryOptions, 'country_id[]', ' class="inputbox chosen" onchange="Eshop.updateStateList(this.value, \'zone'.$rowIndex.'\')"', 'id', 'name', $zoneToGeozone->country_id, 'country'.$rowIndex); ?>
							</td>
							<td style="text-align: center;">
								<select name="zone[]" class="inputbox" style="width: 220px;" id="zone<?php echo $rowIndex; ?>"></select>
							</td>
							<td style="text-align: center;">
								<input type="button" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" class="btn btn-small btn-primary" onclick="jQuery('#zone-to-geo-zone-row<?php echo $rowIndex; ?>').remove();">
							</td>
						</tr>						
						<?php
							$rowIndex++;	
						}					
					}
					?>
					</tbody>
					<tfoot>
						<tr>
			              <td colspan="3" class="left"><input class="btn btn-small btn-primary" type="button" name="add" value="<?php echo JText::_('ESHOP_BTN_ADD')?>" onclick="addGeoZone();" ></td>
			            </tr>	
					</tfoot>									
				</table>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />	
</form>