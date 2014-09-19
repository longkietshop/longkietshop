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
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_ENABLE'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_ENABLE_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['social_enable']; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong><u><?php echo JText::_('ESHOP_CONFIG_SOCIAL_FACEBOOK'); ?></u></strong></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_APPLICATION_ID'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_APPLICATION_ID_DESC'); ?></span>
		</td>
		<td>
			<input class="input-medium" type="text" name="app_id" id="app_id"  value="<?php echo $this->config->app_id; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_FONT'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_FONT_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['button_font']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_THEME'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_THEME_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['button_theme']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_LANGUAGE'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_LANGUAGE_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['button_language']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_BUTTON'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_BUTTON_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_facebook_button']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_LIKE_BUTTON_LAYOUT'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_LIKE_BUTTON_LAYOUT_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['button_layout']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACES'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACES_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_faces']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_WIDTH'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_BUTTON_WIDTH_DESC'); ?></span>
		</td>
		<td>
			<input class="input-mini" type="text" name="button_width" id="button_width"  value="<?php echo $this->config->button_width; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_COMMENT'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_FACEBOOK_COMMENT_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_facebook_comment']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_NUMBER_OF_POSTS'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_NUMBER_OF_POSTS_DESC'); ?></span>
		</td>
		<td>
			<input class="input-mini" type="text" name="num_posts" id="num_posts"  value="<?php echo $this->config->num_posts; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_COMMENT_WIDTH'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_COMMENT_WIDTH_DESC'); ?></span>
		</td>
		<td>
			<input class="input-mini" type="text" name="comment_width" id="comment_width"  value="<?php echo $this->config->comment_width; ?>" />
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong><u><?php echo JText::_('ESHOP_CONFIG_SOCIAL_TWITTER'); ?></u></strong></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_TWITTER_BUTTON'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_TWITTER_BUTTON_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_twitter_button']; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong><u><?php echo JText::_('ESHOP_CONFIG_SOCIAL_PINTEREST'); ?></u></strong></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_PINIT_BUTTON'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_PINIT_BUTTON_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_pinit_button']; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong><u><?php echo JText::_('ESHOP_CONFIG_SOCIAL_GOOGLE'); ?></u></strong></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_GOOGLE_BUTTON'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_GOOGLE_BUTTON_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_google_button']; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong><u><?php echo JText::_('ESHOP_CONFIG_SOCIAL_LINKEDIN'); ?></u></strong></td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_LINKEDIN_BUTTON'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_SHOW_LINKEDIN_BUTTON_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['show_linkedin_button']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('ESHOP_CONFIG_SOCIAL_LINKEDIN_LAYOUT'); ?>:<br />
			<span class="help"><?php echo JText::_('ESHOP_CONFIG_SOCIAL_LINKEDIN_LAYOUT_DESC'); ?></span>
		</td>
		<td>
			<?php echo $this->lists['linkedin_layout']; ?>
		</td>
	</tr>
</table>