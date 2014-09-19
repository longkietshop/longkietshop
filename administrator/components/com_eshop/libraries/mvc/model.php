<?php
/**
 * @version		1.0
 * @package		OSFramework
 * @subpackage	EShopModel
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Basic Model class to implement Generic function
 * @author Giang Dinh Truong
 *
 */
class EShopModel extends JModelLegacy
{

	/**
	 * Entity ID
	 *
	 * @var int
	 */
	protected $_id = null;

	/**
	 * Entity data
	 *
	 * @var array
	 */
	protected $_data = null;

	/**
	 * Table name where the object stored
	 * @var
	 */
	protected $_tableName = null;

	/**
	 * Name of component
	 * @var string
	 */
	protected $_component = null;

	/**
	 * This object can be translated into different language or not
	 * @var Boolean
	 */
	protected $translatable = false;

	/**
	 * List of fields which can be translated
	 * @var array
	 */
	protected $translatableFields = array();

	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	public function __construct($config = array())
	{
		parent::__construct();
		$db = $this->getDbo();
		if (isset($config['table_name']))
		{
			$this->_tableName = $config['table_name'];
		}
		else
		{
			$this->_tableName = $db->getPrefix() . strtolower(ESHOP_TABLE_PREFIX . '_' . EShopInflector::pluralize($this->name));
		}
		
		$r = null;
		
		if (preg_match('/(.*)Model(.*)/i', get_class($this), $r))
		{
			$this->_component = strtolower($r[1]);
		}
		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit = JRequest::getVar('edit', true);
		if ($edit)
			$this->setId((int) $array[0]);
		
		#Adding support for translatable objects
		if (isset($config['translatable']))
			$this->translatable = $config['translatable'];
		else
			$this->translatable = false;
		if (isset($config['translatable_fields']))
			$this->translatableFields = $config['translatable_fields'];
		else
			$this->translatableFields = array();
	}
	
	/**
	 * 
	 * Function to get a specific model
	 * @param string $name
	 * @return model object
	 */
	function getModel($name = '')
	{
		if ($name == '')
		{
			$name = $this->name;
		}
		JLoader::import( $name, JPATH_SITE .'/components/com_eshop/models' );
		$model = JModelLegacy::getInstance( $name, 'EshopModel' );
		return $model;
	}

	/**
	 * Method to set the item identifier
	 *
	 * @access	public
	 * @param	int item identifier
	 */
	public function setId($id)
	{
		// Set id and data
		$this->_id = $id;
		$this->_data = null;
	}

	public function setTableName($table)
	{
		$this->_tableName = $table;
	}

	public function getTranslatable()
	{
		return $this->translatable;
	}

	/**
	 * Method to get an item data
	 *
	 * @since 1.5
	 */
	function &getData()
	{
		if (empty($this->_data))
		{
			if ($this->_id)
				$this->_loadData();
			else
				$this->_initData();
		}
		
		return $this->_data;
	}

