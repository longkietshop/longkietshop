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
 * EShop Component Coupon Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EshopModelCoupon extends EShopModel
{

	function store(&$data)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if ($data['id'])
		{
			$query->delete('#__eshop_couponproducts')
				->where('coupon_id = ' . intval($data['id']));
			$db->setQuery($query);
			$db->query();
		}
		parent::store($data);
		//save new data
		if (isset($data['procuct_id']))
		{
			$productIds = $data['procuct_id'];
			if (count($productIds))
			{
				$query->clear();
				$query->insert('#__eshop_couponproducts')->columns('coupon_id, product_id');
				$couponId = $data['id'];
				for ($i = 0; $i < count($productIds); $i++)
				{
					$productId = $productIds[$i];
					$query->values("$couponId, $productId")
					;
				}
				$db->setQuery($query);
				$db->query();
			}
		}
		
		return true;
	}

}