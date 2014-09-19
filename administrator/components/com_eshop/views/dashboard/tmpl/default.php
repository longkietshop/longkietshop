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
JToolBarHelper::title(JText::_('ESHOP_DASHBOARD'), 'generic.png');
?>
<table>
	<tr>
		<td>
			<div id="cpanel">
				<?php
				$this->quickiconButton('index.php?option=com_eshop&view=categories', 'icon-48-categories.png', JText::_('ESHOP_CATEGORIES'));
				$this->quickiconButton('index.php?option=com_eshop&view=products', 'icon-48-products.png', JText::_('ESHOP_PRODUCTS'));
				$this->quickiconButton('index.php?option=com_eshop&view=attributes', 'icon-48-attributes.png', JText::_('ESHOP_ATTRIBUTES'));
				$this->quickiconButton('index.php?option=com_eshop&view=attributegroups', 'icon-48-attributegroups.png', JText::_('ESHOP_ATTRIBUTEGROUPS'));
				$this->quickiconButton('index.php?option=com_eshop&view=options', 'icon-48-options.png', JText::_('ESHOP_OPTIONS'));
				$this->quickiconButton('index.php?option=com_eshop&view=manufacturers', 'icon-48-manufacturers.png', JText::_('ESHOP_MANUFACTURERS'));
				$this->quickiconButton('index.php?option=com_eshop&view=reviews', 'icon-48-reviews.png', JText::_('ESHOP_REVIEWS'));
				$this->quickiconButton('index.php?option=com_eshop&view=payments', 'icon-48-payments.png', JText::_('ESHOP_PAYMENTS'));
				$this->quickiconButton('index.php?option=com_eshop&view=shippings', 'icon-48-shippings.png', JText::_('ESHOP_SHIPPINGS'));
				$this->quickiconButton('index.php?option=com_eshop&view=themes', 'icon-48-themes.png', JText::_('ESHOP_THEMES'));
				$this->quickiconButton('index.php?option=com_eshop&view=orders', 'icon-48-orders.png', JText::_('ESHOP_ORDERS'));
				$this->quickiconButton('index.php?option=com_eshop&view=customers', 'icon-48-customers.png', JText::_('ESHOP_CUSTOMERS'));
				$this->quickiconButton('index.php?option=com_eshop&view=customergroups', 'icon-48-customergroups.png', JText::_('ESHOP_CUSTOMERGROUPS'));
				$this->quickiconButton('index.php?option=com_eshop&view=coupons', 'icon-48-coupons.png', JText::_('ESHOP_COUPONS'));
				$this->quickiconButton('index.php?option=com_eshop&view=configuration', 'icon-48-configuration.png', JText::_('ESHOP_CONFIGURATION'));
				$this->quickiconButton('index.php?option=com_eshop&view=countries', 'icon-48-countries.png', JText::_('ESHOP_COUNTRIES'));
				$this->quickiconButton('index.php?option=com_eshop&view=stockstatuses', 'icon-48-stockstatuses.png', JText::_('ESHOP_STOCKSTATUSES'));
				$this->quickiconButton('index.php?option=com_eshop&view=orderstatuses', 'icon-48-orderstatuses.png', JText::_('ESHOP_ORDERSTATUSES'));
				$this->quickiconButton('index.php?option=com_eshop&view=lengths', 'icon-48-lengths.png', JText::_('ESHOP_LENGTHS'));
				$this->quickiconButton('index.php?option=com_eshop&view=weights', 'icon-48-weights.png', JText::_('ESHOP_WEIGHTS'));
				$this->quickiconButton('index.php?option=com_eshop&view=currencies', 'icon-48-currencies.png', JText::_('ESHOP_CURRENCIES'));
				$this->quickiconButton('index.php?option=com_eshop&view=zones', 'icon-48-zones.png', JText::_('ESHOP_ZONES'));
				$this->quickiconButton('index.php?option=com_eshop&view=geozones', 'icon-48-geozones.png', JText::_('ESHOP_GEOZONES'));
				$this->quickiconButton('index.php?option=com_eshop&view=taxclasses', 'icon-48-taxclasses.png', JText::_('ESHOP_TAXCLASSES'));
				$this->quickiconButton('index.php?option=com_eshop&view=taxrates', 'icon-48-taxrates.png', JText::_('ESHOP_TAXRATES'));
				$this->quickiconButton('index.php?option=com_eshop&view=messages&layout=messages', 'icon-48-messages.png', JText::_('ESHOP_MESSAGES'));
				$this->quickiconButton('index.php?option=com_eshop&view=language', 'icon-48-translation.png', JText::_('ESHOP_TRANSLATION'));
				$this->quickiconButton('index.php?option=com_eshop&view=reports&layout=orders', 'icon-48-reports.png', JText::_('ESHOP_REPORTS'));
				$this->quickiconButton('index.php?option=com_eshop&view=exports', 'icon-48-exports.png', JText::_('ESHOP_EXPORTS'));
				?>
			</div>
		</td>
		<td valign="top" width="40%" style="padding: 0 0 0 5px">
			<?php
			if (version_compare(JVERSION, '3.0', 'le'))
			{
				echo $this->pane->startPane('statistics_pane');
				
				echo $this->pane->startPanel(JText::_('ESHOP_OVERVIEW'), 'overview');
				echo $this->loadTemplate('overview');
				echo $this->pane->endPanel();
				
				echo $this->pane->startPanel(JText::_('ESHOP_TOP_SALES'), 'top_sales');
				echo $this->loadTemplate('top_sales');
				echo $this->pane->endPanel();
				
				echo $this->pane->startPanel(JText::_('ESHOP_TOP_HITS'), 'top_hits');
				echo $this->loadTemplate('top_hits');
				echo $this->pane->endPanel();
				
				echo $this->pane->startPanel(JText::_('ESHOP_TOP_RATES'), 'top_rates');
				echo $this->loadTemplate('top_rates');
				echo $this->pane->endPanel();
				
				echo $this->pane->startPanel(JText::_('ESHOP_TOP_REVIEWS'), 'top_reviews');
				echo $this->loadTemplate('top_reviews');
				echo $this->pane->endPanel();
				
				echo $this->pane->startPanel(JText::_('ESHOP_CREDITS'), 'credits');
				echo $this->loadTemplate('credits');
				echo $this->pane->endPanel();
				
				echo $this->pane->endPane();
			}
			else 
			{
				echo JHtml::_('sliders.start', 'statistics_pane');
				
				echo JHtml::_('sliders.panel', JText::_('ESHOP_OVERVIEW'), 'overview');
				echo $this->loadTemplate('overview');
				
				echo JHtml::_('sliders.panel', JText::_('ESHOP_TOP_SALES'), 'top_sales');
				echo $this->loadTemplate('top_sales');
				
				echo JHtml::_('sliders.panel', JText::_('ESHOP_TOP_HITS'), 'top_hits');
				echo $this->loadTemplate('top_hits');
				
				echo JHtml::_('sliders.panel', JText::_('ESHOP_TOP_RATES'), 'top_rates');
				echo $this->loadTemplate('top_rates');
				
				echo JHtml::_('sliders.panel', JText::_('ESHOP_TOP_REVIEWS'), 'top_reviews');
				echo $this->loadTemplate('top_reviews');
				
				echo JHtml::_('sliders.panel', JText::_('ESHOP_CREDITS'), 'credits');
				echo $this->loadTemplate('credits');
				
				echo JHtml::_('sliders.end');
			}
			?>
		</td>
	</tr>
</table>