
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
jimport('joomla.application.component.helper');
set_time_limit(0);

class jefaqproModelImportcsv extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true)
	{

	}

function stringURLSafe($string)
    {
        //remove any '-' from the string they will be used as concatonater
        $str = str_replace('-', ' ', $string);
        $str = str_replace('_', ' ', $string);

        //$lang =& JFactory::getLanguage();
        //$str = $lang->transliterate($str);

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $str);

        // lowercase and trim
        $str = trim(strtolower($str));
        return $str;
    }
	public function importcsvfaq() {
		 // Check for request forgeries
		 JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		 $target_path = JPATH_SITE.DS.'images'.DS;

		 $target_path = $target_path . $_FILES['uploadedfile']['name'];

			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				// path where your CSV file is located
				define('CSV_PATH', JPATH_SITE.DS.'images'.DS);
				// Name of your CSV file
				//$csv_file = CSV_PATH . "jefaqpro_faq.csv";
/*Developed by jextn raja starts*/
				$csv_file = CSV_PATH . $_FILES['uploadedfile']['name'];
			   	if (($getfile = fopen($csv_file, "r")) !== FALSE) {
	        	$col = fgetcsv($getfile, 1000, ",");
	        	$col['2']='catid';  											/*assign category name to catid.*/
	        	//print_r($col);
	        	//exit;
				$count = count($col);
	       		$db =JFactory::getDBO();
				while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
	         		$val =  array_slice($data, 0, $count);
					//print_r($val);exit;
					$query = $db->getQuery(true);
					$catname = $col['2'];
					if($val != ''){
						$sql="Select * from #__categories where extension='com_jefaqpro' and title='".$val['2']."'";
						$db->setQuery($sql);
						$res = $db->loadObject();
						if(!empty($res)){
							$val['2']=$res->id;
							$query
					    ->insert($db->quoteName('#__jefaqpro_faq'))
					    ->columns($db->quoteName($col))
					    ->values("'" . implode("','", $val) . "'");
					$db->setQuery($query);
					$result = $db->query();
						}
						else{
							$v="SELECT rgt FROM #__categories WHERE extension = 'system' LIMIT 0 , 30";
							$db->setQuery($v);
							$s = $db->loadObject();
							//print_r($s);
							$co=array();
							$co[0]=$s->rgt+1;
							//print_r($co);
							//exit;
							$col1 = array();
							$val1 = array();
							$val1[0]=$val['2'];
							$col1[0]='title';
							$col1[1]='published';
							$val1[1]=1;
							$col1[2]='access';
							$val1[2]=1;
							$col1[3]='created_time';
							$da=JFactory::getDate('now');
							$val1[3]=$da;
							$col1[4]='language';
							$val1[4]='*';
							$col1[5]='created_user_id';
							$u=JFactory::getUser();
							$us=$u->get('id');
							$val1[5]=$us;
							$col1[6]='params';
							$val1[6]='{"category_layout":"","image":""}';
							$col1[7]='extension';
							$val1[7]='com_jefaqpro';
							$col1[8]='level';
							$val1[8]=1;
							$col1[9]='alias';
							$ailias=JFilterOutput::stringURLSafe($val1[0]);
							$val1[9]=$ailias;
							$col1[10]='path';
							$val1[10]=$val1[9];
							$col1[11]='parent_id';
							$val1[11]=1;
							$col1[12]='rgt';
							$val1[12]=$s->rgt+1;
							$col1[13]='lft';
							$val1[13]=$s->rgt;
							$v1="update #__categories set rgt=$s->rgt+2   WHERE extension = 'system'";
							$db->setQuery($v1);
							$s1 = $db->execute();
							 $query
							    ->insert($db->quoteName('#__categories'))
							    ->columns($db->quoteName($col1))
							    ->values("'" .implode("','", $val1). "'");
							$db->setQuery($query);
							$result = $db->query();
							$catid = $db->insertid();/*get category id.*/
							//print_r($catid);
							//exit;
								$val2=array();
							    //$val2=array_slice($data, 0, $count);
							    $col2=array();
								$col2[0]='questions';
								$val2[0]=$val[0];
								$col2[1]='answers';
								$val2[1]='<p>'.$val[1].'</p>';
							    $col2[2]='catid';
							    $val2[2]=$catid;
							    $col2[3]='language';
							    $val2[3]='*';
							    $col2[4]='published';
							    $val2[4]=1;
							    $col2[5]='access';
							    $val2[5]='1';
							    $col2[6]='ordering';
							    $val2[6]='1';
							    $col2[7]='uid';
								$u=JFactory::getUser();
								$us=$u->get('id');
								$val2[7]=$us;
								$col2[8]='posted_date';
								$da=JFactory::getDate('now');
								$val2[8]=$da;
								$col2[9]='posted_by';
								$n=JFactory::getUser();
								$n1=$n->get('username');
								$val2[9]=$n1;
								$col2[10]='posted_email';
								$em=$n->email;
								$val2[10]=$em;
										//print_r($col2);
							    		$query1 = $db->getQuery(true);
										$query1
					   					 ->insert($db->quoteName('#__jefaqpro_faq'))
					    				->columns($db->quoteName($col2))
					   					 ->values("'" . implode("','", $val2) . "'");
										$db->setQuery($query1);
										$result = $db->query();

						}
					}
	       		}

/*Developed by jextn raja ends*/
					$messages = JText::_('COM_JEXTNFAQPRO_FAQ_IMPORTED_SUCCESSFULLY');
			  	}
			  	else {
				  	 JError::raiseWarning( 100, JText::_('COM_JEXTNFAQPRO_ERROR_UPLOADING') );
				}

			}
			else {
			     JError::raiseWarning( 100, JText::_('COM_JEXTNFAQPRO_ERROR_UPLOADING') );
			}
				$route = "index.php?option=com_jefaqpro&view=importcsv";
				//$this->setRedirect(JRoute::_($route), $messages);
				//JFactory::getapplication()->Redirect(JRoute::_($route), $messages);
				$app = JFactory::getapplication();
				$app->Redirect(JRoute::_($route,false), $messages);
				return true;
				return true;
        }


	public function importcsvcategories() {

		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$target_path = JPATH_SITE.DS.'images'.DS;

		$target_path = $target_path . $_FILES['uploadedfile']['name'];

		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

			   // path where your CSV file is located
				define('CSV_PATH', JPATH_SITE.DS.'images'.DS);

				// Name of your CSV file
				$csv_file = CSV_PATH . "jefaqpro_categories.csv";

			    if (($getfile = fopen($csv_file, "r")) !== FALSE) {
	        	$col = fgetcsv($getfile, 1000, ",");
	        	print_r($col);
	        	exit;
	       		$count = count($col);
				$db =JFactory::getDBO();

	       		while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
	         		$val =  array_slice($data, 0, $count);

					$query = $db->getQuery(true);
				  echo  $query
					    ->insert($db->quoteName('#__categories'))
					    ->columns($db->quoteName($col))
					    ->values("'" . implode("','", $val) . "'");
					$db->setQuery($query);
					$result = $db->query();
	       		}
					$messages = JText::_('COM_JEXTNFAQPRO_CATEGORIES_IMPORTED_SUCCESSFULLY');
			  }

			   else {

				 	JError::raiseWarning( 100, JText::_('COM_JEXTNFAQPRO_ERROR_UPLOADING') );
				}

		} else{

		    JError::raiseWarning( 100, JText::_('COM_JEXTNFAQPRO_ERROR_UPLOADING') );

		}
			$route = "index.php?option=com_jefaqpro&view=importcsv";
			JFactory::getapplication()->Redirect(JRoute::_($route), $messages);
			return true;
        }

}
?>
