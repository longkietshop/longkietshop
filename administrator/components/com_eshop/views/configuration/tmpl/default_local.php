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
			<?php echo  JText::_('ESHOP_CONFIG_COUNTRY'); ?>:
		</td>
		<td>
			<?php echo $this->lists['country_id']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_REGION_STATE'); ?>:
		</td>
		<td>
			<?php echo $this->lists['zone_id']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_DEFAULT_CURRENCY'); ?>:
		</td>
		<td>
			<?php echo $this->lists['default_currency_code']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_AUTO_UPDATE_CURRENCY'); ?>:
		</td>
		<td>
			<?php echo $this->lists['auto_update_currency']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_LENGTH'); ?>:
		</td>
		<td>
			<?php echo $this->lists['length_id']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_WEIGHT'); ?>:
		</td>
		<td>
			<?php echo $this->lists['weight_id']; ?>
		</td>
	</tr>
</table>