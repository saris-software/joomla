<?php 
/** 
 * @package JMAP::CPANEL::administrator::components::com_jmap
 * @subpackage views
 * @subpackage cpanel
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<!-- ZIGSTAT SEOSTATS -->
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-align-left glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_mozrank}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_MOZRANK');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-certificate glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_mozdomainauth}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_MOZDOMAINAUTH');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-star glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_mozpageauth}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_MOZPAGEAUTH');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-dashboard glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_pagespeed}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_PAGESPEED');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-transfer glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_backlinks}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_BACKLINKS');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-align-right glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_alexarank}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_ALEXARANK');?></li>
	</ul>
</div>

<div class="single_stat_rowseparator"></div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-user glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_dailyvisitor}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_DAILYVISITORS');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-file glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{zigstat_dailypageviews}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ZIGSTAT_DAILYPAGEVIEWS');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-globe glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats semrush_popovers">
		<li></li>
		<li data-bind="{zigstat_backlinks_list}" class="es-stat-no hasClickPopover"><?php echo JText::_('COM_JMAP_ZIGSTAT_BACKLINKS_LIST');?></li>
	</ul>
</div>

<div class="well well-stats well-maxwidth well-hidden" data-bind="{website_report_text}"></div>