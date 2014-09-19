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
 
$user = JFactory::getUser();
$language = JFactory::getLanguage();
$tag = $language->getTag();
if (!$tag)
	$tag = 'en-GB';
if(!$this->orderProducts)
{
	?>
	<div class="warning"><?php echo JText::_('ESHOP_ORDER_DOES_NOT_EXITS'); ?></div>
	<?php
}
else
{
	$hasShipping = $this->orderInfor->shipping_method;
	?>
	<form id="adminForm">
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td colspan="2" class="left">
						<?php echo JText::_('ESHOP_ORDER_DETAILS'); ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td style="width: 50%;" class="left">
						 <b><?php echo JText::_('ESHOP_ORDER_ID'); ?>: </b> # <?php echo $this->orderInfor->id; ?><br />
	         			 <b><?php echo JText::_('ESHOP_DATE_ADDED'); ?>: </b> <?php echo JHtml::_('date',$this->orderInfor->created_date,'m/d/Y'); ?>
	         		</td>
					<td style="width: 50%;" class="left">
					    <b><?php echo JText::_('ESHOP_PAYMENT_METHOD'); ?>: </b> <?php echo $this->orderInfor->payment_method_title; ?><br />
					    <b><?php echo JText::_('ESHOP_SHIPPING_METHOD'); ?>: </b> <?php echo $this->orderInfor->shipping_method_title; ?><br />
	                </td>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td class="left">
						<?php echo JText::_('ESHOP_PAYMENT_ADDRESS'); ?>
					</td>
					<?php
					if ($hasShipping)
					{
						?>
						<td class="left">
							<?php echo JText::_('ESHOP_SHIPPING_ADDRESS'); ?>
						</td>
						<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="left">
						<?php echo $this->orderInfor->payment_firstname.' '.$this->orderInfor->payment_lastname ; ?><br />
						<?php echo $this->orderInfor->payment_address_1 . ($this->orderInfor->payment_address_2 != '' ? ', ' . $this->orderInfor->payment_address_2 : ''); ?><br />
						<?php echo $this->orderInfor->payment_city . ', ' . $this->orderInfor->payment_zone_name . ' ' . $this->orderInfor->payment_postcode; ?>
					</td>
					<?php
					if ($hasShipping)
					{
						?>
						<td class="left">
							<?php echo $this->orderInfor->shipping_firstname.' '.$this->orderInfor->shipping_lastname ; ?><br />
							<?php echo $this->orderInfor->shipping_address_1 . ($this->orderInfor->shipping_address_2 != '' ? ', ' . $this->orderInfor->shipping_address_2 : ''); ?><br />
							<?php echo $this->orderInfor->shipping_city . ', ' . $this->orderInfor->shipping_zone_name . ' ' . $this->orderInfor->shipping_postcode; ?>
						</td>
						<?php
					}
					?>
				</tr>
			</tbody>
		</table>
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td class="left">
						<?php echo JText::_('ESHOP_PRODUCT_NAME'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_MODEL'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_QUANTITY'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_PRICE'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_TOTAL'); ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->orderProducts as $product)
				{
					$options = $product->options;
					?>
					<tr>
						<td class="left">
							<?php
							echo '<b>' . $product->product_name . '</b>';
							for ($i = 0; $n = count($options), $i < $n; $i++)
							{
								echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value;
							}
							?>
						</td>
						<td class="left"><?php echo $product->product_sku; ?></td>
						<td class="left"><?php echo $product->quantity; ?></td>
						<td class="right"><?php echo $product->price; ?></td>
						<td class="right"><?php echo $product->total_price; ?></td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<tfoot>
				<?php
					foreach ($this->orderTotals as $ordertotal)
					{ 
				?>
				<tr>
					<td colspan="3"></td>
					<td class="right">
						<b><?php echo $ordertotal->title?>: </b>
					</td>
					<td class="right">
						<?php echo $ordertotal->text?>
					</td>
				</tr>
				<?php
					} 
				?>
			</tfoot>
		</table>
		
		<h2><?php echo JText::_('ESHOP_ORDER_HISTORY'); ?></h2>
		<table cellpadding="0" cellspacing="0" class="list">
			<thead>
				<tr>
					<td class="left">
						<?php echo JText::_('ESHOP_DATE_ADDED'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_STATUS'); ?>
					</td>
					<td class="left">
						<?php echo JText::_('ESHOP_COMMENT'); ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="left">
						<?php echo JHtml::_('date', $this->orderInfor->created_date, 'm/d/Y'); ?>
					</td>
					<td class="left">
						<?php echo EshopHelper::getOrderStatusName($this->orderInfor->order_status_id, $tag); ?>
					</td>
					<td class="left">
						<?php echo $this->orderInfor->comment; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
	<div class="no_margin_left">
		<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-user-orderinfor" class="btn btn-primary pull-right">
	</div>
	<?php
}
?>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('#button-user-orderinfor').click(function(){
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=orders'); ?>';
				$(location).attr('href',url);
			});
		})
	})(jQuery);
</script>
