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

class JFormFieldDescription extends JFormField
{
	protected $type = 'Description';

	protected function getInput()
	{
		return null;
	}

	protected function getLabel()
	{
		return '<div class="description">'. JText::_('MOD_RAXO_ALLMODE_DESCRIPTION') .'</div>';
	}
}
