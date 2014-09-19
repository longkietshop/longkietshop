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
JToolBarHelper::title(JText::_('ESHOP_ORDERS_REPORT'), 'generic.png');
JToolBarHelper::custom('exports.process', 'export', 'export', Jtext::_('ESHOP_EXPORTS'), false);
JToolBarHelper::custom('order.downloadInvoice', 'print', 'print', JText::_('ESHOP_DOWNLOAD_INVOICE'), false);
JToolBarHelper::cancel('reports.cancel');
?>
<form action="index.php?option=com_eshop&view=reports&layout=orders" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo JText::_('ESHOP_START_DATE')?>:&nbsp;
				<?php echo JHtml::_('calendar', JRequest::getVar('date_start', date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'))), 'date_start', 'date_start', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('ESHOP_END_DATE')?>:&nbsp;
				<?php echo JHtml::_('calendar', JRequest::getVar('date_end', date('Y-m-d')), 'date_end', 'date_end', '%Y-%m-%d', array('style' => 'width: 100px;')); ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('ESHOP_GROUP_BY'); ?>:&nbsp;
				<?php echo $this->lists['group_by']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('ESHOP_ORDERSTATUS'); ?>:&nbsp;
				<?php echo $this->lists['order_status_id']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
			</td>
		</tr>
	</table>
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="20%" class="text_left"><?php echo JText::_('ESHOP_START_DATE'); ?></th>
				<th width="20%" class="text_left"><?php echo JText::_('ESHOP_END_DATE'); ?></th>
				<th width="15%" class="text_right"><?php echo JText::_('ESHOP_NUMBER_ORDERS'); ?></th>
				<th width="15%" class="text_right"><?php echo JText::_('ESHOP_NUMBER_PRODUCTS'); ?></th>
				<th width="15%" class="text_right"><?php echo JText::_('ESHOP_TAX'); ?></th>
				<th width="15%" class="text_right"><?php echo JText::_('ESHOP_TOTAL'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_left">
						<?php echo JHtml::_('date', $row->date_start, 'm-d-Y'); ?>
					</td>																			
					<td class="text_left">
						<?php echo JHtml::_('date', $row->date_end, 'm-d-Y'); ?>
					</td>
					<td class="text_right">
						<?php echo $row->orders; ?>
					</td>
					<td class="text_right">
						<?php echo $row->products; ?>
					</td>
					<td class="text_right">
						<?php echo $this->currency->format($row->tax ? $row->tax : 0, EshopHelper::getConfigValue('default_currency_code')); ?>
					</td>
					<td class="text_right">
						<?php echo $this->currency->format($row->total ? $row->total : 0, EshopHelper::getConfigValue('default_currency_code')); ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
		</tbody>
	</table>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="export_type" value="orders" />
	<input type="hidden" name="from_exports" value="1" />
</form>
