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
<h1><?php echo sprintf(JText::_('ESHOP_ORDER_COMPLETED_TITLE'), $this->order_id); ?></h1>
<p><?php echo sprintf(JText::_('ESHOP_ORDER_COMPLETED_DESC'), $this->order_id); ?></p>