	/**
	 * Method to store an item
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function store(&$data)
	{
		$db = $this->getDbo();
		jimport('joomla.filesystem.file');
		$user = JFactory::getUser();
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_eshop/tables/' . $this->name . '.php'))
			$row = $this->getTable($this->name, $this->_component . 'Table');
		else
			$row = new EShopTable($this->_tableName, 'id', $db);
		if ($data['id'])
		{
			$row->load($data['id']);
		}
		if (!$row->id && property_exists($row, 'ordering'))
		{
			$row->ordering = $row->getNextOrder($this->getWhereNextOrdering());
		}
		if (property_exists($row, 'hits') && !$row->hits)
		{
			$row->hits = 0;
		}
		if (property_exists($row, 'created_date') && !$row->created_date)
		{
			$row->created_date = JFactory::getDate()->toSql();
		}
		if (property_exists($row, 'created_by') && !$row->created_by)
		{
			$row->created_by = $user->get('id');
		}
		if (property_exists($row, 'modified_date'))
		{
			$row->modified_date = JFactory::getDate()->toSql();
		}
		if (property_exists($row, 'modified_by'))
		{
			$row->modified_by = $user->get('id');
		}
		if (property_exists($row, 'checked_out'))
		{
			$row->checked_out = 0;
		}
		if (property_exists($row, 'checked_out_time'))
		{
			$row->checked_out_time = '0000-00-00 00:00:00';
		}
		if (isset($data[$this->name . '_alias']) && empty($data[$this->name . '_alias']))
		{
			$data[$this->name . '_alias'] = JApplication::stringURLSafe($data[$this->name . '_name']);
		}
		if (!$row->bind($data))
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		if (!$row->check())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		if (!$row->store())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}
		$data['id'] = $row->id;
		//Adding support for translable objects
		if ($this->translatable)
		{
			if (JLanguageMultilang::isEnabled())
			{
				$languages = EshopHelper::getLanguages();
				foreach ($languages as $language)
				{
					$langCode = $language->lang_code;
					$detailsRow = new EShopTable(EShopInflector::singularize($this->_tableName).'details', 'id', $db);
					$detailsRow->id = $data['details_id_' . $langCode];
					$detailsRow->{$this->name.'_id'} = $data['id'];
					foreach ($this->translatableFields as $field)
					{
						if ($field == $this->name . '_alias')
						{
							if (empty($data[$this->name . '_alias_' . $langCode]))
							{
								$detailsRow->{$field} = JApplication::stringURLSafe($data[$this->name . '_name_' . $langCode]);
							}
							else
							{
								$detailsRow->{$field} = $data[$this->name . '_alias_' . $langCode];
							}
						}
						else 
						{
							$detailsRow->{$field} = $data[$field.'_' . $langCode];
						}
					}
					$detailsRow->language = $langCode;
					$detailsRow->store();
				}
			}
			else 
			{
				$detailsRow = new EShopTable(EShopInflector::singularize($this->_tableName).'details', 'id', $db);
				$detailsRow->id = $data['details_id'];
				$detailsRow->{$this->name.'_id'} = $data['id'];
				foreach ($this->translatableFields as $field)
				{
					if ($field == $this->name . '_alias')
					{
						if (empty($data[$this->name . '_alias']))
						{
							$detailsRow->{$field} = JApplication::stringURLSafe($data[$this->name . '_name']);
						}
						else
						{
							$detailsRow->{$field} = $data[$this->name . '_alias'];
						}
					}
					else
					{
						$detailsRow->{$field} = $data[$field];
					}
				}
				$detailsRow->language = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
				$detailsRow->store();
			}
		}
		return true;
	}

	/**
	 * Method to remove items
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
			$query = $db->getQuery(true);
			$query->delete($this->_tableName)
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				//Removed error
				return 0;
			// Delete details records
			if ($this->translatable)
			{
				$query->clear();
				$query->delete(EShopInflector::singularize($this->_tableName).'details')
					->where($this->name . '_id IN (' . $cids . ')');
				$db->setQuery($query);
				if (!$db->query())
					//Removed error
					return 0;
			}
			if ($db->getAffectedRows() < count($cid))
			{
				//Removed warning
				return 2;
			}
		}
		//Removed success
		return 1;
	}

	/**
	 * Load the data
	 *
	 */
	public function _loadData()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*')
			->from($this->_tableName)
			->where('id = ' . intval($this->_id));
		$db->setQuery($query);
				
