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

class EShopModelProduct extends EShopModel
{
	/**
	 * Entity ID
	 *
	 * @var int
	 */
	protected $id = null;

	/**
	 * Entity data
	 *
	 * @var array
	 */
	protected $data = null;
	
	/**
	 * Current active language
	 *
	 * @var string
	 */
	protected $language = null;
	
	/**
	 * 
	 * Constructor
	 * @since 1.5
	 */
	public function __construct($config = array())
	{
		parent::__construct();
		$this->id = JRequest::getInt('id');
		$this->data = null;
		$this->language = JFactory::getLanguage()->getTag();
	}
	
	/**
	 * 
	 * Function to get product data
	 * @see EShopModel::getData()
	 */
	function &getData()
	{
		if (empty($this->data))
		{
			$this->_loadData();
		}
		return $this->data;
	}
	
	/**
	 * 
	 * Function to load product data
	 * @see EShopModel::_loadData()
	 */
	function _loadData() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*, b.product_name, b.product_alias, b.product_desc, b.product_short_desc, b.product_tag, b.meta_key, b.meta_desc')
			->from('#__eshop_products AS a')
			->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
			->where('a.id = ' . intval($this->id))
			->where('b.language = "' . $this->language . '"');
		$db->setQuery($query);
		$this->data = $db->loadObject();
	}
	
	/**
	 * 
	 * Function to write review
	 * @param array $data
	 * @return json array
	 */
	function writeReview($data)
	{
		$user = JFactory::getUser();
		$json = array();
		if (utf8_strlen($data['author']) < 3 || utf8_strlen($data['author']) > 25)
		{
			$json['error'] = JText::_('ESHOP_ERROR_YOUR_NAME');
			return $json;
		}
		if (utf8_strlen($data['review']) < 25 || utf8_strlen($data['review']) > 1000)
		{
			$json['error'] = JText::_('ESHOP_ERROR_YOUR_REVIEW');
			return $json;
		}
		if (!$data['rating'])
		{
			$json['error'] = JText::_('ESHOP_ERROR_RATING');
			return $json;
		}
		if (!$json)
		{
			$row = JTable::getInstance('Eshop', 'Review');
			$row->bind($data);
			$row->id = '';
			$row->product_id = $data['product_id'];
			$row->customer_id = $user->get('id') ? $user->get('id') : 0;
			$row->published = 0;
			$row->created_date = JFactory::getDate()->toSql();
			$row->created_by = $user->get('id') ? $user->get('id') : 0;
			$row->modified_date = JFactory::getDate()->toSql();
			$row->modified_by = $user->get('id') ? $user->get('id') : 0;
			$row->checked_out = 0;
			$row->checked_out_time = '0000-00-00 00:00:00';
			if ($row->store())
			{
				$json['success'] = JText::_('ESHOP_REVIEW_SUBMITTED_SUCESSFULLY');
			}
			else 
			{
				$json['error'] = JText::_('ESHOP_REVIEW_SUBMITTED_FAILURED');
			}
			return $json;
		}
	}
}