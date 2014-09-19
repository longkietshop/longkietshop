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
defined('_JEXEC') or die();

/**
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewDashboard extends JViewLegacy
{

	function display($tpl = null)
	{
		if (version_compare(JVERSION, '3.0', 'le'))
		{
			jimport('joomla.html.pane');
			$pane = JPane::getInstance('sliders', array('allowAllClose' => 1));
			$this->pane = $pane;
		}
		$overviewData = $this->get('OverviewData');
		$topSalesData = $this->get('TopSalesData');
		$topHitsData = $this->get('TopHitsData');
		$topRatesData = $this->get('TopRatesData');
		$topReviewsData = $this->get('TopReviewsData');
		$this->overviewData = $overviewData;
		$this->topSalesData = $topSalesData;
		$this->topHitsData = $topHitsData;
		$this->topRatesData = $topRatesData;
		$this->topReviewsData = $topReviewsData;
		parent::display($tpl);
	}
	
	/**
	 * 
	 * Function to create the buttons view.
	 * @param string $link targeturl
	 * @param string $image path to image
	 * @param string $text image description
	 */
	function quickiconButton($link, $image, $text)
	{
		$language = JFactory::getLanguage();
		?>
		<div style="float:<?php echo ($language->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>" title="<?php echo $text; ?>">
					<?php echo JHtml::_('image', 'administrator/components/com_eshop/assets/icons/' . $image, $text); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
}