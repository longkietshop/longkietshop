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
<script src="<?php echo JURI::base(); ?>components/com_eshop/assets/colorbox/jquery.colorbox.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".colorbox").colorbox();
	});
</script>
<?php
if (count($this->methods))
{
	?>
	<div>
		<p><?php echo JText::_('ESHOP_PAYMENT_METHOD_TITLE'); ?></p>
		<?php
		for ($i = 0 , $n = count($this->methods); $i < $n; $i++)
		{
			$paymentMethod = $this->methods[$i];
			if ($paymentMethod->getName() == $this->paymentMethod)
			{
				$checked = ' checked="checked" ';
			}
			else
				$checked = '';
			?>
			<label class="radio">
				<input type="radio" name="payment_method" value="<?php echo $paymentMethod->getName(); ?>" <?php echo $checked; ?> /> <?php echo JText::_($paymentMethod->title); ?> <br />
			</label>
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
	<?php
	if (isset($this->checkoutTermsLink) && $this->checkoutTermsLink != '')
	{
		?>
		<span class="privacy">
			<input type="checkbox" value="1" name="checkout_terms_agree" <?php echo ($this->checkout_terms_agree) ? $this->checkout_terms_agree : ''; ?>/>
			&nbsp;<?php echo JText::_('ESHOP_CHECKOUT_TERMS_AGREE'); ?>&nbsp;<a class="colorbox cboxElement" href="<?php echo $this->checkoutTermsLink; ?>"><?php echo JText::_('ESHOP_CHECKOUT_TERMS_AGREE_TITLE'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</span>	
		<?php
	}
	?>
	<input type="button" class="btn btn-primary pull-right" id="button-payment-method" value="<?php echo JText::_('ESHOP_CONTINUE'); ?>" />
</div>