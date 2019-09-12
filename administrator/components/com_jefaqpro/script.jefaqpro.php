<?php
/**
* @package	 JE FAQPro
* @author    J-Extension <contact@jextn.com>
* @link      http://www.jextn.com
* @copyright Copyright (C) 2012 - 2013 J-Extension
* @license	 GNU/GPL, see LICENSE.php for full license.
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
define('DS', DIRECTORY_SEPARATOR);

/**
 * Script file of Ola component
 */
class Com_JEFAQProInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		$this->installitems();
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		echo '<div> <span class="label label-success">Success</span> <b> <span style="color:#009933"> JE FAQPro Component Uninstalled successfully </span></b> </div>';
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		$this->installitems();
	}
	
	function installitems(){
	
		// Joomla predefiend function to connect db
		$db			= JFactory::getDBO();
		
		//Used to Store category temporary for Joomla V1.5 FAQs Import
		$cquery_tcat	= "CREATE TABLE IF NOT EXISTS `#__jefaqpro_tempcat` (
						  `id` int(10) NOT NULL AUTO_INCREMENT,
						  `oldcatid` int(10) NOT NULL,
						  `newcatid` int(10) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store category temporary for Joomla V1.5 FAQs Import'";
		$db->setQuery($cquery_tcat);
		$db->execute();
		
		//Used to Store FAQ temporary for Joomla V1.5 FAQs Import
		$cquery_tfaq 	 = "CREATE TABLE IF NOT EXISTS `#__jefaqpro_tempfaq` (
						  `id` int(10) NOT NULL AUTO_INCREMENT,
						  `oldfaqid` int(10) NOT NULL,
						  `newfaqid` int(10) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store FAQ temporary for Joomla V1.5 FAQs Import'";
		$db->setQuery($cquery_tfaq);
		$db->execute();

		// Insert default configuration details
		$query 		= "SELECT count(*) FROM #__jefaqpro_settings";
		$db->setQuery( $query );
		$total_rows = $db->loadResult();

	// Check the whether the data's already there.
	if(!$total_rows) {
		$query  = "INSERT INTO `#__jefaqpro_settings` " .
				" (`id`, `theme`, `date_format`, `orderby`, `sortby`) " .
				" VALUES " .
				" (1, 1, 'l,j F Y', 'ordering',  'desc')";
		$db->setQuery( $query );
		$db->query();
	}

	// Insert template styles..
		$query 		= "SELECT count(*) FROM #__jefaqpro_themes";
		$db->setQuery( $query );
		$total_rows = $db->loadResult();

		if(!$total_rows) {
			$query = "INSERT INTO `#__jefaqpro_themes` (`id`, `themes`) VALUES
					(1, 'Triangular Light Blue'),
					(2, 'Light White'),
					(3, 'Medium Purple Arrow'),
					(4, 'Cadet Blue  Wheel'),
					(5, 'Parrot Green Circle'),
					(6, 'Light Steel Blue'),
					(7, 'Light Yellow Circle'),
					(8, 'Linen Arrow in Circle'),
					(9, 'Golden Rod Sqare'),
					(10, 'Black Triangle'),
					(11, 'Prosperity'),
					(12, 'Dependability'),
					(13, 'Earthiness'),
					(14, 'Freshness'),
					(15, 'Truthfulness'),
					(16, 'Sunshine'),
					(17, 'Moderation'),
					(18, 'Royalty'),
					(19, 'Strength and Endurance'),
					(20, 'Highly Spiritual'),
					(21, 'Cloudy'),
					(22, 'Multi High Spiritual'),
					(23, 'Multi line Freshness'),
					(24, 'Multi Strength and Endurance')";
			$db->setQuery( $query );
			$db->query();
		}
		
		// Code for Install jefaqpro search plugin.
		if (!JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'search'.DS.'jefaqpro')) {
			JFolder::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'jefaqpro', JPATH_ROOT.DS.'plugins'.DS.'search'.DS.'jefaqpro');

			if (!JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini')) {
				JFile::move(JPATH_ROOT.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini');
			}

			if(JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'search'.DS.'jefaqpro') && JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini')) {

				// Insert search plugin details.
				$query = "INSERT INTO `#__extensions` ( `extension_id` ,`name` ,`type` ,`element` ,`folder` ,`client_id` ,`enabled` ,`access` ,`protected` ,`manifest_cache` ,`params` ,`custom_data` ,`system_data` ,`checked_out` ,`checked_out_time` ,`ordering` ,`state` ) VALUES 
( NULL , 'Search - JE Faqpro', 'plugin', 'jefaqpro', 'search', '0', '1', '1', '0', '', '', '', '', '0', '0000-00-00 00:00:00', '0', '0' )";
				$db->setQuery( $query );
				$db->query();
			}
		} else {
			JFolder::delete(JPATH_ROOT.DS.'plugins'.DS.'search'.DS.'jefaqpro');
			JFile::delete(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini');
			
			JFolder::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'jefaqpro', JPATH_ROOT.DS.'plugins'.DS.'search'.DS.'jefaqpro');
			JFile::move(JPATH_ROOT.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_search_jefaqpro.ini');
		}
	// Code ended for Install search plugin.
	
	// Code for Install jefaqpro content plugin.
		if(!JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro')) {
				JFolder::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'jefaqpro', JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro');
			
			if (!JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.php')) {
			JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'jefaqpro.php',JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.php');
			}
			
			if (!JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.xml')) {
				JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'jefaqpro.xml',JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.xml');
			}
			
			if (!JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_content_jefaqpro.ini')) {
				JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'en-GB.plg_content_jefaqpro.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_content_jefaqpro.ini');
			}	
			if (!JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_content_jefaqpro.sys.ini')) {
				JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'en-GB.plg_content_jefaqpro.sys.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_content_jefaqpro.sys.ini');
			}				
			
			if(JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.php') && JFile::exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.xml') && JFile::exists(JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_content_jefaqpro.ini') && JFolder::exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro')) {
				
				//Insert content plugin details.
					$query = "INSERT INTO `#__extensions` (`extension_id`, `name`, `type`, `element`, `folder`, `client_id`, `enabled`, `access`, `protected`, `manifest_cache`, `params`, `custom_data`, `system_data`, `checked_out`, `checked_out_time`, `ordering`, `state`) VALUES ('', 'Content - JEFaqpro', 'plugin', 'jefaqpro', 'content', '', '0', '1', '0', '', '{\"sort\":\"ordering\",\"order\":\"desc\"}', '', '', '0', '0000-00-00 00:00:00', '0', '0');";
				$db->setQuery( $query );
				$db->query();		
				
				JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro');
			}
		} else
		{ 
			JFolder::delete(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro');
			
			JFolder::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'jefaqpro', JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro');	
			
			JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'jefaqpro.php',JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.php');
			
			JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'jefaqpro.xml',JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jefaqpro'.DS.'jefaqpro.xml');
			
			JFile::move(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro'.DS.'en-GB.plg_content_jefaqpro.ini',JPATH_ROOT.DS.'administrator'.DS.'language'.DS.'en-GB'.DS.'en-GB.plg_content_jefaqpro.ini');
			
			JFolder::delete(JPATH_ROOT.DS.'components'.DS.'com_jefaqpro'.DS.'plg_jeFaqpro');
		}
	
	// Code ended for Install jefaqpro content plugin.	

	// Message area.
	echo '<div class="alert alert-success">';
	echo '<h3>JE FAQPro component Installed Successfully. </h3>';
	echo '<h3>-- Also, The JE FAQPro content plugin has been installed successfully. </h3>';
	echo '<div class="alert alert-info"><p>Please enable this plugin from Extensions / Plugin Manager.</p>';
	echo '<div><p>JE FAQPro content plugin Replaces <b>{faqpro}</b> tag in content. It will be displays All FAQ"s.</p></div><div><p><b>{faqpro|theme|5}</b> It will be displays All FAQ"s with "<i>Parrot Green Circle</i>" theme.</p><p>Simply add <b>theme|themeid</b> on other tags (ex) <b>{faqpro|count|1|c|2|theme|15}</b></p><p><i>Get your favorite theme ID from FAQPro component "Global Settings"</i></p></p></div><div><p><b>{faqpro|f|1,2}</b> It will displays the FAQ of id 1 and 2</p></div><div><p><b>{faqpro|c|1,2}</b> It will displays all the FAQ from the category id 1 and 2</p></div><div><p><b>{faqpro|count|1}</b> It will displays only one faq from all FAQ\'s ( Ordering as per backend plugin params )</p></div><div><p><b>{faqpro|count|1|c|2}</b> It will displays only one faq from the category id 2 ( Ordering as per backend plugin params )</p></div>';
	echo '</div>';
	echo '</div>';
	
	echo '<div class="hero-unit" style="text-align:left;">';
    echo '<h1>JE FAQ Pro</h1>';
    echo '<p>JE FAQ Pro is an easy to use but powerful and excellent FAQ management. Our core competency from our front end and backend features will make you to suitable because we take care of your needs in the FAQ Joomla component needs. This is where we extending the suitability in Joomla.</p>';
    echo '<p>';
    echo '<a href="http://www.jextn.com/" target="_blank" class="btn btn-primary btn-large">';
    echo 'Learn more';
    echo '</a>';
    echo '</p>';
    echo '</div>';
	
	}
}
