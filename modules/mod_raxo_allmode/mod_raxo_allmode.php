<?php
/**
 * =============================================================
 * @package		RAXO All-mode PRO J3.x
 * -------------------------------------------------------------
 * @copyright	Copyright (C) 2009-2016 RAXO Group
 * @link		http://www.raxo.org
 * @license		GNU General Public License v2.0
 * 				http://www.gnu.org/licenses/gpl-2.0.html
 * =============================================================
 */


defined('_JEXEC') or die;


// Check the page type
if ($params->get('hide_option', 0))
{
	$input = JFactory::getApplication()->input;
	if ($input->get('option') == 'com_content' && $input->get('view') == 'article')
	{
		return;
	}
}


// Include the helper functions
require_once __DIR__ .'/helper.php';


// Module cache parameters
$cacheparams				= new stdClass;
$cacheparams->cachemode		= 'itemid';
if ($params->get('ordering') == 'random' || !$params->get('current_item', 0))
{
	$cacheparams->cachemode	= 'safeuri';
}
$cacheparams->class			= 'ModRaxoAllmodeHelper';
$cacheparams->method		= 'getList';
$cacheparams->methodparams	= $params;
$cacheparams->modeparams	= array('id' => 'int', 'Itemid' => 'int');


// Get module data
$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
if (empty($list))
{
	return;
}

// Separate the top items
$count_top		= (int) $params->get('count_top', 2);
$toplist		= array();
if ($count_top) {
	$toplist	= array_slice($list, 0, $count_top);
	$list		= array_slice($list, $count_top);
}


// Module layout name
$module_layout	= $params->get('module_layout', '_:raxo-columns');
$layout_name	= explode(':', $module_layout);
$layout_name	= isset($layout_name[1]) ? $layout_name[1] : '';
$layout_path	= JModuleHelper::getLayoutPath('mod_raxo_allmode', $module_layout);
$module_class	= htmlspecialchars($params->get('module_class'), ENT_COMPAT, 'UTF-8');
$module_class	= trim('allmode-box '. $layout_name .' '. $module_class);
?>

<div class="<?php echo $module_class; ?>">
<?php

// Module block name
$blockname_text	= trim($params->get('name_text'));
$blockname_link	= trim($params->get('name_link'));
if ($blockname_text && $blockname_link) {
	echo '<h3 class="allmode-name"><a href="'. $blockname_link .'"><span>'. $blockname_text .'</span></a></h3>';
} elseif ($blockname_text) {
	echo '<h3 class="allmode-name"><span>'. $blockname_text .'</span></h3>';
}

// Render the module layout
if (file_exists($layout_path))
{
	require($layout_path);
}
else
{
	echo JText::_('MOD_RAXO_ALLMODE_ERROR_LAYOUT');
}

// Module show all link
$showall_text	= trim($params->get('showall_text'));
$showall_link	= trim($params->get('showall_link'));
if ($showall_text && $showall_link) {
	echo '<div class="allmode-showall"><a href="'. $showall_link .'">'. $showall_text .'</a></div>';
} elseif ($showall_text) {
	echo '<div class="allmode-showall">'. $showall_text .'</div>';
}
?>
</div>
