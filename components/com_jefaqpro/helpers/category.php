<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.categories');

/**
 * JE FAQPro Component Category Tree
 */
class jefaqproCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__jefaqpro_faq';
		$options['extension'] = 'com_jefaqpro';
		$options['published'] = 'published';
		parent::__construct($options);
	}
}