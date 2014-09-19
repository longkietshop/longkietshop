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

echo $this->loadTemplate('category');
if (count($this->subCategories))
{
	echo $this->loadTemplate('subcategories');
}
echo $this->loadTemplate('products');