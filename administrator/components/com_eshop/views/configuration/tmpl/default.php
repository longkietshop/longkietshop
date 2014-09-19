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
JToolBarHelper::title(JText::_('ESHOP_CONFIGURATION'), 'generic.png');
JToolBarHelper::apply('configuration.save');
JToolBarHelper::cancel('configuration.cancel');
$editor = JFactory::getEditor();
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'configuration.cancel') {
			Joomla.submitform(pressbutton, form);
			return;
		} else {
			//Validate the entered data before submitting
			if (form.store_name.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_STORE_NAME'); ?>");
				form.store_name.focus();
				return;
			}
			if (form.store_owner.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_STORE_OWNER'); ?>");
				form.store_owner.focus();
				return;
			}
			if (form.address.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_ADDRESS'); ?>");
				form.address.focus();
				return;
			}
			if (form.email.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_EMAIL'); ?>");
				form.email.focus();
				return;
			}
			if (form.telephone.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_TELEPHONE'); ?>");
				form.telephone.focus();
				return;
			}
			if (form.catalog_limit.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_DEFAULT_ITEMS_PER_PAGE'); ?>");
				form.catalog_limit.focus();
				return;
			}
			if (form.image_category_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CATEGORY_IMAGE_WIDTH'); ?>");
				form.image_category_width.focus();
				return;
			}
			if (form.image_category_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CATEGORY_IMAGE_HEIGHT'); ?>");
				form.image_category_height.focus();
				return;
			}
			if (form.image_thumb_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_THUMB_WIDTH'); ?>");
				form.image_thumb_width.focus();
				return;
			}
			if (form.image_thumb_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_THUMB_HEIGHT'); ?>");
				form.image_thumb_height.focus();
				return;
			}
			if (form.image_popup_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_POPUP_WIDTH'); ?>");
				form.image_popup_width.focus();
				return;
			}
			if (form.image_popup_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_POPUP_HEIGHT'); ?>");
				form.image_popup_height.focus();
				return;
			}
			if (form.image_list_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_LIST_WIDTH'); ?>");
				form.image_list_width.focus();
				return;
			}
			if (form.image_list_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_PRODUCT_IMAGE_LIST_HEIGHT'); ?>");
				form.image_list_height.focus();
				return;
			}
			if (form.image_additional_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_ADDITIONAL_PRODUCT_IMAGE_WIDTH'); ?>");
				form.image_additional_width.focus();
				return;
			}
			if (form.image_additional_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_ADDITIONAL_PRODUCT_IMAGE_HEIGHT'); ?>");
				form.image_additional_height.focus();
				return;
			}
			if (form.image_related_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_RELATED_PRODUCT_IMAGE_WIDTH'); ?>");
				form.image_related_width.focus();
				return;
			}
			if (form.image_related_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_RELATED_PRODUCT_IMAGE_HEIGHT'); ?>");
				form.image_related_height.focus();
				return;
			}
			if (form.image_compare_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_COMPARE_IMAGE_WIDTH'); ?>");
				form.image_compare_width.focus();
				return;
			}
			if (form.image_compare_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_COMPARE_IMAGE_HEIGHT'); ?>");
				form.image_compare_height.focus();
				return;
			}
			if (form.image_wishlist_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_WISH_LIST_IMAGE_WIDTH'); ?>");
				form.image_wishlist_width.focus();
				return;
			}
			if (form.image_wishlist_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_WISH_LIST_IMAGE_HEIGHT'); ?>");
				form.image_wishlist_height.focus();
				return;
			}
			if (form.image_cart_width.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CART_IMAGE_WIDTH'); ?>");
				form.image_cart_width.focus();
				return;
			}
			if (form.image_cart_height.value == '') {
				alert("<?php echo JText::_('ESHOP_CONFIG_ENTER_CART_IMAGE_HEIGHT'); ?>");
				form.image_cart_height.focus();
				return;
			}
			Joomla.submitform(pressbutton, form);
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_GENERAL'); ?></a></li>
			<li><a href="#local-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_LOCAL'); ?></a></li>
			<li><a href="#option-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_OPTION'); ?></a></li>
			<li><a href="#image-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_IMAGE'); ?></a></li>
			<li><a href="#layout-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_LAYOUT'); ?></a></li>
			<li><a href="#order-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_SORTING'); ?></a></li>
			<li><a href="#social-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_SOCIAL'); ?></a></li>
			<li><a href="#mail-page" data-toggle="tab"><?php echo JText::_('ESHOP_CONFIG_MAIL'); ?></a></li>
		</ul>	
		<div class="tab-content">			
			<div class="tab-pane active" id="general-page">
				<div class="span10">
					<?php echo $this->loadTemplate('general'); ?>
				</div>
			</div>
			<div class="tab-pane" id="local-page">
				<div class="span10">
					<?php echo $this->loadTemplate('local'); ?>
				</div>	
			</div>
			<div class="tab-pane" id="option-page">
				<div class="span10">
					<?php echo $this->loadTemplate('option'); ?>
				</div>	
			</div>
			<div class="tab-pane" id="image-page">
				<div class="span10">
					<?php echo $this->loadTemplate('image'); ?>
				</div>
			</div>
			<div class="tab-pane" id="layout-page">
				<div class="span10">
					<?php echo $this->loadTemplate('layout'); ?>
				</div>
			</div>
			<div class="tab-pane" id="order-page">
				<div class="span12">
					<?php echo $this->loadTemplate('sorting'); ?>
				</div>
			</div>
			<div class="tab-pane" id="social-page">
				<div class="span10">
					<?php echo $this->loadTemplate('social'); ?>
				</div>
			</div>
			<div class="tab-pane" id="mail-page">
				<div class="span10">
					<?php echo $this->loadTemplate('mail'); ?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<div class="clearfix"></div>
</form>