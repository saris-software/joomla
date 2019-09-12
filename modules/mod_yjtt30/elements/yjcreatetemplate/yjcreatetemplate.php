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
	
	if(JRequest::getVar('foldername')!=''){
			 $input_text= str_replace(" ","",$_POST['foldername']);
			  $destpath = JPATH_ROOT.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl".DIRECTORY_SEPARATOR.$input_text; 
			  JFolder::create($destpath);
			  $empty="";
			  
			 $filesrc		 		= JPATH_ROOT.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl".DIRECTORY_SEPARATOR."Default".DIRECTORY_SEPARATOR;
			 $filename1 			= 'default.php';
			 $filename2 			= 'index.html';
			 $filefull_1			= $filesrc.$filename1;
			 $filefull_2			= $filesrc.$filename2;
			 
			if (!is_readable($filesrc) || !is_writable($filesrc)) {
					JText::_( 'NOT_WRITABLE' );
				 exit;
			}
			
			if (!JFile::exists($destpath.DIRECTORY_SEPARATOR.$filename1) || !JFile::exists($destpath.DIRECTORY_SEPARATOR.$filename2)){
				
				JFile::copy($filefull_1, $destpath.DIRECTORY_SEPARATOR.$filename1 );
				JFile::copy($filefull_2, $destpath.DIRECTORY_SEPARATOR.$filename2 );
				$app =& JFactory::getApplication();
				echo'<span class="thnx">';
				echo JText::_( 'TEMPLATE_CREATED1' ).'&nbsp;&nbsp;';
				echo '<span class="tmname">'.$input_text.'&nbsp;&nbsp</span>';
				echo JText::_( 'TEMPLATE_CREATED2' );
				echo'</span>';
			}elseif (JFolder::exists($destpath)){
				echo'<span class="error">';
				echo $input_text.'&nbsp;'.JText::_( 'TEMPLATE_EXISTS' );
				echo'</span>';
			}
	}
}

?>
