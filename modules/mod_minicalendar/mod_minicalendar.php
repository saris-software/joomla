<?php
/********************************************************************
Product    : MiniCalendar
Date       : 17 January 2014
Copyright  : Les Arbres Design 2009-2014
Contact    : http://www.lesarbresdesign.info
Licence    : GNU General Public License
*********************************************************************/
defined('_JEXEC') or die('Restricted Access');

require_once (dirname(__FILE__).'/helper.php');

// Get module parameters

$startyear 	     = trim($params->get('startyear'));
$startmonth	     = trim($params->get('startmonth'));
$numMonths 	     = trim($params->get('numMonths',1));
$numCols 	     = trim($params->get('numCols',1));
$timeZone	     = $params->get('timeZone',0);
$day_name_length = trim($params->get('dayLength',1));	// length of the day names
$start_day       = trim($params->get('firstDay',0));	// 0 for Sunday, 1 for Monday, etc
$weekHdr         = trim($params->get('weekHdr'));
$debug 		     = $params->get('debug',0);

if ($debug)
	mc_init_debug();
else
	@unlink(JPATH_ROOT.'/modules/mod_minicalendar/trace.txt');

// If any internal styles are defined, add them to the document head

$styles = '';
$style_table = trim($params->get('style_table'));
if ($style_table != '')
	$styles .= "\n.mod_minical_table {".$style_table.'}';
$style_head = trim($params->get('style_head'));
if ($style_head != '')
	$styles .= "\n.mod_minical_table th {".$style_head.'}';
$style_day = trim($params->get('style_day'));
if ($style_day != '')
	$styles .= "\n.mod_minical_table td {".$style_day.'}';
$style_nonday = trim($params->get('style_nonday'));
if ($style_nonday != '')
	$styles .= "\n.mod_minical_table td.mod_minical_nonday {".$style_nonday.'}';
$style_today = trim($params->get('style_today'));
if ($style_today != '')
	$styles .= "\n.mod_minical_table td#mod_minical_today {".$style_today.'}';
$style_week = trim($params->get('style_week'));
if ($style_week != '')
	$styles .= "\n.mod_minical_weekno {".$style_week.'}';
$style_div = trim($params->get('style_div'));
if ($style_div != '')
	$styles .= "\n.mod_minical_div {".$style_div.'}';
if ($styles != '')
	{
	$style = "\n".'<style type="text/css">'.$styles."\n</style>\n";
	$document = JFactory::getDocument();
	$document->addCustomTag($style);
	}

if (($timeZone != '0') and (function_exists('date_default_timezone_set')))
	date_default_timezone_set($timeZone);

// Set the month and year, defaulting to the current month

if ($startyear)
	$year 	= $startyear;
else
	$year 	= date('Y');

if ($startmonth)
	$month 	= $startmonth;
else
	$month 	= date('m');

$startdate = mktime(0,0,0,$month, 1, $year);
$month = date('m',$startdate);
$year = date('Y',$startdate);

// Draw the number of calendars requested in the module parameters

echo '<table><tr style="vertical-align:top">';
$colcount = 0;
for ($monthcount = 1; $monthcount <= $numMonths ; $monthcount ++)
	{
	$colcount ++;
	echo '<td>';
	echo make_calendar($year, $month, $day_name_length, $start_day, $weekHdr, $debug);
	$link = '';						// only draw links on first calendar
	echo '</td>';
	if (($colcount == $numCols) && ($monthcount < $numMonths))
		{
		echo '</tr><tr><td colspan="'.$numCols.'"><div class="mod_minical_div"></div></td></tr><tr style="vertical-align:top">';
		$colcount = 0;
		}
	$month ++;
	if ($month > 12)
		{
		$month = 1;
		$year ++;
		}
	}
echo '</tr></table>';

?>
