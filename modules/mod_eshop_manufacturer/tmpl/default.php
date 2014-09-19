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
<div class="eshop_manufacturer<?php echo $params->get('moduleclass_sfx' ); ?>">
	<ul id="mycarousel" class="jcarousel-skin-tango">
		<?php
		foreach ($items as $item)
		{ 
			$viewManufacturerUrl = JRoute::_(EshopRoute::getManufacturerRoute($item->id));
			?>
			<li>
				<a href="<?php echo $viewManufacturerUrl; ?>" title="<?php echo $item->manufacturer_name; ?>">
					<img class="img-polaroid" src="<?php echo $item->image; ?>" alt="<?php echo $item->manufacturer_name; ?>" />
				</a>
			</li>
			<?php
		}
		?>
	</ul>
</div>
<script type="text/javascript">
	(function($){
		$(document).ready(function(){
		    jQuery('#mycarousel').jcarousel({
		        start: 3
			});
		});
	})(jQuery);
</script>
<style>
	.jcarousel-skin-tango .jcarousel-container-horizontal {
	    width: <?php echo $slideWidth; ?>px;
	    padding: 20px 40px;
	}
	.jcarousel-skin-tango .jcarousel-clip-horizontal {
	   width: <?php echo $slideWidth; ?>px;
	   height: 90px;
	}
</style>