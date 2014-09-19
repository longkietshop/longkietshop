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
<table class="admintable adminform">
	<tr>
		<td>
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_STORE_NAME'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="store_name" id="store_name" size="15" maxlength="250" value="<?php echo $this->config->store_name; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_STORE_OWNER'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="store_owner" id="store_owner" size="15" maxlength="250" value="<?php echo $this->config->store_owner; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_ADDRESS'); ?>:
		</td>
		<td>
			<textarea rows="5" cols="40" name="address"><?php echo $this->config->address; ?></textarea>					
		</td>
	</tr>
	<tr>
		<td>
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_EMAIL'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="email" id="email" size="15" maxlength="100" value="<?php echo $this->config->email; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<span class="required">*</span><?php echo  JText::_('ESHOP_CONFIG_TELEPHONE'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="telephone" id="telephone" size="10" maxlength="15" value="<?php echo $this->config->telephone; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_FAX'); ?>:
		</td>
		<td>
			<input class="text_area" type="text" name="fax" id="fax" size="10" maxlength="15" value="<?php echo $this->config->fax; ?>" />
		</td>
	</tr>
</table>