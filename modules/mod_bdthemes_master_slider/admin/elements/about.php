<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.version');


class JFormFieldAbout extends JFormField {
	protected $type = 'About';

	protected function getInput() {
		$version = new JVersion;
		$ver = $version->getShortVersion();
		
		return '<div id="bdt_about_us" data-jversion="'.$ver.'">' . JText::_('MOD_BDTHEMES_MASTER_SLIDER_ABOUT_US_CONTENT') . '</div>';
	}
}
