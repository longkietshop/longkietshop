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
JToolBarHelper::title(JText::_('ESHOP_HELP'), 'generic.png');
?>
<table cellpadding="3" cellspacing="3" border="0" width="100%" style="font-size: 13px; line-height: 20px;">
	<tr>
		<td>
			<?php echo JText::_('ESHOP_HELP_RELEASED'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_HELP_DEMO'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_HELP_DOCUMENTATION'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_HELP_SUPPORT'); ?>
			<ol>
				<li><?php echo JText::_('ESHOP_HELP_SUPPORT_1'); ?></li>
				<li><?php echo JText::_('ESHOP_HELP_SUPPORT_2'); ?></li>
				<li>
					<?php echo JText::_('ESHOP_HELP_SUPPORT_3'); ?>
					<ul>
						<li><?php echo JText::_('ESHOP_HELP_SUPPORT_3_SKYPE'); ?></li>
						<li><?php echo JText::_('ESHOP_HELP_SUPPORT_3_YM'); ?></li>
						<li><?php echo JText::_('ESHOP_HELP_SUPPORT_3_GTALK'); ?></li>
					</ul>
				</li>	
			</ol>
		</td>
	</tr>
</table>