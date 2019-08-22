<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
	// default functions used in both cases no matter what content source
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	/**
	 * Smart Image detection inside article. Searches in intro text and if not found, in full article text from Joomla 2.5 intro image and full image from params. Image within article has priority.
	 *
	 * @param object $row
	 * @return string - image path
	 */
	if(!function_exists('yjme_art_image'))
	{	 
		function yjme_art_image ($row)
		{
			
			$version = new JVersion;
			if($version->RELEASE > 1.7){	
				
				$img_from_params 	= json_decode($row->images);
				if(isset($img_from_params->image_intro)){
					$img_intro 			= $img_from_params->image_intro;
				}else{
					$img_intro='';
				}
				if(isset($img_from_params->image_fulltext)){
					$image_fulltext 	= $img_from_params->image_fulltext;
				}else{
					$image_fulltext='';
				}
				$img       			= yjme_search_image ( $row->introtext );
				$img_full 			= yjme_search_image ( $row->fulltext );
				if( $img ) return $img;
				if( $img_full ) return $img_full;
				if( $img_intro ) return $img_intro;
				if( $image_fulltext ) return $image_fulltext;

			}else{
				
				$img = yjme_search_image ( $row->introtext );
				if( $img ) return $img;
						
				$img = yjme_search_image ( $row->fulltext );
				return $img;			
				
			}
		}
	}
		/**
		 * Searches for all images inside a text and returns the first one found
		 *
		 * @param string $text
		 * @return string
		 */
	if(!function_exists('yjme_search_image'))
	{	
		function yjme_search_image ( $text )
		{		
			preg_match_all("#\<img(.*)src\=\"(.*)\"#Ui", $text, $mathes);		
			return isset($mathes[2][0]) ? $mathes[2][0] : '';			
		}
	}
	
	
	// get item url joomla
	if(!function_exists('yjme_get_url'))
	{	
		function yjme_get_url ( $row )
		{		
			$item_url = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid));
			return $item_url;
		}
	}
	
	
	// get cat url joomla
	if(!function_exists('yjme_get_cat_url'))
	{	
		function yjme_get_cat_url ( $row )
		{		
			$cat_url = JRoute::_(ContentHelperRoute::getCategoryRoute($row->catslug));
			return $cat_url;
		}
	}	
	
	// get author url joomla :) maybe one day :)
	if(!function_exists('yjme_get_author_url'))
	{	
		function yjme_get_author_url ( $row )
		{		
			$author_url = '';
			return $author_url;
		}
	}	
	

	
?>