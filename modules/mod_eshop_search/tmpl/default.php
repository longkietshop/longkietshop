<?php
/**
 * @version		1.0.4
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
	<div class="eshop-search<?php echo $params->get( 'moduleclass_sfx' ) ?>">
        <div class="input-prepend">
            <span class="add-on"><i class="icon-search"></i></span>
            <input class="inputbox product_search" type="text" name="product_advancedsearch" id="prependedInput" value="" placeholder="<?php echo JText::_('ESHOP_FIND_A_PRODUCT'); ;?>">
        </div>
		<ul id="eshop_result">
		
		</ul>
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root(); ?>">
		<input type="hidden" name="thumbwidth" id="thumbwidth" value="<?php echo $params->get('image_width')?>">
		<input type="hidden" name="thumbheight" id="thumbheight" value="<?php echo $params->get('image_height')?>">
		<input type="hidden" name="category_id" id="category_id" value="<?php echo $params->get('category_id') ? implode( $params->get('category_id'), ',') : ''?>">
		<input type="hidden" name="number_product" id="number_product" value="<?php echo $params->get('number_product',5)?>">
		<input type="hidden" name="description_max_chars" id="description_max_chars" value="<?php echo $params->get('description_max_chars',50); ?>">
		<input type="hidden" name="replacer" id="replacer" value="<?php echo $params->get('replacer','...'); ?>">
		
	</div>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('#eshop_result').hide();
		$('input.product_search').val('');
		$('#eshop_result').mouseleave(function() {
			$('#eshop_result').slideUp('slow');
		});
		function search() {
			var query_value = $('input.product_search').val();
			$('b#search-string').html(query_value);
			if(query_value !== ''){
				$('.product_search').addClass('eshop-loadding');
				$.ajax({
					type: "POST",
					url: $('#live_site').val() + "index.php?option=com_eshop&view=search&format=raw",
					data: '&keyword=' + query_value + '&width=' + $('#thumbwidth').val() + '&height=' + $('#thumbheight').val() + '&category_id=' + $('#category_id').val() + '&number_product=' + $('#number_product').val() + '&description_max_chars=' + $('#description_max_chars').val() + '&replacer=' + $('#replacer').val(),
					cache: false,
					success: function(html){
						$("ul#eshop_result").html(html);
						$('.product_search').removeClass('eshop-loadding');
					}
				});
			}return false;    
		}
		
		$("input.product_search").live("keyup", function(e) {
			//Set Timeout
			clearTimeout($.data(this, 'timer'));
			// Set Search String
			var search_string = $(this).val();
			// Do Search
			if (search_string == '') {
				$('.product_search').removeClass('eshop-loadding');
				$("ul#eshop_result").slideUp();
			}else{
				$("ul#eshop_result").slideDown('slow');
				$(this).data('timer', setTimeout(search, 100));
			};
		});
			
	});
})(jQuery);
</script>

<style>
#eshop_result
{
	 background-color:#ffffff;
	 width:<?php echo $params->get('width_result',270)?>px;
	 position:absolute;
	 z-index:9999;
}
</style>