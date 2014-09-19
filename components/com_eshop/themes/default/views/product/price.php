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
$uri = JURI::getInstance();
?>
<h2>
	<strong>
		<?php echo JText::_('ESHOP_PRICE'); ?>:
		<?php
		$productPriceArray = EshopHelper::getProductPriceArray($this->item->id, $this->item->product_price);
		if ($productPriceArray['salePrice'])
		{
			?>
			<span class="base-price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['basePrice'], $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>&nbsp;
			<span class="sale-price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['salePrice'] + $this->option_price, $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
			<?php
		}
		else
		{
			?>
			<span class="price"><?php echo $this->currency->format($this->tax->calculate($productPriceArray['basePrice'], $this->item->product_taxclass_id, EshopHelper::getConfigValue('tax'))); ?></span>
			<?php
		}
		?>
	</strong><br />
	<?php
	if (EshopHelper::getConfigValue('tax'))
	{
		?>
		<small>
			<?php echo JText::_('ESHOP_EX_TAX'); ?>:
		<?php
		if ($productPriceArray['salePrice'])
		{
			echo $this->currency->format($productPriceArray['salePrice']);
		}
		else
		{
			echo $this->currency->format($productPriceArray['basePrice']);
		}
		?>
		</small>
		<?php
	}
	?>
</h2>