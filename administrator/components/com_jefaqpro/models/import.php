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

class jefaqproModelImport extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{

	}

	function removePa($array) { //remove parenthesis [array_map]
	    return str_replace(array("(",")"), '', $array);
	}

	public function scrapQuery($query) {
		preg_match_all("/\(.*?\)/",$query,$value);  //get the text between parenthesis
		$item  = array_map(array($this,'removePa'), $value[0]);
		unset($item['0']);			  				//unsetting the first array value bcoz its field name of the table
		return array_values($item); 				//rearrange the key of array
	}

	public function CatinTemp($oid = null,$nid = null,$returnC = null) {
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('newcatid')
	    	  ->from('#__jefaqpro_tempcat')
	          ->where('oldcatid = '.$oid);
	    $db->setQuery($query);
		$cat    = $db->loadResult();

		if($returnC)
			return $cat;
		else if($oid && $nid) {
			$catTemp 		   = new stdClass();
			$catTemp->oldcatid = $oid;
			$catTemp->newcatid = $nid;

			if($cat)
				$db->updateObject('#__jefaqpro_tempcat', $catTemp, 'oldcatid');
			else
				$db->insertObject('#__jefaqpro_tempcat', $catTemp);
		}
	}

	public function FaqinTemp($oid = null,$nid = null,$returnC = null) {
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('newfaqid')
	    	  ->from('#__jefaqpro_tempfaq')
	          ->where('oldfaqid = '.$oid);
	    $db->setQuery($query);
		$cat    = $db->loadResult();

		if($returnC)
			return $cat;
		else if($oid && $nid) {
			$catTemp 		   = new stdClass();
			$catTemp->oldfaqid = $oid;
			$catTemp->newfaqid = $nid;

			if($cat)
				$db->updateObject('#__jefaqpro_tempfaq', $catTemp, 'oldfaqid');
			else
				$db->insertObject('#__jefaqpro_tempfaq', $catTemp);
		}
	}

	public function importCategoryItems() {
		$queries 									= array();
		$db     									= JFactory::getDbo();
		$result 									= $this->insertTheSQLFile('category.sql');
		if(!$result){
			return false;
		}
		$query  									= $db->getQuery(true);
		$query->select('ocat.*')
	    	  ->from('#__jos_je_faq_category AS ocat');
	    $db->setQuery($query);
		$oldCat   									= $db->loadObjectList();

		if($oldCat) {
			$error   								= $count = array();
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_categories'.DS.'models');
			$config  								= array( 'table_path' => JPATH_ADMINISTRATOR . '/components/com_categories/tables');
			$model   								= JModelLegacy::getInstance("Category", "CategoriesModel",$config);
			$user    								= JFactory::getuser();

			foreach($oldCat as $item) {
				$catOid							  	= $item->id;
				$categoryData 			     	  	= array();
				$categoryData["id"] 	     	  	= null;
				$categoryData["parent_id"]   	  	= "1";
				$categoryData["extension"]   	  	= "com_jefaqpro";
				$categoryData["title"]	     	  	= $item->category;
				$categoryData["alias"] 	     	  	= $item->alias;
				$categoryData["note"]        	  	= "";
				$categoryData["description"] 	  	= $item->introtext;
				$categoryData["published"]   	  	= $item->state;
				$categoryData["access"]           	= "1";
				$categoryData["metadesc"]         	= "";
				$categoryData["metakey"]          	= "";
				$categoryData["created_user_id"]  	= $user->id;
				$categoryData["language"] 	      	= "*";
				$categoryData["rules"]     		  	= array("core.create" => array("6" => "1","3" => "1") ,"core.delete" => array("6" => "1"),"core.edit" => array("6" => "1","4" => "1"),"core.edit.state" =>array("6" => "1","5" => "1"));
				$categoryData["params"]    		  	= array("category_layout"=>"","image"=>$item->image);
				$categoryData["metadata"]  		  	= array("author"=>"","robots"=>"");
				$csave							 	= $model->save($categoryData);
				$catNid							  	= $model->getState('category.id');
				$model->setState('category.id', null);
				$this->CatinTemp($catOid,$catNid);
				if(!$csave) {
					$error[] 					  	= 1;
					JError::raiseWarning(21, $model->getError()." : ".$categoryData["alias"]);
				} else {
					$count[]					  	= 1;
				}
			}

			if(!count($error)) {
				JFactory::getApplication()->enqueueMessage(count($count).' '.JText::_("COM_JEFAQPRO_CATEGORY_IMPORTED_SUCCESS"));
				if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jefaqpro'.DS.'assets'.DS.'importtables'.DS.'faq.sql'))
				return true;
				else
					return false;
			}

		} else {
			return false;
		}
	}

	protected function insertTheSQLFile($sqlFile){
		$queries 		= array();
		$db     		= JFactory::getDbo();

		$query 			= 'DROP TABLE IF EXISTS `#__jos_je_faq`;';
		$db->setQuery($query);
		$db->execute();
		$query 			= '';

		$query 			= 'DROP TABLE IF EXISTS `#__jos_je_faq_category`;';
		$db->setQuery($query);
		$db->execute();
		$query 			= '';

		$query 			= 'DROP TABLE IF EXISTS `#__jos_je_faq_responses`;';

		$db->setQuery($query);
		$db->execute();
		$query 			= '';

		$sqlfile 		= JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jefaqpro'.DS.'assets'.DS.'importtables'.DS.$sqlFile;

		// Check that sql files exists before reading. Otherwise raise error for rollback
		if (!file_exists($sqlfile))
		{
			JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_FILENOTFOUND', $sqlfile));

			return false;
		}

		$buffer 		= file_get_contents($sqlfile);
		$buffer 		= str_replace("jos_je_", "#__jos_je_", $buffer);
		// Graceful exit and rollback if read not successful
		if ($buffer === false)
		{
			JError::raiseWarning(1, JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'));

			return false;
		}

		// Create an array of queries from the sql file
		$queries 		= JInstallerHelper::splitSql($buffer);

		if (count($queries) == 0)
		{
			// No queries to process
			return 0;
		}

		// Process each query in the $queries array (split out of sql file).
		foreach ($queries as $query)
		{
			$query 		= trim($query);

			if ($query != '' && $query{0} != '#')
			{
				$db->setQuery($query);

				if (!$db->execute())
				{
					JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));

					return false;
				}
			}
		}
		return true;
	}

	public function importFaqItems() {
		$queries 							= array();
		$db     							= JFactory::getDbo();
		$result 							= $this->insertTheSQLFile('faq.sql');
		$query  							= $db->getQuery(true);
		$query->select('ofaq.*')
	    	  ->from('#__jos_je_faq AS ofaq');
	    $db->setQuery($query);
		$oldFAQs   							= $db->loadObjectList();

		if($oldFAQs) {
			$error   						= $count = array();
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jefaqpro'.DS.'models');
			$model   						= JModelLegacy::getInstance("Faq", "jefaqproModel");
			$user    						= JFactory::getuser();

			foreach($oldFAQs as $item) {
				$Updateid    				= $this->FaqinTemp($item->id);
				$Faqdata                 	= array();
				$Faqdata['id']           	= $Updateid ? $Updateid : null;
				$Faqdata['questions']    	= $item->questions;
				$Faqdata['answers']      	= $item->answers;
				$Faqdata['catid']	     	= $this->CatinTemp($item->catid,null,true);
				$Faqdata['published']    	= $item->state;
				$Faqdata['access']       	= 1;
				$Faqdata['language']     	= "*";
				$Faqdata['hits']         	= $item->hits;
				$Faqdata['email_status'] 	= $item->email_status;

				$csave					 	= $model->save($Faqdata);
				$faqNid					 	= $model->getState('faq.id');
				$model->setState('faq.id', null);
				$this->FaqinTemp($item->id,$faqNid);
				if(!$csave) {
					$error[] 			 	= 1;
					JError::raiseWarning(21, "Faq id : ".$item->id." ".JText::_("COM_JEFAQPRO_FAQ_IMPORTING_FAILED"));
				} else {
					$count[]			 	= 1;
				}
			}
			if(!count($error)) {
				JFactory::getApplication()->enqueueMessage(count($count).' '.JText::_("COM_JEFAQPRO_FAQ_IMPORTED_SUCCESS"));
				if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jefaqpro'.DS.'assets'.DS.'importtables'.DS.'responses.sql'))
				return true;
				else
					return false;
			}
		} else {
			return false;
		}
	}

	/*Like & Unlike*/
	public function importResposeItems() {
		$queries 							= array();
		$db     							= JFactory::getDbo();
		$result 							= $this->insertTheSQLFile('responses.sql');
		$query  							= $db->getQuery(true);
		$query->select('rfaq.*')
	    	  ->from('#__jos_je_faq_responses AS rfaq');
	    $db->setQuery($query);
		$oldRFAQs   						= $db->loadObjectList();

		if($oldRFAQs) {
			$error   						= $count = array();
			JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jefaqpro'.DS.'tables');
			$table   						= JTable::getInstance("Response", "jefaqproTable");
			$user    						= JFactory::getuser();
			foreach($oldRFAQs as $item) {
				$fid                      	= $item->faqid;
				$eData				      	= $table->load($fid,'faqid');
				$Resdata                  	= array();
				$Resdata['faqid']         	= $this->FaqinTemp($fid,null,true);
				$Resdata['userid']        	= $item->userid;
				$Resdata['response_yes']  	= $item->response_yes;
				$Resdata['response_no']   	= $item->response_no;
				$csave				 	  	= $table->save($Resdata);
				if(!$csave) {
					$error[] 			  	= 1;
					JError::raiseWarning(21, "Response id : ".$item->id." ".JText::_("COM_JEFAQPRO_RES_IMPORTING_FAILED"));
				} else {
					$count[]		     	= 1;
				}
			}
			if(!count($error)) {
				JFactory::getApplication()->enqueueMessage(count($count).' '.JText::_("COM_JEFAQPRO_RES_IMPORTED_SUCCESS"));
				return true;
			}
		} else {
			return false;
		}
	}
}
?>
