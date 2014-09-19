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
?>
<div class="caption">
    <h4><?php echo JText::_('ESHOP_REFINE_SEARCH'); ?></h4>
    <ul>
		<?php 
		foreach ($this->subCategories as $subCategory) {
			?>
			<li>
				<h5>
					<a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($subCategory->id)); ?>">
						<?php echo $subCategory->category_name; ?>
					</a>
				</h5>
			</li>
			<?php
		}
		?> 
    </ul>
</div>
<hr />