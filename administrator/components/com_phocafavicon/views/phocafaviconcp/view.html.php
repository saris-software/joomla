<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

class PhocaFaviconCpViewPhocaFaviconCp extends JViewLegacy
{
	protected $t;
	
	public function display($tpl = null) {
		
		$this->t	= PhocaFaviconUtils::setVars('cp');
		$this->views= array(
		''			=> $this->t['l'] . '_CREATE_FAVICON',
		'in'		=> $this->t['l'] . '_INFO'
		);
		
		JHTML::stylesheet( $this->t['s'] );
		JHTML::_('behavior.tooltip');
		$this->t['version'] = PhocaFaviconHelper::getPhocaVersion('com_phocafavicon');

		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	protected function addToolbar() {
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconcp.php';

		$state	= $this->get('State');
		$canDo	= PhocaFaviconHelperControlPanel::getActions($this->t);
		JToolBarHelper::title( JText::_( 'COM_PHOCAFAVICON_PF_CONTROL_PANEL' ), 'home-2 cpanel' );
		
		$bar = JToolBar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocafavicon" class="btn btn-small"><i class="icon-home-2" title="'.JText::_('COM_PHOCAGALLERY_CONTROL_PANEL').'"></i> '.JText::_('COM_PHOCAFAVICON_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_phocafavicon');
			JToolBarHelper::divider();
		}
		
		JToolBarHelper::help( 'screen.phocafavicon', true );
	}
}
?>