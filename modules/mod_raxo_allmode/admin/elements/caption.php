<?php
/**
 * =============================================================
 * @package		RAXO All-mode PRO J3.x
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2016 RAXO Group
 * @link		http://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


defined('_JEXEC') or die;

class JFormFieldCaption extends JFormField
{
	protected $type = 'Caption';

	protected function getInput()
	{
		return null;
	}

	protected function getLabel()
	{
		$text = $this->element['label'] ? (string) $this->element['label'] : '';
		return '<h3 class="caption"><span>'. JText::_($text) .'</span></h3>';
	}
}