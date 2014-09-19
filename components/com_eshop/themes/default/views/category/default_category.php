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
?>
<h1><?php echo $this->category->category_name; ?></h1>
<div class="row-fluid">
	<div class="span4">
		<img class="img-polaroid" src="<?php echo $this->category->image; ?>" />
	</div>
	<div class="span8"><?php echo $this->category->category_desc; ?></div>		
</div>
<hr />