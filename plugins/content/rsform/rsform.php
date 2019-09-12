<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class plgContentRSForm extends JPlugin
{
	// Check if RSForm! Pro can be loaded
	protected function canRun() {
		if (class_exists('RSFormProHelper')) {
			return true;
		}
		
		$helper = JPATH_ADMINISTRATOR.'/components/com_rsform/helpers/rsform.php';
		if (file_exists($helper)) {
			require_once($helper);
			return true;
		}
		
		return false;
	}
	
	// Joomla! Triggers - onContentBeforeDisplay()
	public function onContentBeforeDisplay($context, &$row, &$params, $page=0) {
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}
		
		if (is_object($row) && isset($row->text)) {
			$this->_addForm($row->text);
		} elseif (is_string($row)) {
			$this->_addForm($row);
		}
	}
	
	// Joomla! Triggers - onContentPrepare()
	public function onContentPrepare($context, &$row, &$params, $page=0) {
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		if (is_object($row) && isset($row->text)) {
			$this->_addForm($row->text);
		} elseif (is_string($row)) {
			$this->_addForm($row);
		}
	}
	
	// Syntax replacement function
	protected function _addForm(&$text) {		
		// Performance check
		if (strpos($text, '{rsform ') !== false) {
			if (!$this->canRun()) {
				return false;
			}

			// Expression to search for
			$pattern = '#\{rsform ([0-9]+)(.*?)?\}#i';
			// Found matches
			if (preg_match_all($pattern, $text, $matches)) {
				// No replacement when we're not dealing with HTML
				if (JFactory::getDocument()->getType() != 'html') {
					$text = preg_replace($pattern, '', $text);
					return true;
				}
				
				// Load language
				JFactory::getLanguage()->load('com_rsform', JPATH_SITE);
				
				// Disable caching
				$cache = JFactory::getCache('com_content');
				$cache->setCaching(false);
				
				foreach ($matches[0] as $i => $fullMatch)
				{
					$attributes = trim($matches[2][$i]);
					if (preg_match_all('#[a-z0-9_\-]+=".*?"#i', $attributes, $attributesMatches))
					{
						$data = array();
						
						foreach ($attributesMatches[0] as $pair)
						{
							list($attribute, $value) = explode('=', $pair, 2);
							
							$attribute  = trim(html_entity_decode($attribute));
							$value 		= html_entity_decode(trim($value, '"'));
							
							if (isset($data[$attribute]))
							{
								if (!is_array($data[$attribute]))
								{
									$data[$attribute] = (array) $data[$attribute];
								}
								
								$data[$attribute][] = $value;
							}
							else
							{
								$data[$attribute] = $value;
							}
						}
						
						if ($data)
						{
							JFactory::getApplication()->input->get->set('form', $data);
						}
					}
					
					$formId = $matches[1][$i];
					$text = str_replace($fullMatch, RSFormProHelper::displayForm($formId, true), $text);
				}
			}
		}
	}
}