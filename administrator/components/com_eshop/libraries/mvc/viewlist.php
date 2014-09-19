<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OSFramework
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class EShopViewList extends JViewLegacy
{
    public $lang_prefix = ESHOP_LANG_PREFIX;

    public function display($tpl = null)
    {
        $state = $this->get('State');
        $items		= $this->get( 'Data');
        $pagination = $this->get( 'Pagination' );

        $this->state = $state;

        $lists = array();
        $lists['order_Dir'] = $state->filter_order_Dir;
        $lists['order'] = $state->filter_order;
        $lists['filter_state'] = JHtml::_('grid.state', $state->filter_state);
        $this->_buildListArray($lists, $state);
        $this->assignRef('lists',		$lists);
        $this->assignRef('items',		$items);
        $this->assignRef('pagination',	$pagination);

        $this->_buildToolbar();

        parent::display($tpl);
    }

    /**
     * Build all the lists items used in the form and store it into the array
     * @param  $lists
     * @return boolean
     */
    public function _buildListArray(&$lists, $state)
    {
        return true;
    }
    /**
     * Build the toolbar for view list
     */
    public function _buildToolbar()
    {
        $viewName = $this->getName();
        $controller = EShopInflector::singularize($this->getName());
        JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName)));

        $canDo	= EshopHelper::getActions($viewName);

        if ($canDo->get('core.delete'))
            JToolBarHelper::deleteList(JText::_($this->lang_prefix.'_DELETE_'.strtoupper($this->getName()).'_CONFIRM') , $controller.'.remove');
        if ($canDo->get('core.edit'))
            JToolBarHelper::editList($controller.'.edit');
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew($controller.'.add');
            //JToolBarHelper::custom( $controller.'.copy', 'copy.png', 'copy_f2.png', 'Copy', true );
        }
        if ($canDo->get('core.edit.state')) {
            JToolBarHelper::publishList($controller.'.publish');
            JToolBarHelper::unpublishList($controller.'.unpublish');
        }
    }
}
