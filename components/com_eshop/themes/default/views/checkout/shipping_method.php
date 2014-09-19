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

if (isset($this->shipping_methods))
{
	?>
	<div>
		<p><?php echo JText::_('ESHOP_SHIPPING_METHOD_TITLE'); ?></p>
		<?php
		foreach ($this->shipping_methods as $shippingMethod)
		{
			?>
			<div>
				<strong><?php echo $shippingMethod['title']; ?></strong><br />
				<?php
				foreach ($shippingMethod['quote'] as $quote)
				{
					$checkedStr = ' ';
					if ($quote['name'] == $this->shipping_method)
					{
						$checkedStr = ' checked = "checked" ';
					}
					?>
					<label class="radio">
						<input type="radio" value="<?php echo $quote['name']; ?>" name="shipping_method" <?php echo $checkedStr; ?>/>
						<?php echo $quote['title'] . ' (' . $quote['text'] . ')'; ?>
					</label>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
?>
<br />
<div class="control-group">
	<label for="textarea" class="control-label"><?php echo JText::_('ESHOP_COMMENT_ORDER'); ?></label>
	<div class="controls">
		<textarea rows="8" id="textarea" class="input-xlarge span12" name="comment"><?php echo $this->comment; ?></textarea>
	</div>
</div>
<div class="no_margin_left">
	<input type="button" class="btn btn-primary pull-right" id="button-shipping-method" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>