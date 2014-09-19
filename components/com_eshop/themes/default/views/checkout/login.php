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
<div class="span6 no_margin_left">
	<h4><?php echo JText::_('ESHOP_CHECKOUT_NEW_CUSTOMER'); ?></h4>
	<p><?php echo JText::_('ESHOP_CHECKOUT_NEW_CUSTOMER_INTRO'); ?></p>
	<label class="radio"><input type="radio" value="register" name="account" checked="checked" /> <?php echo JText::_('ESHOP_REGISTER_ACCOUNT'); ?></label>
	<?php 
	if (EshopHelper::getConfigValue('guest_checkout') && !EshopHelper::getConfigValue('customer_price'))
	{
		?>
		<label class="radio"><input type="radio" value="guest" name="account" /> <?php echo JText::_('ESHOP_GUEST_CHECKOUT'); ?></label>
		<?php
	}
	?>
	<input type="button" class="btn btn-primary pull-left" id="button-account" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>
<div id="login" class="span5">
	<h4><?php echo JText::_('ESHOP_REGISTERED_CUSTOMER'); ?></h4>
	<p><?php echo JText::_('ESHOP_REGISTERED_CUSTOMER_INTRO'); ?></p>
	<fieldset>
		<div class="control-group">
			<label for="username" class="control-label"><?php echo JText::_('ESHOP_USERNAME'); ?></label>
			<div class="controls">
				<input type="text" placeholder="<?php echo JText::_('ESHOP_USERNAME_INTRO'); ?>" id="username" name="username" class="input-xlarge focused" />
			</div>
		</div>
		<div class="control-group">
			<label for="password" class="control-label"><?php echo JText::_('ESHOP_PASSWORD'); ?></label>
			<div class="controls">
				<input type="password" placeholder="<?php echo JText::_('ESHOP_PASSWORD_INTRO'); ?>" id="password" name="password" class="input-xlarge" />
			</div>
		</div>
		<label class="checkbox" for="remember">
			<input type="checkbox" alt="<?php echo JText::_('ESHOP_REMEMBER_ME'); ?>" value="yes" class="inputbox" name="remember" id="remember" /><?php echo JText::_('ESHOP_REMEMBER_ME'); ?>
		</label>
		<ul>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
				<?php echo JText::_('ESHOP_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				<?php echo JText::_('ESHOP_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
		</ul>
		<input type="button" class="btn btn-primary pull-left" id="button-login" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</fieldset>
</div>
	