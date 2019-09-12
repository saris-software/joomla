<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallModelRsfirewall extends JModelLegacy
{
	protected $config;
	
	public function __construct($config = array()) {
		parent::__construct($config);

		$this->config = RSFirewallConfig::getInstance();
	}
	
	public function getLastMonthLogs() {       
        $db     = $this->getDbo();
		$query  = $db->getQuery(true);
		
        // get the date format
        $format = $db->getDateFormat();
        // get the date class
        $date   = JFactory::getDate();
        $now    = JFactory::getDate();
       
        $date->modify('-30 days');
       
        $query->select("COUNT(`id`) AS num, YEAR(date) AS y, LPAD(MONTH(date), 2, '0') AS m, LPAD(DAY(date), 2, '0') AS d, level")
			  ->from('#__rsfirewall_logs')
			  ->where($db->qn('date').' > '.$db->q($date->format($format)))
			  ->group('level, y, m, d');
        $db->setQuery($query);
        $results = $db->loadObjectList();
       
        $nowformat = $now->format('d.m.Y');
        $dates = array();
        while ($nowformat != $date->format('d.m.Y')) {
            $format = $date->format('Y, m-1, d');
            $dates[$format] = array(
                'low'       => 0,
                'medium'	=> 0,
                'high'      => 0,
                'critical'  => 0
            );
            $date->modify('+1 day');
        }
        // add the current day as well
        $format = $date->format('Y, m-1, d');
        $dates[$format] = array(
            'low'        => 0,
            'medium'     => 0,
            'high'       => 0,
            'critical'   => 0
        );
	   
        foreach ($results as $result) {
            $y = $result->y;
            $m = $result->m.'-1';
            $d = $result->d;
           
            $format = "$y, $m, $d";
           
            if (!isset($dates[$format])) {
                $dates[$format] = array(
                    'low'       => 0,
                    'medium'    => 0,
                    'high'      => 0,
                    'critical'  => 0
                );
            }
           
            $dates[$format][$result->level] = $result->num;
        }
       
        return $dates;
    }
	
	public function getLogOverviewNum() {
		return $this->config->get('log_overview');
	}
	
	public function getLastLogs() {
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);		
		$query->select('*')
			  ->from('#__rsfirewall_logs')
			  ->order($db->qn('date').' DESC');
		
		$db->setQuery($query, 0, $this->getLogOverviewNum());
		return $db->loadObjectList();
	}
	
	public function getCode() {
		return $this->config->get('code');
	}
	
	public function getFeeds() {
		$feeds  = array();
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);		
		$query->select('*')
			  ->from('#__rsfirewall_feeds')
			  ->where($db->qn('published').'='.$db->q(1))
			  ->order($db->qn('ordering').' ASC');
		$db->setQuery($query);
		$items = $db->loadObjectList();
		foreach ($items as $item) {
			if ($feed = $this->getParsedFeed($item)) {
				$feeds[] = $feed;
			}
		}
		
		return $feeds;
	}
	
	protected function getParsedFeed($item) {
		$parsedFeed = new stdClass;
		$url	 	= $item->url;

		// 3.x
		$parser = new JFeedFactory;
		try {
			$feed = $parser->getFeed($url);
		} catch (Exception $e) {
			JError::raiseError($e->getCode(), $e->getMessage());
			return false;
		}

		$parsedFeed->title = $feed->title;
		$parsedFeed->items = array();

		if (!empty($feed[0])) {
			for ($i = 0; $i < $item->limit; $i++) {
				if (!empty($feed[$i])) {
					$uri = !empty($feed[$i]->guid) || !is_null($feed[$i]->guid) ? $feed[$i]->guid : $feed[$i]->uri;
					$uri = substr($uri, 0, 4) != 'http' ? '' : $uri;

					$parsedFeed->items[] = (object) array(
						'title' => $feed[$i]->title,
						'date' 	=> $feed[$i]->updatedDate,
						'link'	=> $uri
					);
				}
			}

		}

		return $parsedFeed;
	}
	
	public function getModifiedFiles() {
		$db 		= $this->getDbo();
		$query 		= $db->getQuery(true);		
		$jversion 	= new JVersion();
		
		$query->select('*')
			  ->from('#__rsfirewall_hashes')
			  ->where('('.$db->qn('type').'='.$db->q('protect').' OR '.$db->qn('type').'='.$db->q($jversion->getShortVersion()).')')
			  ->where($db->qn('flag').'!='.$db->q(''));
		$db->setQuery($query);
		$files = $db->loadObjectList();
		foreach ($files as $i => $file) {
			$file->error = false;
			$file->path  = $file->type == 'protect' ? $file->file : JPATH_SITE.DIRECTORY_SEPARATOR.$file->file;
			
			if (!is_file($file->path)) {
				$file->modified_hash = JText::_('COM_RSFIREWALL_FILE_IS_MISSING');
				$file->error = true;
			} elseif (!is_readable($file->path)) {
				$file->modified_hash = JText::sprintf('COM_RSFIREWALL_COULD_NOT_READ_FILE', $file->file);
				$file->error = true;
			} else {
				$file->modified_hash = md5_file($file->path);
			}
			
			if ($file->modified_hash === $file->hash) {
				unset($files[$i]);
				
				$query->clear()
					  ->update($db->qn('#__rsfirewall_hashes'))
					  ->set($db->qn('flag').'='.$db->q(''))
					  ->where($db->qn('id').'='.$db->q($file->id));
				
				$db->setQuery($query)->execute();
			}
		}
		
		return $files;
	}
	
	public function acceptModifiedFiles($cids) {
		$files = $this->getModifiedFiles();
		
		foreach ($files as $file) {
			if (!$file->error) {
				$table = JTable::getInstance('Hashes', 'RsfirewallTable');
				$table->bind(array(
					'id' => $file->id,
					'hash' => $file->modified_hash,
					'flag' => ''
				));
				$table->store();
			}
		}
		
		return true;
	}

	public function getCountryBlocking()
	{
		return $this->config->get('blocked_countries');
	}

	public function getGeoIPStatus()
	{
		// Load model
		require_once JPATH_ADMINISTRATOR . '/components/com_rsfirewall/models/configuration.php';
		$model = new RsfirewallModelConfiguration();
		
		// Get info on GeoIP
		$info = $model->getGeoIPInfo();
		
		// Does it work?
		return $info->works;
	}

	public function isPluginEnabled() {
		return JPluginHelper::isEnabled('system', 'rsfirewall');
	}
	
	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';
		
		return RSFirewallToolbarHelper::render();
	}
}