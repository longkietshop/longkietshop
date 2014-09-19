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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form action="index.php?option=com_eshop&view=orders" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo JText::_( 'ESHOP_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->state->search; ?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_RESET' ); ?></button>		
			</td>
			<td align="right">
				<?php echo $this->lists['filter_state']; ?>
			</td>	
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%">
						<?php echo JText::_( 'ESHOP_NUM' ); ?>
					</th>
					<th width="2%">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th class="title" width="25%" style="text-align: left;">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_CUSTOMER'), 'a.firstname', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>	
					<th class="title" width="15%">
						<?php echo JText::_('ESHOP_ORDER_STATUS'); ?>
					</th>
					<th class="title" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ORDER_TOTAL'), 'a.total', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="title" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_CREATED_DATE'), 'a.created_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="title" width="10%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_MODIFIED_DATE'), 'a.modified_date', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="5%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_ID'), 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="20%">
						<?php echo JText::_('ESHOP_ACTION'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				$link 	= JRoute::_('index.php?option=com_eshop&task=order.edit&cid[]='. $row->id);
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="text_center">
						<?php echo $this->pagination->getRowOffset( $i ); ?>
					</td>
					<td class="text_center">
						<?php echo $checked; ?>
					</td>
					<td class="text_left">
						<?php echo $row->firstname . ' ' . $row->lastname; ?>
					</td>
					<td class="text_center">
						<?php echo EshopHelper::getOrderStatusName($row->order_status_id, JComponentHelper::getParams('com_languages')->get('site', 'en-GB')); ?>
					</td>
					<td class="text_center">
						<?php echo $this->currency->format($row->total, $row->currency_code, $row->currency_exchanged_value); ?>
					</td>
					<td class="text_center">
						<?php
						if ($row->created_date != $this->nullDate)
							echo JHtml::_('date', $row->created_date,'m-d-Y');
						?>
					</td>
					<td class="text_center">
						<?php
						if ($row->modified_date != $this->nullDate)
							echo JHtml::_('date', $row->modified_date,'m-d-Y');
						?>
					</td>
					<td class="text_center">
						<?php echo $row->id; ?>
					</td>
					<td class="text_center">
						<a href="<?php echo $link; ?>"><?php echo JText::_('ESHOP_EDIT'); ?></a>&nbsp;|&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_eshop&task=order.downloadInvoice&cid[]='. $row->id); ?>"><?php echo JText::_('ESHOP_DOWNLOAD_INVOICE'); ?></a>
					</td>
				</tr>		
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>			
</form>