<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2 Copyleft CSS / JS / IMAGES are Copyrighted Commercial
 */
sleep(2);
// Check to ensure this file is within the rest of the framework
if(!defined('_JEXEC')) define( '_JEXEC', 1 );
// do some form check before continuing
function yjsg_validate_data (&$array)
{
    if (is_array($array))
        foreach ($array as $key => $value)
            yjsg_validate_data($array[$key]);
    else
        $array = preg_replace("|([^\w\s\'])|i",'',$array);
}
yjsg_validate_data($_POST);
yjsg_validate_data($_GET);

// get the module name for base path
$yj_mod_name 	= basename(dirname(dirname(dirname(__FILE__))));
$yj_element 	= basename(dirname(__FILE__));
// load joomla framework
define( 'DS', DIRECTORY_SEPARATOR );
define('JPATH_BASE', str_replace("modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."elements".DIRECTORY_SEPARATOR.$yj_element,"",dirname(__FILE__)) );
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );
require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php' );
jimport('joomla.filesystem.file');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.methods' );
// get base vars
$mainframe 			=& JFactory::getApplication('administrator');
$lang 				=& JFactory::getLanguage();
$user 				=& JFactory::getUser();
$session		 	=& JFactory::getSession();
$default_lang 		= $lang->getDefault();
$yj_mod_name 		= basename(dirname(dirname(dirname(__FILE__))));
$yj_element 		= basename(dirname(__FILE__));
$baselink 			= str_replace("/elements/".$yj_element."","",JURI::base());
$mainframe->initialise();
$lang->load(''.$yj_mod_name.'', JPATH_SITE);
// joomla is on :)
$userGroups = $user->getAuthorisedGroups();
if ( in_array(8,$userGroups) ||  in_array(7,$userGroups) ||  in_array(6,$userGroups)){
	$isadmin = true;
}else{
	$isadmin = false;
}
	// Check for request forgeries
JRequest::checkToken() or jexit( 'Invalid Token' );
	// double check and stop if not admin :)
if($isadmin){

// joomla is on :)


$cssfile 		= JRequest::getVar('css_upload', null, 'files', 'array');
$cssname 		= JFile::makeSafe($cssfile['name']);
$source			= $cssfile['tmp_name'];
$destination 	= JPATH_ROOT."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR.$cssname; 
		// check  if input has value or exit
		if($source ==''){
			echo '<span class="error">Error:'.JText::_('CHOOSE_CSS').'</span>';
			exit;
		}
		
		// go on 
		if(!JFile::exists($destination)) {
				if ( strtolower(JFile::getExt($cssname) ) == 'css') {
				   if ( JFile::upload($source, $destination) ) {
					  //Success
					  echo '<span class="thnx">'.JText::_('CSS_UP').'</span>';
				   } else {
					  //Error message
					  echo '<span class="error">Error: '.JText::_('NO_UPLOAD').'</span>';
				   }
				} else {
				   //Wrong extension
				   echo '<span class="error">Error: '.JText::_('WRONG_EX').'</span>';
				}
		}else{
			echo '<span class="error">Error: '.JText::_('CSS_EXIST').'</span>';
		}


}
