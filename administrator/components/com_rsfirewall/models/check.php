<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallModelCheck extends JModelLegacy
{
	const HASHES_DIR = '/components/com_rsfirewall/assets/hashes/';
	const SIGS_DIR = '/components/com_rsfirewall/assets/sigs/';
	const DICTIONARY = '/components/com_rsfirewall/assets/dictionary/passwords.txt';
	const CHUNK_SIZE = 2048;

	protected $count 	= 0;
	protected $folders 	= array();
	protected $files 	= array();
	protected $limit 	= 0;

	protected $ignored = array();

	protected $log = false;

	public function __construct($config = array()) {
		parent::__construct($config);

		// Enable logging
		if ($this->getConfig()->get('log_system_check') && is_writable(JFactory::getConfig()->get('log_path'))) {
			$this->log = true;
		}
	}

	protected function addLogEntry($data, $error=false) {
		if (!$this->log) {
			return false;
		}

		static $path;
		if (!$path) {
			$path = JFactory::getConfig()->get('log_path').'/rsfirewall.log';
		}
		$prepend = gmdate('Y-m-d H:i:s ');
		if ($error) {
			$prepend .= '** ERROR ** ';
		}
		return file_put_contents($path, $prepend.$data."\n", FILE_APPEND);
	}

	public function getConfig() {
		return RSFirewallConfig::getInstance();
	}

	protected function connect($url, $caching = true) {
		$cache = JFactory::getCache('com_rsfirewall');
		$cache->setCaching($caching);

		try {
			$response = $cache->get(array('RsfirewallModelCheck', 'connectCache'), array($url));
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		return $response;
	}

	public static function connectCache($url) {
		$http = JHttpFactory::getHttp();
		$response = $http->get($url, array(), 30);

		return $response;
	}

	public function getCurrentJoomlaVersion() {
		static $current = null;

		if (is_null($current)) {
			$jversion 	= new JVersion();
			$current	= $jversion->getShortVersion();
			// workaround for DutchJoomla! and other variations
			if (strpos($current, ' ') !== false) {
				$current = reset(explode(' ', $current));
			}
		}

		return $current;
	}

	protected function _loadPasswords() {
		static $passwords;
		if (is_null($passwords)) {
			jimport('joomla.filesystem.file');

			$passwords = array();
			if ($contents = file_get_contents(JPATH_ADMINISTRATOR.self::DICTIONARY)) {
				$passwords = $this->explode($contents);
			}
		}

		return $passwords;
	}

	protected function explode($string) {
		$string = str_replace(array("\r\n", "\r"), "\n", $string);
		return explode("\n", $string);
	}

	protected function checkWeakPassword($original) {
		$passwords = $this->_loadPasswords();
		foreach ($passwords as $password) {
			if ($original == $password)
				return $password;
		}

		return false;
	}

	protected function isWindows() {
		static $result = null;
		if (!is_bool($result)) {
			$result = substr(PHP_OS, 0, 3) == 'WIN';
		}
		return $result;
	}

	public function getIsWindows() {
		return $this->isWindows();
	}

	public function getIsPHP54() {
		return version_compare(phpversion(), '5.4.0', '>=');
	}

	public function getIsOldIE() {
		$browser = JBrowser::getInstance();
		return $browser->getBrowser() == 'msie' && $browser->getMajor() < 9;
	}

	public function checkJoomlaVersion() {
		$this->addLogEntry('System check started.');

		$code 	 = $this->getConfig()->get('code');
		$current = $this->getCurrentJoomlaVersion();
		$url 	 = 'http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=version&version=joomla&current='.urlencode($current).'&code='.urlencode($code);

		// could not connect
		if (!($response = $this->connect($url))) {
			return false;
		}

		// error response code
		if ($response->code != 200) {
			if (isset($response->headers) && is_array($response->headers) && isset($response->headers['Reason'])) {
				$this->setError(strip_tags($response->headers['Reason']));
				return false;
			}
			$this->setError(JText::sprintf('COM_RSFIREWALL_HTTP_ERROR_RESPONSE_CODE', $response->code));
			return false;
		}

		$latest = $response->body;

		return array($current, $latest, version_compare($current, $latest, '>='));
	}

	public function checkRSFirewallVersion() {
		$code 	 = $this->getConfig()->get('code');
		$current = $this->getCurrentJoomlaVersion();
		$version = new RSFirewallVersion();
		$url 	 = 'http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=version&version=firewall&current='.urlencode($current).'&firewall='.urlencode((string) $version).'&code='.urlencode($code);

		// could not connect
		if (!($response = $this->connect($url))) {
			return false;
		}

		// error response code
		if ($response->code != 200) {
			if (isset($response->headers) && is_array($response->headers) && isset($response->headers['Reason'])) {
				$this->setError(strip_tags($response->headers['Reason']));
				return false;
			}
			$this->setError(JText::sprintf('COM_RSFIREWALL_HTTP_ERROR_RESPONSE_CODE', $response->code));
			return false;
		}

		$current = (string) $version;
		$latest  = $response->body;

		return array($current, $latest, version_compare($current, $latest, '>='));
	}

	public function checkSQLPassword() {
		if (($password = $this->checkWeakPassword(JFactory::getConfig()->get('password'))) !== false) {
			return $password;
		}

		return false;
	}

	public function hasAdminUser() {
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);

		$query->select($db->qn('id'))
			  ->from($db->qn('#__users'))
			  ->where($db->qn('username').'='.$db->q('admin'))
			  ->where($db->qn('block').'='.$db->q('0'));

		$db->setQuery($query);
		return $db->loadResult();
	}

	public function hasFTPPassword() {
		return JFactory::getConfig()->get('ftp_pass') != '';
	}

	public function isSEFEnabled() {
		return JFactory::getConfig()->get('sef') > 0;
	}

	public function buildConfiguration($overwrite=null) {
		$data = get_object_vars(new JConfig());
		if (is_array($overwrite)) {
			foreach ($overwrite as $key => $value) {
				if (isset($data[$key]))
					$data[$key] = $value;
			}
		}

		return $this->arrayToString($data);
	}

	protected function arrayToString($object) {
		// Build the object variables string
		$vars = '';

		foreach ($object as $k => $v)
		{
			if (is_scalar($v))
			{
				$vars .= "\tpublic $" . $k . " = '" . addcslashes($v, '\\\'') . "';\n";
			}
			elseif (is_array($v) || is_object($v))
			{
				$vars .= "\tpublic $" . $k . " = " . $this->getArrayString((array) $v) . ";\n";
			}
		}

		$str = "<?php\nclass JConfig {\n";
		$str .= $vars;
		$str .= "}";

		return $str;
	}

	protected function getArrayString($a)
	{
		$s = 'array(';
		$i = 0;

		foreach ($a as $k => $v)
		{
			$s .= ($i) ? ', ' : '';
			$s .= '"' . $k . '" => ';

			if (is_array($v) || is_object($v))
			{
				$s .= $this->getArrayString((array) $v);
			}
			else
			{
				$s .= '"' . addslashes($v) . '"';
			}

			$i++;
		}

		$s .= ')';

		return $s;
	}
	
	public function getDisableFunctions()
	{
		return array(
			'system',
			'shell_exec',
			'passthru',
			'exec',
			'popen',
			'proc_open'
		);
	}

	public function buildPHPini() {
		$isPHP54 = $this->getIsPHP54();

		$contents = array(
			'expose_php=Off',
			'allow_url_include=Off',
			'disable_functions=' . implode(', ', $this->getDisableFunctions())
		);

		if (!$isPHP54) {
			$contents[] = 'register_globals=Off';
			$contents[] = 'safe_mode=Off';
		}

		if ($this->compareINI('open_basedir', '')) {
			$paths 		= array();
			$delimiter 	= $this->isWindows() ? ';' : ':';

			// add the path to the Joomla! installation
			if (JPATH_SITE) {
				$paths[] = JPATH_SITE;
			}
			// add the path to the Joomla! configuration if it's not in the default location
			if (JPATH_CONFIGURATION && JPATH_CONFIGURATION != JPATH_SITE) {
				$paths[] = JPATH_CONFIGURATION;
			}
			// try to add the path for the server temporary folder
			if ($path = $this->getINI('upload_tmp_dir')) {
				$paths[] = $path;
			}
			if ($temp_dir = sys_get_temp_dir()) {
				$paths[] = $temp_dir;
			}
			// try to add the path for the server session folder
			if ($path = $this->getINI('session.save_path')) {
				$paths[] = $path;
			}
			$paths[] = $this->getTemporaryFolder();
			$paths[] = $this->getLogFolder();

			$paths = array_filter($paths);

			$contents[] = 'open_basedir='.implode($delimiter, array_unique($paths));
		} else {
			$contents[] = 'open_basedir='.$this->getINI('open_basedir');
		}

		return implode("\r\n", $contents);
	}

	public function isConfigurationModified() {
		jimport('joomla.filesystem.file');

		$reflector 	= new ReflectionClass('JConfig');
		$config 	= $reflector->getFileName();

		$contents 		= file_get_contents($config);
		$configuration 	= $this->buildConfiguration();

		if ($contents != $configuration) {
			$contents = explode("\n", $contents);
			$configuration = explode("\n", $configuration);
			$diff  = array_diff($contents, $configuration);

			return $diff;
		} else {
			return false;
		}
	}

	protected function getAdminUsers() {
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/users.php';

		return RSFirewallUsersHelper::getAdminUsers();
	}

	public function checkAdminPasswords() {
		$passwords 	= $this->_loadPasswords();
		$users	   	= $this->getAdminUsers();
		$return 	= array();

		foreach ($users as $user) {
			foreach ($passwords as $password) {
				$match = false;
				if (substr($user->password, 0, 4) == '$2y$') {
					// Cracking these passwords is extremely CPU intensive, skip.
					continue 2;
				} elseif (substr($user->password, 0, 8) == '{SHA256}') {
					// Check the password
					$parts	= explode(':', $user->password);
					$crypt	= $parts[0];
					$salt	= @$parts[1];
					$testcrypt = JUserHelper::getCryptedPassword($password, $salt, 'sha256', false);

					if ($user->password == $testcrypt) {
						$match = true;
					}
				} else {
					// Check the password
					$parts	= explode(':', $user->password);
					$crypt	= $parts[0];
					$salt	= @$parts[1];

					$testcrypt = JUserHelper::getCryptedPassword($password, $salt, 'md5-hex', false);

					if ($crypt == $testcrypt) {
						$match = true;
					}
				}

				if ($match === true) {
					$found = new stdClass();
					$found->username = $user->username;
					$found->password = $password;

					$return[] = $found;
					break;
				}
			}
		}

		return $return;
	}

	public function getSessionLifetime() {
		return JFactory::getConfig()->get('lifetime');
	}

	public function getTemporaryFolder() {
		return JFactory::getConfig()->get('tmp_path');
	}

	public function getLogFolder() {
		return JFactory::getConfig()->get('log_path');
	}

	public function getServerSoftware() {
		if (preg_match('#IIS/([\d.]*)#', $_SERVER['SERVER_SOFTWARE'])) {
			return 'iis';
		}

		return 'apache';
	}

	public function getFiles($folder, $recurse=false, $sort=true, $fullpath=true, $ignore=array()) {
		if (!is_dir($folder)) {
			$this->addLogEntry("[getFiles] $folder is not a valid folder!", true);

			$this->setError("$folder is not a valid folder!");
			return false;
		}

		$arr = array();

		try {
			$handle = @opendir($folder);
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..' && !in_array($file, $ignore)) {
					$dir = $folder . DIRECTORY_SEPARATOR . $file;
					if (is_file($dir)) {
						if ($fullpath) {
							$arr[] = $dir;
						} else {
							$arr[] = $file;
						}
					} elseif (is_dir($dir) && $recurse) {
						$arr = array_merge($arr, $this->getFiles($dir, $recurse, $sort, $fullpath, $ignore));
					}
				}
			}
			closedir($handle);
		}
		catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		if ($sort) {
			asort($arr);
		}
		return $arr;
	}

	public function getFolders($folder, $recurse=false, $sort=true, $fullpath=true) {
		if (!is_dir($folder)) {
			$this->addLogEntry("[getFolders] $folder is not a valid folder!", true);

			$this->setError(JText::sprintf('COM_RSFIREWALL_FOLDER_IS_NOT_A_VALID_FOLDER', $folder));
			return false;
		}

		$arr = array();

		try {
			$handle = @opendir($folder);
			if ($handle) {
				while (($file = readdir($handle)) !== false) {
					if ($file != '.' && $file != '..') {
						$dir = $folder . DIRECTORY_SEPARATOR . $file;
						if (is_dir($dir)) {
							if ($fullpath) {
								$arr[] = $dir;
							} else {
								$arr[] = $file;
							}
							if ($recurse) {
								$arr = array_merge($arr, $this->getFolders($dir, $recurse, $sort, $fullpath));
							}
						}
					}
				}
				closedir($handle);
			} else {
				$this->setError(JText::sprintf('COM_RSFIREWALL_FOLDER_CANNOT_BE_OPENED', $folder));
				return false;
			}
		}
		catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		if ($sort) {
			asort($arr);
		}

		return $arr;
	}

	protected function getParent($path) {
		$parts   = explode(DIRECTORY_SEPARATOR, $path);
		array_pop($parts);

		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	protected function getAdjacentFolder($folder) {
		// one level up
		$parent = $this->getParent($folder);
		$folders = $this->getFolders($parent, false, false, true);
		if ($this->ignored['folders']) {
			// remove ignored folders
			$folders = array_diff($folders, $this->ignored['folders']);
			// renumber indexes
			$folders = array_merge(array(), $folders);
		}
		if ($folders !== false) {
			if (($pos = array_search($folder, $folders)) !== false) {
				if (isset($folders[$pos+1])) {
					return $folders[$pos+1];
				} else {
					if ($parent == JPATH_SITE || $parent == '/') {
						// this means that there are no more folders left in the Joomla! installation
						// so we're done here
						return false;
					}

					// up again
					return $this->getAdjacentFolder($parent);
				}
			}
		} else {
			return false;
		}
	}

	protected function getAdjacentFolderFiles($folder) {
		if ($folder == JPATH_SITE) {
			return false;
		}

		// one level up
		$parent = $this->getParent($folder);
		$folders = $this->getFolders($parent, false, false, true);

		if ($this->ignored['folders']) {
			// remove ignored folders
			$folders = array_diff($folders, $this->ignored['folders']);
			// renumber indexes
			$folders = array_merge(array(), $folders);
		}
		if ($folders !== false) {
			if (($pos = array_search($folder, $folders)) !== false) {
				if (isset($folders[$pos+1])) {
					return $folders[$pos+1];
				} else {

					if (!$this->addFiles($parent, false)) {
						return false;
					}

					if ($parent == JPATH_SITE || $parent == '/') {
						// this means that there are no more folders left in the Joomla! installation
						// so we're done here
						return false;
					}

					// up again
					return $this->getAdjacentFolderFiles($parent);
				}
			}
		} else {
			return false;
		}
	}

	public function getFoldersLimit($folder) {
		if (!is_dir($folder)) {
			$this->setError(JText::sprintf('COM_RSFIREWALL_FOLDER_IS_NOT_A_VALID_FOLDER', $folder));
			return false;
		}

		try {
			$handle = @opendir($folder);
			if ($handle) {
				if (!is_link($folder)) {
					while (($file = readdir($handle)) !== false) {
						// check the limit
						if (count($this->folders) >= $this->limit) {
							$this->addLogEntry("[getFoldersLimit] Limit '{$this->limit}' reached!");

							return true;
						}
						$dir = $folder . DIRECTORY_SEPARATOR . $file;
						if ($file != '.' && $file != '..' && is_dir($dir)) {
							// is it ignored? if so, continue
							if (in_array($dir, $this->ignored['folders'])) {
								$this->addLogEntry("[getFoldersLimit] Skipping '$dir' because it's ignored.");

								continue;
							}

							$this->addLogEntry("[getFoldersLimit] Adding '$dir' to array.");

							$this->folders[] = $dir;
							$this->getFoldersLimit($dir);
							return true;
						}
					}
				}
				closedir($handle);
			} else {
				$this->addLogEntry("[getFoldersLimit] Error opening $folder!");
			}

			// try to find the next folder
			if (($dir = $this->getAdjacentFolder($folder)) !== false) {
				$this->addLogEntry("[getFoldersLimit] Adding adjacent '$dir' to array.");

				$this->folders[] = $dir;
				$this->getFoldersLimit($dir);
			}
		}
		catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}

	public function getFilesLimit($startfile) {
		if (is_file($startfile)) {
			$folder = dirname($startfile);
			$scan_subdirs = false;
		} else {
			$folder = $startfile;
			$scan_subdirs = true;
		}

		$this->addLogEntry("[getFilesLimit] Reading from '$startfile'");

		try {
			$handle = @opendir($folder);
			if ($handle) {
				if (!is_link($folder)) {
					if ($scan_subdirs) {
						while (($file = readdir($handle)) !== false) {
							$path = $folder . DIRECTORY_SEPARATOR . $file;
							if ($file != '.' && $file != '..' && is_dir($path)) {
								// is it ignored? if so, continue
								if (in_array($path, $this->ignored['folders'])) {
									continue;
								}

								$this->getFilesLimit($path);
								return true;
							}
						}
					}
				}
				closedir($handle);

				if (!$this->addFiles($folder, is_file($startfile) ? $startfile : false)) {
					return true;
				}
			} else {
				$this->addLogEntry("[getFilesLimit] Error opening $folder!");
			}

			// done here, try to find the next folder to parse
			if (($dir = $this->getAdjacentFolderFiles($folder)) !== false) {
				$this->getFilesLimit($dir);
			}
		}
		catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}

	protected function addFiles($folder, $skip_until=false) {
		$handle = @opendir($folder);
		if ($handle) {
			$passed = false;

			// no more subdirectories here, search for files
			while (($file = readdir($handle)) !== false) {
				$path = $folder . DIRECTORY_SEPARATOR . $file;
				if ($file != '.' && $file != '..' && is_file($path)) {
					// is it ignored? if so, continue
					if (in_array($path, $this->ignored['files'])) {
						$this->addLogEntry("[addFiles] Skipping '$path' because it's ignored.");

						continue;
					}

					if ($skip_until !== false) {
						if (!$passed && $path == $skip_until) {
							$passed = true;
							continue;
						}

						if (!$passed) {
							continue;
						}
					}

					if (count($this->files) >= $this->limit) {
						$this->addLogEntry("[addFiles] Limit '{$this->limit}' reached!");

						return false;
					}

					$this->addLogEntry("[addFiles] Adding '$path' to array.");

					$this->files[] = $path;
				}
			}
			closedir($handle);

			return true;
		}
	}

	public function getAccessFile() {
		static $software;
		if (!$software) {
			$software = $this->getServerSoftware();
		}

		switch ($software) {
			case 'apache':
				return '.htaccess';
			break;

			case 'iis':
				return 'web.config';
			break;
		}
	}

	public function getDefaultAccessFile() {
		static $software;
		if (!$software) {
			$software = $this->getServerSoftware();
		}

		switch ($software) {
			case 'apache':
				return 'htaccess.txt';
			break;

			case 'iis':
				return 'web.config.txt';
			break;
		}
	}

	public function hasHtaccess() {
		$file = $this->getAccessFile();
		if (file_exists(JPATH_SITE.'/'.$file)) {
			return true;
		}

		return false;
	}

	public function getSessionHandler() {
		return JFactory::getConfig()->get('session_handler');
	}

	public function checkGoogleSafeBrowsing(){
		require_once JPATH_ADMINISTRATOR.'/components/com_rsfirewall/helpers/google-safe-browsing.php';

		$check = RSFirewallGoogleSafeBrowsing::getInstance();
		return $check->check();

	}

	public function getINI($name) {
		return ini_get($name);
	}

	public function compareINI($name, $against='1') {
		return $this->getINI($name) == $against;
	}

	protected function getHash($version) {
		$path = JPATH_ADMINISTRATOR.self::HASHES_DIR.$version.'.csv';

		if (!file_exists($path)) {
			// Attempt to download the new hashes

			// Make sure we have a valid code before continuing
			$code = $this->getConfig()->get('code');
			if (!$code || strlen($code) != 20) {
				throw new Exception(JText::_('COM_RSFIREWALL_CODE_FOR_HASHES'));
			}

			$url = 'http://www.rsjoomla.com/index.php?'.http_build_query(array(
				'option' 	=> 'com_rsfirewall_kb',
				'task'	 	=> 'gethash',
				'site'		=> JUri::root(),
				'code'	 	=> $code,
				'version' 	=> $version
			));

			// Connect to grab hashes (no caching)
			if (!($response = $this->connect($url, false))) {
				return false;
			}

			// Error code?
			if ($response->code != 200) {
				if (isset($response->headers) && is_array($response->headers) && isset($response->headers['Reason'])) {
					throw new Exception(strip_tags($response->headers['Reason']));
				}
				throw new Exception(JText::sprintf('COM_RSFIREWALL_HTTP_ERROR_RESPONSE_CODE', $response->code));
			}

			jimport('joomla.filesystem.file');
			if (!JFile::write($path, $response->body)) {
				throw new Exception(JText::sprintf('COM_RSFIREWALL_COULD_NOT_WRITE_HASH_FILE', $path));
			}

			// Let's find out if we need to add the hashes to the database
			$db 	= JFactory::getDbo();
			$query 	= $db->getQuery(true);

			$query->select('*')
				  ->from($db->qn('#__rsfirewall_hashes'))
				  ->where($db->qn('file').'='.$db->q('index.php'))
				  ->where($db->qn('type').'='.$db->q($version));
			if (!$db->setQuery($query)->loadObject()) {
				$files = array(
					'plugins/user/joomla/joomla.php',
					'plugins/authentication/joomla/joomla.php',
					'index.php',
					'administrator/index.php'
				);
				$count = 0;

				if ($handle = @fopen($path, 'r')) {
					while (($data = fgetcsv($handle, self::CHUNK_SIZE, ',')) !== false && $count < 4) {
						list($file_path, $file_hash) = $data;

						if (in_array($file_path, $files)) {
							$query->clear()
								  ->insert($db->qn('#__rsfirewall_hashes'))
								  ->set($db->qn('file').'='.$db->q($file_path))
								  ->set($db->qn('hash').'='.$db->q($file_hash))
								  ->set($db->qn('type').'='.$db->q($version));

							$db->setQuery($query)->execute();
							$count++;
						}
					}
					fclose($handle);
				} else {
					throw new Exception(JText::sprintf('COM_RSFIREWALL_COULD_NOT_READ_HASH_FILE', $path));
				}
			}
		}

		return $path;
	}

	protected function getMemoryLimitInBytes() {
		$memory_limit = $this->getINI('memory_limit');
		switch (substr($memory_limit, -1)) {
			case 'K':
				$memory_limit = (int) $memory_limit * 1024;
			break;

			case 'M':
				$memory_limit = (int) $memory_limit * 1024 * 1024;
			break;

			case 'G':
				$memory_limit = (int) $memory_limit * 1024 * 1024 * 1024;
			break;
		}
		return $memory_limit;
	}

	protected function getIgnoredHashedFiles() {
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);

		$query->select($db->qn('file'))
			  ->select($db->qn('hash'))
			  ->select($db->qn('flag'))
			  ->select($db->qn('type'))
			  ->from($db->qn('#__rsfirewall_hashes'))
              ->where('('. $db->qn('type') . '=' . $db->q('ignore') . ' OR ' . $db->qn('type') . ' = ' . $db->q($this->getCurrentJoomlaVersion()) . ')');
		$db->setQuery($query);

		$results = $db->loadObjectList('file');
		
		$ignored = array(
			'administrator/language/en-GB/en-GB.com_associations.ini',
			'administrator/language/en-GB/en-GB.com_associations.sys.ini',
			'administrator/language/en-GB/en-GB.com_banners.ini',
			'administrator/language/en-GB/en-GB.com_banners.sys.ini',
			'administrator/language/en-GB/en-GB.com_contact.ini',
			'administrator/language/en-GB/en-GB.com_contact.sys.ini',
			'administrator/language/en-GB/en-GB.com_contenthistory.ini',
			'administrator/language/en-GB/en-GB.com_contenthistory.sys.ini',
			'administrator/language/en-GB/en-GB.com_fields.ini',
			'administrator/language/en-GB/en-GB.com_fields.sys.ini',
			'administrator/language/en-GB/en-GB.com_finder.ini',
			'administrator/language/en-GB/en-GB.com_finder.sys.ini',
			'administrator/language/en-GB/en-GB.com_newsfeeds.ini',
			'administrator/language/en-GB/en-GB.com_newsfeeds.sys.ini',
			'administrator/language/en-GB/en-GB.com_search.ini',
			'administrator/language/en-GB/en-GB.com_search.sys.ini',
			'administrator/language/en-GB/en-GB.mod_feed.ini',
			'administrator/language/en-GB/en-GB.mod_feed.sys.ini',
			'administrator/language/en-GB/en-GB.mod_latest.ini',
			'administrator/language/en-GB/en-GB.mod_latest.sys.ini',
			'administrator/language/en-GB/en-GB.mod_logged.ini',
			'administrator/language/en-GB/en-GB.mod_logged.sys.ini',
			'administrator/language/en-GB/en-GB.mod_multilangstatus.ini',
			'administrator/language/en-GB/en-GB.mod_multilangstatus.sys.ini',
			'administrator/language/en-GB/en-GB.mod_popular.ini',
			'administrator/language/en-GB/en-GB.mod_popular.sys.ini',
			'administrator/language/en-GB/en-GB.mod_sampledata.ini',
			'administrator/language/en-GB/en-GB.mod_sampledata.sys.ini',
			'administrator/language/en-GB/en-GB.mod_version.ini',
			'administrator/language/en-GB/en-GB.mod_version.sys.ini',
			'administrator/language/en-GB/en-GB.plg_authentication_cookie.ini',
			'administrator/language/en-GB/en-GB.plg_authentication_cookie.sys.ini',
			'administrator/language/en-GB/en-GB.plg_authentication_gmail.ini',
			'administrator/language/en-GB/en-GB.plg_authentication_gmail.sys.ini',
			'administrator/language/en-GB/en-GB.plg_authentication_ldap.ini',
			'administrator/language/en-GB/en-GB.plg_authentication_ldap.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_contact.ini',
			'administrator/language/en-GB/en-GB.plg_content_contact.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_emailcloak.ini',
			'administrator/language/en-GB/en-GB.plg_content_emailcloak.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_finder.ini',
			'administrator/language/en-GB/en-GB.plg_content_finder.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_joomla.ini',
			'administrator/language/en-GB/en-GB.plg_content_joomla.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_loadmodule.ini',
			'administrator/language/en-GB/en-GB.plg_content_loadmodule.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_pagebreak.ini',
			'administrator/language/en-GB/en-GB.plg_content_pagebreak.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_pagenavigation.ini',
			'administrator/language/en-GB/en-GB.plg_content_pagenavigation.sys.ini',
			'administrator/language/en-GB/en-GB.plg_content_vote.ini',
			'administrator/language/en-GB/en-GB.plg_content_vote.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_article.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_article.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_contact.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_contact.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_fields.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_fields.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_image.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_image.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_menu.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_menu.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_module.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_module.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_pagebreak.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_pagebreak.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_readmore.ini',
			'administrator/language/en-GB/en-GB.plg_editors-xtd_readmore.sys.ini',
			'administrator/language/en-GB/en-GB.plg_editors_tinymce.ini',
			'administrator/language/en-GB/en-GB.plg_editors_tinymce.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_calendar.ini',
			'administrator/language/en-GB/en-GB.plg_fields_calendar.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_checkboxes.ini',
			'administrator/language/en-GB/en-GB.plg_fields_checkboxes.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_color.ini',
			'administrator/language/en-GB/en-GB.plg_fields_color.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_editor.ini',
			'administrator/language/en-GB/en-GB.plg_fields_editor.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_imagelist.ini',
			'administrator/language/en-GB/en-GB.plg_fields_imagelist.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_integer.ini',
			'administrator/language/en-GB/en-GB.plg_fields_integer.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_list.ini',
			'administrator/language/en-GB/en-GB.plg_fields_list.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_media.ini',
			'administrator/language/en-GB/en-GB.plg_fields_media.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_radio.ini',
			'administrator/language/en-GB/en-GB.plg_fields_radio.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_sql.ini',
			'administrator/language/en-GB/en-GB.plg_fields_sql.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_text.ini',
			'administrator/language/en-GB/en-GB.plg_fields_text.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_textarea.ini',
			'administrator/language/en-GB/en-GB.plg_fields_textarea.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_url.ini',
			'administrator/language/en-GB/en-GB.plg_fields_url.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_user.ini',
			'administrator/language/en-GB/en-GB.plg_fields_user.sys.ini',
			'administrator/language/en-GB/en-GB.plg_fields_usergrouplist.ini',
			'administrator/language/en-GB/en-GB.plg_fields_usergrouplist.sys.ini',
			'administrator/language/en-GB/en-GB.plg_finder_categories.ini',
			'administrator/language/en-GB/en-GB.plg_finder_categories.sys.ini',
			'administrator/language/en-GB/en-GB.plg_finder_contacts.ini',
			'administrator/language/en-GB/en-GB.plg_finder_contacts.sys.ini',
			'administrator/language/en-GB/en-GB.plg_finder_content.ini',
			'administrator/language/en-GB/en-GB.plg_finder_content.sys.ini',
			'administrator/language/en-GB/en-GB.plg_finder_newsfeeds.ini',
			'administrator/language/en-GB/en-GB.plg_finder_newsfeeds.sys.ini',
			'administrator/language/en-GB/en-GB.plg_finder_tags.ini',
			'administrator/language/en-GB/en-GB.plg_finder_tags.sys.ini',
			'administrator/language/en-GB/en-GB.plg_sampledata_blog.ini',
			'administrator/language/en-GB/en-GB.plg_sampledata_blog.sys.ini',
			'administrator/language/en-GB/en-GB.plg_search_categories.ini',
			'administrator/language/en-GB/en-GB.plg_search_categories.sys.ini',
			'administrator/language/en-GB/en-GB.plg_search_contacts.ini',
			'administrator/language/en-GB/en-GB.plg_search_contacts.sys.ini',
			'administrator/language/en-GB/en-GB.plg_search_content.ini',
			'administrator/language/en-GB/en-GB.plg_search_content.sys.ini',
			'administrator/language/en-GB/en-GB.plg_search_newsfeeds.ini',
			'administrator/language/en-GB/en-GB.plg_search_newsfeeds.sys.ini',
			'administrator/language/en-GB/en-GB.plg_search_tags.ini',
			'administrator/language/en-GB/en-GB.plg_search_tags.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_debug.ini',
			'administrator/language/en-GB/en-GB.plg_system_debug.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_fields.ini',
			'administrator/language/en-GB/en-GB.plg_system_fields.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_highlight.ini',
			'administrator/language/en-GB/en-GB.plg_system_highlight.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_languagecode.ini',
			'administrator/language/en-GB/en-GB.plg_system_languagecode.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_p3p.ini',
			'administrator/language/en-GB/en-GB.plg_system_p3p.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_sef.ini',
			'administrator/language/en-GB/en-GB.plg_system_sef.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_stats.ini',
			'administrator/language/en-GB/en-GB.plg_system_stats.sys.ini',
			'administrator/language/en-GB/en-GB.plg_system_updatenotification.ini',
			'administrator/language/en-GB/en-GB.plg_system_updatenotification.sys.ini',
			'administrator/language/en-GB/en-GB.plg_twofactorauth_totp.ini',
			'administrator/language/en-GB/en-GB.plg_twofactorauth_totp.sys.ini',
			'administrator/language/en-GB/en-GB.plg_twofactorauth_yubikey.ini',
			'administrator/language/en-GB/en-GB.plg_twofactorauth_yubikey.sys.ini',
			'administrator/language/en-GB/en-GB.plg_user_contactcreator.ini',
			'administrator/language/en-GB/en-GB.plg_user_contactcreator.sys.ini',
			'administrator/language/en-GB/en-GB.plg_user_profile.ini',
			'administrator/language/en-GB/en-GB.plg_user_profile.sys.ini',
			'language/en-GB/en-GB.com_contact.ini',
			'language/en-GB/en-GB.com_finder.ini',
			'language/en-GB/en-GB.com_newsfeeds.ini',
			'language/en-GB/en-GB.com_search.ini',
			'language/en-GB/en-GB.mod_articles_archive.ini',
			'language/en-GB/en-GB.mod_articles_archive.sys.ini',
			'language/en-GB/en-GB.mod_articles_categories.ini',
			'language/en-GB/en-GB.mod_articles_categories.sys.ini',
			'language/en-GB/en-GB.mod_articles_category.ini',
			'language/en-GB/en-GB.mod_articles_category.sys.ini',
			'language/en-GB/en-GB.mod_articles_latest.ini',
			'language/en-GB/en-GB.mod_articles_latest.sys.ini',
			'language/en-GB/en-GB.mod_articles_news.ini',
			'language/en-GB/en-GB.mod_articles_news.sys.ini',
			'language/en-GB/en-GB.mod_articles_popular.ini',
			'language/en-GB/en-GB.mod_articles_popular.sys.ini',
			'language/en-GB/en-GB.mod_banners.ini',
			'language/en-GB/en-GB.mod_banners.sys.ini',
			'language/en-GB/en-GB.mod_feed.ini',
			'language/en-GB/en-GB.mod_feed.sys.ini',
			'language/en-GB/en-GB.mod_finder.ini',
			'language/en-GB/en-GB.mod_finder.sys.ini',
			'language/en-GB/en-GB.mod_footer.ini',
			'language/en-GB/en-GB.mod_footer.sys.ini',
			'language/en-GB/en-GB.mod_random_image.ini',
			'language/en-GB/en-GB.mod_random_image.sys.ini',
			'language/en-GB/en-GB.mod_related_items.ini',
			'language/en-GB/en-GB.mod_related_items.sys.ini',
			'language/en-GB/en-GB.mod_search.ini',
			'language/en-GB/en-GB.mod_search.sys.ini',
			'language/en-GB/en-GB.mod_stats.ini',
			'language/en-GB/en-GB.mod_stats.sys.ini',
			'language/en-GB/en-GB.mod_tags_popular.ini',
			'language/en-GB/en-GB.mod_tags_popular.sys.ini',
			'language/en-GB/en-GB.mod_tags_similar.ini',
			'language/en-GB/en-GB.mod_tags_similar.sys.ini',
			'language/en-GB/en-GB.mod_users_latest.ini',
			'language/en-GB/en-GB.mod_users_latest.sys.ini',
			'language/en-GB/en-GB.mod_whosonline.ini',
			'language/en-GB/en-GB.mod_whosonline.sys.ini',
			'language/en-GB/en-GB.mod_wrapper.ini',
			'language/en-GB/en-GB.mod_wrapper.sys.ini'
		);
		
		foreach ($ignored as $file)
		{
			if (!file_exists(JPATH_SITE . '/' . $file))
			{
				$results[$file] = (object) array(
					'file' => $file,
					'hash' => '',
					'flag' => 'M',
					'type' => 'ignore'
				);
			}
		}
		
		return $results;
	}

	protected function _getIgnored() {
		if (empty($this->ignored)) {
			$this->ignored	= array(
				'folders' => array(),
				'files'   => array()
			);
			$db 	= $this->getDbo();
			$query 	= $db->getQuery(true);

			$query->select('*')
				  ->from($db->qn('#__rsfirewall_ignored'))
				  ->where($db->qn('type').'='.$db->q('ignore_folder').' OR '.$db->qn('type').'='.$db->q('ignore_file'));
			$db->setQuery($query);
			$results = $db->loadObjectList();
			foreach ($results as $result) {
				$this->ignored[$result->type == 'ignore_folder' ? 'folders' : 'files'][] = $result->path;
			}
		}
	}

	protected function getOptionalFolders() {
		return array(
			/* administrator components */
			'administrator/components/com_associations',
			'administrator/components/com_banners',
			'administrator/components/com_contact',
			'administrator/components/com_contenthistory',
			'administrator/components/com_fields',
			'administrator/components/com_finder',
			'administrator/components/com_newsfeeds',
			'administrator/components/com_search',
			'administrator/components/com_weblinks',

			/* administrator modules */
			'administrator/modules/mod_feed',
			'administrator/modules/mod_latest',
			'administrator/modules/mod_logged',
			'administrator/modules/mod_menu',
			'administrator/modules/mod_popular',
			'administrator/modules/mod_status',
			'administrator/modules/mod_submenu',
			'administrator/modules/mod_sampledata',
			'administrator/modules/mod_stats_admin',
			'administrator/modules/mod_title',
			'administrator/modules/mod_multilangstatus',
			'administrator/modules/mod_version',

			/* administrator templates */
			'administrator/templates/bluestork',
			'administrator/templates/isis',
			'administrator/templates/hathor',

			/* components */
			'components/com_banners',
			'components/com_contact',
			'components/com_contenthistory',
			'components/com_fields',
			'components/com_finder',
			'components/com_newsfeeds',
			'components/com_search',
			'components/com_weblinks',

			/* media */
			'media/editors/tinymce',
			'media/com_finder',
			'media/mod_sampledata',
			'images/sampledata',

			/* modules */
			'modules/mod_articles_archive',
			'modules/mod_articles_categories',
			'modules/mod_articles_category',
			'modules/mod_articles_popular',
			'modules/mod_articles_latest',
			'modules/mod_articles_news',
			'modules/mod_banners',
			'modules/mod_random_image',
			'modules/mod_related_items',
			'modules/mod_search',
			'modules/mod_stats',
			'modules/mod_weblinks',
			'modules/mod_whosonline',
			'modules/mod_wrapper',
			'modules/mod_feed',
			'modules/mod_finder',
			'modules/mod_footer',
			'modules/mod_tags_popular',
			'modules/mod_tags_similar',
			'modules/mod_users_latest',

			/* plugins */
			'plugins/content/contact',
			'plugins/content/emailcloak',
			'plugins/content/fields',
			'plugins/content/finder',
			'plugins/content/joomla',
			'plugins/content/loadmodule',
			'plugins/content/pagebreak',
			'plugins/content/pagenavigation',
			'plugins/content/vote',
			'plugins/authentication/cookie',
			'plugins/authentication/gmail',
			'plugins/authentication/ldap',
			'plugins/captcha/recaptcha',
			'plugins/editors/tinymce',
			'plugins/editors-xtd/article',
			'plugins/editors-xtd/contact',
			'plugins/editors-xtd/fields',
			'plugins/editors-xtd/image',
			'plugins/editors-xtd/menu',
			'plugins/editors-xtd/module',
			'plugins/editors-xtd/pagebreak',
			'plugins/editors-xtd/readmore',
			'plugins/fields/calendar',
			'plugins/fields/checkboxes',
			'plugins/fields/color',
			'plugins/fields/editor',
			'plugins/fields/imagelist',
			'plugins/fields/integer',
			'plugins/fields/list',
			'plugins/fields/media',
			'plugins/fields/radio',
			'plugins/fields/sql',
			'plugins/fields/text',
			'plugins/fields/textarea',
			'plugins/fields/url',
			'plugins/fields/user',
			'plugins/fields/usergrouplist',
			'plugins/finder/categories',
			'plugins/finder/contacts',
			'plugins/finder/content',
			'plugins/finder/newsfeeds',
			'plugins/finder/tags',
			'plugins/sampledata/blog',
			'plugins/search/categories',
			'plugins/search/contacts',
			'plugins/search/content',
			'plugins/search/newsfeeds',
			'plugins/search/tags',
			'plugins/system/debug',
			'plugins/system/fields',
			'plugins/system/highlight',
			'plugins/system/languagecode',
			'plugins/system/p3p',
			'plugins/system/sef',
			'plugins/system/stats',
			'plugins/system/updatenotification',
			'plugins/twofactorauth/totp',
			'plugins/twofactorauth/yubikey',
			'plugins/user/contactcreator',
			'plugins/user/profile',

			/* templates */
			'templates/atomic',
			'templates/beez3',
			'templates/beez5',
			'templates/beez_20',
			'templates/protostar'
		);
	}

	public function isAlpha($version = null) {
		if (is_null($version)) {
			$version = $this->getCurrentJoomlaVersion();
		}

		return preg_match('#[a-z]+#i', $version);
	}

	public function checkHashes($start=0, $limit) {
		// version information
		$version = $this->getCurrentJoomlaVersion();

		// Below stable?
		if ($this->isAlpha($version)) {
			$this->setError(JText::sprintf('COM_RSFIREWALL_NO_HASHES_FOR_ALPHA', $version));
			return false;
		}

		try {
			if ($hash_file = $this->getHash($version)) {
				if ($handle = @fopen($hash_file, 'r')) {
					// set pointer to last value
					fseek($handle, $start);

					$result				= new stdClass();
					$result->wrong 		= array(); // files with wrong checksums
					$result->missing 	= array(); // files missing
					$result->fstop		= 0; // the pointer (bytes) where the scanning stopped
					$result->size		= filesize($hash_file); // the file size so that we can compute the progress
					$result->ignored    = array();

					$ignored_files 		= $this->getIgnoredHashedFiles();
					$ignored_folders 	= $this->getOptionalFolders();

					// memory variables
					$memory_limit = $this->getMemoryLimitInBytes();
					$memory_usage = memory_get_usage();

					// read data
					while (($data = fgetcsv($handle, self::CHUNK_SIZE, ',')) !== false && $limit > 0) {
						list($file_path, $file_hash) = $data;
						$full_path = JPATH_SITE.'/'.$file_path;

						// is it an optional folder, that might have been uninstalled?
						$parts = explode('/', $file_path);
						// this removes the filename
						array_pop($parts);
						// we do this so that subfolders are ignored as well
						while ($parts) {
							$folder = implode('/', $parts);
							if (in_array($folder, $ignored_folders) && !is_dir(JPATH_SITE.'/'.$folder)) {
								continue 2;
							}
							array_pop($parts);
						}

						// get the new hash
						if (isset($ignored_files[$file_path])) {
							// if there's an M flag this means the file should be missing
							if ($ignored_files[$file_path]->flag == 'M') {
								// we check if the file is indeed missing...
								if (!is_file($full_path)) {
									// ... and skip the hash checks
									continue;
								} // ... because if it isn't, we need to check it since the administrator might have put it back after he noticed it was missing
							} else {
								// grab the hash from the file found in the database
								$file_hash = $ignored_files[$file_path]->hash;
							}
							if ($ignored_files[$file_path]->type == 'ignore') {
								$result->ignored[] = $file_path;
							}
						}

						if (file_exists($full_path)) {
							$file_size = filesize($full_path);

							// let's hope the file can be read
							if ($memory_usage + $file_size < $memory_limit) {
								// does this file have a wrong checksum ?
								if (md5_file($full_path) != $file_hash) {
									$result->wrong[] = $file_path;

									// refresh this
									$memory_usage = memory_get_usage();
								}
							}
						} else {
							$result->missing[] = $file_path;

							// refresh this
							$memory_usage = memory_get_usage();
						}

						$limit--;
					}

					// get the current pointer
					$result->fstop = ftell($handle);
					// we're done, close
					fclose($handle);

					return $result;
				} else {
					$this->setError(JText::sprintf('COM_RSFIREWALL_COULD_NOT_READ_HASH_FILE', $hash_file));
					return false;
				}
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		$this->setError(JText::sprintf('COM_RSFIREWALL_NO_HASHES_FOUND', $version));
		return false;
	}

	public function checkPermissions($path) {
		if (!is_readable($path)) {
			return false;
		}

		return substr(decoct(@fileperms($path)),-3);
	}

	public function setOffsetLimit($limit) {
		$this->limit = $limit;
	}

	public function getFoldersRecursive($folder) {
		// cache the ignored items
		$this->_getIgnored();

		$result = $this->getFoldersLimit($folder);
		// something has gone wrong, tell the controller to throw an error message
		if ($result === false) {
			return false;
		}

		if ($this->folders) {
			// found folders...
			return $this->folders;
		} else {
			// this most likely means we've reached the end
			return true;
		}
	}

	public function getFilesRecursive($startfile) {
		// cache the ignored items
		$this->_getIgnored();

		$this->files = array();
		$result = $this->getFilesLimit($startfile);
		// something has gone wrong, tell the controller to throw an error message
		if ($result === false) {
			return false;
		}

		$root = JPATH_SITE;
		// workaround to grab the correct root
		if ($root == '') {
			$root = '/';
		}

		// This is an exceptional case when all files are ignored from the root.
		if (!$this->files && dirname($startfile) == $root) {
			$this->files = array($this->getLastFile());
		}

		// found files
		return $this->files;
	}

	public function _loadSignatures()
	{
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);

		$query->select('*')
			  ->from($db->qn('#__rsfirewall_signatures'));

		$db->setQuery($query);
		$signatures = $db->loadObjectList();
		
		// Load MD5 signatures
		$file = JPATH_ADMINISTRATOR . self::SIGS_DIR . '/php.csv';
		
		if (file_exists($file) && is_readable($file) && $this->getConfig()->get('check_md5'))
		{
			$lines = file($file, FILE_IGNORE_NEW_LINES);
			foreach ($lines as $line)
			{
				list($hash, $desc) = explode(',', $line);
				$signatures[] = (object) array(
					'signature' => $hash,
					'type' 		=> 'md5',
					'reason' 	=> $desc
				);
			}
		}
		
		return $signatures;
	}

	protected function readableFilesize($bytes, $decimals = 2) {
		$size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}

	public function checkSignatures($file)
	{
		static $signatures;
		if (!is_array($signatures)) {
			jimport('joomla.filesystem.file');
			$signatures = $this->_loadSignatures();
		}

		if (empty($signatures))
		{
			throw new Exception (JText::_('COM_RSFIREWALL_NO_MALWARE_SIGNATURES'));
		}
		
		$ext = strtolower(JFile::getExt($file));

		if ($ext == 'php')
		{
			if (!is_readable($file))
			{
				$this->addLogEntry("[checkSignatures] Error reading '$file'.", true);

				$this->setError(JText::sprintf('COM_RSFIREWALL_COULD_NOT_READ_FILE', $file));
				return false;
			}

			$bytes = filesize($file);

			// More than 512 kb
			if ($bytes >= 524288) {
				$this->addLogEntry("[checkSignatures] File '$file' is {$this->readableFilesize($bytes)}.", true);

				$this->setError(JText::sprintf('COM_RSFIREWALL_BIG_FILE_PLEASE_SKIP', $file, $this->readableFilesize($bytes)));
				return false;
			}

			$this->addLogEntry("[checkSignatures] Opening '$file' ({$this->readableFilesize($bytes)}) for reading.");

			$contents = file_get_contents($file);
			$md5 = md5($contents);
		}
		
		$basename 	= $this->basename($file);
		$dirname	= dirname($file);

		foreach ($signatures as $signature)
		{
			if (strpos($signature->type, 'regex') === 0 && $ext == 'php')
			{
				$flags = str_replace('regex', '', $signature->type);
				if (preg_match('#'.$signature->signature.'#'.$flags, $contents, $match))
				{
					$this->addLogEntry("[checkSignatures] Malware found ({$signature->reason})");
					return array('match' => $match[0], 'reason' => $signature->reason);
				}
			}
			elseif ($signature->type == 'filename')
			{
				if (preg_match('#'.$signature->signature.'#i', $basename, $match))
				{
					$this->addLogEntry("[checkSignatures] Malware found ({$signature->reason})");
					return array('match' => $match[0], 'reason' => $signature->reason);
				}
			}
			elseif ($signature->type == 'md5' && $ext == 'php')
			{
				if ($signature->signature === $md5)
				{
					$this->addLogEntry("[checkSignatures] Malware found ({$signature->reason})");
					return array('match' => $signature->signature, 'reason' => $signature->reason);
				}
			}
		}

		if ($ext == 'php')
		{
			// Checking for base64 inside index.php
			if (in_array(strtolower($basename), array('index.php', 'home.php'))) {
				if (preg_match('#base64\_decode\((.*?)\)#is', $contents, $match)) {
					$this->addLogEntry("[checkSignatures] Malware found (".JText::_('COM_RSFIREWALL_BASE64_IN_FILE').")");

					return array('match' => $match[0], 'reason' => JText::_('COM_RSFIREWALL_BASE64_IN_FILE'));
				}
			}

			// Check if there are php files in root
			if ($dirname == JPATH_SITE) {
				if (!in_array($basename, array('index.php', 'configuration.php'))) {
					$this->addLogEntry("[checkSignatures] Malware found (".JText::_('COM_RSFIREWALL_SUSPICIOUS_FILE_IN_ROOT').")");

					return array('match' => $basename, 'reason' => JText::_('COM_RSFIREWALL_SUSPICIOUS_FILE_IN_ROOT'));
				}
			}

			// Check if there are php files in the /images folder
			if (strpos($dirname, JPATH_SITE.DIRECTORY_SEPARATOR.'images') === 0) {
				$this->addLogEntry("[checkSignatures] Malware found (".JText::sprintf('COM_RSFIREWALL_SUSPICIOUS_FILE_IN_FOLDER', 'images').")");

				return array('match' => $basename, 'reason' => JText::sprintf('COM_RSFIREWALL_SUSPICIOUS_FILE_IN_FOLDER', 'images'));
			}

			$folders = array(
				// site view
				'components',
				'templates',
				'plugins',
				'modules',
				'language',

				// admin view
				'administrator'.DIRECTORY_SEPARATOR.'components',
				'administrator'.DIRECTORY_SEPARATOR.'templates',
				'administrator'.DIRECTORY_SEPARATOR.'modules',
				'administrator'.DIRECTORY_SEPARATOR.'language');

				foreach ($folders as $folder) {
					if ($dirname == JPATH_SITE.DIRECTORY_SEPARATOR.$folder) {
						$this->addLogEntry("[checkSignatures] Malware found (".JText::sprintf('COM_RSFIREWALL_SUSPICIOUS_FILE_IN_FOLDER', $folder).")");

						return array('match' => $basename, 'reason' => JText::sprintf('COM_RSFIREWALL_SUSPICIOUS_FILE_IN_FOLDER', $folder));
					}
				}
		}
		else
		{
			if ($basename[0] == ' ')
			{
				return array('match' => $basename, 'reason' => JText::_('COM_RSFIREWALL_SUSPICIOUS_SPACE_FILE'));
			}

			$ignoredDotFiles = array(
				'.htaccess',
				'.htpasswd',
				'.htusers',
				'.htgroups',
				'.gitignore',
				'.gitattributes',
				'.mailmap',
				'.php_cs',
			);
			if ($basename[0] == '.' && !in_array(strtolower($basename), $ignoredDotFiles) && $ext != 'yml')
			{
				return array('match' => $basename, 'reason' => JText::_('COM_RSFIREWALL_SUSPICIOUS_HIDDEN_FILE'));
			}
		}

		$this->addLogEntry("[checkSignatures] File $basename appears to be clean. Moving on to next...");

		return false;
	}
	
	protected function basename($filename)
	{
		$parts = explode(DIRECTORY_SEPARATOR, $filename);
		return end($parts);
	}

	public function getLastFile($root) {
		static $last_file;

		if (!$last_file) {
			// cache the ignored items
			$this->_getIgnored();

			$files = $this->getFiles($root, false, false);
			// must remove ignored files
			if ($this->ignored['files']) {
				// remove ignored files
				$files = array_diff($files, $this->ignored['files']);
				// renumber indexes
				$files = array_merge(array(), $files);
			}
			$last_file = end($files);
			// this shouldn't happen
			if (!$files) {
				$last_file = $root.DIRECTORY_SEPARATOR.'index.php';
			}
		}

		return $last_file;
	}

	public function getOffset() {
		return RSFirewallConfig::getInstance()->get('offset');
	}

	public function saveGrade() {
		$grade = JFactory::getApplication()->input->get('grade', '', 'int');

		$this->getConfig()->set('grade', $grade);

		$this->getConfig()->set('system_check_last_run', JFactory::getDate()->toSql(true));

		$this->addLogEntry("System check finished: $grade");
	}

	public function getSideBar() {
		require_once JPATH_COMPONENT.'/helpers/toolbar.php';

		return RSFirewallToolbarHelper::render();
	}
}