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

if (isset($this->warning))
{
	?>
	<div class="warning"><?php echo $this->warning; ?></div>
	<?php
}
if ($this->categoryId && isset($this->category))
{
	?>
	<h1><?php echo $this->category->category_name; ?></h1>
	<div class="row">
		<div class="span2">
			<img src="<?php echo $this->category->image; ?>" />
		</div>
		<div class="span7"><?php echo $this->category->category_desc; ?></div>
	</div>
	<hr />
	<?php
}
if (count($this->items)) {
	if ($this->params->get('show_page_heading'))
	{
		?>
		<h2><?php echo ($this->categoryId ? JText::_('ESHOP_SUB_CATEGORIES'): JText::_('ESHOP_CATEGORIES')); ?></h2>
		<?php
	}
	?>
	<ul class="thumbnails">
		<?php
		for ($i = 0; $n = count($this->items), $i < $n; $i++)
		{
			$item = $this->items[$i];
			?>
			<li>
                <a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($item->id)); ?>">
                    <img alt="<?php echo $item->category_name; ?>" src="<?php echo $item->image; ?>">
                </a>
                <div class="info_block">
                <h5>
                    <a href="<?php echo JRoute::_(EshopRoute::getCategoryRoute($item->id)); ?>">
                        <?php echo $item->category_name; ?>
                    </a>
              	</h5>
                </div>
        	</li>
			<?php
		}
		?>
        	</ul>
	<?php
	if ($this->pagination->total > $this->pagination->limit) {
		?>
		<div class="pagination">
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php
	}
}
?>