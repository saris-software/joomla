<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2 Copyleft CSS / JS / IMAGES are Copyrighted Commercial
 */

// Check to ensure this file is within the rest of the framework
if(!defined('_JEXEC')) define( '_JEXEC', 1 );
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
global $yj_mod_name;
$yj_mod_name 		= basename(dirname(dirname(dirname(__FILE__))));
$yj_element 		= basename(dirname(__FILE__));
$baselink 			= str_replace("/elements/".$yj_element."","",JURI::base());
$mainframe->initialise();
$lang->load(''.$yj_mod_name.'', JPATH_SITE);
jimport('joomla.client.helper');

// joomla is on :)
$userGroups = $user->getAuthorisedGroups();
if ( in_array(8,$userGroups) ||  in_array(7,$userGroups) ||  in_array(6,$userGroups)){
	$isadmin = true;
}else{
	$isadmin = false;
}
include("yjeditcss.php");
?>
<?php if($isadmin){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $default_lang ?>" lang="<?php echo $default_lang ?>" >
<head>
<title>Edit CSS file</title>
<link rel="stylesheet" href="<?php echo $baselink ?>elements/css/stylesheet.css" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.2/mootools-yui-compressed.js" type="text/javascript"></script>
<script src="<?php echo $baselink ?>elements/cmc/js/codemirror.js"></script>
<script type="text/javascript">
window.addEvent('domready', function() {
				var save_button =$$('.save_button');
				save_button.addEvent('click',function(e) {
				save_button.set('yjme_saveresponse', {duration: 500, transition: 'bounce:out'});
				$('yjme_saveresponse').morph({height: 500, width: 700});
				$('yjme_spin').set('text', 'Saving your CSS file please wait...');
			});

});
</script>
</head>
<body class="create">
<div id="yjme_saveresponse"><div id="yjme_spin"></div></div>
<div id="yjme_edit">
<?php echo $top_select ?>
    <fieldset id="yjme_fileview">
        <legend><?php echo JText::_( 'EDIT_CSS_FILE' ). $send_msg; ?></legend>
<?php
		$writeable   = '<b><font color="green">'.$yjme_name_only .'&nbsp;&nbsp;'. JText::_( 'WRITABLE' ) .'</font></b>';
		$unwriteable = '<b><font color="red">'.$yjme_name_only .'&nbsp;&nbsp;'. JText::_( 'UNWRITABLE' ) .'</font></b>';
if (isset($_POST['yjme_selected']) || isset($_POST['yjme_file_content'])){
		echo is_writable( $yjme_file_yjn) ? $writeable : $unwriteable;
}else{
	echo'<b><font color="green">Select file from the list-></font></b>';
}
		$disabled = is_writable( $yjme_file_yjn ) ? "" : "disabled=\"disabled\"";
?>         
	        <form name="yjme_save" id="yjme_save" method="post">
			<button type="submit" class="save_button top" <?php echo $disabled; ?>><?php echo JText::_( 'UPDATE_FILE' ); ?></button>      
            <textarea id="yjme_code" cols="120" rows="30" name="yjme_file_content" <?php echo $disabled; ?>><?php echo $yjme_file_yjc ?></textarea>
            <button type="submit" class="save_button" <?php echo $disabled; ?>><?php echo JText::_( 'UPDATE_FILE' ); ?></button>
            <?php echo JHTML::_( 'form.token' ); ?>
            <input type="hidden" name="yjme_to_save" value="<?php echo $yjme_name_only ?>" />
            </form>            
    </fieldset>   
<script>
	var editor = CodeMirror.fromTextArea('yjme_code', {
	height: "350px",
	parserfile: ["parsecss.js"],
	stylesheet: ["<?php echo $baselink ?>elements/cmc/css/csscolors.css"],
	path: "<?php echo $baselink ?>elements/cmc/js/"
 });
</script>
</div>
</body>
</html>
<?php }else{ echo 'Restricted accsess. Please login as administrator';} ?>