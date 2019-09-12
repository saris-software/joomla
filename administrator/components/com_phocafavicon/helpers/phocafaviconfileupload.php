<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaFaviconFileUploadSingle
{
	public $returnUrl	= '';

	public function __construct() {}
	
	public function getSingleUploadHTML( $frontEnd = 0) {
		
		
		$html = '<input type="file" id="sfile-upload" name="Filedata" />'
		.'<button class="btn btn-primary" id="sfile-upload-submit"><i class="icon-upload icon-white"></i> '.JText::_('COM_PHOCAFAVICON_START_UPLOAD').'</button>'
		.'<input type="hidden" name="return-url" value="'. base64_encode($this->returnUrl).'" />';
		
		return $html;
		
	}
}

class PhocaFaviconFileUpload
{
	public static function realSingleUpload( $frontEnd = 0 ) {

		$app			= JFactory::getApplication();
		JRequest::checkToken( 'request' ) or jexit( 'ERROR: '. JTEXT::_('COM_PHOCAFAVICON_INVALID_TOKEN'));
		JResponse::allowCache(false);
		
		$path			= PhocaFaviconHelper::getPathSet();
		$file 			= JRequest::getVar( 'Filedata', '', 'files', 'array' );
		$folder			= JRequest::getVar( 'folder', '', '', 'path' );
		$format			= JRequest::getVar( 'format', 'html', '', 'cmd');
		$return			= JRequest::getVar( 'return-url', null, 'post', 'base64' );//includes field
		$viewBack		= JRequest::getVar( 'viewback', '', '', '' );
		$field			= JRequest::getVar( 'field' );
		$errUploadMsg	= '';
		$folderUrl 		= $folder;
		$tabUrl			= '';
		$component		= JRequest::getVar( 'option', '', '', 'string' );
		
		
	
		
		// In case no return value will be sent (should not happen)
		if ($component != '' && $frontEnd == 0) {
			$componentUrl 	= 'index.php?option='.$component;
		} else {
			$componentUrl	= 'index.php';
		}

		
		$ftp = JClientHelper::setCredentialsFromRequest('ftp');
		
		// Make the filename safe
		if (isset($file['name'])) {
			$file['name']	= JFile::makeSafe($file['name']);
		}
		
		
		if (isset($folder) && $folder != '') {
			$folder	= $folder . DS;
		}
		
		
		// All HTTP header will be overwritten with js message
		if (isset($file['name'])) {
			$filepath = JPath::clean($path['orig_abs_ds'].$folder.strtolower($file['name']));

			if (!PhocaFaviconFileUpload::canUpload( $file, $errUploadMsg, $frontEnd )) {
				
				if ($errUploadMsg == 'COM_PHOCAFAVICON_WARNING_FILE_TOOLARGE') {
					$errUploadMsg 	= JText::_($errUploadMsg) . ' ('.PhocaFaviconHelper::getFileSizeReadable($file['size']).')';
				} else if ($errUploadMsg == 'COM_PHOCAFAVICON_WARNING_FILE_TOOLARGE_RESOLUTION') {
					$imgSize		= PhocaFaviconHelper::getImageSize($file['tmp_name'], 0, 1);
					$errUploadMsg 	= JText::_($errUploadMsg) . ' ('.(int)$imgSize[0].' x '.(int)$imgSize[1].' px)';
				} else {
					$errUploadMsg 	= JText::_($errUploadMsg);
				}
			
			
				if ($return) {
					$app->redirect(base64_decode($return).'&folder='.$folderUrl, $errUploadMsg, 'error');
					exit;
				} else {
					$app->redirect($componentUrl, $errUploadMsg, 'error');
					exit;
				}
			}

			if (JFile::exists($filepath)) {
				if ($return) {
					$app->redirect(base64_decode($return).'&folder='.$folderUrl, JText::_('COM_PHOCAFAVICON_FILE_ALREADY_EXISTS'), 'error');
					exit;
				} else {
					$app->redirect($componentUrl, JText::_('COM_PHOCAFAVICON_FILE_ALREADY_EXISTS'), 'error');
					exit;
				}
			}

			if (!JFile::upload($file['tmp_name'], $filepath, false, true)) {
				if ($return) {
					$app->redirect(base64_decode($return).'&folder='.$folderUrl, JText::_('COM_PHOCAFAVICON_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');
					exit;
				} else {
					$app->redirect($componentUrl, JText::_('COM_PHOCAFAVICON_ERROR_UNABLE_TO_UPLOAD_FILE'), 'error');
					exit;
				}
			} else {
			
				if ((int)$frontEnd > 0) {
					return $file['name'];
				}
			
				if ($return) {
					$app->redirect(base64_decode($return).'&folder='.$folderUrl, JText::_('COM_PHOCAFAVICON_SUCCESS_FILE_UPLOAD'));
					exit;
				} else {
					$app->redirect($componentUrl, JText::_('COM_PHOCAFAVICON_SUCCESS_FILE_UPLOAD'));
					exit;
				}
			}
		} else {
			$msg = JText::_('COM_PHOCAFAVICON_ERROR_UNABLE_TO_UPLOAD_FILE');
			if ($return) {
				$app->redirect(base64_decode($return).'&folder='.$folderUrl, $msg);
				exit;
			} else {
				switch ($viewBack) {
					case 'phocafaviconi':
						$app->redirect('index.php?option=com_phocafavicon&view=phocafaviconi&tmpl=component'.$tabUrl.'&folder='.$folder.'&field='.$field, $msg, 'error');
						exit;
					break;
					
					default:
						$app->redirect('index.php?option=com_phocafavicon', $msg, 'error');
						exit;
					break;
				
				}
			}
		}
		
	}


/**
	 * can Upload
	 *
	 * @param array $file
	 * @param string $errorUploadMsg
	 * @param int $frontEnd - if it is called from frontend or backend (1  - category view, 2 user control panel)
	 * @param boolean $chunkMethod - if chunk method is used (multiple upload) then there are special rules
	 * @param string $realSize - if chunk method is used we get info about real size of file (not only the part)
	 * @return boolean True on success
	 * @since 1.5
	 */

	
	public static function canUpload( $file, &$errUploadMsg, $frontEnd = 0, $chunkEnabled = 0, $realSize = 0 ) {
		
		$params 	= JComponentHelper::getParams( 'com_phocafavicon' );
		$paramsL 	= array();
		$paramsL['upload_extensions'] 	= 'gif,jpg,png,jpeg';
		$paramsL['image_extensions'] 	= 'gif,jpg,png,jpeg';
		$paramsL['upload_mime']			= 'image/jpeg,image/gif,image/png';
		$paramsL['upload_mime_illegal']	='application/x-shockwave-flash,application/msword,application/excel,application/pdf,application/powerpoint,text/plain,application/x-zip,text/html';
		
		// The file doesn't exist
		if(empty($file['name'])) {
			$errUploadMsg = 'COM_PHOCAFAVICON_ERROR_UNABLE_TO_UPLOAD_FILE';
			return false;
		}

		// Not safe file
		jimport('joomla.filesystem.file');
		if ($file['name'] !== JFile::makesafe($file['name'])) {
			$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_FILENAME';
			return false;
		}

		$format = strtolower(JFile::getExt($file['name']));

		// Allowable extension
		$allowable = explode( ',', $paramsL['upload_extensions']);
		if (!in_array($format, $allowable)) {
			$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_FILETYPE';
			return false;
		}
		
		// 'COM_PHOCAFAVICON_MAX_RESOLUTION'
		$imgSize		= PhocaFaviconHelper::getImageSize($file['tmp_name'], 0, 1);
		$maxResWidth 	= $params->get( 'upload_maxres_width', 3072 );
		$maxResHeight 	= $params->get( 'upload_maxres_height', 2304 );
		if (((int)$maxResWidth > 0 && (int)$maxResHeight > 0) 
		&& ((int)$imgSize[0] > (int)$maxResWidth || (int)$imgSize[1] > (int)$maxResHeight)) {
			$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_FILE_TOOLARGE_RESOLUTION';
			return false;
		}
		
		
		// Max size of image
		// If chunk method is used, we need to get computed size
		$maxSize = $params->get( 'upload_maxsize', 3145728 );
		if ($chunkEnabled == 1) {
			if ((int)$maxSize > 0 && (int)$realSize > (int)$maxSize) {
				$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_FILE_TOOLARGE';
				return false;
			}
		} else {
			if ((int)$maxSize > 0 && (int)$file['size'] > (int)$maxSize) {
				$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_FILE_TOOLARGE';
				return false;
			}
		}

		$user = JFactory::getUser();
		$imginfo = null;
		
		
		// Image check
		$images = explode( ',', $paramsL['image_extensions']);
		if(in_array($format, $images)) { // if its an image run it through getimagesize
			if ($chunkEnabled != 1) {
				if(($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
					$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_INVALIDIMG';
					return false;
				}
			}
		} else if(!in_array($format, $images)) {
			// if its not an image...and we're not ignoring it
			$allowed_mime = explode(',', $paramsL['upload_mime']);
			$illegal_mime = explode(',', $paramsL['upload_mime_illegal']);
			if(function_exists('finfo_open')) {
				// We have fileinfo
				$finfo = finfo_open(FILEINFO_MIME);
				$type = finfo_file($finfo, $file['tmp_name']);
				if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
					$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_INVALIDMIME';
					return false;
				}
				finfo_close($finfo);
			} else if(function_exists('mime_content_type')) {
				// we have mime magic
				$type = mime_content_type($file['tmp_name']);
				if(strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
					$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_INVALIDMIME';
					return false;
				}
			}/* else if(!$user->authorize( 'login', 'administrator' )) {
				$errUploadMsg =  = 'WARNNOTADMIN';
				return false;
			}*/
		}
		
		// XSS Check
		$xss_check =  JFile::read($file['tmp_name'],false,256);
		$html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
		foreach($html_tags as $tag) {
			// A tag is '<tagname ', so we need to add < and a space or '<tagname>'
			if(stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
				$errUploadMsg = 'COM_PHOCAFAVICON_WARNING_IEXSS';
				return false;
			}
		}
		return true;
	}

}
?>