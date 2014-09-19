<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();
$ordering = ($this->lists['order'] == 'a.ordering');

?>
<form action="index.php?option=com_eshop&view=payments&type=0" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
	<table width="100%">
		<tr>
			<td align="left">
				<?php echo JText::_( 'ESHOP_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->state->search;?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_RESET' ); ?></button>		
			</td>	
			<td style="text-align: right;">		
				<?php echo $this->lists['filter_state']; ?>
			</td>
		</tr>
	</table>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="2%">
						<?php echo JText::_( 'NUM' ); ?>
					</th>
					<th width="2%">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th class="title" style="text-align: left;" widht="15%">
						<?php echo JHtml::_('grid.sort',  JText::_('ESHOP_NAME'), 'a.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="title" style="text-align: left;" width="15%">
						<?php echo JHtml::_('grid.sort', JText::_('ESHOP_TITLE'), 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="title" style="text-align: left;" width="15%">
						<?php echo JHtml::_('grid.sort', JText::_('ESHOP_AUTHOR') , 'a.author', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="title" style="text-align: left;" width="15%">
						<?php echo JHtml::_('grid.sort', JText::_('ESHOP_AUTHOR_EMAIL'), 'a.email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="title" style="text-align: center;" width="10%">
						<?php echo JHtml::_('grid.sort', JText::_('ESHOP_PUBLISHED') , 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="15%" nowrap="nowrap">
						<?php echo JHtml::_('grid.sort',  'ESHOP_ORDER', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
						<?php echo JHtml::_('grid.order',  $this->items , 'filesave.png', 'save_payment_order' ); ?>
					</th>												
					<th class="title" style="text-align: center;" width="5%">
						<?php echo JHtml::_('grid.sort', JText::_('ESHOP_ID') , 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
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
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=payment.edit&cid[]='. $row->id );
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$published 	= JHtml::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'payment.' );
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset( $i ); ?>
					</td>
					<td>
						<?php echo $checked; ?>
					</td>	
					<td>
						<a href="<?php echo $link; ?>">
							<?php echo $row->name; ?>
						</a>
					</td>
					<td>
						<?php echo $row->title; ?>
					</td>												
					<td>
						<?php echo $row->author; ?>
					</td>
					<td align="center">
						<?php echo $row->author_email;?>
					</td>
					<td style="text-align: center;">
						<?php echo $published; ?>
					</td>			
					<td class="order">
						<span><?php echo $this->pagination->orderUpIcon( $i, true,'payment.orderup', 'Move Up', $ordering ); ?></span>
						<span><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'payment.orderdown', 'Move Down', $ordering ); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="input-mini" style="text-align: center" />
					</td>			
					<td style="text-align: center;">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
		<table class="adminform" style="margin-top: 20px;">
			<tr>
				<td>
					<fieldset class="adminform">
						<legend><?php echo JText::_('ESHOP_INSTALL_NEW_PAYMENT_PLUGIN'); ?></legend>
						<table>
							<tr>
								<td>
									<input type="file" name="plugin_package" id="plugin_package" size="57" class="input_box" />
									<input type="button" class="btn btn-primary" value="<?php echo JText::_('ESHOP_INSTALL'); ?>" onclick="installPlugin();" />
								</td>
							</tr>
						</table>					
					</fieldset>
				</td>
			</tr>		
		</table>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />	
	<?php echo JHtml::_( 'form.token' ); ?>				 
	<script type="text/javascript">
		function installPlugin() {
			var form = document.adminForm;
			if (form.plugin_package.value =="") {
				alert("<?php echo JText::_('ESHOP_CHOOSE_PAYMENT_PLUGIN'); ?>");
				return;	
			}
			
			form.task.value = 'payment.install';
			form.submit();
		}
	</script>
</form>