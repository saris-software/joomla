<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );

class PhocaFaviconCpViewPhocaFavicon extends JViewLegacy
{
	protected $form;
	protected $t;

	public function display($tpl = null) {
		$this->t	= PhocaFaviconUtils::setVars('');
		JHTML::stylesheet( $this->t['s'] );
		$this->form		= $this->get('Form');
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
	
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocafaviconcp.php';
		$canDo	= PhocaFaviconHelperControlPanel::getActions($this->t);
	
		$text = JText::_( 'COM_PHOCAFAVICON_CREATE' );
		JToolBarHelper::title( JText::_( 'COM_PHOCAFAVICON_FAVICON' ).': <small><small>[ ' . $text.' ]</small></small>' , 'wand');
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::custom('phocafavicon.create', 'new.png', '','COM_PHOCAFAVICON_CREATE', false);
		}
		JToolBarHelper::cancel( 'phocafavicon.cancel', 'COM_PHOCAFAVICON_CLOSE' );
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocafavicon', true );
	}
}
?>