		$this->_data = $db->loadObject();
		if ($this->translatable)
		{
			if (JLanguageMultilang::isEnabled())
			{
				$query->clear();
				$query->select('*')
					->from(EShopInflector::singularize($this->_tableName).'details')
					->where($this->name.'_id = ' . $this->_id);
				$db->setQuery($query);
				$rows = $db->loadObjectList('language');
				if (count($rows))
				{
					foreach ($rows as $language => $row)
					{
						foreach ($this->translatableFields as $field)
						{
							if ($field == $this->name . '_name')
							{
								$this->_data->{$field . '_' . $language} = htmlspecialchars($row->{$field});
							}
							else
							{
								$this->_data->{$field . '_' . $language} = $row->{$field};
							}
						}
						$this->_data->{'details_id_' . $language} = $row->id;
					}
				}
			}
			else 
			{
				$query->clear();
				$query->select('*')
					->from(EShopInflector::singularize($this->_tableName).'details')
					->where($this->name.'_id = ' . intval($this->_id))
					->where('language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"');
				$db->setQuery($query);
				$row = $db->loadObject();
				if (is_object($row))
				{
					foreach ($this->translatableFields as $field)
					{
						if ($field == $this->name . '_name')
						{
							$this->_data->{$field} = htmlspecialchars($row->{$field});
						}
						else 
						{
							$this->_data->{$field} = $row->{$field};
						}
					}
					$this->_data->{'details_id'} = $row->id;
				}
			}
		}
	}

	/**
	 * Init data
	 *
	 */
	public function _initData()
	{
		jimport('joomla.filesystem.file');
		$db = $this->getDbo();
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_eshop/tables/' . $this->name . '.php'))
			$row = $this->getTable($this->name, $this->_component . 'Table');
		else
			$row = new EShopTable($this->_tableName, 'id', $db);
		$this->_data = $row;
		if ($this->translatable)
		{
			if (JLanguageMultilang::isEnabled())
			{
				$languages = EshopHelper::getLanguages();
				foreach ($languages as $language)
				{
					$langCode = $language->lang_code;
					foreach ($this->translatableFields as $field)
					{
						$this->_data->{$field . '_' . $langCode} = '';
					}
				}
				$this->_data->{'details_id_' . $langCode} = '';
			}
			else 
			{
				foreach ($this->translatableFields as $field)
				{
					$this->_data->{$field} = '';
				}
				$this->_data->{'details_id'} = '';
			}
		}
	}

	/**
	 * Publish the selected items
	 *
	 * @param  array   $cid
	 * @return boolean
	 */
	public function publish($cid, $state)
	{
		if (count($cid))
		{
			$db = $this->getDbo();
			$cids = implode(',', $cid);
			
			$query = $db->getQuery(true);
			$query->update($this->_tableName)
				->set('published = ' . $state)
				->where('id IN (' . $cids . ')');
			$db->setQuery($query);
			if (!$db->query())
				return false;
		}
		
		return true;
	}

	/**
	 * Save the order of entities
	 *
	 * @param array $cid
	 * @param array $order
	 */
	public function saveOrder($cid, $order)
	{
		jimport('joomla.filesystem.file');
		$db = $this->getDbo();
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_eshop/tables/' . $this->name . '.php'))
			$row = $this->getTable($this->getName(), $this->getName());
		else
			$row = new EShopTable($this->_tableName, 'id', $db);
		$groupings = array();
		// update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);
			// track parents
			if (property_exists($row, $this->name . '_parent_id'))
			{
				$groupings[] = $row->{$this->name . '_parent_id'};
			}
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store())
				{
					$this->setError($db->getErrorMsg());
					
					return false;
				}
			}
		}
		// execute updateOrder for each parent group
		$groupings = array_unique($groupings);
		foreach ($groupings as $group)
		{
			$row->reorder($this->name . '_parent_id = ' . (int) $group);
		}
		
		return true;
	}

	/**
	 * Change ordering of ann item
	 *
	 */
	public function move($direction)
	{
		jimport('joomla.filesystem.file');
		$db = $this->getDbo();
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_eshop/tables/' . $this->name . '.php'))
			$row = $this->getTable($this->name, $this->_component . 'Table');
		else
			$row = new EShopTable($this->_tableName, 'id', $db);
		$row->load($this->_id);
		if (!$row->move($direction))
		{
			$this->setError($db->getErrorMsg());
			
			return false;
		}
		
		return true;
	}

	/**
	 * Copy an entity
	 *
	 */
	public function copy($id)
	{
		jimport('joomla.filesystem.file');
		$db = $this->getDbo();
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_eshop/tables/' . $this->name . '.php'))
		{
			$row = $this->getTable($this->name, $this->_component . 'Table');
			$rowOld = $this->getTable($this->name, $this->_component . 'Table');
		}
		else
		{
			$rowO = new EShopTable($this->_tableName, 'id', $db);
			$rowOld = new EShopTable($this->_tableName, 'id', $db);
		}
		$rowOld->load($id);
		$data = JArrayHelper::fromObject($rowOld);
		$data['id'] = 0;
		$data['title'] = $data['title'] . ' Copy';
		$row->bind($data);
		$row->check();
		if (property_exists($row, 'ordering'))
		{
			$row->ordering = $row->getNextOrder($this->getWhereNextOrdering());
		}
		$row->store();
		
		return $row->id;
	}

	public function getWhereNextOrdering()
	{
		return '';
	}
}
