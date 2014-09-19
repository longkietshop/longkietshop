<?php
/**
 * @version		1.1.8
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2013 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

/**
 * 
 * Build the route for the com_eshop component
 * @param	array	An array of URL arguments
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 * @since	1.5
 */
function EshopBuildRoute(&$query)
{
	$segments = array();
	require_once JPATH_ROOT . '/components/com_eshop/helpers/helper.php';
	require_once JPATH_ROOT . '/components/com_eshop/helpers/route.php';
	$db = JFactory::getDbo();
	$queryArr = $query;
	if (isset($queryArr['option']))
		unset($queryArr['option']);
	if (isset($queryArr['Itemid']))
		unset($queryArr['Itemid']);
	//Store the query string to use in the parseRouter method
	$queryString = http_build_query($queryArr);
	
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	
	//We need a menu item.  Either the one specified in the query, or the current active one if none specified
	if (empty($query['Itemid']))
		$menuItem = $menu->getActive();
	else
		$menuItem = $menu->getItem($query['Itemid']);
	
	if (empty($menuItem->query['view']))
	{
		$menuItem->query['view'] = '';
	}
	//Are we dealing with an product or category that is attached to a menu item?
	if (($menuItem instanceof stdClass) && isset($query['view']) && isset($query['id']) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == intval($query['id']))
	{
		unset($query['view']);
		if (isset($query['catid']))
			unset($query['catid']);
		unset($query['id']);
	}
	
	if (($menuItem instanceof stdClass) && $menuItem->query['view'] == 'category' && isset($query['catid']) && $menuItem->query['id'] == intval($query['catid']))
	{
		if (isset($query['catid']))
			unset($query['catid']);
	}
	
	$parentId = 0;
	if (($menuItem instanceof stdClass))
	{
		if (isset($menuItem->query['view']) && ($menuItem->query['view']== 'categories' || $menuItem->query['view'] == 'category'))
		{
			$parentId = (int)$menuItem->query['id'];
		}
	}
			
	$view = isset($query['view']) ? $query['view'] : '';
	$id = 	isset($query['id']) ? (int) $query['id'] : 0;
	$catid = isset($query['catid']) ? (int) $query['catid'] : 0;

	if ($view == 'cart' || $view == 'checkout' || $view == 'wishlist' || $view == 'compare')
	{
		if (isset($query['Itemid']) && !EshopRoute::findView($view))
		{
			unset($query['Itemid']);
		}
	}
	
	switch ($view)
	{
		case 'categories':
		case 'category' :
			if ($id)
				$segments = array_merge( $segments, EshopHelper::getCategoryPath($id, 'alias', '', $parentId));
			break;
		case 'product' :
			if ($id)
			{
				$segments[] = EshopHelper::getElementAlias($id, 'product');
			}
			if ($catid)
			{
				$segments = array_merge(EshopHelper::getCategoryPath($catid, 'alias'), $segments);
			}
			break;
		case 'manufacturer':
			if ($id)
			{
				$segments[] = EshopHelper::getElementAlias($id, 'manufacturer');
			}
			break;
		case 'checkout':
				if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId()))
				$segments[] = JText::_('ESHOP_CHECKOUT');
			break;
		case 'cart':
			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId()))
				$segments[] = JText::_('ESHOP_SHOPPING_CART');
			break;
		case 'wishlist':
			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId()))
				$segments[] = JText::_('ESHOP_WISHLIST');
			break;
		case 'compare':
			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId()))
				$segments[] = JText::_('ESHOP_COMPARE');
			break;
		case 'customer':
			if (!isset($query['Itemid']) || (isset($query['Itemid']) && $query['Itemid'] == EshopRoute::getDefaultItemId()))
				$segments[] = $query['id'];
			break;
	}
	
	if (isset($query['start']) || isset($query['limitstart']))
	{
		$limit = $app->getUserState('limit');
		$limitStart = isset($query['limitstart']) ? (int)$query['limitstart'] : (int)$query['start'];
		$page = ceil(($limitStart + 1) / $limit);
		$segments[] = JText::_('ESHOP_PAGE').'-'.$page;
	}

	if (isset($query['task']) && $query['task'] == 'customer.downloadInvoice')
		$segments[] = JText::_('ESHOP_DOWNLOAD_INVOICE');
	
	if (isset($query['task']))
		unset($query['task']);
	
	if (isset($query['view']))
		unset($query['view']);
	
	if (isset($query['id']))
		unset($query['id']);
	
	if (isset($query['catid']))
		unset($query['catid']);
	
	if (isset($query['key']))
		unset($query['key']);

	if (isset($query['redirect']))
		unset($query['redirect']);
		
	if (isset($query['start']))
		unset($query['start']);
	
	if (isset($query['limitstart']))
		unset($query['limitstart']);
	
	if (count($segments))
	{
		$segments = array_map('JApplication::stringURLSafe', $segments);
		$key = md5(implode('/', $segments));
		$q = $db->getQuery(true);
		$q->select('COUNT(*)')
			->from('#__eshop_urls')
			->where('md5_key="'.$key.'"');
		$db->setQuery($q);
		$total = $db->loadResult();
		if (!$total)
		{
			$q->clear();
			$q->insert('#__eshop_urls')
				->columns('md5_key, `query`')
				->values("'$key', '$queryString'");
			$db->setQuery($q);
			$db->query();
		}
	}
		
	return $segments;
}

/**
 * 
 * Parse the segments of a URL.
 * @param	array	The segments of the URL to parse.
 * @return	array	The URL attributes to be used by the application.
 * @since	1.5
 */
function EshopParseRoute($segments)
{		
	$vars = array();
	if (count($segments))
	{
		$db = JFactory::getDbo();
		$key = md5(str_replace(':', '-', implode('/', $segments)));
		$query = $db->getQuery(true);
		$query->select('`query`')
			->from('#__eshop_urls')
			->where('md5_key = "' . $key . '"');
		$db->setQuery($query);
		$queryString = $db->loadResult();
		if ($queryString)
			parse_str($queryString, $vars);
	}
	
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	if ($item = $menu->getActive())
	{
		foreach ($item->query as $key=>$value)
		{
			if ($key != 'option' && $key != 'Itemid' && !isset($vars[$key]))
				$vars[$key] = $value;
		}
	}
	return $vars;
}