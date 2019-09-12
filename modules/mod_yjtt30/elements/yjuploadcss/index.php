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
?>
<?php if($isadmin){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $default_lang ?>" lang="<?php echo $default_lang ?>" >
<head>
<title>Upload CSS file</title>
<link rel="stylesheet" href="<?php echo $baselink ?>elements/css/stylesheet.css" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.2/mootools-yui-compressed.js" type="text/javascript"></script>
<script src="<?php echo $baselink ?>elements/src/iFrameFormRequest.js" type="text/javascript"></script>
<script src="<?php echo $baselink ?>elements/src/input_file_modifier.js" type="text/javascript"></script>
<script type="text/javascript">
window.addEvent('domready',function(){
	var iFrame = new iFrameFormRequest('yjupload',{
		onRequest: function(){
			document.id('response').addClass('loading');
			document.id('responsein').addClass('spin');
			document.id('response').set('text','Uploading please wait...');
		},
		onComplete: function(response){
			document.id('response').removeAttribute('class');
			document.id('responsein').removeAttribute('class');
			document.id('response').set('html',response);
		}
	});

});

</script>
</head>
<body class="create">
	<div id="createholder" class="css_upload">
	<div class="inside">
		<form action="<?php echo $yj_element ?>.php" method="post" enctype="multipart/form-data" id="yjupload">
			<h3><?php echo JText::_('SELECT_CSS') ?></h3>
			<div id="typeholder">
				<input type="file" name="css_upload" id="css_upload" />
				<span class="button"><input id="submit" type="submit" value="Upload CSS" /></span>
			</div>
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
			<div id="response"></div>
			<div id="responsein"></div>
		</div>
	</div>
</body>
</html>
<?php  }else{ echo 'Restricted accsess. Please login as administrator';} ?>