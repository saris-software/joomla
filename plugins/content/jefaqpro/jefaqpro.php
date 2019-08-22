<?php
/**
 * jeFaqpro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2012 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

class plgContentjefaqpro extends JPlugin
{
	/**
	 * Constructor
	 * For php4 compatability
	 * @since 1.1
	 */
	function plgContentjefaqpro( &$subject, $params )
	{
		parent::__construct( $subject, $params );

		$language 					= JFactory::getLanguage();
		$language->load('plg_content_jefaqpro', JPATH_ADMINISTRATOR);
	}

	public function getTotalresponse( $like, $faqid )
	{
		$db    = JFactory::getDBO();
		$faq				= 0;
		$query				= $db->getQuery(true);

		if ($like) {
			$query->select('SUM(response_yes)');
		} else {
			$query->select('SUM(response_no)');
		}

		$query->where('faqid = '.$faqid);
		$query->from('#__jefaqpro_responses');
		$db->setQuery($query);
		$faq				= $db->loadResult();

		if ( $faq == '' || $faq == null ) {
			$faq = 0;
		}

		return $faq;
	}

	/**
	 * Replace the faqpros and show faqpro contents and themes in article
	 */
	public function onContentPrepare($context, &$row, &$params, $page=0 )
	{
		$portfolio = "";

		// simple performance check to determine whether bot should process further
		if ( JString::strpos( $row->text, '{faqpro' ) === false ) {
			return true;
		}

		// define the regular expression for the bot
		$regex = '/{faqpro\s*.*?}/i';

		// find all instances of plugin and put in $url_video
		preg_match_all( $regex, $row->text, $matches);

		// Number of plugins
		$count = count( $matches[0] );

		// Plugin only processes if there are any instances of the plugin in the text
		if ( $count ) {
			$row->text = preg_replace_callback( $regex, array($this, 'getPortfolio'), $row->text );
		}
	}

	function get_between ($text, $s1, $s2) {
		$mid_url 	= "";
		$pos_s 		= strpos($text,$s1);
		$pos_e 		= strpos($text,$s2);
		for ( $i=$pos_s+strlen($s1) ; (( $i<($pos_e)) && $i < strlen($text)) ; $i++ ) {
			$mid_url .= $text[$i];
		}
		return $mid_url;
	}
	/**
	 * Display the portfolio images
	 * @param array $matches A array with regex content.
	 */
	protected function getPortfolio(&$matches)
	{
		$faqPluginText 		= explode("|",$this->get_between($matches[0],'{','}'));
		$faqcon  			= $this->get_between($matches[0],'{','}');
		jimport('libraries.joomla.application.application.php');

		$app = JFactory::getApplication();

		$context			= 'com_jefaqpro.s.id.list.';
		$sort 				= $this->params->get('sort');
		if($sort==='random')
			$sort_order				= 'rand()';
		else
			$sort_order				= 's.'.$sort;
		$param 				= JComponentHelper::getParams( 'com_jefaqpro' );
		$filter_order		= $app->getUserStateFromRequest( $context.'filter_order',		'filter_order',	$sort_order,	'cmd' );
		$limit				= $app->getUserStateFromRequest($context.'limit_faq', 'limit_faq', $param->get('list_limit'), 'int');
		$limitstart_faq 		= $app->getUserStateFromRequest( $context.'limitstart_faq', 'limitstart_faq', 0, 'int' );
		$settings 			= $this->getSettings();

		if(strtolower(@$faqPluginText[count($faqPluginText) - 2]) == 'theme')
			$settings->theme		= $faqPluginText[count($faqPluginText) - 1];

        $limitstart_faq 		= ($limit != 0 ? (floor($limitstart_faq / $limit) * $limit) : 0);

		// function for total count
		$total     			= $this->getTotal($filter_order,$faqPluginText);

		require_once(JPATH_SITE."/plugins/content/jefaqpro/pagination.php");

		$pageNav1   			= new JEFAQPagination( $total, $limitstart_faq, $limit );

		$limit				= $pageNav1->limit_faq;
		$limitstart_faq		    = $pageNav1->limitstart_faq;

		$order=$this->params->get('order');

		// function for get faqs

		$rows     			= $this->getFaqpro($filter_order,$limitstart_faq,$limit,$order,$faqPluginText);

		$limitfaqitems 				= 0;
		if(isset($faqPluginText[1]))
		if($faqPluginText[1]=='count'){
			if(isset($faqPluginText[2]))
			if($faqPluginText[2]){
				$limitfaqitems 		=(int)$faqPluginText[2];
			}
		}

		$pageNav1->jelimitstart_faq = $limitstart_faq;
		$jefaqpros 		= $this->displayFaqpro($rows,$param,$pageNav1,$settings,$limitfaqitems,$total, $faqcon);


		return $jefaqpros;
	}

	protected function getTotal($filter,$faqPluginText)
	{
		$where = array();

		$where[]    = 's.published = 1';   // while published

		//Check Category or FAQ from the {faqpro}
		if(isset($faqPluginText[1]))
		if($faqPluginText[1]=='c'){
			if(isset($faqPluginText[2]))
			if($faqPluginText[2]!= null){
				$aryText 					= explode(",",$faqPluginText[2]);
				foreach($aryText as $key => $value)
			        $aryText[$key]			= (int)$value;
				$faqPluginText[2]			= implode(",",$aryText);
				$where[]    				= 's.catid IN ('.$faqPluginText[2].')';
			}
		}
		if(isset($faqPluginText[1]))
		if($faqPluginText[1]=='f'){
			if(isset($faqPluginText[2]))
			if($faqPluginText[2]!= null){
				$aryText 					= explode(",",$faqPluginText[2]);
				foreach($aryText as $key => $value)
			        $aryText[$key] 			= (int)$value;
				$faqPluginText[2] 			= implode(",",$aryText);
				$where[]    				= 's.id IN ('.$faqPluginText[2].')';
			}
		}
		$limitfaqitems 						= 0;
		if(isset($faqPluginText[1]))
		if($faqPluginText[1]=='count'){
			if(isset($faqPluginText[2]))
			if($faqPluginText[2]){
				$limitfaqitems 				= (int)$faqPluginText[2];
				if(isset($faqPluginText[3]))
				if($faqPluginText[3]=='c'){
					if(isset($faqPluginText[4]))
					if($faqPluginText[4]!= null){
						$aryText 			= explode(",",$faqPluginText[4]);
						foreach($aryText as $key => $value)
					        $aryText[$key] 	= (int)$value;
						$faqPluginText[4] 	= implode(",",$aryText);
						$where[]    		= 's.id IN ('.$faqPluginText[4].')';
					}
				}
			}
		}

		// get the total number of records
		$db    = JFactory::getDBO();
		$lang 								= JFactory::getLanguage();
		$user								= JFactory::getUser();
		$groups								= implode(',', $user->getAuthorisedViewLevels());
		$where[]    						= 'cat.published = 1';
		$where[]    						= 's.language IN( \''.$lang->getTag().'\',\'*\')';
		$where[]    						= 's.access IN ('.$groups.')';
		$where[]    						= 'cat.access IN ('.$groups.')';
		
		$where		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		$query = 'SELECT count(*) FROM #__jefaqpro_faq AS s LEFT JOIN #__categories AS cat ON cat.id = s.catid'
			. $where
			;
		if($limitfaqitems)
			$query .= ' limit '.$limitfaqitems;

		$db->setQuery( $query );
		if (!$db->query()) {
			echo $db->getErrorMsg();
		}

		$total = $db->loadResult();

		return $total;
	}

	protected function getSettings()
	{
			$db    = JFactory::getDBO();
			$query	= $db->getQuery(true);
			$query->select('*');
			$query->from('`#__jefaqpro_settings`');
			$db->setQuery($query);
			$settings		= $db->loadObject();

		return $settings;
	}

	protected function getFaqpro($filter_order,$limitstart_faq,$limit,$order,$faqPluginText)
	{
			$orderby	= $filter_order ;	
			$lang 		= JFactory::getLanguage();
			$user		= JFactory::getUser();
			$groups		= implode(',', $user->getAuthorisedViewLevels());
			$db    		= JFactory::getDBO();
			$query		= $db->getQuery(true);
			$query->select('s.*');
			$query->from('#__jefaqpro_faq As s');
			$query->where('s.published = 1');
			$query->where('s.access IN ('.$groups.')');
			$query->where('s.language IN( \''.$lang->getTag().'\',\'*\')');
			$query->join( 'LEFT', '#__categories AS cat ON cat.id = s.catid' );
			$query->where('cat.published = 1');
			$query->where('cat.access IN ('.$groups.')');

			//Check Category or FAQ from the {faqpro}
			if(isset($faqPluginText[1]))
			if($faqPluginText[1]=='c'){
				if(isset($faqPluginText[2]))
				if($faqPluginText[2]!= null){
					$aryText 					= explode(",",$faqPluginText[2]);
					foreach($aryText as $key => $value)
				        $aryText[$key] 			= (int)$value;
					$faqPluginText[2] 			= implode(",",$aryText);
					$query->where( 's.catid IN ('.$faqPluginText[2].')');
				}
			}
			if(isset($faqPluginText[1]))
			if($faqPluginText[1]=='f'){
				if(isset($faqPluginText[2]))
				if($faqPluginText[2]!= null){
					$aryText 					= explode(",",$faqPluginText[2]);
					foreach($aryText as $key => $value)
				        $aryText[$key] 			= (int)$value;
					$faqPluginText[2] 			= implode(",",$aryText);
					$query->where( 's.id IN ('.$faqPluginText[2].')');
				}
			}
			$limitfaqitems 						= 0;
			if(isset($faqPluginText[1]))
			if($faqPluginText[1]=='count'){
				if(isset($faqPluginText[2]))
				if($faqPluginText[2]){
					$limitfaqitems 				= (int)$faqPluginText[2];
					if(isset($faqPluginText[3]))
					if($faqPluginText[3]=='c'){
						if(isset($faqPluginText[4]))
						if($faqPluginText[4]!= null){
							$aryText 			= explode(",",$faqPluginText[4]);
							foreach($aryText as $key => $value)
						        $aryText[$key] 	= (int)$value;
							$faqPluginText[4] 	= implode(",",$aryText);
							$query->where( 's.catid IN ('.$faqPluginText[4].')');
						}
					}
				}
			}
			$query->order($orderby.' '.$order);
			if($limitfaqitems)
				$db->setQuery($query,0, $limitfaqitems);
			else
			$db->setQuery($query,$limitstart_faq, $limit);

			$row		= $db->loadObjectList();

			return $row;
	}

	protected function displayFaqpro($rows,$param,$pageNav1,$settings,$limitfaqitems,$total, $faqcon)
	{

		$path 		= JURI::root();
		$doc        = JFactory::getDocument();

		$js 		= JURI::root().'components/com_jefaqpro/assets/js/utilities.js';
		$js1 		= JURI::root().'components/com_jefaqpro/assets/js/accordionview.js';
		$js2 		= JURI::root().'components/com_jefaqpro/assets/js/iepngfix_tilebg.js';
		$css1 		= JURI::root().'components/com_jefaqpro/assets/css/accordionview.css';
		$css2		= JURI::root().'components/com_jefaqpro/assets/css/accordionviewcategorized.css';
		$css   	    = JURI::root().'components/com_jefaqpro/assets/css/style.css';

		JHTML::_('behavior.modal');

		$doc->addStyleSheet($css1);
		$doc->addStyleSheet($css2);
		$doc->addStyleSheet($css);
		$doc->addScript($js);
		$doc->addScript($js1);
		$doc->addScript($js2);

		$uri 	= JFactory::getURI();
		$user 	= JFactory::getUSER();
		$action = $uri->toString();

//*********************************************************************************

		if($param->get('show_onlyregusers')) {
			if( $user->id > 0 ) {
				$show_systems	= true;

			} else {
				$show_systems	= false;

			}
		} else {
			$show_systems		= true;

		}

//*********************************************************************************
		$jefaqpro ='';

		/****** New FAQ *****/
		$user						= JFactory::getUser();
		if (empty($rows->id)) {
			$authorised				= $user->authorise('core.create', 'com_jefaqpro') || (count($user->getAuthorisedCategories('com_jefaqpro', 'core.create')));
		} else {
			$authorised				= $params->get('access-edit');
		}
		if ($authorised !== true) {
			$allowed = 0;
		}
		else
		{
			$allowed = 1;
		}
		require_once(JPATH_SITE."/components/com_jefaqpro/helpers/route.php");
		$itemid   = JRequest::getVar('Itemid');
		$form	  = JRoute::_('index.php?option=com_jefaqpro&view=form&layout=edit&Itemid='.jefaqproHelperRoute::getaddFormRoute());
		if ( $param->get('show_onlyregusers', 1) && $param->get('add_votes', 1)) {
			if ( $allowed == '1' && $user->get('id') >0 ) {
				$jefaqpro .='<div id="je-newbutton">';
				$jefaqpro .='<div style="text-align : right">';
				$jefaqpro .='<a id="je-addbutton" href="'. $form.'" title="'.JText::_('JE_ADDNEW').'" > <strong> '.JText::_('JE_ADDNEW').' </strong> </a>';
				$jefaqpro .='</div>';
				$jefaqpro .='</div>';
				$jefaqpro .='<br/><br/><br/>';
			}
		} else {
			if ($allowed == '1' ) {
				$jefaqpro .='<div id="je-newbutton">';
				$jefaqpro .='<div style="text-align : right">';
				$jefaqpro .='<a id="je-addbutton" href="'. $form.'" title="'.JText::_('JE_ADDNEW').'" > <strong> '.JText::_('JE_ADDNEW').' </strong> </a>';
				$jefaqpro .='</div>';
				$jefaqpro .='</div>';
				$jefaqpro .='<br/><br/><br/>';
				}
			}
		/******New FAQ Ends*****/

		$jefaqpro .= "<form action=\"$action\" method=\"post\" name=\"adminForm1\">";
		//$jefaqpro.= "<script type=\"text/javascript\" src=\"/joomla/joomla_new/media/system/js/mootools.js\"></script>";
  		$jefaqpro.= "<script type=\"text/javascript\">";
		$jefaqpro.= "window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });";
  		$jefaqpro.="</script>";
		$k 			= 0;
		if($param->get('show_expand_collapse',0)){
		$jefaqpro .= '<div id="jextn_d_exco" class="jextn_d_excoc">';
		$jefaqpro 	.= '<span onclick="expandAll();" id="jextnfaq_span_exd">'.JText::_('COM_JEXTN_FAQPRO_EXPAND_ALL').'</span>';
		$jefaqpro 	.= '<span onclick="collapseAll();" id="jextnfaq_span_coll">'.JText::_('COM_JEXTN_FAQPRO_COLLAPSE_ALL').'</span>';
		$jefaqpro .= '</div>';
		}
		$jefaqpro .= "<div id=\"yui-skin-sam\">";
		$jefaqpro .= 	"<div id=\"wrapper1\">";
		$jefaqpro .= 		"<ul id=\"mymenu".$faqcon.$settings->theme."\" class=\"mymenu".$settings->theme."\">";

		if(count($rows) > 0){
			foreach( $rows as $key=>$value)
			{

				$w	= $key+1;
				$jefaqpro .= 		"<li>";
				$jefaqpro .= 			"<p>$value->questions</p>";
				$jefaqpro .= 			"<div>";
				$jefaqpro .= 				"<div class=\"padded clearfix\">";
				if (($param->get('show_postedby') || $param->get('show_posteddate')) && $show_systems == true)
				{
					$jefaqpro .= 				"<div id=\"je-posted\" style=\"padding : 5px; text-align : right; font-style : italic;\">";
					if ($param->get('show_posteddate'))
					{
						$jefaqpro .= 				"<span id=\"je-posteddate\">";
														$date 	 = JFactory::getDate( $value->posted_date );
														$posted  = $date->format( $settings->date_format );
														$jefaqpro .=$posted;

						$jefaqpro .= 				"</span>";
					}
					if ($param->get('show_postedby'))
					{
						$jefaqpro .= 				"<span id=\"je-author\">&nbsp;&nbsp;$value->posted_by</span>";

					}
					$jefaqpro .= 				"</div>";
				}
				// Answer texts
				$jefaqpro .=$value->answers;

				//Area for voting & hits
				if($param->get('show_votes') && $show_systems == true)
				{

					$jefaqpro .= 				"<div style=\"padding : 5px 0px 5px 0px; \">";
					$jefaqpro .= 					"<ul id=\"je-response-ul\">";
					$jefaqpro .= 						"<li id=\"je-response\" >";
					$jefaqpro .= 							"<span id=\"je-userlogin$key\"></span>";
					$jefaqpro .= 						"</li>";
					$jefaqpro .= 						"<li id=\"je-response\" >";
					$jefaqpro .= 							"<span class=\"editlinktip hasTip\" title=\"".JText::_( 'JE_RESPONSE' )." <br> ".JText::_( 'JE_LIKE' ) ."\">";
					$jefaqpro .= 								"<span id=\"je-responsetop$key\">";

					$response_yes= $this->getTotalresponse( $like=true, $value->id );
					$jefaqpro .=$response_yes;

					$jefaqpro .= 								"</span>";
					$jefaqpro .= 								"<a id=\"je-atagtop$key\" href=\"javascript:void(0);\"  onclick=\"getResponselike('$key','$value->id')\" >";
					$jefaqpro .= 									"<span id=\"je-top\"> &nbsp; </span>";
					$jefaqpro .= 								"</a>";
					$jefaqpro .= 							"</span>";
					$jefaqpro .= 						"</li>";
					$jefaqpro .= 						"<li id=\"je-response\" >";
					$jefaqpro .= 							"<span class=\"editlinktip hasTip\" title=\"". JText::_( 'JE_RESPONSE' )." <br> ".JText::_( 'JE_DISLIKE' ) ."\">";
					$jefaqpro .= 								"<span id=\"je-responsebot$key\">";

					$response_no	= $this->getTotalresponse( $like=false, $value->id );
					$jefaqpro .=$response_no ;
					$jefaqpro .= 								"</span>";
					$jefaqpro .= 								"<a id=\"je-atagbot$key\"  href=\"javascript:void(0);\"  onclick=\"getResponsedislike('$key','$value->id')\"  >";
					$jefaqpro .= 									"<span id=\"je-bot\"> &nbsp; </span>";
					$jefaqpro .= 								"</a>";
					$jefaqpro .= 							"</span>";
					$jefaqpro .= 						"</li>";
					$jefaqpro .= 					"</ul>";
					$jefaqpro .= 				"</div>";
				}
				if($param->get('show_hits') && $show_systems == true)
				{
					$jefaqpro .= 				"<div id=\"je-hits$w\" style=\"text-align : right; padding : 2px; font-style : italic;\" >";
					$jefaqpro .= JText::_('JE_HITS').'&nbsp; '.$value->hits;
					$jefaqpro .= 				"</div>";

				}
				$jefaqpro .= 				"<input type=\"hidden\" name=\"ques_id$w\" id=\"ques_id$w\" value=\"$value->id\">";
				$jefaqpro .= 			"</div>";
				$jefaqpro .= 		"</div>";
				$jefaqpro .= 	"</li>";
				$k = 1 - $k;
			}
			$jefaqpro .= 	"</ul>";

			$jefaqpro .= "</div>";


			$jefaqpro .= "<script type=\"text/javascript\" src=".JURI::root().'components/com_jefaqpro/assets/js/accordionview.js'."></script>";
			$jefaqpro .= "<script type=\"text/javascript\">";
			$expand    = $param->get('expand_first',1) ? "expandItem : 0," : "";
			$jefaqpro .= "var menu1 = new YAHOO.widget.AccordionView('mymenu".$faqcon.$settings->theme."', {".$expand."collapsible: true, width: '100%', margin : '0', animationSpeed: '0.3', animate: true, effect: YAHOO.util.Easing.easeBothStrong})";
			$jefaqpro .= "</script>";
			$jefaqpro .= "<input type=\"hidden\" name=\"site_path\" id=\"site_path\" value=\"".JURI::root()."\" />";
			$jefaqpro .= "</div>";
			if(!$limitfaqitems){
				if(($total > 5) && $param->get('show_pagination',1)){
					//<!-- Pagination code start_faq -->
					$jefaqpro .= "<div class=\"pagination\">";
					$jefaqpro .= "<div id=\"jefaqpro-paginationarea\">";
					$jefaqpro .=	 	$pageNav1->getListFooter();
					$jefaqpro .=	 "<input type=\"hidden\" name=\"jelimitstart_faq\" value=\"$pageNav1->jelimitstart_faq\">";
					$jefaqpro .= "</div>";

					$jefaqpro .= "</div>";
					$jefaqpro .= '<div style="clear:both;"></div>';
					//<!-- Pagination code End -->
				}
			}

			$jefaqpro .= "</form>";
		} else {
			$jefaqpro.="<div style=\"text-align : center; font-weight: bold;\" >".JText::_("JE_FAQS")."</div>";
		}

		if($param->get('show_footertext'))
		{
			$jefaqpro .= "<p class=\"copyright\" style=\"text-align : center; font-size : 10px;\">";

			$pathToXML_File = JPATH_ADMINISTRATOR .'/components/com_jefaqpro/jefaqpro.xml';
			$xml	 		= JFactory::getXML($pathToXML_File,$isFile = true);
			$name 			= $xml->name;
			$version 		= $xml->version;
			$author 		= $xml->author;
			$authorurl 		= $xml->authorUrl;

			$jefaqpro .= $name."&nbsp;".$version."&nbsp;-";
			$jefaqpro .= $name['0']->_data."&nbsp;".$version['0']->_data;
			$jefaqpro .='<a href="http://www.jextn.com/" title="'. JText::_('JE_DEVELOPED').'" target="_blank">'.JText::_('JE_DEVELOPED').'</a>';

			$jefaqpro .= "</p>";
		}

		return $jefaqpro;

	}
	protected function checkNum($num)
	{
	  return ($num%2) ? true : false;
	}
}

