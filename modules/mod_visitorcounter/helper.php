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

use Joomla\Registry\Registry;

/**
 * Main helper class of the Visitorcounter module
 */
class ModVisitorcounterHelper
{
    protected $db;

    public function __construct()
    {
        $this->db = JFactory::getDbo();
    }

    /**
     * Creates the needed database tables to store the data of the visitorcounter
     *
     * @param int $cleanDb Determines whether the clean database option is activated
     */
    public function createSqlTables($cleanDb)
    {
        // Max. IPv6 string length is 45 - IPv4-mapped IPv6
        $query = "CREATE TABLE IF NOT EXISTS " . $this->db->quoteName('#__vcnt') . " (" . $this->db->quoteName('tm') . " INT NOT NULL, " . $this->db->quoteName('ip') . " VARCHAR(45) NOT NULL DEFAULT '0.0.0.0')";
        $this->db->setQuery($query);
        $this->db->execute();

        if (!empty($cleanDb)) {
            $query = "CREATE TABLE IF NOT EXISTS " . $this->db->quoteName('#__vcnt_pc') . " (" . $this->db->quoteName('cnt') . " INT NOT NULL DEFAULT '0')";
            $this->db->setQuery($query);
            $this->db->execute();

            $query = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt_pc');
            $this->db->setQuery($query);
            $numRows = $this->db->loadResult();

            if (empty($numRows)) {
                $query = "INSERT INTO " . $this->db->quoteName('#__vcnt_pc') . " VALUES(0)";
                $this->db->setQuery($query);
                $this->db->execute();
            }
        }
    }

    /**
     * Checks the call and counts it if conditions are fulfilled
     *
     * @param $params
     *
     * @throws Exception
     */
    public function countRequest($params)
    {
        $lockTime = $params->get('locktime', 60) * 60;
        $noBots = $params->get('nobots');
        $botsList = $params->get('botslist');
        $noIp = $params->get('noip');
        $ipsList = $params->get('ipslist');
        $anonymizeIp = $params->get('anonymize_ip');

        $now = time();
        $ip = $this->getIpAddress();

        if ($noBots) {
            $agent = $_SERVER['HTTP_USER_AGENT'];

            // Agent must be transmitted if Exclude Bots feature is used
            if (empty($agent)) {
                return;
            }

            $botsArray = array_map('trim', explode(',', $botsList));

            foreach ($botsArray as $botValue) {
                if (preg_match('@' . $botValue . '@i', $agent)) {
                    return;
                }
            }
        }

        if ($noIp) {
            // IP must be transmitted if "Ban IP Address" feature is used
            if (empty($ip)) {
                return;
            }

            $ipsArray = array_map('trim', explode(',', $ipsList));

            foreach ($ipsArray as $ipValue) {
                if (preg_match('@' . $ipValue . '@i', $ip)) {
                    return;
                }
            }
        }

        if ($anonymizeIp == 1) {
            // Anonymize IP - set last octet of address to 0
            $ip = substr($ip, 0, strrpos($ip, '.')) . '.0';
        } elseif ($anonymizeIp == 2) {
            // Create a sha1 hash of the IP using the secret key as salt
            $secretKey = JFactory::getApplication()->get('secret');
            $ip = sha1($ip . $secretKey);
        }

        // Check whether the same IP is not already counted or the reload time has expired
        $query = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('ip') . " = " . $this->db->quote($ip) . " AND (" . $this->db->quoteName('tm') . " + " . $this->db->quote($lockTime) . ") > " . $this->db->quote($now);
        $this->db->setQuery($query);
        $items = $this->db->loadResult();

        // Store the hit to the database
        if (empty($items)) {
            $query = "INSERT INTO " . $this->db->quoteName('#__vcnt') . " (" . $this->db->quoteName('tm') . ", " . $this->db->quoteName('ip') . ") VALUES (" . $this->db->quote($now) . ", " . $this->db->quote($ip) . ")";
            $this->db->setQuery($query);
            $this->db->execute();
        }
    }

    /**
     * Determines correct IP address (correct usage also with a proxy)
     *
     * @return mixed
     */
    private function getIpAddress()
    {
        $headers = $_SERVER;

        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        }

