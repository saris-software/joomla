<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
set_time_limit(0);

class jefaqproModelExportcsv extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{

	}
	public function exportcategory() {

		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('*')
	    	  ->from('#__categories')
	          ->where("extension='com_jefaqpro'");
	    $db->setQuery($query);
		$options    = $db->loadObjectList();
		foreach ($options as $row){
	    $id = $row->id;
	    $path = $row->path;
	    $parent_id = $row->parent_id;
	    $level = $row->level;
	    $extension = $row->extension;
	    $title = $row->title;
		}

		$count  = count($options);

		$heading = $db->getTableColumns('#__categories');

		$output 		   = '';
		foreach ($heading['#__categories'] as $key=>$head ) {
				if(($key=='id') || ($key=='title') || ($key=='description'))
				{
			$output .= '"'.$key.'",';
				}
			}
			$output .="\n";

		// Get Records from the table
		foreach ($options as $row) {

			$output .= '"'.$row->id.'",';

			$output .= '"'.$row->title.'",';

			$output .= '"'.$row->description.'",';

		    $output .="\n";
		}
		$heading='';
	 	$heading .='"'.$output.'",' ;
		// Download the file

		$filename = "jefaqpro_categories.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;
	}


	public function exportfaq() {
/*Developed by jextn raja starts */
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__jefaqpro_faq');
		$db->setQuery($query);
		$options    = $db->loadObjectList();
		foreach ($options as $row){
	    $id 		= $row->id;
	    $questions 	= $row->questions;
		}
		$count  	= count($options);
		$heading 	= $db->getTableColumns('#__jefaqpro_faq');

		$output 		   = '';
		foreach ($heading as $key=>$head ) {
				if(($key=='questions') || ($key=='answers'))
				{
			$output .= '"'.$key.'",';
			}
		}

			$heading_cat = $db->getTableColumns('#__categories');
			foreach ($heading_cat as $key=>$head ) {

				if($key=='title')
				{
					$output .= '"categoryname",';
				}
				/*if($key=='description')
				{
					$output .= '"categorydescription",';
				}*/
			}
			$output .="\n";

		// Get Records from the table
		foreach ($options as $row) {

			//$output .= '"'.$row->id.'",';
			$output .= '"'.$row->questions.'",';
			$output .= '"'.$row->answers.'",';
			//$output .= '"'.$row->catid.'",';
				$query1  = $db->getQuery(true);
				$query1->select('*')
					  ->from('#__categories')
					  ->where("extension='com_jefaqpro' AND id=".$row->catid);
				$db->setQuery($query1);
				$catdata    = $db->loadObject();
				//print_r($catdata);
				//exit;
				$output .= '"'.$catdata->title.'",';

		    $output .="\n";
		}
		$heading ='';
	 	$heading .='"'.$output.'",';
		// Download the file

		$filename = "jefaqpro_faq.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;
	}

/*Developed by jextn raja ends */


}
?>
