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
			<?php echo  JText::_('ESHOP_CONFIG_THEME'); ?>:
		</td>
		<td>
			<?php echo $this->lists['theme']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_LOAD_BOOTSTRAP_CSS'); ?>:
		</td>
		<td>
			<?php echo $this->lists['load_bootstrap_css']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo  JText::_('ESHOP_CONFIG_LOAD_BOOTSTRAP_JAVASCRIPT'); ?>:
		</td>
		<td>
			<?php echo $this->lists['load_bootstrap_js']; ?>
		</td>
	</tr>
</table>