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
jimport( 'joomla.application.component.controller' );
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaFaviconHelper
{

	public static function getPathSet() {
		$path['orig_abs_ds'] 	= JPATH_ROOT . DS . 'images' . DS . 'phocafavicon' . DS ;
		$path['orig_abs'] 		= JPATH_ROOT . DS . 'images' . DS . 'phocafavicon' ;
		$path['tmpl_abs_ds'] 	= JPATH_ROOT . DS . 'templates' . DS;
		$path['orig_rel_ds'] 	= '../' . "images/phocafavicon/";
		
		return $path;
	}

	public static function getFileResize($size='all') {
		$large_width	= 640;
		$large_height	= 480;
		$medium_width	= 100;
		$medium_height	= 100;
		$small_width	= 16;//favicon using
		$small_height	= 16;//favicon using
		
		switch ($size) {			
			case 'large':
			$file_resize['width']	=	$large_width;
			$file_resize['height']	=	$large_height;
			break;
			
			case 'medium':
			$file_resize['width']	=	$medium_width;
			$file_resize['height']	=	$medium_height;
			break;
			
			case 'small':
			$file_resize['width']	=	$small_width;
			$file_resize['height']	=	$small_height;
			break;
			
			
			default:
			case 'all':
			$file_resize['smallwidth']	=	$small_width;
			$file_resize['smallheight']	=	$small_height;
			$file_resize['mediumwidth']	=	$medium_width;
			$file_resize['mediumheight']=	$medium_height;
			$file_resize['largewidth']	=	$large_width;
			$file_resize['largeheight']	=	$large_height;
			break;			
		}
		return $file_resize;
	}
	

	public static function getTemplateFolders() {
		$folders 	= array ();
		$path 		= PhocaFaviconHelper::getPathSet();
		$folder_list 	= JFolder::folders($path['tmpl_abs_ds'], '', false, false, array(0 => 'system'));
	
		if ($folder_list !== false)
		{
			foreach ($folder_list as $folder)
			{
				$tmp 							= new JObject();
				$tmp->name 						= basename($folder);
				$tmp->path_with_name 			= str_replace(DS, '/', JPath::clean($path['tmpl_abs_ds'] . DS . $folder));
				$folders[] = $tmp;
			}
		}
		return $folders;
	}
		
	/*
	 * For ICO only
	 */
	public static function getOrCreateThumbnailIco(&$errorMsg, $orig_path, $file_no, $template, $refresh_url, $small=0)
	{
		
		$path 									= PhocaFaviconHelper::getPathSet();
		$file['name']							= PhocaFaviconHelper::getTitleFromFilenameWithExt($file_no);
		$file['path_with_name']					= str_replace(DS, '/', JPath::clean($orig_path.DS.$file_no));
		$file['path_without_name_no'] 			= str_replace($file['name'], '', $file['path_with_name']);
		$file['path_without_name_thumbs_no'] 	= str_replace($file['name'], '', $file['path_with_name'] .'thumbs');
		
		$ext = strtolower(JFile::getExt($file['name']));
		
		switch ($ext) {
			case 'jpg':
			case 'png':
			case 'gif':
			case 'jpeg':

			//Get File thumbnails name
			$thumbnail_file_s 				= PhocaFaviconHelper::getThumbnailName ($file_no, 'small');
			$file['thumb_name_s_no_abs'] 	= $thumbnail_file_s['abs'];
			$file['thumb_name_s_no_rel'] 	= $thumbnail_file_s['rel'];
			
			PhocaFaviconHelper::createFolderThumbnail($file['path_without_name_no'], $file['path_without_name_thumbs_no'] . '/' );
			
			//Create thumbnail if not exists
			if (JFolder::exists($file['path_without_name_thumbs_no'])) {				
				
				//There are a lot of photos, please create thumbnails
				$creating = 0;
				
				// --- ICON GENERATING ------------------------------------------------------------------------------------
				//Small thumbnail
				//Reade to icon making
				if ($small == 1)
				{
					$creating = PhocaFaviconHelper::createFileThumbnail($errorMsg, $file['path_with_name'], $file['thumb_name_s_no_abs'], 'small');
					if ($creating == 1 || $creating == 3)//thumbnail now created or thumbnail exists now
					{	
						$file_favicon 	= str_replace(DS, '/', JPath::clean($path['tmpl_abs_ds']. DS .$template . DS. 'favicon.ico'));
						$file_resize	= PhocaFaviconHelper::getFileResize('favicon');
		
						if (JFile::exists($file['thumb_name_s_no_abs'])) {
							if (JFile::exists($file_favicon))//if favicon exists, rename it to bak file
							{
								if (JFile::copy( $file_favicon, $file_favicon . '.bak', '' )) {
									$icon_data = PhocaFaviconIcoHelper::createIcoFile($file['thumb_name_s_no_abs'], $file_favicon);
									if ($icon_data)
									{
										JFile::write( $file_favicon, $icon_data );
										$creating = 1;
									} 
									else 
									{
										$creating = 2;
									}
								} else {
									$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_CREATING_FAVICON_UNABLE_COPY_FAVICON');
									return false;
								}
							}
							else
							{
								$icon_data = PhocaFaviconIcoHelper::createIcoFile($file['thumb_name_s_no_abs'], $file_favicon);
								if ($icon_data)
								{
									JFile::write( $file_favicon, $icon_data );
									$creating = 1;
								} 
								else 
								{
									$creating = 2;
								}
							}
						} else {
							$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_CREATING_FAVICON_THUMB_NOT_EXISTS');
							return false;
						}
					}
				}
				// --- END ICON GENERATING --------------------------------------------------------------------------------
				
				
				//Refresh the site after creating thumbnails - we can do e.g. 100 thumbanails
				// Thumbnail was created
				if ($creating == 1) {
				
					echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
					echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
					echo '<head>'. "\n";
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
					echo '<title>'.JText::_( 'COM_PHOCAFAVICON_FAVICON_CREATING').'</title>'. "\n";
					echo '<link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocafavicon/assets/phocafavicon.css" type="text/css" />';
					echo '</head>'. "\n";
					echo '<body>'. "\n";
				
					echo '<center>';
					echo '<div style="width:70%;border:5px solid #FFE699; margin-top:30px;font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px">'
						.'<span>'. JText::_( 'COM_PHOCAFAVICON_CREATING_FAVICON_WAIT' ) . '</span>';
					echo '<p>' .JText::_( 'COM_PHOCAFAVICON_CREATING_FAVICON_FROM_FILE' ) 
						.' <span style="color:#0066cc"> '. $file['name'] . '</span>' 
						.' ... <span style="color:#009900">'.JText::_('COM_PHOCAFAVICON_OK').'</span></p></center></div></body></html>';				
					//$creating = 0;
					echo '<meta http-equiv="refresh" content="1;url='.$refresh_url.'" />';
					exit;			
				}
				// Thumbnail was not created - error - stop it all
				if ($creating == 2)
				{
				
					echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
					echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
					echo '<head>'. "\n";
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
					echo '<title>'.JText::_( 'COM_PHOCAFAVICON_FAVICON_CREATING').'</title>'. "\n";
					echo '<link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocafavicon/assets/phocafavicon.css" type="text/css" />';
					echo '</head>'. "\n";
					echo '<body>'. "\n";
				
					echo '<center>';
					echo '<div style="width:70%;border:5px solid #FFE699; margin-top:30px;font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px">'
						.'<span>'. JText::_( 'COM_PHOCAFAVICON_CREATING_FAVICON_WAIT' ) . '</span>';
					echo '<p>' .JText::_( 'COM_PHOCAFAVICON_CREATING_FAVICON_FROM_FILE' ) 
						.' <span style="color:#0066cc"> '. $file['name'] . '</span>' 
						.' ... <span style="color:#fc0000">'.JText::_('COM_PHOCAFAVICON_ERROR').'</span></p>';
					echo '<p>' .JText::_( 'COM_PHOCAFAVICON_ERROR_CRATING_FAVICON' ).'</p>';
					
					//we are in whole site or in modal box
					$positioni = strpos($refresh_url, "view=phocafaviconi");
					if ($positioni === false)//we are in whole window - not in modal box
					{
						echo '<p><a href="index.php?option=com_phocafavicon">' .JText::_( 'COM_PHOCAFAVICON_BACK' ).'</a></p></center></div></body></html>';
					}
					else //we are in modal box
					{
						echo '<p><a href="#" onclick="window.parent.SqueezeBox.close();">' .JText::_( 'COM_PHOCAFAVICON_CLOSE_WINDOW' ).'</a></p></center></div></body></html>';
					}
					//$creating = 0;
					//echo '<meta http-equiv="refresh" content="0;url='.$refresh_url.'" />';
					exit;			
				}
				
			}
			break;
		}
		return $file;
	}

	/*
	 * Main thumbnail creating function	
	 * file = abc.jpg - file_no	= folder/abc.jpg
	 * if small, medium, large = 1, create small, medium, large thumbnail
	 */
	public static function getOrCreateThumbnail(&$errorMsg, $orig_path, $file_no, $refresh_url, $small=0, $medium=0,$large=0)
	{
		
		$path 				= PhocaFaviconHelper::getPathSet();
		$orig_path_server 	= str_replace(DS, '/', $path['orig_abs'] .'/');
	
		$file['name']							= PhocaFaviconHelper::getTitleFromFilenameWithExt($file_no);
		$file['path_with_name']					= str_replace(DS, '/', JPath::clean($orig_path.DS.$file_no));
		$file['path_with_name_relative']		= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $file['path_with_name']);
		$file['path_with_name_relative_no']		= str_replace($orig_path_server, '', $file['path_with_name']);
		
		$file['path_without_name']				= str_replace(DS, '/', JPath::clean($orig_path.DS));
		$file['path_without_name_relative']		= $path['orig_rel_ds'] . str_replace($orig_path_server, '', $file['path_without_name']);
		$file['path_without_name_relative_no']	= str_replace($orig_path_server, '', $file['path_without_name']);
		$file['path_without_name_thumbs'] 		= $file['path_without_name'] .'thumbs';
		$file['path_without_name_no'] 			= str_replace($file['name'], '', $file['path_with_name']);
		$file['path_without_name_thumbs_no'] 	= str_replace($file['name'], '', $file['path_with_name'] .'thumbs');
		
		
		$ext = strtolower(JFile::getExt($file['name']));
	
		switch ($ext)
		{
			case 'jpg':
			case 'png':
			case 'gif':
			case 'jpeg':

			//Get File thumbnails name
			$thumbnail_file_s 	= PhocaFaviconHelper::getThumbnailName ($file_no, 'small');
			$file['thumb_name_s_no_abs'] = $thumbnail_file_s['abs'];
			$file['thumb_name_s_no_rel'] = $thumbnail_file_s['rel'];
			//$file['thumb_name_s_no']= str_replace($file['name'], 'thumbs/' . $file['thumb_name_s'], $file_no);
			
			$thumbnail_file_m  	= PhocaFaviconHelper::getThumbnailName ($file_no, 'medium');
			$file['thumb_name_m_no_abs'] = $thumbnail_file_m['abs'];
			$file['thumb_name_m_no_rel'] = $thumbnail_file_m['rel'];
			//$file['thumb_name_m_no']= str_replace($file['name'], 'thumbs/' . $file['thumb_name_m'], $file_no);
			
			$thumbnail_file_l	= PhocaFaviconHelper::getThumbnailName ($file_no, 'large');
			$file['thumb_name_l_no_abs'] = $thumbnail_file_l['abs'];
			$file['thumb_name_l_no_rel'] = $thumbnail_file_l['rel'];
			//$file['thumb_name_l_no']= str_replace($file['name'], 'thumbs/' . $file['thumb_name_l'], $file_no);
					
			//Create thumbnail folder if not exists
			
			PhocaFaviconHelper::createFolderThumbnail($file['path_without_name_no'], $file['path_without_name_thumbs_no'] . '/' );
			
			
			//Create thumbnail if not exists
			if (JFolder::exists($file['path_without_name_thumbs_no']))
			{				
				//There are a lot of photos, please create thumbnails
				$creating = 0;
				
				//Small thumbnail
				if ($small == 1)
				{
					$creating = PhocaFaviconHelper::createFileThumbnail($errorMsg, $file['path_with_name'], $file['thumb_name_s_no_abs'], 'small');
				}
				
				//Medium thumbnail
				if ($medium == 1)
				{
					$creating = PhocaFaviconHelper::createFileThumbnail($errorMsg, $file['path_with_name'], $file['thumb_name_m_no_abs'], 'medium');
				}
				
				//Large thumbnail
				if ($large == 1)
				{
					$creating = PhocaFaviconHelper::createFileThumbnail($errorMsg, $file['path_with_name'], $file['thumb_name_l_no_abs'], 'large');
				}
				
				//Refresh the site after creating thumbnails - we can do e.g. 100 thumbanails
				// Thumbnail was created
				if ($creating == 1)
				{
					echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
					echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
					echo '<head>'. "\n";
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
					echo '<title>'.JText::_( 'COM_PHOCAFAVICON_FAVICON_THUMBNAIL_CREATING').'</title>'. "\n";
					echo '<link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocafavicon/assets/phocafavicon.css" type="text/css" />';
					echo '</head>'. "\n";
					echo '<body>'. "\n";
				
					echo '<center>';
					echo '<div style="width:70%;border:5px solid #FFE699; margin-top:30px;font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px">'
						.'<span>'. JText::_( 'COM_PHOCAFAVICON_CREATING_THUMB_WAIT' ) . '</span>';
					echo '<p>' .JText::_( 'COM_PHOCAFAVICON_CREATING_OF_THUMB' ) 
						.' <span style="color:#0066cc"> '. $file['name'] . '</span>' 
						.' ... <span style="color:#009900">' .JText::_( 'COM_PHOCAFAVICON_OK' ).'</span></p></center></div></body></html>';				
					//$creating = 0;
					echo '<meta http-equiv="refresh" content="1;url='.$refresh_url.'" />';
					exit;			
				}
				// Thumbnail was not created - error - stop it all
				if ($creating == 2)
				{
					echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
					echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-en" lang="en-en" dir="ltr" >'. "\n";
					echo '<head>'. "\n";
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'. "\n\n";
					echo '<title>'.JText::_( 'COM_PHOCAFAVICON_FAVICON_THUMBNAIL_CREATING').'</title>'. "\n";
					echo '<link rel="stylesheet" href="'.JURI::base(true).'/components/com_phocafavicon/assets/phocafavicon.css" type="text/css" />';
					echo '</head>'. "\n";
					echo '<body>'. "\n";
					
					echo '<center>';
					echo '<div style="width:70%;border:5px solid #FFE699; margin-top:30px;font-family: sans-serif, Arial;font-weight:normal;color:#666;font-size:14px;padding:10px">'
						.'<span>'. JText::_( 'COM_PHOCAFAVICON_CREATING_THUMB_WAIT' ) . '</span>';
					echo '<p>' .JText::_( 'COM_PHOCAFAVICON_CREATING_OF_THUMB' ) 
						.' <span style="color:#0066cc"> '. $file['name'] . '</span>' 
						.' ... <span style="color:#fc0000">' .JText::_( 'COM_PHOCAFAVICON_ERROR' ).'</span></p>';
					echo '<p>' .JText::_( 'COM_PHOCAFAVICON_ERROR_CRATING_THUMB' ).'</p>';
					
					//we are in whole site or in modal box
					$positioni = strpos($refresh_url, "view=phocafaviconi");
					if ($positioni === false)//we are in whole window - not in modal box
					{
						echo '<p><a href="index.php?option=com_phocafavicon">' .JText::_( 'COM_PHOCAFAVICON_BACK' ).'</a></p></center></div></body></html>';
					}
					else //we are in modal box
					{
						echo '<p><a href="#" onclick="window.parent.SqueezeBox.close();">' .JText::_( 'COM_PHOCAFAVICON_CLOSE_WINDOW' ).'</a></p></center>';
					}
					//$creating = 0;
					//echo '<meta http-equiv="refresh" content="0;url='.$refresh_url.'" />';
					exit;			
				}
				
			}
			break;
		}
		
		return $file;
	}
	

	public static function createFolderThumbnail($folder_original, $folder_thumbnail)
	{	
	
		if (JFolder::exists($folder_original))
		{
			if (strlen($folder_thumbnail) > 0)
			{
				$folder_thumbnail = JPath::clean($folder_thumbnail);
				if (!is_dir($folder_thumbnail) && !is_file($folder_thumbnail))
				{
					JFolder::create($folder_thumbnail, 0755);
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($folder_thumbnail.DS."index.html", $data);
				}
			}
		}
	}


	public static function createFileThumbnail(&$errorMsg, $file_original, $file_thumbnail, $size)
	{	
		$file_original 	= str_replace(DS, '/', JPath::clean($file_original));
		$file_thumbnail = str_replace(DS, '/', JPath::clean($file_thumbnail));
			
		$file_resize	= PhocaFaviconHelper::getFileResize($size);
		
		if (JFile::exists($file_original))
		{
			if (!JFile::exists($file_thumbnail))//file doesn't exist, create thumbnail
			{
				//Don't do thumbnail if the file is smaller (width, height) than the possible thumbnail
				list($width, $height) = GetImageSize($file_original);
				if ($width > $file_resize['width'] || $height > $file_resize['width'])//larger
				{
					if (PhocaFaviconHelper::imageMagic($errorMsg, $file_original, $file_thumbnail, $file_resize['width'] , $file_resize['height'])) {return 1;} else {return 2;}
				}
				else
				{
					if (PhocaFaviconHelper::imageMagic($errorMsg, $file_original, $file_thumbnail, $width , $height)) {return 1;} else {return 2;};
				}
				return 1;//thumbnail now created
			}
			return 3;//thumbnail exists
		}
	}
	

	public static function getThumbnailName ($filename, $size)
	{
		$path 					= PhocaFaviconHelper::getPathSet();
		$filename_orig_path_abs	= str_replace(DS, '/', JPath::clean($path['orig_abs_ds'] . $filename));
		$filename_orig_path_rel	= str_replace(DS, '/', JPath::clean($path['orig_rel_ds'] . $filename));
		$filename_orig 			= PhocaFaviconHelper::getTitleFromFilenameWithExt($filename);
		
		switch ($size)
		{
			case 'large':
			$filename_thumbl 			= 'phoca_thumb_l_'. $filename_orig;
			$thumbnail_name['abs']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbl, $filename_orig_path_abs);
			$thumbnail_name['rel']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbl, $filename_orig_path_rel);
			break;
			
			case 'medium':
			$filename_thumbm 			= 'phoca_thumb_m_'. $filename_orig;
			$thumbnail_name['abs']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbm, $filename_orig_path_abs);
			$thumbnail_name['rel']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbm, $filename_orig_path_rel);
			break;
			
			default:
			case 'small':
			$filename_thumbs 			= 'phoca_thumb_s_'. $filename_orig;
			$thumbnail_name['abs']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbs, $filename_orig_path_abs);
			$thumbnail_name['rel']		= str_replace ($filename_orig, 'thumbs/' . $filename_thumbs, $filename_orig_path_rel);
			
			break;	
		}
		return $thumbnail_name;
	}
	
	/**
	* need GD library (first PHP line WIN: dl("php_gd.dll"); UNIX: dl("gd.so");
	* www.boutell.com/gd/
	* interval.cz/clanky/php-skript-pro-generovani-galerie-obrazku-2/
	* cz.php.net/imagecopyresampled
	* www.linuxsoft.cz/sw_detail.php?id_item=871
	* www.webtip.cz/art/wt_tech_php/liquid_ir.html
	* php.vrana.cz/zmensovani-obrazku.php
	* diskuse.jakpsatweb.cz/
	*
	* @param string $file_in Vstupni soubor (mel by existovat)
	* @param string $file_out Vystupni soubor, null ho jenom zobrazi (taky kdyz nema pravo se zapsat :)
	* @param int $width Vysledna sirka (maximalni)
	* @param int $height Vysledna vyska (maximalni)
	* @param bool $crop Orez (true, obrazek bude presne tak velky), jinak jenom Resample (udane maximalni rozmery)
	* @param int $type_out IMAGETYPE_type vystupniho obrazku
	* @return bool Chyba kdyz vrati false
	*/
	public static function imageMagic(&$errorMsg, $file_in, $file_out=null, $width=null, $height=null, $crop=null, $type_out=null) {
	    
		
		echo '<div style="font-family: sans-serif, Arial">'.JText::_('COM_PHOCAFAVICON_POSSIBLE_PROBLEM_IMAGE_CREATING').'</div>';
		
		// Memory - - - - - - - -
		$memory = 8;
		$memoryLimitChanged = 0;
		$memory = (int)ini_get( 'memory_limit' );
		if ($memory == 0) {
			$memory = 8;
		}
		// - - - - - - - - - - -

		
		
		if ($file_in !=="" && File_Exists($file_in))
	    {
	        
			if ($memory < 50) {
				ini_set('memory_limit', '50M');
				$memoryLimitChanged = 1;
			}
			
			// get width, height, IMAGETYPE_type
	        list($w, $h, $type) = GetImageSize($file_in); //array of width, height, IMAGETYPE, "height=x width=x" (string)
	        // print("Input: ". image_type_to_mime_type($type) ." $w x $h<br>");

	        // vyber funkce pro nacteni vstupu
	        switch($type)
	        {
	            case IMAGETYPE_JPEG: $imgIn = 'ImageCreateFromJPEG'; break;
	            case IMAGETYPE_PNG : $imgIn = 'ImageCreateFromPNG';  break;
	            case IMAGETYPE_GIF : $imgIn = 'ImageCreateFromGIF';  break;
	            case IMAGETYPE_WBMP: $imgIn = 'ImageCreateFromWBMP'; break;
	            default: 
					$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_NOT_SUPPORTED_IMAGE_TYPE');
					return false;
				break;
	        }

	        // jaky typ chcem vyrobit? jestli je to fuk, tak ten samy...
	        if ($type_out == null) {    // ...krom Wbitmapy, ta je blba :)
	            $type_out = ($type == IMAGETYPE_WBMP) ? IMAGETYPE_PNG : $type;
	        }
	        switch($type_out)
	        {
	            case IMAGETYPE_JPEG: $imgOut = 'ImageJPEG'; break;
	            case IMAGETYPE_PNG : $imgOut = 'ImagePNG';  break;
	            case IMAGETYPE_GIF : $imgOut = 'ImageGIF';  break;
	            case IMAGETYPE_WBMP: $imgOut = 'ImageWBMP'; break; // bitmapa je blbost
	            default: 
					$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_NOT_SUPPORTED_IMAGE_TYPE');
					return false;
				break;
	        }

	        // toz, jak ma byt vysledek veliky?
	        if ($width == null || $width == 0) { // neni zadana sirka
	            $width = $w;
	        }
	        else if ($height == null || $height == 0) { // neni zadana vyska, ale sirka jo... hmm, toz je dam stejne :)
	            $height = $width;
	        }

	        if ($height == null || $height == 0) { // neni zadana vyska, ale sirka taky ne :)
	            $height = $h;
	        }

	        // jestli se ma jenom zmensovat...
	        if (!$crop) { // prepocti velikost - nw, nh (new width/height)
	            $scale = (($width / $w) < ($height / $h)) ? ($width / $w) : ($height / $h); // vyber mensi pomer

	            $src = array(0,0, $w, $h);
	            $dst = array(0,0, floor($w*$scale), floor($h*$scale));
	        }
	        else { // bude se orezavat
	            $scale = (($width / $w) > ($height / $h)) ? ($width / $w) : ($height / $h); // vyber vetsi pomer a zkus to nejak dopasovat...
	            $newW = $width/$scale;    // jak by mel byt zdroj velky (pro poradek :)
	            $newH = $height/$scale;

	            // ktera strana precuhuje vic (kvuli chybe v zaokrouhleni)
	            if (($w - $newW) > ($h - $newH)) {
	                $src = array(floor(($w - $newW)/2), 0, floor($newW), $h);
	            }
	            else {
	                $src = array(0, floor(($h - $newH)/2), $w, floor($newH));
	            }

	            $dst = array(0,0, floor($width), floor($height));
	        }

	        // print("posX\t posY\t widt\t heig\t - transformation:<br>"."$src[0]\t $src[1]\t $src[2]\t $src[3]\t <br>"."$dst[0]\t $dst[1]\t $dst[2]\t $dst[3]\t <br>");
	        // print("Output: ". image_type_to_mime_type($type) ." $dst[2] x $dst[3]<br>");

	        // proved resampling...
			
		
			
	        if ($image1 = $imgIn($file_in))
	        {
	            $image2 = ImageCreateTruecolor($dst[2], $dst[3]);
				
				if (!$image2) {
					$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_GD_IMAGE_CREATE_TRUE_COLOR');
					return false;
				}
				
				switch($type) {
					case IMAGETYPE_PNG:
						//imagealphablending($image1, false);
						imagealphablending($image2, false);
						//imagesavealpha($image1, true);
						imagesavealpha($image2, true);
					break;
				}
				
				
	            ImageCopyResampled($image2, $image1, $dst[0],$dst[1], $src[0],$src[1], $dst[2],$dst[3], $src[2],$src[3]);

	            // pokud se ma pouze zobrazit, dej vedet co prijde (MIME type)... (tedko uz se snad nic nepokazi)
	            if ($file_out == null) {
	                header("Content-type: ". image_type_to_mime_type($type_out));
	            }

	            // set jpeg/png quality to 85 before saving
	           // if ($type_out == IMAGETYPE_JPEG || $type_out == IMAGETYPE_PNG)    {
			    
				$jfile_thumbs = 1;
				if ($type_out == IMAGETYPE_JPEG)    {

	                //$imgOut($image2, $file_out, 85);
					if ($jfile_thumbs == 1) {
						ob_start();
						$imgOut($image2, NULL, 85);
						$imgJPEGToWrite = ob_get_contents();
						ob_end_clean();
						JFile::write( $file_out, $imgJPEGToWrite);
					} else {
						$imgOut($image2, $file_out, 85);
					}
					
					
	            }
	            else { // save the image
	                //$imgOut($image2, $file_out);
					if ($jfile_thumbs == 1) {
						ob_start();
						$imgOut($image2, NULL);
						$imgOtherToWrite = ob_get_contents();
						ob_end_clean();
						JFile::write( $file_out, $imgOtherToWrite);
					} else {
						$imgOut($image2, $file_out);
					}
	            }

	            ImageDestroy($image1); // free memory
	            ImageDestroy($image2);
				
				if ($memoryLimitChanged == 1) {
					$memoryString = $memory . 'M';
					ini_set('memory_limit', $memoryString);
				}

	            return true; // tohle je jediny misto, kde se da vratit uspech
	        } else {
				$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_GD_IMAGE');
				return false;
			}
			
			if ($memoryLimitChanged == 1) {
				$memoryString = $memory . 'M';
				ini_set('memory_limit', $memoryString);
			}
	    }
		$errorMsg = JText::_('COM_PHOCAFAVICON_ERROR_CREATING_IMAGE_FILE_NOT_EXISTS');
	    return false;
	}
	
	
	public static function deleteFileThumbnail ($filename, $small=0, $medium=0, $large=0)
	{			
		//Get folder variables from Helper
		$path 				= PhocaFaviconHelper::getPathSet();
		$filename_orig_path	= str_replace(DS, '/', JPath::clean($path['orig_abs_ds'] . $filename));
		$filename_orig 		= PhocaFaviconHelper::getTitleFromFilenameWithExt($filename);
		
		if ($small == 1)
		{
			$filename_thumbs = PhocaFaviconHelper::getThumbnailName ($filename, 'small');
			//$filename_thumbs = str_replace ($filename_orig, 'thumbs/' . $filename_thumbs, $filename_orig_path);
			if (JFile::exists($filename_thumbs))
			{
				JFile::delete($filename_thumbs);
			}
		}
		
		if ($medium == 1)
		{
			$filename_thumbm = PhocaFaviconHelper::getThumbnailName ($filename, 'medium');
			//$filename_thumbm = str_replace ($filename_orig, 'thumbs/' . $filename_thumbm, $filename_orig_path);
			if (JFile::exists($filename_thumbm))
			{
				JFile::delete($filename_thumbm);
			}
		}
		
		if ($large == 1)
		{
			$filename_thumbl = PhocaFaviconHelper::getThumbnailName ($filename, 'large');
			//$filename_thumbl = str_replace ($filename_orig, 'thumbs/' . $filename_thumbl, $filename_orig_path);
			if (JFile::exists($filename_thumbl))
			{
				JFile::delete($filename_thumbl);
			}
		}
		return true;
	}
	
	/*
	 * getThumbnailName
	 * Clear Thumbs folder - if there are files in the thumbs directory but not original files e.g.:
	 * phoca_thumbs_l_some.jpg exists in thumbs directory but some.jpg doesn't exists - delete it
	 */	
	public static function cleanThumbsFolder()
	{
		//Get folder variables from Helper
		$path = PhocaFaviconHelper::getPathSet();
		
		// Initialize variables
		$orig_path = $path['orig_abs_ds'];
		$orig_path_server = str_replace(DS, '/', $path['orig_abs'] .'/');

		// Get the list of files and folders from the given folder
		$file_list 		= JFolder::files($orig_path, '', true, true);
			
		// Iterate over the files if they exist
		if ($file_list !== false)
		{
			foreach ($file_list as $file)
			{	
				if (is_file($file) && substr($file, 0, 1) != '.' && strtolower($file) !== 'index.html')
				{
					//Clean absolute path
					$file = str_replace(DS, '/', JPath::clean($file));
					
					$positions = strpos($file, "phoca_thumb_s_");//is there small thumbnail
					$positionm = strpos($file, "phoca_thumb_m_");//is there medium thumbnail
					$positionl = strpos($file, "phoca_thumb_l_");//is there large thumbnail
					
					//Clean small thumbnails if original file doesn't exist
					if ($positions === false) {}
					else 
					{
						$filename_thumbs = $file;//only thumbnails will be listed
						$filename_origs	= str_replace ('thumbs/phoca_thumb_s_', '', $file);//get fictive original files 
						
						//There is Thumbfile but not Originalfile - we delete it
						if (JFile::exists($filename_thumbs) && !JFile::exists($filename_origs))
						{
							JFile::delete($filename_thumbs);
						}
					//  Reverse
					//  $filename_thumb = PhocaFaviconHelper::getTitleFromFilenameWithExt($file);
					//	$filename_original = PhocaFaviconHelper::getTitleFromFilenameWithExt($file);	
					//	$filename_thumb = str_replace ($filename_original, 'thumbs/phoca_thumb_m_' . $filename_original, $file); 
					}
					
					//Clean medium thumbnails if original file doesn't exist
					if ($positionm === false) {}
					else 
					{
						$filename_thumbm = $file;//only thumbnails will be listed
						$filename_origm 	= str_replace ('thumbs/phoca_thumb_m_', '', $file);//get fictive original files 
						
						//There is Thumbfile but not Originalfile - we delete it
						if (JFile::exists($filename_thumbm) && !JFile::exists($filename_origm))
						{
							JFile::delete($filename_thumbm);
						}
					}
					
					//Clean large thumbnails if original file doesn't exist
					if ($positionl === false) {}
					else 
					{
						$filename_thumbl = $file;//only thumbnails will be listed
						$filename_origl 	= str_replace ('thumbs/phoca_thumb_l_', '', $file);//get fictive original files 
						
						//There is Thumbfile but not Originalfile - we delete it
						if (JFile::exists($filename_thumbl) && !JFile::exists($filename_origl))
						{
							JFile::delete($filename_thumbl);
						}
					}
				}
			}
		}
	}
	
	public static function getFileOriginal ($filename)
	{
		$path		= PhocaFaviconHelper::getPathSet();
		$file_original 	= $path['orig_abs_ds'] . $filename;//original file
		return $file_original;
	}


	public static function existsFileOriginal($filename)
	{
		$file_original = PhocaFaviconHelper::getFileOriginal ($filename);
		if (JFile::exists($file_original))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function getAliasName($clean_filename)
	{
	    if (function_exists('iconv'))
		{
			$filename = $clean_filename;
		    $filename = preg_replace('~[^\\pL0-9_.]+~u', '-', $filename);
		    $filename = trim($filename, "-");
		    $filename = iconv("utf-8", "us-ascii//TRANSLIT", $filename);
		    $filename = strtolower($filename);
		    $filename = preg_replace('~[^-a-z0-9_.]+~', '', $filename);
		    return $filename;
		}
		else
		{
			return $clean_filename;
		}
	}

	
	public static function getTitleFromFilenameWithoutExt (&$filename)
	{
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return PhocaFaviconHelper::removeExtension($folder_array[$last_array_value]);
	}

	
	public static function getTitleFromFilenameWithExt (&$filename)
	{
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return $folder_array[$last_array_value];
	}


	public static function removeExtension($file_name)
	{
		return substr($file_name, 0, strrpos( $file_name, '.' ));
	}
	
	public static function wordDelete($string,$length,$end="...")
	{
		if (strlen($string) < $length || strlen($string) == $length)
		{
			return $string; //sting is shorter than we want to short - we send it back without end
		}
		else
		{
			return substr($string, 0, $length) . $end;//string is larger than we want we cut it and add end to this string
		}
	}
	
	
	public static function getPhocaVersion()
	{
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_phocafavicon';
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.'com_phocafavicon';
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	
	public function setMessage($new = '', $current = '') {
		
		$message = $current;
		if($new != '') {
			if ($current != '') {
				$message .= '<br />';
			}
			$message .= $new;
		}
		return $message;
	}
	
	public static function  renderFTPaccess() {
	
		$ftpOutput = '<fieldset title="'.JText::_('COM_PHOCAFAVICON_FTP_LOGIN_LABEL'). '">'
		.'<legend>'. JText::_('COM_PHOCAFAVICON_FTP_LOGIN_LABEL').'</legend>'
		.JText::_('COM_PHOCAFAVICON_FTP_LOGIN_DESC')
		.'<table class="adminform nospace">'
		.'<tr>'
		.'<td width="120"><label for="username">'. JText::_('JGLOBAL_USERNAME').':</label></td>'
		.'<td><input type="text" id="username" name="username" class="input_box" size="70" value="" /></td>'
		.'</tr>'
		.'<tr>'
		.'<td width="120"><label for="password">'. JText::_('JGLOBAL_PASSWORD').':</label></td>'
		.'<td><input type="password" id="password" name="password" class="input_box" size="70" value="" /></td>'
		.'</tr></table></fieldset>';
		return $ftpOutput;
	}
	
	/*
	 * http://aidanlister.com/repos/v/function.size_readable.php
	 */
	public static function  getFileSizeReadable ($size, $retstring = null, $onlyMB = false) {
	
		if ($onlyMB) {
			$sizes = array('B', 'kB', 'MB');
		} else {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }
		

		if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);
		
        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
		
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}
	
	public static function  getImageSize($filename, $returnString = 0, $abs = 0) {
		
		$path			= PhocaFaviconHelper::getPathSet();
		if ($abs == 1) {
			$fileNameAbs	= JPath::clean($filename);
		} else {
			$fileNameAbs	= JPath::clean($path['orig_abs_ds'] . $filename);
		}
			
		if ($returnString == 1) {
			$imageSize = getimagesize($fileNameAbs);
			return $imageSize[0] . ' x '.$imageSize[1];
		} else {
			return getimagesize($fileNameAbs);
		}
	}
}
?>