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

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldInputboxes extends JFormField
{
	protected $type = 'Inputboxes';
	protected $forceMultiple = true;

	protected function getInput()
	{
		$html = array();
		$class = $this->element['class'] ? ' class="inputboxes input-append '.(string) $this->element['class'].'"' : ' class="inputboxes input-append"';
		$options = $this->getOptions();

		$html[] = '<fieldset id="'.$this->id.'"'.$class.'>';

		foreach ($options as $i => $option) {
			$value		= isset($this->value[$i]) ? htmlspecialchars($this->value[$i], ENT_COMPAT, 'UTF-8') : $option->value;
			$class		= !empty($option->class) ? ' class="'.$option->class.'"' : '';

			$html[] = '<input type="text" id="'.$this->id.$i.'" name="'.$this->name.'" value="'.$value.'"'.$class.'/>';
			$html[] = !empty($option->dimension) ? '<span class="add-on">'.JText::_($option->dimension).'</span>' : '';
		}

		$html[] = '</fieldset>';

		return implode($html);
	}

	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option) {
			if ($option->getName() != 'option') {
				continue;
			}

			$tmp = JHtml::_('select.option', (string) $option['value'], trim((string) $option), 'value', 'text', ((string) $option['disabled']=='true'));
			$tmp->class = (string) $option['class'];
			$tmp->dimension = (string) $option['dimension'];

			$options[] = $tmp;
		}

		reset($options);
		return $options;
	}
}