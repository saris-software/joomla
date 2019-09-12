<?php
/**
 * ------------------------------------------------------------------------
 * JA Bulletin Module for J3.x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
$mainframe = JFactory::getApplication();
if (!defined('_MODE_JABULLETIN_ASSETS_')) {
    define('_MODE_JABULLETIN_ASSETS_', 1);
    JHTML::stylesheet('modules/' . $module->module . '/assets/style.css');
    if (is_file(JPATH_SITE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'css' . DS . $module->module . ".css"))
        JHTML::stylesheet('templates/' . $mainframe->getTemplate() . '/css/' . $module->module . ".css");
}

// Include the syndicate functions only once
require_once (dirname(__FILE__) . DS . 'helper.php');
require_once (dirname(__FILE__) . DS . 'jaimage.php');
$helper = new modJABulletin();
$useCustomText = $params->get("use_custom_text", 0);
$customText = $params->get("custom_text", "");
$showcreater = $params->get('show_author', 0);
$showreadmore = $params->get('show_readmore', 0);

// if enable caching data
$list = $helper->getListArticles($params);
$app =  JFactory::getApplication();
$template_name = $app->getTemplate();

require (JModuleHelper::getLayoutPath('mod_jabulletin'));
?>
<script type="text/javascript">
  var Ja_direction = '';
  var cookie_path = '/';
  var cur_template_name = '<?php echo $template_name; ?>';
  window.addEvent('load', function(){

		   if(typeof(tmpl_name) =='undefined')
		   {
			  cookie_path = "<?php echo $template_name."_direction"; ?>";
		   }
		   else
		   {
			  cookie_path = tmpl_name+"_direction";
		   }
		   Ja_direction = Cookie.read(cookie_path);
		   if(!Ja_direction)
		   {
				cookie_path = cookie_path.replace("_direction","_profile");
			   Ja_direction = Cookie.read(cookie_path);
		   }
		   var style_l_value = 'auto';
		   if(Ja_direction == 'rtl')
			{
			  <?php
			   if (!defined ('_MODE_JABULLETIN_ASSETS_RTL')) {
			     define ('_MODE_JABULLETIN_ASSETS_RTL',1);
			  ?>
					setStyleLinkWithRTLDirection();
			<?php
			   }
			?>
			}
	});
</script>
<script type="text/javascript" language="javascript">
 function setStyleLinkWithRTLDirection()
 {
    var links = document.getElementsByTagName ('link');
	<?php
		$filename = "mod_jabuletin_rtl.css";
		$tplpath = DS . 'templates' . DS . $mainframe->getTemplate () . DS . 'css' . DS;
		$tplurl = '/templates/' . $mainframe->getTemplate () . '/css/';
		$modurl = 'modules/'.$module->module.'/assets/';
		$cssurl = $tplurl;
		if (! file_exists ( JPATH_SITE . $tplpath . $filename )) {
			$cssurl = $modurl;
		}
		$cssurl = JURI::base () . $cssurl;
		?>
	var script = document.createElement('link');
	script.setAttribute('type', 'text/css');
	script.setAttribute('rel', 'stylesheet');
	script.setAttribute('href', '<?php
echo $cssurl . $filename;
?>');
	document.getElementsByTagName("head")[0].appendChild(script);
 }
</script>