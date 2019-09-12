<?php
/**
 * @Copyright
 *
 * @package    Visitorcounter - VCNT for Joomla! 3
 * @author     Viktor Vogel <admin@kubik-rubik.de>
 * @version    3.3.0 - 2018-05-22
 * @link       https://joomla-extensions.kubik-rubik.de/vcnt-visitorcounter
 *
 * @license    GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') || die('Restricted access');

JLoader::register('ModVisitorcounterHelper', __DIR__ . '/helper.php');

$today = $params->get('today', JText::_('MOD_VISITORCOUNTER_TODAY'));
$yesterday = $params->get('yesterday', JText::_('MOD_VISITORCOUNTER_YESTERDAY'));
$all = $params->get('all', JText::_('MOD_VISITORCOUNTER_ALL'));
$xMonth = $params->get('month', JText::_('MOD_VISITORCOUNTER_MONTH'));
$xWeek = $params->get('week', JText::_('MOD_VISITORCOUNTER_WEEK'));
$sToday = $params->get('s_today');
$sYesterday = $params->get('s_yesterday');
$sAll = $params->get('s_all');
$sWeek = $params->get('s_week');
$sMonth = $params->get('s_month');
$cleanDb = (int) $params->get('clean_db', 1);
$linkToProject = $params->get('linktoproject', 1);
$horizontal = $params->get('horizontal');
$separator = $params->get('separator');
$horText = $params->get('hor_text');
$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx', ''));
$whoisonline = $params->get('whoisonline');
$whoisonlineLinknames = $params->get('whoisonline_linknames');
$whoisonlineSession = (int) $params->get('whoisonline_session');
$sqlCheck = $params->get('sql');

$start = new ModVisitorcounterHelper();

if ($sqlCheck) {
    $start->createSqlTables($cleanDb);
}

$start->countRequest($params);

if ($cleanDb) {
    $start->cleanDatabase();
}

if (!empty($whoisonline)) {
    $usersOnline = $start->whoIsOnline($whoisonlineSession);

    if (!empty($whoisonlineLinknames)) {
        $itemId = $start->getItemId($whoisonlineLinknames);
    }
}

$showAllowedUser = $start->showAllowedUser($params);

if ($showAllowedUser == 1) {
    list($allVisitors, $todayVisitors, $yesterdayVisitors, $weekVisitors, $monthVisitors) = $start->getData($params);
    JFactory::getDocument()->addStyleSheet('modules/mod_visitorcounter/mod_visitorcounter.css');

    require JModuleHelper::getLayoutPath('mod_visitorcounter', 'default');
}
