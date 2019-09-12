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
<!-- ALEXA SEOSTATS -->
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-align-left glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{alexa_rank}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ALEXA_PAGE_RANK');?></li>
	</ul>
</div>
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-export glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{google_pagerank}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_GOOGLE_PAGE_RANK');?></li>
	</ul>
</div>
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-hdd glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{google_indexed_links}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_GOOGLE_INDEXED_LINKS');?></li>
	</ul>
</div>
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-transfer glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{alexa_backlinks}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ALEXA_BACKLINKS');?></li>
	</ul>
</div>
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-dashboard glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{alexa_pageload_time}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ALEXA_PAGELOADTIME');?></li>
	</ul>
</div>
<div class="single_stat_container alexachart alexachart_top">
	<ul class="subdescription_stats alexachart">
		<li data-bind="{alexa_graph}" class="es-stat-no fancybox-image"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ALEXA_GRAPH');?></li>
	</ul>
</div>

<div class="single_stat_rowseparator"></div>
	
<!-- SEMRush row -->
<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-align-right glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{semrush_rank}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_SEMRUSH_RANK');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-tag glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats semrush_popovers">
		<li></li>
		<li data-bind="{semrush_keywords}" class="es-stat-no hasClickPopover"><?php echo JText::_('COM_JMAP_SEMRUSH_KEYWORDS');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-globe glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats semrush_popovers">
		<li></li>
		<li data-bind="{semrush_competitors}" class="es-stat-no hasClickPopover"><?php echo JText::_('COM_JMAP_SEMRUSH_COMPETITORS');?></li>
	</ul>
</div>

<div class="single_stat_container">
	<div class="statcircle">
		<span class="glyphicon glyphicon-book glyphicon-large"></span>
	</div>
	<ul class="subdescription_stats">
		<li data-bind="{alexa_daily_pageviews}" class="es-stat-no"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_ALEXA_DAILY_PAGEVIEWS');?></li>
	</ul>
</div>

<div class="single_stat_container alexachart">
	<ul class="subdescription_stats alexachart">
		<li data-bind="{semrush_graph}" class="es-stat-no fancybox-image"></li>
		<li class="es-stat-title"><?php echo JText::_('COM_JMAP_SEMRUSH_GRAPH');?></li>
	</ul>
</div>
