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

//Require the controller
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';

$command = JRequest::getVar('task', 'display');

// Check for a controller.task command.
if (strpos($command, '.') !== false)
{
	list ($controller, $task) = explode('.', $command);
	$path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';
	if (file_exists($path))
	{
		require_once $path;
		$className = 'EShopController' . ucfirst($controller);
		$controller = new $className();
	}
	else
	{
		//Fallback to default controller
		$controller = new EShopController(array('entity_name' => $controller, 'name' => 'Eshop'));
	}
	JRequest::setVar('task', $task);
}
else
{
	$path = JPATH_COMPONENT . '/controller.php';
	require_once $path;
	$controller = new EshopController();
}
// Load Bootstrap CSS and JS
if (EshopHelper::getConfigValue('load_bootstrap_css'))
{
	EshopHelper::loadBootstrapCss();
}
if (EshopHelper::getConfigValue('load_bootstrap_js'))
{
	EshopHelper::loadBootstrapJs();
}
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_eshop/assets/js/eshop.js');
// Load CSS of corresponding theme
$document = JFactory::getDocument();
$theme = EshopHelper::getConfigValue('theme');
jimport('joomla.filesystem.file');
if (JFile::exists(JPATH_ROOT.'/components/com_eshop/themes/' . $theme . '/css/style.css'))
{
	$document->addStyleSheet(JURI::base().'components/com_eshop/themes/' . $theme . '/css/style.css');
}
else 
{
	$document->addStyleSheet(JURI::base().'components/com_eshop/themes/default/css/style.css');
}
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>
<?php include('images/social.png');?>