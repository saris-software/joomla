<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2 Copyleft CSS / JS / IMAGES are Copyrighted Commercial
 */
defined('_JEXEC') or die('Restricted access');
		// path to css directory
		$path		= JPATH_ROOT.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl";
		$filter		= '.php';// css files only
		$exclude	= '';
		$stripExt	= '';
		$files		= JFolder::folders($path, $filter);


		$path		= JPATH_ROOT.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl";
		$filter		= '';
		$exclude	= '';
		$folders	= JFolder::folders($path, $filter);

		$options = array ();
		$options[] = JHTML::_('select.option', '', '- '.JText::_('Select Template').' -');

		if ( is_array($folders) )
		{
			foreach ($folders as $key=>$folder)
			{
				
				if ($exclude)
				{
					if (preg_match( chr( 1 ) . $exclude . chr( 1 ), $folder ))
					{
						continue;
					}
				}
				if ($stripExt)
				{
					$folder = JFile::stripExt( $folder );
				}
				$options[] = JHTML::_('select.option', $folder.DIRECTORY_SEPARATOR.'default.php', $folder);
			}
		} ;



////////////////////////////////////////////////////////////////////
		if(isset($_POST['yjme_selected'])){
			global $yjme_name_only;
			$yjme_name_only 			= $_POST['yjme_selected'];
		}elseif(isset($_POST['yjme_file_content'])){
			global $yjme_name_only;
			$yjme_name_only 			= $_POST['yjme_to_save'];
		}else{
			global $yjme_name_only;
			$yjme_name_only 			= "Default".DIRECTORY_SEPARATOR."default.php";
		}
		global $yjme_file_dir;
		$yjme_file_dir 			= JPATH_ROOT."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl".DIRECTORY_SEPARATOR;
		global $yjme_file;
		$yjme_file 				= $yjme_file_dir.$yjme_name_only;
	function getedit_file(){
		global $mainframe;
		global $yj_mod_name;
		global $yjme_file_dir;
		global $yjme_name_only;
		global $yjme_file;	
		
		$yjme_file 				= $yjme_file_dir.$yjme_name_only;
		 
		//@chmod($yjme_file,0777);
		$file_content = JFile::read($yjme_file);
		global $return;
		$return = array("yjme_file_name" => $yjme_file, "file_content" => $file_content);
		
		
		global $yjme_file_yjc;
		global $yjme_file_yjn;
		$yjme_file_yjc 			= stripslashes($return['file_content']);
		$yjme_file_yjn 			= $return['yjme_file_name'];
	
		return $return;
		
	}	global $return; 

		function save_file(){
		sleep(2);
		global $yj_mod_name;
		$yjme_name_only 			= JArrayHelper::getValue( $_REQUEST, 'yjme_to_save', '' );
		$yjme_file_dir 			= JPATH_ROOT.DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$yj_mod_name.DIRECTORY_SEPARATOR."tmpl".DIRECTORY_SEPARATOR;
		$yjme_file 				= $yjme_file_dir.$yjme_name_only;

		$yjme_file_content	= JArrayHelper::getValue( $_REQUEST, 'yjme_file_content', '' );
		$yjme_file_edited	= $yjme_file;			

		JFilterOutput::objectHTMLSafe( $yjme_file_edited );
		global $yjme_file_yjc;
		global $yjme_file_yjn;
		$yjme_file_yjc 			= stripslashes($_POST['yjme_file_content']);
		$yjme_file_yjn 			= $yjme_file;
		 
		if($yjme_file_content != ''){
			JFile::write($yjme_file_edited,stripslashes($yjme_file_content));
			global $send_msg;
			$send_msg = '<span class="file_saved_txt">'. JText::_('SAVED_MSG') .'</span>';
		}
		
		return true;		
	}global $send_msg; 
///////////////////////////////////////////////////////////////////

		if(!isset($_POST['yjme_file_content']) && !isset($_POST['yjme_selected'])){
			$yjme_file_yjc 			= JText::_('SELECT_TMPL_FILE');
			$yjme_file_yjn 			= '';
		}
if (isset($_POST['yjme_selected']) || isset($_POST['yjme_file_content'])){
	$current_file_editing = $yjme_name_only;
}else{
	$current_file_editing = JText::_('CHOOSE_FILE');
}
$top_select = '
<div id="top_select">
	<form method="post">
	'.JHTML::_('select.genericlist',  $options, 'yjme_selected','onchange="this.form.submit()" class="inputbox"', 'value', 'text').'
	</form>
	<span class="current_edit">
		You are editing<span class="current_name">'.$current_file_editing.'</span>
	</span>
</div>
';
// fire edit on selection
if (isset($_POST['yjme_selected'])){
	getedit_file();
}
// fire save on save
if (isset($_POST['yjme_file_content'])){
	save_file();
}

?>
