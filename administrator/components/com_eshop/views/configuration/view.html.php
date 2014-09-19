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
class EShopViewConfiguration extends JViewLegacy
{

	function display($tpl = null)
	{
		$config = $this->get('Data');
		$db = JFactory::getDbo();
		
		//Country list
		$query = $db->getQuery(true);
		$query->select('id, country_name AS name')
			->from('#__eshop_countries')
			->where('published = 1');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'id', 'name');
		$options = array_merge($options, $db->loadObjectList());
		$lists['country_id'] = JHtml::_('select.genericlist', $options, 'country_id', ' class="inputbox" onchange="Eshop.updateStateList(this.value, \'zone_id\')" ', 'id', 'name', $config->country_id);
		
		//Zone list
		$query->clear();
		$query->select('id, zone_name')
			->from('#__eshop_zones')
			->where('country_id = ' . (int) $config->country_id)
			->where('published = 1');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'id', 'zone_name');
		$options = array_merge($options, $db->loadObjectList());
		$lists['zone_id'] = JHtml::_('select.genericlist', $options, 'zone_id', ' class="inputbox" ', 'id', 'zone_name', $config->zone_id);
		
		//Currencies list
		$query->clear();
		$query->select('currency_code, currency_name')
			->from('#__eshop_currencies')
			->where('published = 1');
		$db->setQuery($query);
		$lists['default_currency_code'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'default_currency_code', ' class="inputbox" ', 'currency_code', 'currency_name', $config->default_currency_code);
		
		//Lengths list
		$query->clear();
		$query->select('a.id, b.length_name')
			->from('#__eshop_lengths AS a')
			->innerJoin('#__eshop_lengthdetails AS b ON (a.id = b.length_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$lists['length_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'length_id', ' class="inputbox" ', 'id', 'length_name', $config->length_id);
		
		//Weights list
		$query->clear();
		$query->select('a.id, b.weight_name')
			->from('#__eshop_weights AS a')
			->innerJoin('#__eshop_weightdetails AS b ON (a.id = b.weight_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$lists['weight_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'weight_id', ' class="inputbox" ', 'id', 'weight_name', $config->weight_id);
		
		//Customer group list
		$query->clear();
		$query->select('a.id, b.customergroup_name AS name')
			->from('#__eshop_customergroups AS a')
			->innerJoin('#__eshop_customergroupdetails AS b ON (a.id = b.customergroup_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
			->order('b.customergroup_name');
		$db->setQuery($query);
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'id', 'name');
		$options = array_merge($options, $db->loadObjectList());
		$lists['customergroup_id'] = JHtml::_('select.genericlist', $options, 'customergroup_id', ' class="inputbox" ', 'id', 'name', $config->customergroup_id);
		
		//Customer group display list
		$customerGroupDisplay = explode(',', $config->customer_group_display);
		$lists['customer_group_display'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'customer_group_display[]', ' class="inputbox chosen" multiple ', 'id', 'name', $customerGroupDisplay);
		
		//Stock status list
		$query->clear();
		$query->select('a.id, b.stockstatus_name')
			->from('#__eshop_stockstatuses AS a')
			->innerJoin('#__eshop_stockstatusdetails AS b ON (a.id = b.stockstatus_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$lists['stock_status_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'stock_status_id', ' class="inputbox" ', 'id', 'stockstatus_name', $config->stock_status_id);
		
		//Order status and complete status list
		$query->clear();
		$query->select('a.id, b.orderstatus_name')
			->from('#__eshop_orderstatuses AS a')
			->innerJoin('#__eshop_orderstatusdetails AS b ON (a.id = b.orderstatus_id)')
			->where('a.published = 1')
			->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
		$db->setQuery($query);
		$lists['order_status_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'order_status_id', ' class="inputbox" ', 'id', 'orderstatus_name', $config->order_status_id);
		$lists['complete_status_id'] = JHtml::_('select.genericlist', $db->loadObjectList(), 'complete_status_id', ' class="inputbox" ', 'id', 'orderstatus_name', $config->complete_status_id);

		//Tax default
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'));
		$options[] = JHtml::_('select.option', 'shipping', JText::_('Shipping Address'));
		$options[] = JHtml::_('select.option', 'payment', JText::_('Payment Address'));
		$lists['tax_default'] = JHtml::_('select.genericlist', $options, 'tax_default', ' class="inputbox" ', 'value', 'text', $config->tax_default);
		
		//Tax customer
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'));
		$options[] = JHtml::_('select.option', 'shipping', JText::_('Shipping Address'));
		$options[] = JHtml::_('select.option', 'payment', JText::_('Payment Address'));
		$lists['tax_customer'] = JHtml::_('select.genericlist', $options, 'tax_customer', ' class="inputbox" ', 'value', 'text', $config->tax_customer);
		
		//Account terms and Checkout terms
		$query->clear();
		$query->select('id, title')
			->from('#__content')
			->where('state = 1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'id', 'title');
		$options = array_merge($options, $rows);
		$lists['account_terms'] = JHtml::_('select.genericlist', $options, 'account_terms', ' class="inputbox" ', 'id', 'title', $config->account_terms);
		$lists['checkout_terms'] = JHtml::_('select.genericlist', $options, 'checkout_terms', ' class="inputbox" ', 'id', 'title', $config->checkout_terms);
		
		//Themes list
		$query->clear();
			$query->select('name AS value, title AS text')
				->from('#__eshop_themes')
				->where('published = 1');
			$db->setQuery($query);
			$rows = $db->loadObjectList();
		$lists['theme'] = JHtml::_('select.genericlist', $rows, 'theme', ' class="inputbox" ', 'value', 'text', $config->theme);
		
		//Other boolean list
		$lists['auto_update_currency']	= JHtml::_('select.booleanlist', 'auto_update_currency', ' class="inputbox" ', $config->auto_update_currency);
		$lists['product_count']			= JHtml::_('select.booleanlist', 'product_count', ' class="inputbox" ', $config->product_count);
		$lists['allow_reviews']			= JHtml::_('select.booleanlist', 'allow_reviews', ' class="inputbox" ', $config->allow_reviews);
		$lists['allow_wishlist']		= JHtml::_('select.booleanlist', 'allow_wishlist', ' class="inputbox" ', $config->allow_wishlist);
		$lists['allow_compare']			= JHtml::_('select.booleanlist', 'allow_compare', ' class="inputbox" ', $config->allow_compare);
		$lists['dynamic_price']			= JHtml::_('select.booleanlist', 'dynamic_price', ' class="inputbox" ', $config->dynamic_price);
		$lists['tax']					= JHtml::_('select.booleanlist', 'tax', ' class="inputbox" ', $config->tax);
		$lists['catalog_mode']			= JHtml::_('select.booleanlist', 'catalog_mode', ' class="inputbox" ', $config->catalog_mode);
		$lists['customer_price']		= JHtml::_('select.booleanlist', 'customer_price', ' class="inputbox" ', $config->customer_price);
		$lists['order_alert_mail']		= JHtml::_('select.booleanlist', 'order_alert_mail', ' class="inputbox" ', $config->order_alert_mail);
		$lists['cart_weight']			= JHtml::_('select.booleanlist', 'cart_weight', ' class="inputbox" ', $config->cart_weight);
		$lists['allow_coupon']			= JHtml::_('select.booleanlist', 'allow_coupon', ' class="inputbox" ', $config->allow_coupon);
		$lists['guest_checkout']		= JHtml::_('select.booleanlist', 'guest_checkout', ' class="inputbox" ', $config->guest_checkout);
		$lists['stock_display']			= JHtml::_('select.booleanlist', 'stock_display', ' class="inputbox" ', $config->stock_display);
		$lists['stock_warning']			= JHtml::_('select.booleanlist', 'stock_warning', ' class="inputbox" ', $config->stock_warning);
		$lists['stock_checkout']		= JHtml::_('select.booleanlist', 'stock_checkout', ' class="inputbox" ', $config->stock_checkout);
		$lists['load_bootstrap_css']	= JHtml::_('select.booleanlist', 'load_bootstrap_css', ' class="inputbox" ', $config->load_bootstrap_css);
		$lists['load_bootstrap_js']		= JHtml::_('select.booleanlist', 'load_bootstrap_js', ' class="inputbox" ', $config->load_bootstrap_js);
		
		//Sort options list
		$sortOptions = $config->sort_options;
		$sortOptions = explode(',', $sortOptions);
		$sortValues = array (
			'b.product_name-ASC',
			'b.product_name-DESC',
			'a.product_sku-ASC',
			'a.product_sku-DESC',
			'a.product_price-ASC',
			'a.product_price-DESC',
			'a.product_length-ASC',
			'a.product_length-DESC',
			'a.product_width-ASC',
			'a.product_width-DESC',
			'a.product_height-ASC',
			'a.product_height-DESC',
			'a.product_weight-ASC',
			'a.product_weight-DESC',
			'a.product_quantity-ASC',
			'a.product_quantity-DESC',
			'b.product_short_desc-ASC',
			'b.product_short_desc-DESC',
			'b.product_desc-ASC',
			'b.product_desc-DESC'
		 );
		$sortTexts = array (
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_NAME_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_NAME_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_SKU_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_SKU_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_PRICE_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_PRICE_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_LENGTH_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_LENGTH_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_WIDTH_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_WIDTH_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_HEIGHT_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_HEIGHT_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_WEIGHT_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_WEIGHT_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_QUANTITY_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_QUANTITY_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_SHORT_DESC_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_SHORT_DESC_DESC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_DESC_ASC'),
			JText::_('ESHOP_CONFIG_SORTING_PRODUCT_DESC_DESC')
		);
		//Image
		$options = array();
		$options[] = JHtml::_('select.option', 'resizeImage', 'Resize Image');
		$options[] = JHtml::_('select.option', 'cropsizeImage', 'Cropsize Image');
		$options[] = JHtml::_('select.option', 'maxsizeImage', 'Maxsize Image');
		$lists['image_size_function'] = JHtml::_('select.genericlist', $options, 'image_size_function', ' class="inputbox" ', 'value', 'text', $config->image_size_function);
		
		//Social
		$options = array();
		$options[] = JHtml::_('select.option', 'arial', 'arial');
		$options[] = JHtml::_('select.option', 'lucida grande', 'lucida grande');
		$options[] = JHtml::_('select.option', 'segoe ui', 'segoe ui');
		$options[] = JHtml::_('select.option', 'tahoma', 'tahoma');
		$options[] = JHtml::_('select.option', 'trebuchet ms', 'trebuchet ms');
		$options[] = JHtml::_('select.option', 'verdana', 'verdana');
		$lists['button_font'] = JHtml::_('select.genericlist', $options, 'button_font', ' class="inputbox" ', 'value', 'text', $config->button_font);
		
		$options = array();
		$options[] = JHtml::_('select.option', 'light', 'light');
		$options[] = JHtml::_('select.option', 'dark', 'dark');
		$lists['button_theme'] = JHtml::_('select.genericlist', $options, 'button_theme', ' class="inputbox" ', 'value', 'text', $config->button_theme);
		
		$options = array();
		$options[] = JHtml::_('select.option', 'af_ZA', 'Afrikaans');
		$options[] = JHtml::_('select.option', 'ar_AR', 'Arabic');
		$options[] = JHtml::_('select.option', 'az_AZ', 'Azerbaijani');
		$options[] = JHtml::_('select.option', 'be_BY', 'Belarusian');
		$options[] = JHtml::_('select.option', 'bg_BG', 'Bulgarian');
		$options[] = JHtml::_('select.option', 'bn_IN', 'Bengali');
		$options[] = JHtml::_('select.option', 'bs_BA', 'Bosnian');
		$options[] = JHtml::_('select.option', 'ca_ES', 'Catalan');
		$options[] = JHtml::_('select.option', 'cs_CZ', 'Czech');
		$options[] = JHtml::_('select.option', 'cy_GB', 'Welsh');
		$options[] = JHtml::_('select.option', 'da_DK', 'Danish');
		$options[] = JHtml::_('select.option', 'de_DE', 'German');
		$options[] = JHtml::_('select.option', 'el_GR', 'Greek');
		$options[] = JHtml::_('select.option', 'en_GB', 'English (UK)');
		$options[] = JHtml::_('select.option', 'en_PI', 'English (Pirate)');
		$options[] = JHtml::_('select.option', 'en_UD', 'English (Upside Down)');
		$options[] = JHtml::_('select.option', 'en_US', 'English (US)');
		$options[] = JHtml::_('select.option', 'eo_EO', 'Esperanto');
		$options[] = JHtml::_('select.option', 'es_ES', 'Spanish (Spain)');
		$options[] = JHtml::_('select.option', 'es_LA', 'Spanish');
		$options[] = JHtml::_('select.option', 'et_EE', 'Estonian');
		$options[] = JHtml::_('select.option', 'eu_ES', 'Basque');
		$options[] = JHtml::_('select.option', 'fa_IR', 'Persian');
		$options[] = JHtml::_('select.option', 'fb_LT', 'Leet Speak');
		$options[] = JHtml::_('select.option', 'fi_FI', 'Finnish');
		$options[] = JHtml::_('select.option', 'fo_FO', 'Faroese');
		$options[] = JHtml::_('select.option', 'fr_CA', 'French (Canada)');
		$options[] = JHtml::_('select.option', 'fr_FR', 'French (France)');
		$options[] = JHtml::_('select.option', 'fy_NL', 'Frisian');
		$options[] = JHtml::_('select.option', 'ga_IE', 'Irish');
		$options[] = JHtml::_('select.option', 'gl_ES', 'Galician');
		$options[] = JHtml::_('select.option', 'he_IL', 'Hebrew');
		$options[] = JHtml::_('select.option', 'hi_IN', 'Hindi');
		$options[] = JHtml::_('select.option', 'hr_HR', 'Croatian');
		$options[] = JHtml::_('select.option', 'hu_HU', 'Hungarian');
		$options[] = JHtml::_('select.option', 'hy_AM', 'Armenian');
		$options[] = JHtml::_('select.option', 'id_ID', 'Indonesian');
		$options[] = JHtml::_('select.option', 'is_IS', 'Icelandic');
		$options[] = JHtml::_('select.option', 'it_IT', 'Italian');
		$options[] = JHtml::_('select.option', 'ja_JP', 'Japanese');
		$options[] = JHtml::_('select.option', 'ka_GE', 'Georgian');
		$options[] = JHtml::_('select.option', 'km_KH', 'Khmer');
		$options[] = JHtml::_('select.option', 'ko_KR', 'Korean');
		$options[] = JHtml::_('select.option', 'ku_TR', 'Kurdish');
		$options[] = JHtml::_('select.option', 'la_VA', 'Latin');
		$options[] = JHtml::_('select.option', 'lt_LT', 'Lithuanian');
		$options[] = JHtml::_('select.option', 'lv_LV', 'Latvian');
		$options[] = JHtml::_('select.option', 'mk_MK', 'Macedonian');
		$options[] = JHtml::_('select.option', 'ml_IN', 'Malayalam');
		$options[] = JHtml::_('select.option', 'ms_MY', 'Malay');
		$options[] = JHtml::_('select.option', 'nb_NO', 'Norwegian (bokmal)');
		$options[] = JHtml::_('select.option', 'ne_NP', 'Nepali');
		$options[] = JHtml::_('select.option', 'nl_NL', 'Dutch');
		$options[] = JHtml::_('select.option', 'nn_NO', 'Norwegian (nynorsk)');
		$options[] = JHtml::_('select.option', 'pa_IN', 'Punjabi');
		$options[] = JHtml::_('select.option', 'pl_PL', 'Polish');
		$options[] = JHtml::_('select.option', 'ps_AF', 'Pashto');
		$options[] = JHtml::_('select.option', 'pt_BR', 'Portuguese (Brazil)');
		$options[] = JHtml::_('select.option', 'pt_PT', 'Portuguese (Portugal)');
		$options[] = JHtml::_('select.option', 'ro_RO', 'Romanian');
		$options[] = JHtml::_('select.option', 'ru_RU', 'Russian');
		$options[] = JHtml::_('select.option', 'sk_SK', 'Slovak');
		$options[] = JHtml::_('select.option', 'sl_SI', 'Slovenian');
		$options[] = JHtml::_('select.option', 'sq_AL', 'Albanian');
		$options[] = JHtml::_('select.option', 'sr_RS', 'Serbian');
		$options[] = JHtml::_('select.option', 'sv_SE', 'Swedish');
		$options[] = JHtml::_('select.option', 'sw_KE', 'Swahili');
		$options[] = JHtml::_('select.option', 'ta_IN', 'Tamil');
		$options[] = JHtml::_('select.option', 'te_IN', 'Telugu');
		$options[] = JHtml::_('select.option', 'th_TH', 'Thai');
		$options[] = JHtml::_('select.option', 'tl_PH', 'Filipino');
		$options[] = JHtml::_('select.option', 'tr_TR', 'Turkish');
		$options[] = JHtml::_('select.option', 'uk_UA', 'Ukrainian');
		$options[] = JHtml::_('select.option', 'vi_VN', 'Vietnamese');
		$options[] = JHtml::_('select.option', 'zh_CN', 'Simplified Chinese (China)');
		$options[] = JHtml::_('select.option', 'zh_HK', 'Traditional Chinese (Hong Kong)');
		$options[] = JHtml::_('select.option', 'zh_TW', 'Traditional Chinese (Taiwan)');
		$lists['button_language'] = JHtml::_('select.genericlist', $options, 'button_language', ' class="inputbox" ', 'value', 'text', $config->button_language);
		
		$options = array();
		$options[] = JHtml::_('select.option', 'standard', 'standard');
		$options[] = JHtml::_('select.option', 'button_count', 'button_count');
		$options[] = JHtml::_('select.option', 'box_count', 'box_count');
		$lists['button_layout'] = JHtml::_('select.genericlist', $options, 'button_layout', ' class="inputbox" ', 'value', 'text', $config->button_layout);
		
		$options = array();
		$options[] = JHtml::_('select.option', 'top', 'Vertical');
		$options[] = JHtml::_('select.option', 'right', 'Horizontal');
		$options[] = JHtml::_('select.option', 'no-count', 'No Count');
		$lists['linkedin_layout'] = JHtml::_('select.genericlist', $options, 'linkedin_layout', ' class="inputbox" ', 'value', 'text', $config->linkedin_layout);
		
		$lists['social_enable']	= JHtml::_('select.booleanlist', 'social_enable', ' class="inputbox" ', $config->social_enable);
		$lists['show_facebook_button']	= JHtml::_('select.booleanlist', 'show_facebook_button', ' class="inputbox" ', $config->show_facebook_button);
		$lists['show_faces']	= JHtml::_('select.booleanlist', 'show_faces', ' class="inputbox" ', $config->show_faces);
		$lists['show_facebook_comment']	= JHtml::_('select.booleanlist', 'show_facebook_comment', ' class="inputbox" ', $config->show_facebook_comment);
		$lists['show_twitter_button']	= JHtml::_('select.booleanlist', 'show_twitter_button', ' class="inputbox" ', $config->show_twitter_button);
		$lists['show_pinit_button']	= JHtml::_('select.booleanlist', 'show_pinit_button', ' class="inputbox" ', $config->show_pinit_button);
		$lists['show_google_button']	= JHtml::_('select.booleanlist', 'show_google_button', ' class="inputbox" ', $config->show_google_button);
		$lists['show_linkedin_button']	= JHtml::_('select.booleanlist', 'show_linkedin_button', ' class="inputbox" ', $config->show_linkedin_button);
		$lists['add_category_path'] = JHtml::_('select.booleanlist', 'add_category_path', ' class="inputbox" ', $config->add_category_path);
		
		// Initialize variables.
		$query->clear();
		$rows = array();
		$query->select('a.id AS value, a.title AS text, a.level');
		$query->from('#__menu AS a');
		$query->join('LEFT', $db->quoteName('#__menu').' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		$query->where('a.menutype != '.$db->quote(''));
		$query->where('a.component_id IN (SELECT extension_id FROM #__extensions WHERE element="com_eshop")');
		$query->where('a.client_id = 0');
		$query->where('a.published = 1');

		$query->group('a.id, a.title, a.level, a.lft, a.rgt, a.menutype, a.parent_id, a.published');
		$query->order('a.lft ASC');
		
		// Get the options.
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$rows[$i]->text = str_repeat('- ', $rows[$i]->level).$rows[$i]->text;
		}
		$options = array();
		$options[] = JHtml::_('select.option', 0, JText::_('ESHOP_NONE'), 'value', 'text');
		$rows = array_merge($options, $rows);
		
		$lists['default_menu_item'] = JHtml::_('select.genericlist', $rows, 'default_menu_item',
			array(
				'option.text.toHtml'	=> false,
				'option.text'			=> 'text',
				'option.value'			=> 'value',
				'list.attr'				=> ' class="inputbox" ',
				'list.select'			=> $config->default_menu_item));
		
		$this->lists = $lists;
		$this->config = $config;
		$this->sortOptions = $sortOptions;
		$this->sortValues = $sortValues;
		$this->sortTexts = $sortTexts;
		JFactory::getDocument()->addScript(JURI::root() . 'administrator/components/com_eshop/assets/js/eshop.js')->addScriptDeclaration(EshopHtmlHelper::getZonesArrayJs());
		EshopHelper::chosen();
		
		parent::display($tpl);
	}
}