<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * RSEvents!Pro PDF system plugin
 */
class plgSystemRSEproPDF extends JPlugin
{
	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
	}
	
	protected function canRun() {
		if (file_exists(JPATH_SITE.'/components/com_rseventspro/rseventspro.php')) {
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
			JFactory::getLanguage()->load('plg_system_rsepropdf',JPATH_ADMINISTRATOR);
			return true;
		}
		
		return false;
	}
	
	public function rsepro_activationEmail($vars) {
		if (!$this->canRun()) return;
		
		jimport('joomla.filesystem.file');
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/pdf.php';
		
		$layout		= $vars['layout'];
		$name		= $vars['name'];
		$eventName	= $this->getEventName($vars['id']);
		$path		= $this->_getTmp().'/'.$this->_createId('activation').'/'.$this->_getFilename($eventName.' - '.$name);
		$layout 	= $this->icons($vars['id'], $layout);
		
		// Create the PDF output buffer
		$pdf	= new RSEventsProPDF();
		$buffer = $pdf->write($layout);
		
		// Delete small icon
		if ($this->small && file_exists($this->small)) {
			JFile::delete($this->small);
		}
		
		// Delete big icon
		if ($this->big && file_exists($this->big)) {
			JFile::delete($this->big);
		}
		
		// Write the PDF buffer
		if (JFile::write($path, $buffer)) {
			$vars['attachment'][] = $path;
		}
	}
	
	public function rsepro_activationEmailCleanup($vars) {
		if (!$this->canRun()) return;
		
		$folder = $this->_getTmp().'/'.$this->_createId('activation');
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
			
		if (is_dir($folder)) {
			JFolder::delete($folder);
		}
	}
	
	protected function _getFilename($filename) {
		$filename = str_replace(array('\\', '/'), '', $filename);
		if (empty($filename))
			$filename = 'attachment';
		
		return $filename.'.pdf';
	}
	
	protected function _createId($suffix) {
		static $hash;
		if (!$hash) {
			$session = JFactory::getSession();
			$hash = md5($session->getId());
		}
		
		return $hash.'_'.$suffix;
	}
	
	protected function _getTmp() {
		static $tmp;
		if (!$tmp) {
			$config = JFactory::getConfig();
			$tmp = $config->get('tmp_path');
		}
		
		return $tmp;
	}
	
	protected function getEventName($id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		// Get ticket details
		$query->clear()
			->select($db->qn('name'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	protected function icons($id, $layout) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		// Get ticket details
		$query->clear()
			->select($db->qn('icon'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $id);
		
		$db->setQuery($query);
		$icon = $db->loadResult();
		
		$small	= '';
		$big	= '';
		$normal	= '';
		
		if (strpos($layout,'{EventIconPdf}') !== FALSE) {
			if (!empty($icon)) {
				$normal = JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$icon;
			}
			
			$layout = str_replace('{EventIconPdf}', $normal, $layout);
		}
		
		if (strpos($layout,'{EventIconSmallPdf}') !== FALSE) {
			$query->clear()
				->select($db->qn('name'))->select($db->qn('icon'))->select($db->qn('properties'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			if ($event = $db->loadObject()) {
				$cache = JFactory::getCache('com_rseventspro');
				$cache->setCaching(true);
				if ($data = $cache->get(array('rseventsproHelper', 'createImage'), array($event, rseventsproHelper::getConfig('icon_small_width','int')))) {
					$small = JPATH_SITE.'/components/com_rseventspro/assets/barcode/'.md5('small'.$id).'.'.$data['ext'];
					JFile::write($small,$data['content']);
				}
			}
			
			$layout = str_replace('{EventIconSmallPdf}', $small, $layout);
		}
		
		if (strpos($layout,'{EventIconBigPdf}') !== FALSE) {
			$query->clear()
				->select($db->qn('name'))->select($db->qn('icon'))->select($db->qn('properties'))
				->from($db->qn('#__rseventspro_events'))
				->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			if ($event = $db->loadObject()) {
				$cache = JFactory::getCache('com_rseventspro');
				$cache->setCaching(true);
				if ($data = $cache->get(array('rseventsproHelper', 'createImage'), array($event, rseventsproHelper::getConfig('icon_big_width','int')))) {
					$big = JPATH_SITE.'/components/com_rseventspro/assets/barcode/'.md5('big'.$id).'.'.$data['ext'];
					JFile::write($big,$data['content']);
				}
			}
			
			$layout = str_replace('{EventIconBigPdf}', $big, $layout);
		}
		
		$this->small = $small;
		$this->big = $big;
		
		return $layout;
	}
}