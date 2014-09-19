<?php
/**
 * @version		1.0.7
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

$language = JFactory::getLanguage();
$tag = $language->getTag();
if (!$tag)
	$tag = 'en-GB';
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_ADDRESS_HISTORY'); ?></h1>
<?php
if (!count($this->addresses))
{
	?>
	<div class="no-content"><?php echo JText::_('ESHOP_NO_ADDRESS'); ?></div>
	<?php
}
else
{
	?>
	<div class="row-fluid">
		<form id="adminForm" class="order-list">
			<?php
			foreach ($this->addresses as $address)
			{
				?>
				<div class="content">
					<table class="list">
						<tr>
							<td class="left" width="80%">
								<?php echo $address->firstname .' '. $address->lastname; ?>
								<?php
								if ($address->company != '')
								{
									?>
									<br><?php echo $address->company; ?>
									<?php
								}
								if ($address->company_id != '')
								{
									?>
									<br><?php echo $address->company_id; ?>
									<?php
								}
								?>
								<br><?php echo $address->address_1; ?>
								<?php
								if ($address->address_2 != '')
								{
									?>
									<br><?php echo $address->address_2; ?>
									<?php
								}
								?>
								<br><?php echo $address->country_name; ?>
								<br><?php echo $address->zone_name; ?>
							</td>
							<td class="right" width="20%">
								<input type="button" value="<?php echo JText::_('ESHOP_EDIT'); ?>" id="button-edit-address" class="btn btn-primary" onclick="window.location.assign('<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=address&aid='.$address->id); ?>');" />&nbsp; 
								<input type="button" value="<?php echo JText::_('ESHOP_DELETE'); ?>" id="<?php echo $address->id; ?>" class="button-delete-address btn btn-primary pull-right" />
							</td>
						</tr>
					</table>
				</div>	
				<?php
			}
			?>
		</form>
	</div>
	<?php
}
?>
<div class="row-fluid">
	<input type="button" value="<?php echo JText::_('ESHOP_BACK'); ?>" id="button-back-address" class="btn btn-primary pull-left" />
	<input type="button" value="<?php echo JText::_('ESHOP_ADD_ADDRESS'); ?>" id="button-new-address" class="btn btn-primary pull-right" />
</div>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('#button-back-address').click(function() {
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer')); ?>';
				$(location).attr('href', url);
			});

			$('#button-new-address').click(function() {
				var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=address'); ?>';
				$(location).attr('href', url);
			});

			//process user
			$('.button-delete-address').live('click', function() {
				var id = $(this).attr('id');
				$.ajax({
					url: 'index.php?option=com_eshop&task=customer.deleteAddress&aid=' + id,
					type: 'post',
					data: $("#adminForm").serialize(),
					dataType: 'json',
					success: function(json) {
							$('.warning, .error').remove();
							if (json['return']) {
								window.location.href = json['return'];
							} else {
							$('.error').remove();
							$('.warning, .error').remove();
							
						}	  
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});	
			});

		})
	})(jQuery);
</script>