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
			<?php echo JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL'); ?>:<br>
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_ORDER_ALERT_MAIL_HELP'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['order_alert_mail']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_ALERT_MAILS'); ?>:<br>
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_ALERT_MAILS_HELP'); ?></span>
		</td>
		<td>
			<input class="text_area" type="text" name="alert_emails" id="alert_emails" size="100" maxlength="250" value="<?php echo $this->config->alert_emails; ?>" />
		</td>
	</tr>
</table>	