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

class EShopView extends JViewLegacy
{
    public function __construct($config)
    {
        $paths =  array();
        $paths[] =  JPATH_COMPONENT.'/themes/default/views/'.$this->getName();
        $theme = EshopHelper::getConfigValue('theme');
        if ($theme != 'default')
        {
        	$paths[] =  JPATH_COMPONENT.'/themes/'.$theme.'/views/'.$this->getName();
        }
        $config['template_path'] = $paths;
        parent::__construct($config);
    }
}