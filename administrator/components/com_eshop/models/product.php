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
 * Eshop Component Product Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelProduct extends EShopModel
{
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	public function __construct($config)
	{
		$config['translatable'] = true;
		$config['translatable_fields'] = array(
			'product_name', 
			'product_alias', 
			'product_desc', 
			'product_short_desc', 
			'meta_key', 
			'meta_desc',
			'product_tag');
		parent::__construct($config);
	}

	/**
	 * Function to store product
	 * @see EShopModel::store()
	 */
	function store(&$data)
	{
		jimport('joomla.filesystem.file');
		$imagePath = JPATH_ROOT . '/media/com_eshop/products/';
		if (JRequest::getInt('remove_image') && $data['id'])
		{
			//Remove image first
			$row = new EShopTable('#__eshop_products', 'id', $this->getDbo());
			$row->load($data['id']);
			
			if (JFile::exists($imagePath . $row->product_image))
				JFile::delete($imagePath . $row->product_image);
			
			if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->product_image).'-100x100.'.JFile::getExt($row->product_image)))
				JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->product_image).'-100x100.'.JFile::getExt($row->product_image));
			$data['product_image'] = '';
		}
		// Process main image first
		$productImage = $_FILES['product_image'];
		if (is_uploaded_file($productImage['tmp_name']))
		{
			if ($data['id'])
			{
				// Delete the old image
				$row = new EShopTable('#__eshop_products', 'id', $this->getDbo());
				$row->load($data['id']);
				
				if (JFile::exists($imagePath . $row->product_image))
					JFile::delete($imagePath . $row->product_image);
				
				if (JFile::exists($imagePath . 'resized/' . JFile::stripExt($row->product_image).'-100x100.'.JFile::getExt($row->product_image)))
					JFile::delete($imagePath . 'resized/' . JFile::stripExt($row->product_image).'-100x100.'.JFile::getExt($row->product_image));
			}
			if (JFile::exists($imagePath . $productImage['name']))
			{
				$imageFileName = uniqid('image_') . '_' . $productImage['name'];
			}
			else
			{
				$imageFileName = $productImage['name'];
			}
			JFile::upload($productImage['tmp_name'], $imagePath . $imageFileName);
			// Resize image
			EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/products/', 100, 100);
			$data['product_image'] = $imageFileName;
		}
		parent::store($data);
		$languages = EshopHelper::getLanguages();
		$translatable = JLanguageMultilang::isEnabled() && count($languages) > 1;
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$productId = $data['id'];
		$user = JFactory::getUser();
		//Store product categories
		$categoryId = JRequest::getVar('category_id');
		$query->delete('#__eshop_productcategories')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->query();
		$row = new EShopTable('#__eshop_productcategories', 'id', $db);
		for ($i = 0; $n = count($categoryId), $i < $n; $i++) {
			$row->id = '';
			$row->product_id = $productId;
			$row->category_id = $categoryId[$i];
			$row->store();
		}
		//Store related products
		$relatedProductId = JRequest::getVar('related_product_id');
		$query->clear();
		$query->delete('#__eshop_productrelations')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->query();
		$row = new EShopTable('#__eshop_productrelations', 'id', $db);
		for ($i = 0; $n = count($relatedProductId), $i < $n; $i++) {
			$row->id = '';
			$row->product_id = $productId;
			$row->related_product_id = $relatedProductId[$i];
			$row->store();
		}
		//Store product attributes
		$attributeId = JRequest::getVar('attribute_id');
		$productAttributeId = JRequest::getVar('productattribute_id');
		$attributePublished = JRequest::getVar('attribute_published');
		//Delete in product attributes
		$query->clear();
		$query->delete('#__eshop_productattributes')
			->where('product_id = ' . intval($productId));
		if (count($productAttributeId))
		{
			$query->where('id NOT IN (' . implode($productAttributeId, ',') . ')');
		}
		$db->setQuery($query);
		$db->query();
		//Delete in product attribute details
		$query->clear();
		$query->delete('#__eshop_productattributedetails')
			->where('product_id = ' . intval($productId));
		if (count($productAttributeId))
		{
			$query->where('productattribute_id NOT IN (' . implode($productAttributeId, ',') . ')');
		}
		$db->setQuery($query);
		$db->query();
		if ($translatable)
		{
			for ($i = 0; $n = count($attributePublished), $i < $n; $i++)
			{
				$row = new EShopTable('#__eshop_productattributes', 'id', $db);
				$row->id = isset($productAttributeId[$i]) ? $productAttributeId[$i] : '';
				$row->product_id = $productId;
				$row->attribute_id = $attributeId[$i];
				$row->published = $attributePublished[$i];
				$row->store();
				foreach ($languages as $language)
				{
					$langCode = $language->lang_code;
					$productAttributeDetailsId = JRequest::getVar('productattributedetails_id_' . $langCode);
					$attributeValue = JRequest::getVar('attribute_value_' . $langCode);
					$detailsRow = new EShopTable('#__eshop_productattributedetails', 'id', $db);
					$detailsRow->id = isset($productAttributeDetailsId[$i]) ? $productAttributeDetailsId[$i] : '';
					$detailsRow->productattribute_id = $row->id;
					$detailsRow->product_id = $productId;
					$detailsRow->value = $attributeValue[$i];
					$detailsRow->language = $langCode;
					$detailsRow->store();
				}
			}
		}
		else
		{
			$productAttributeDetailsId = JRequest::getVar('productattributedetails_id');
			$attributeValue = JRequest::getVar('attribute_value');
			for ($i = 0; $n = count($attributePublished), $i < $n; $i++)
			{
				$row = new EShopTable('#__eshop_productattributes', 'id', $db);
				$row->id = isset($productAttributeId[$i]) ? $productAttributeId[$i] : '';
				$row->product_id = $productId;
				$row->attribute_id = $attributeId[$i];
				$row->published = $attributePublished[$i];
				$row->store();
				$detailsRow = new EShopTable('#__eshop_productattributedetails', 'id', $db);
				$detailsRow->id = isset($productAttributeDetailsId[$i]) ? $productAttributeDetailsId[$i] : '';
				$detailsRow->productattribute_id = $row->id;
				$detailsRow->product_id = $productId;
				$detailsRow->value = $attributeValue[$i];
				$detailsRow->language = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
				$detailsRow->store();
			}
		}
		//Store product options
		$productOptionId = JRequest::getVar('productoption_id');
		//Delete product options
		$query->clear();
		$query->delete('#__eshop_productoptions')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->query();
		//Delete product option values
		$query->clear();
		$query->delete('#__eshop_productoptionvalues')
			->where('product_id = ' . intval($productId));
		$db->setQuery($query);
		$db->query();
		if (count($productOptionId)) {
			$row = new EShopTable('#__eshop_productoptions', 'id', $db);
			$valueRow = new EShopTable('#__eshop_productoptionvalues', 'id', $db);
			for ($i = 0; $n = count($productOptionId), $i < $n; $i++) {
				$optionId = $productOptionId[$i];
				//Store options
				$row->id = '';
				$row->product_id = $productId;
				$row->option_id = $optionId;
				$row->required = JRequest::getVar('required_'.$optionId);
				$row->store();
		        //Store options values
		        $optionValueId = JRequest::getVar('optionvalue_'.$optionId.'_id');
		        $optionValueQuantity = JRequest::getVar('optionvalue_'.$optionId.'_quantity');
		        $optionValuePriceSign = JRequest::getVar('optionvalue_'.$optionId.'_price_sign');
		        $optionValuePrice = JRequest::getVar('optionvalue_'.$optionId.'_price');
		        $optionValueWeightSign = JRequest::getVar('optionvalue_'.$optionId.'_weight_sign');
		        $optionValueWeight = JRequest::getVar('optionvalue_'.$optionId.'_weight');
		        for ($j = 0; $m = count($optionValueId), $j < $m; $j++) {
		        	$valueRow->id = '';
		        	$valueRow->product_option_id = $row->id;
		        	$valueRow->product_id = $productId;
		        	$valueRow->option_id = $optionId;
		        	$valueRow->option_value_id = $optionValueId[$j];
		        	$valueRow->quantity = $optionValueQuantity[$j];
		        	$valueRow->price = $optionValuePrice[$j];
		        	$valueRow->price_sign = $optionValuePriceSign[$j];
		        	$valueRow->weight = $optionValueWeight[$j];
		        	$valueRow->weight_sign = $optionValueWeightSign[$j];
		        	$valueRow->store();
		        }
			}
		}
		//Store product discounts
		$productDiscountId = JRequest::getVar('productdiscount_id');
		$discountCustomerGroupId = JRequest::getVar('discount_customergroup_id');
		$discountQuantity = JRequest::getVar('discount_quantity');
		$discountPriority = JRequest::getVar('discount_priority');
		$discountPrice = JRequest::getVar('discount_price');
		$discountDateStart = JRequest::getVar('discount_date_start');
		$discountDateEnd = JRequest::getVar('discount_date_end');
		$discountPublished = JRequest::getVar('discount_published');
		//Remove removed discounts first
		$query->clear();
		$query->delete('#__eshop_productdiscounts')
			->where('product_id = ' . intval($productId));
		if (count($productDiscountId)) {
				$query->where('id NOT IN ('.implode($productDiscountId, ',').')');
		}
		$db->setQuery($query);
		$db->query();
		$row = new EShopTable('#__eshop_productdiscounts', 'id', $db);
		for ($i = 0; $n = count($discountCustomerGroupId), $i < $n; $i++) {
			$row->id = isset($productDiscountId[$i]) ? $productDiscountId[$i] : '';
			$row->product_id = $productId;
			$row->customergroup_id = $discountCustomerGroupId[$i];
			$row->quantity = $discountQuantity[$i];
			$row->priority = $discountPriority[$i];
			$row->price = $discountPrice[$i];
			$row->date_start = $discountDateStart[$i];
			$row->date_end = $discountDateEnd[$i];
			$row->published = $discountPublished[$i];
			$row->store();
		}
		//Store product specials
		$productSpecialId = JRequest::getVar('productspecial_id');
		$specialCustomerGroupId = JRequest::getVar('special_customergroup_id');
		$specialPriority = JRequest::getVar('special_priority');
		$specialPrice = JRequest::getVar('special_price');
		$specialDateStart = JRequest::getVar('special_date_start');
		$specialDateEnd = JRequest::getVar('special_date_end');
		$specialPublished = JRequest::getVar('special_published');
		//Remove removed specials first
		$query->clear();
		$query->delete('#__eshop_productspecials')
			->where('product_id = ' . intval($productId));
		if (count($productSpecialId)) {
			$query->where('id NOT IN ('.implode($productSpecialId, ',').')');
		}
		$db->setQuery($query);
		$db->query();
		$row = new EShopTable('#__eshop_productspecials', 'id', $db);
		for ($i = 0; $n = count($specialCustomerGroupId), $i < $n; $i++) {
			$row->id = isset($productSpecialId[$i]) ? $productSpecialId[$i] : '';
			$row->product_id = $productId;
			$row->customergroup_id = $specialCustomerGroupId[$i];
			$row->priority = $specialPriority[$i];
			$row->price = $specialPrice[$i];
			$row->date_start = $specialDateStart[$i];
			$row->date_end = $specialDateEnd[$i];
			$row->published = $specialPublished[$i];
			$row->store();
		}
		//Images process
		//Old images
		$productImageId = JRequest::getVar('productimage_id');
		$productImageOrdering = JRequest::getVar('productimage_ordering');
		$productImagePublished = JRequest::getVar('productimage_published');
		// Delete image files first
		$query->clear();
		$query->select('image')
			->from('#__eshop_productimages')
			->where('product_id = ' . intval($productId));
		if (count($productImageId)) {
			$query->where('id NOT IN ('.implode($productImageId, ',').')');	
		}
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		for ($i = 0; $n = count($rows), $i < $n; $i++) {
			if (JFile::exists($imagePath . $rows[$i]->image))
			{
				JFile::delete($imagePath . $rows[$i]->image);
			}
		}
		// Then delete data
		$query->clear();
		$query->delete('#__eshop_productimages')
			->where('product_id = ' . intval($productId));
		if (count($productImageId)) {
			$query->where('id NOT IN ('.implode($productImageId, ',').')');	
		}
		$db->setQuery($query);
		$db->query();
		$row = new EShopTable('#__eshop_productimages', 'id', $db);
		for ($i = 0; $n = count($productImageId), $i < $n; $i++)
		{
			$row->id = $productImageId[$i];
			$row->product_id = $productId;
			$row->published = $productImagePublished[$i];
			$row->ordering = $productImageOrdering[$i];
			$row->modified_date = date('Y-m-d H:i:s');
			$row->modified_by = $user->get('id');
			$row->checked_out = 0;
			$row->checked_out_time = '0000-00-00 00:00:00';
			$row->store();
		}
		// New images
		if (isset($_FILES['image'])) {
			$image = $_FILES['image'];
			$imageOrdering = JRequest::getVar('image_ordering');
			$imagePublished = JRequest::getVar('image_published');
			for ($i = 0; $n = count($image), $i < $n; $i++) {
				if (is_uploaded_file($image['tmp_name'][$i]))
				{
					if (JFile::exists($imagePath . $image['name'][$i]))
					{
						$imageFileName = uniqid('image_') . '_' . $image['name'][$i];
					}
					else
					{
						$imageFileName = $image['name'][$i];
					}
					JFile::upload($image['tmp_name'][$i], $imagePath . $imageFileName);
					//Resize image
					EshopHelper::resizeImage($imageFileName, JPATH_ROOT . '/media/com_eshop/products/', 100, 100);
					
					$row->id = '';
					$row->product_id = $productId;
					$row->image = $imageFileName;
					$row->published = $imagePublished[$i];
					$row->ordering = $imageOrdering[$i];
					$row->created_date = date('Y-m-d H:i:s');
					$row->created_by = $user->get('id');
					$row->modified_date = date('Y-m-d H:i:s');
					$row->modified_by = $user->get('id');
					$row->checked_out = 0;
					$row->checked_out_time = '0000-00-00 00:00:00';
					$row->store();
				}
			}
		}
		return true;
	}
	
	/**
	 * Method to remove products
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->select('id')
				->from('#__eshop_products')
				->where('id IN (' . $cids . ')')
				->where('id NOT IN (SELECT  DISTINCT(product_id) FROM #__eshop_orderproducts)');
			$db->setQuery($query);
			$products = $db->loadColumn();
			if (count($products))
			{
				$query->clear();
				$query->delete('#__eshop_products')
					->where('id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				// Delete details records
				$query->clear();
				$query->delete('#__eshop_productdetails')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				$numItemsDeleted = $db->getAffectedRows();
				//Delete product attributes
				$query->clear();
				$query->delete('#__eshop_productattributes')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product attribute details
				$query->clear();
				$query->delete('#__eshop_productattributedetails')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product categories
				$query->clear();
				$query->delete('#__eshop_productcategories')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product discounts
				$query->clear();
				$query->delete('#__eshop_productdiscounts')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product images
				$query->clear();
				$query->delete('#__eshop_productimages')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product options
				$query->clear();
				$query->delete('#__eshop_productoptions')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product option values
				$query->clear();
				$query->delete('#__eshop_productoptionvalues')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product relations
				$query->clear();
				$query->delete('#__eshop_productrelations')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete product specials
				$query->clear();
				$query->delete('#__eshop_productspecials')
					->where('product_id IN (' . implode(',', $products) . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
				//Delete SEF urls to products
				for ($i = 0; $n = count($products), $i < $n; $i++)
				{
					$query->clear();
					$query->delete('#__eshop_urls')
						->where('query LIKE "view=product&id=' . $products[$i] . '&catid=%"');
					$db->setQuery($query);
					$db->query();
				}
				if ($numItemsDeleted < count($cid))
				{
					return 2;	
				}
			}
			else
			{
				return 2;
			}
		}
		//Removed success
		return 1;
	}
	
	/**
	 * 
	 * Function to featured products
	 * @param array $cid
	 * @return boolean
	 */
	function featured($cid) {
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->update('#__eshop_products')
				->set('product_featured = 1')
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				return false;
		}
		return true;
	}
	
	/**
	 * 
	 * Function to unfeatured products
	 * @param array $cid
	 * @return boolean
	 */
	function unfeatured($cid) {
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			$query = $db->getQuery(true);
			$query->update('#__eshop_products')
				->set('product_featured = 0')
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				return false;
		}
		return true;
	}
}