        $ipAddress = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);

        // Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            $ipAddress = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
            $ipAddress = $headers['HTTP_X_FORWARDED_FOR'];
        }

        return $ipAddress;
    }

    /**
     * Reads the current numbers from the database
     *
     * @param Registry $params
     *
     * @return array All needed information for the Visitorcounter
     * @throws Exception
     */
    public function getData($params)
    {
        // Set the correct timezone offset
        $siteOffset = JFactory::getApplication()->get('offset');
        date_default_timezone_set($siteOffset);

        // Calculate the needed time intervalls
        $day = date('d');
        $month = date('m');
        $year = date('Y');
        $dayStart = mktime(0, 0, 0, $month, $day, $year);
        $monthStart = mktime(0, 0, 0, $month, 1, $year);
        $weekStart = $dayStart - ((date('N') - 1) * 24 * 60 * 60);
        $yesterdayStart = $dayStart - (24 * 60 * 60);

        // Create queries for the database call
        $queries = array();

        $queries['query_all'] = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt');
        $queries['query_today'] = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('tm') . " > " . $this->db->quote($dayStart);
        $queries['query_yesterday'] = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('tm') . " > " . $this->db->quote($yesterdayStart) . " && " . $this->db->quoteName('tm') . " < " . $this->db->quote($dayStart);
        $queries['query_week'] = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('tm') . " >= " . $this->db->quote($weekStart);
        $queries['query_month'] = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('tm') . " >= " . $this->db->quote($monthStart);

        // Add the number from the cleaned database table
        $cleanDb = $params->get('clean_db');

        if (!empty($cleanDb)) {
            $queries['query_clean_db'] = "SELECT " . $this->db->quoteName('cnt') . " FROM " . $this->db->quoteName('#__vcnt_pc');
        }

        $queriesString = implode(' UNION ALL ', $queries);
        $this->db->setQuery($queriesString);
        $result = $this->db->loadRowList();

        $allVisitors = $result[0][0];

        // Add the preset number
        $preset = $params->get('preset');

        if (!empty($preset)) {
            $allVisitors += $preset;
        }

        if (!empty($cleanDb)) {
            $allVisitors += $result[5][0];
        }

        $todayVisitors = $result[1][0];
        $yesterdayVisitors = $result[2][0];
        $weekVisitors = $result[3][0];
        $monthVisitors = $result[4][0];

        return array($allVisitors, $todayVisitors, $yesterdayVisitors, $weekVisitors, $monthVisitors);
    }

    /**
     * Cleans the database from old entries which are not needed anymore for the output
     */
    public function cleanDatabase()
    {
        $siteOffset = JFactory::getApplication()->get('offset');
        date_default_timezone_set($siteOffset);

        $month = date('m');
        $year = date('Y');
        $monthStart = mktime(0, 0, 0, $month, 1, $year);

        $cleanStart = $monthStart - (8 * 24 * 60 * 60);

        $query = "SELECT count(*) FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('tm') . " < " . $this->db->quote($cleanStart);
        $this->db->setQuery($query);
        $oldRows = $this->db->loadResult();

        if (!empty($oldRows)) {
            $query = "UPDATE " . $this->db->quoteName('#__vcnt_pc') . " SET " . $this->db->quoteName('cnt') . " = " . $this->db->quoteName('cnt') . " + " . $this->db->quote($oldRows);
            $this->db->setQuery($query);
            $this->db->execute();

            $query = "DELETE FROM " . $this->db->quoteName('#__vcnt') . " WHERE " . $this->db->quoteName('tm') . " < " . $this->db->quote($cleanStart);
            $this->db->setQuery($query);
            $this->db->execute();
        }
    }

    /**
     * Checks the session table and creates a list with all guests and registered user who have an entry in the
     * database in the specified session time
     *
     * @param int $whoisonlineSession
     *
     * @return array $users_online All online visitors in the specified session time
     */
    public function whoIsOnline($whoisonlineSession)
    {
        $usersOnline = array();
        $guest = 0;
        $user = 0;
        $whoisonlineSession = time() - $whoisonlineSession * 60;

        $query = "SELECT " . $this->db->quoteName('guest') . ", " . $this->db->quoteName('client_id') . " , " . $this->db->quoteName('username') . ", " . $this->db->quoteName('userid') . " FROM " . $this->db->quoteName('#__session') . " WHERE " . $this->db->quoteName('client_id') . " = 0 AND " . $this->db->quoteName('time') . " > " . $this->db->quote($whoisonlineSession);
        $this->db->setQuery($query);
        $sessions = (array) $this->db->loadObjectList();

        if (!empty($sessions)) {
            $countedSession = array();

            foreach ($sessions as $session) {
                if ($session->guest == 1 && empty($session->usertype)) {
                    $guest++;
                    continue;
                }

                if (!in_array($session->username, $countedSession)) {
                    $user++;
                    $username = array('username' => $session->username, 'userid' => $session->userid);
                    $usersOnline['usernames'][] = $username;

                    $countedSession[] = $session->username;
                }
            }
        }

        $usersOnline['guest'] = $guest;
        $usersOnline['user'] = $user;

        return $usersOnline;
    }

    /**
     * Checks the group of the visitor to determine whether the template of the module has to be loaded
     *
     * @param Registry $params
     *
     * @return boolean
     */
    public function showAllowedUser($params)
    {
        $user = JFactory::getUser();

        $filterGroups = array_map('intval', (array) $params->get('filter_groups', 1));
        $userGroups = JAccess::getGroupsByUser($user->id);

        foreach ($userGroups as $usergroup) {
            if (in_array($usergroup, $filterGroups)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the Item ID of the component - the Item ID is the ID from the menu entry
     *
     * @param integer $whoisonlineLinknames
     *
     * @return mixed|string
     */
    public function getItemId($whoisonlineLinknames)
    {
        $itemId = '';
        $link = 'index.php?option=com_users&view=profile';

        if ($whoisonlineLinknames == 2) {
            $link = 'index.php?option=com_comprofiler';
        }

        $query = 'SELECT ' . $this->db->quoteName("id") . ' FROM ' . $this->db->quoteName("#__menu") . ' WHERE ' . $this->db->quoteName("link") . ' = "' . $link . '" AND ' . $this->db->quoteName("published") . ' = 1';
        $this->db->setQuery($query);
        $itemIdDb = $this->db->loadResult();

        if (!empty($itemIdDb)) {
            $itemId .= '&Itemid=' . $itemIdDb;
        }

        return $itemId;
    }
}
