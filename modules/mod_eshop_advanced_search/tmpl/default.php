<?php
/**
 * @version		1.0.4
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
?>
<div class="eshop_advanced_search<?php echo $params->get( 'classname' ) ?> row-fluid">
	<?php
		if($params->get( 'show_manufacturer',1 ) == 1)
		{ 
	?>
	<div class="eshop-filter">
		<b><?php echo JText::_('ESHOP_FILTER_MANUFACTURER')?></b><br>
		<?php
			foreach ($manufacturers as $manufacturer)
			{
			?>
				<label class="checkbox">
					<input class="manufacturer" type="checkbox" name="manufacturer[]" id="manu_<?php echo $manufacturer->manufacturer_id?>"  onclick="var manufature_check = document.getElementById('manu_<?php echo $manufacturer->manufacturer_id?>'); if (this.checked) manufature_check.value =<?php echo $manufacturer->manufacturer_id; ?>; else manufature_check.value ='';" value="<?php echo $manufacturer->manufacturer_id; ?>">
					<?php echo $manufacturer->manufacturer_name; ?>
				</label>
			<?php
			}  
		?>
	</div>
	<?php
		} 
	?>
	<?php
		 if($params->get( 'show_price' ) == 1)
		 {
	?>
	<div class="eshop-filter">
		<b><?php echo JText::_('ESHOP_PRICE')?></b><br />
		<input type="text" value="" id="min_price" name="min_price" size="5" class="span6"><br />
		<b><?php echo JText::_('ESHOP_TO')?></b><br />
		<input type="text" value="" id="max_price" name="max_price" size="5" class="span6">
	</div>
	<?php
		 } 
	?>
	<div class="eshop-filter">
        <div class="input-prepend">
        	<span class="add-on"><i class="icon-search"></i></span>
            <input class="span8 inputbox product_advancedsearch" type="text" name="product_advancedsearch" id="prependedInput" value="" placeholder="<?php echo JText::_('ESHOP_FIND_A_PRODUCT'); ?>">
        </div>
	</div>
	
	<ul id="eshop_ajaxresult">
	
	</ul>
	<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root(); ?>">
	<input type="hidden" name="thumbwidth" id="thumbwidth" value="<?php echo $params->get('image_width'); ?>">
	<input type="hidden" name="thumbheight" id="thumbheight" value="<?php echo $params->get('image_height'); ?>">
	<input type="hidden" name="category_id" id="category_id" value="<?php echo implode( $params->get('category_id'), ','); ?>">
	<input type="hidden" name="number_product" id="number_product" value="<?php echo $params->get('number_product',5); ?>">
	<input type="hidden" name="description_max_chars" id="description_max_chars" value="<?php echo $params->get('description_max_chars',50); ?>">
	<input type="hidden" name="replacer" id="replacer" value="<?php echo $params->get('replacer','...'); ?>">
</div>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('#eshop_ajaxresult').hide();
		$('input.product_advancedsearch').val('');
		$('#eshop_ajaxresult').mouseleave(function() {
			$('#eshop_ajaxresult').slideUp('slow');
		});
		function search() {
			var manufacturer = $('input:checkbox:checked.manufacturer').map(function () {
				  return this.value;
				}).get();
			var query_value = $('input.product_advancedsearch').val();
			$('b#search-string').html(query_value);
			if(query_value !== ''){
				$('.product_advancedsearch').addClass('eshop-loadding');
				$.ajax({
					type: "POST",
					url: $('#live_site').val() + "index.php?option=com_eshop&view=search&format=raw",
					data: '&keyword=' + query_value + '&width=' + $('#thumbwidth').val() + '&height=' + $('#thumbheight').val() + '&category_id=' + $('#category_id').val() + '&number_product=' + $('#number_product').val() + '&min_price=' + $('#min_price').val() + '&max_price=' + $('#max_price').val() + '&manufacturers=' + manufacturer + '&description_max_chars=' + $('#description_max_chars').val() + '&replacer=' + $('#replacer').val(),
					cache: false,
					success: function(html){
						$("ul#eshop_ajaxresult").html(html);
						$('.product_advancedsearch').removeClass('eshop-loadding');
					}
				});
			}return false;    
		}
		
		$("input.product_advancedsearch").live("keyup", function(e) {
			//Set Timeout
			clearTimeout($.data(this, 'timer'));
			// Set Search String
			var search_string = $(this).val();
			// Do Search
			if (search_string == '') {
				$('.product_advancedsearch').removeClass('eshop-loadding');
				$("ul#eshop_ajaxresult").slideUp();
			}else{
				$("ul#eshop_ajaxresult").slideDown('slow');
				$(this).data('timer', setTimeout(search, 100));
			};
		});
			
	});
})(jQuery);
</script>

<style>
#eshop_ajaxresult
{
	 background-color:#ffffff;
	 width:<?php echo $params->get('width_result',270)?>px;
	 position:absolute;
}
</style>