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
if (isset($this->success))
{
	?>
	<div class="success"><?php echo $this->success; ?></div>
	<?php
}
?>
<h1><?php echo JText::_('ESHOP_MY_ACCOUNT'); ?></h1>
<div class="row-fluid">
	<ul>
		<li>
			<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=account'); ?>">
				<?php echo JText::_('ESHOP_EDIT_ACCOUNT'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=orders'); ?>">
				<?php echo JText::_('ESHOP_ORDER_HISTORY'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo JRoute::_(EshopRoute::getViewRoute('customer').'&layout=addresses'); ?>">
				<?php echo JText::_('ESHOP_MODIFY_ADDRESS'); ?>
			</a>
		</li>
	</ul>
</div>