<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2010 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldYjholder extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$type = 'Yjholder';

	public function getInput()
	{

		// Output
		$getname = str_replace(array('jform[params]', '[', ']'),'',$this->name);
		return '
		<div id="'.$getname.'" class="yj_holder">

		</div>
		';
	}

}
