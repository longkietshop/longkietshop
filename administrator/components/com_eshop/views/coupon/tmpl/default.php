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
EshopHelper::chosen(); 
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'coupon.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting
			if (form.coupon_name.value == '') {
				alert("<?php echo JText::_('ESHOP_ENTER_NAME'); ?>");
				form.coupon_name.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_GENERAL'); ?></a></li>
			<li><a href="#history-page" data-toggle="tab"><?php echo JText::_('ESHOP_COUPON_HISTORY'); ?></a></li>
		</ul>
		<div class="tab-content" style="overflow: visible !important">
			<div class="tab-pane active" id="general-page">
				<div class="span8">
					<table class="admintable adminform" style="width: 100%;">
						<tr>
							<td class="key">
								<span class="required">*</span>
								<?php echo  JText::_('ESHOP_COUPON_NAME'); ?>
							</td>
							<td>
								<input class="input-xlarge" type="text" name="coupon_name" id="coupon_name" maxlength="250" value="<?php echo $this->item->coupon_name; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_CODE'); ?>
							</td>
							<td>
								<input class="input-large" type="text" name="coupon_code" id="coupon_code" maxlength="250" value="<?php echo $this->item->coupon_code; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_TYPE'); ?>
							</td>
							<td>
								<?php echo $this->lists['coupon_type']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_VALUE'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="coupon_value" id="coupon_value" maxlength="250" value="<?php echo number_format($this->item->coupon_value, 2); ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_MIN_TOTAL'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="coupon_min_total" id="coupon_min_total" maxlength="250" value="<?php echo number_format($this->item->coupon_min_total, 2); ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_SELECT_PRODUCTS'); ?>
							</td>
							<td>
								<?php echo $this->lists['product_id']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_START_DATE'); ?>
							</td>
							<td>
								<?php echo JHtml::_('calendar', (($this->item->coupon_start_date == $this->nullDate) ||  !$this->item->coupon_start_date) ? '' : JHtml::_('date', $this->item->coupon_start_date, 'Y-m-d', null), 'coupon_start_date', 'coupon_start_date', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_END_DATE'); ?>
							</td>
							<td>
								<?php echo JHtml::_('calendar', (($this->item->coupon_end_date == $this->nullDate) ||  !$this->item->coupon_end_date) ? '' : JHtml::_('date', $this->item->coupon_end_date, 'Y-m-d', null), 'coupon_end_date', 'coupon_end_date', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_COUPON_SHIPPING'); ?>
							</td>
							<td>
								<?php echo $this->lists['coupon_shipping']; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_COUPON_TIME'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="coupon_times" id="coupon_times" maxlength="250" value="<?php echo $this->item->coupon_times; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo  JText::_('ESHOP_COUPON_USED'); ?>
							</td>
							<td>
								<input class="input-small" type="text" name="coupon_used" id="coupon_used" maxlength="250" value="<?php echo $this->item->coupon_used; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('ESHOP_PUBLISHED'); ?>
							</td>
							<td>
								<?php echo $this->lists['published']; ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="history-page">
				<div class="span6">
					<table class="adminlist" style="text-align: center;">
						<thead>
							<tr>
								<th class="title" width="10%"><?php echo JText::_('ESHOP_ORDER_ID')?></th>
								<th class="title" width="30%"><?php echo JText::_('ESHOP_AMOUNT')?></th>
								<th class="title" width="20%"><?php echo JText::_('ESHOP_CREATED_DATE')?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$couponHistories = $this->couponHistories;
							if (count($couponHistories) == 0) {
								?>
								<tr>
									<td colspan="3" style="text-align: center;">
										<?php echo JText::_('ESHOP_NO_RESULTS'); ?>
									</td>
								</tr>
								<?php
							} else {
								for ($i = 0; $i< count($couponHistories); $i++){
									$couponHistory = $couponHistories[$i];
									?>
									<tr>
										<td>
											<?php echo $couponHistory->order_id ?>
										</td>
										<td>
											<?php echo $couponHistory->amount ?>
										</td>
										<td>
											<?php echo JHtml::_('date',$couponHistory->created_date,'m-d-Y'); ?>
										</td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />	
</form>