<?php
/**
 * @version		1.0.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
if (count($this->categories)) 
{
	?>
	<h2><?php echo JText::_('ESHOP_CATEGORIES'); ?></h2>
	<ul class="thumbnails">
		<?php 
		foreach ($this->categories as $category) {
			?>
			<li>
                <a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($category->id)); ?>">
                    <img alt="<?php echo $category->category_name; ?>" src="<?php echo $category->image; ?>" />	            
                </a>
                <div class="info_block">
                <h5>
                <a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($category->id)); ?>">
                    <?php echo $category->category_name;?>
                </a>
                </h5>
                </div>
			</li>
			<?php
		}
		?>       
	</ul>
	<hr />	
	<?php
}
?>