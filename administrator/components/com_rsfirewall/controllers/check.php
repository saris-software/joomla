<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallControllerCheck extends JControllerLegacy
{
	protected $folder_permissions 	 = 755;
	protected $file_permissions 	 = 644;

	public function __construct($config = array()) {
		parent::__construct($config);

		$user = JFactory::getUser();
		if (!$user->authorise('check.run', 'com_rsfirewall')) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}

		$config 					= RSFirewallConfig::getInstance();
		$this->folder_permissions 	= $config->get('folder_permissions');
		$this->file_permissions 	= $config->get('file_permissions');
	}

	protected function showResponse($success, $data=null) {
		$app 		= JFactory::getApplication();
		$document 	= JFactory::getDocument();

		// set JSON encoding
		$document->setMimeEncoding('application/json');

		// compute the response
		$response = new stdClass();
		$response->success = $success;
		if ($data) {
			$response->data = $data;
		}

		// show the response
		echo $this->encode($response);

		// close
		$app->close();
	}

	protected function encode($response) {
		$result = json_encode($response);

		// Added a failsafe in case the response isn't encoded - at least we'll have something to work with.
		if ($result === false) {
			$result = $this->jsonEncode($response);
		}

		// Let's see if the JSON can be decoded to avoid JSON.parse errors
		$decoded = json_decode($result);
		if ($decoded === null)
		{
			// Remove wrong control chars
			$result = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/', '', $result);
		}

		return $result;
	}

	protected function jsonEncode($val) {
		if (is_string($val)) {
			$val = str_replace(array("\n", "\r", "\t", "\v", "\f"), array('\n', '\r', '\t', '\v', '\f'), $val);
			return '"'.addcslashes($val, '"\\').'"';
		}

		if (is_numeric($val)) {
			return $val;
		}

		if ($val === null) {
			return 'null';
		}

		if ($val === true) {
			return 'true';
		}

		if ($val === false) {
			return 'false';
		}

		$assoc = is_array($val) ? array_keys($val) !== range(0, count($val) - 1) : true;

		$res = array();
		foreach ($val as $k => $v) {
			$v = $this->jsonEncode($v);
			if ($assoc) {
				$k = '"'.addcslashes($k, '"\\').'"';
				$v = $k.':'.$v;
			}
			$res[] = $v;
		}
		$res = implode(',', $res);
		return ($assoc) ? '{'.$res.'}' : '['.$res.']';
	}

	public function checkRSFirewallVersion() {
		$model = $this->getModel('check');

		if (!($result = $model->checkRSFirewallVersion())) {
			$success 		= false;
			$data	 		= new stdClass();
			$data->message 	= $model->getError();
		} else {
			@list($current, $latest, $is_latest) = $result;
			$success 	  = true;
			$data	 	  = new stdClass();
			$data->result = $is_latest;
			if ($is_latest) {
				$data->message = JText::sprintf('COM_RSFIREWALL_FIREWALL_VERSION_OK', $current);
			} else {
				$data->message = JText::sprintf('COM_RSFIREWALL_FIREWALL_VERSION_NOT_OK', $current, $latest);
				$data->details = JText::_('COM_RSFIREWALL_FIREWALL_VERSION_DETAILS');
			}
		}

		$this->showResponse($success, $data);
	}

	public function checkJoomlaVersion() {
		$model 		= $this->getModel('check');
		$data  		= new stdClass();
		$success 	= true;

		if (!($result = $model->checkJoomlaVersion())) {
			$success = false;
			$data->message = $model->getError();
		} else {
			@list($current, $latest, $is_latest) = $result;
			$data->result = $is_latest;

			if ($model->isAlpha($current)) {
				$data->result = false;
				$data->message = JText::sprintf('COM_RSFIREWALL_JOOMLA_VERSION_ALPHA', $current);
				$data->details = JText::_('COM_RSFIREWALL_JOOMLA_VERSION_DETAILS');
			} else {
				if ($is_latest) {
					$data->message = JText::sprintf('COM_RSFIREWALL_JOOMLA_VERSION_OK', $current);
				} else {
					$data->message = JText::sprintf('COM_RSFIREWALL_JOOMLA_VERSION_NOT_OK', $current, $latest);
					$data->details = JText::_('COM_RSFIREWALL_JOOMLA_VERSION_DETAILS');
				}
			}
		}

		$this->showResponse($success, $data);
	}

	public function checkGoogleSafeBrowsing()
	{
		$model   = $this->getModel('check');
		$data    = new stdClass();
		$result  = $model->checkGoogleSafeBrowsing();
		$success = $result['success'];

		foreach ($result as $key => $value)
		{
			$data->{$key} = $value;
		}

		$this->showResponse($success, $data);
	}

	public function checkBackendPassword()
	{
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if ($model->getConfig()->get('backend_password_enabled'))
		{
			$data->result = true;
			$data->message = JText::_('COM_RSFIREWALL_ADDITIONAL_BACKEND_PASSWORD_OK');
		}
		else
		{
			$data->result  = false;
			$data->message = JText::sprintf('COM_RSFIREWALL_ADDITIONAL_BACKEND_PASSWORD_NOT_OK');
			$data->details = JText::_('COM_RSFIREWALL_ADDITIONAL_BACKEND_PASSWORD_DETAILS');
		}

		$this->showResponse($success, $data);
	}

	public function checkSQLPassword() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if (($password = $model->checkSQLPassword()) !== false) {
			$data->result 	= false;
			$data->message 	= JText::sprintf('COM_RSFIREWALL_SQL_PASSWORD_NOT_OK', $password === '' ? '<em>'.JText::_('COM_RSFIREWALL_EMPTY_PASSWORD').'</em>' : $password);
			$data->details  = JText::_('COM_RSFIREWALL_SQL_PASSWORD_DETAILS');
		} else {
			$data->result 	= true;
			$data->message 	= JText::_('COM_RSFIREWALL_SQL_PASSWORD_OK');
		}

		$this->showResponse($success, $data);
	}

	public function checkAdminUser() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if ($id = $model->hasAdminUser()) {
			$data->result 	= false;
			$data->message 	= JText::sprintf('COM_RSFIREWALL_ADMIN_USER_NOT_OK', $id);
			$data->details  = JText::_('COM_RSFIREWALL_ADMIN_USER_DETAILS');
		} else {
			$data->result 	= true;
			$data->message 	= JText::_('COM_RSFIREWALL_ADMIN_USER_OK');
		}

		$this->showResponse($success, $data);
	}

	public function checkFTPPassword() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if ($model->hasFTPPassword()) {
			$data->result 	= false;
			$data->message 	= JText::_('COM_RSFIREWALL_FTP_PASSWORD_NOT_OK');
			$data->details	= JText::_('COM_RSFIREWALL_FTP_PASSWORD_DETAILS');
		} else {
			$data->result 	= true;
			$data->message 	= JText::_('COM_RSFIREWALL_FTP_PASSWORD_OK');
		}

		$this->showResponse($success, $data);
	}

	public function checkSEFEnabled() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if ($model->isSEFEnabled()) {
			$data->result 	= true;
			$data->message 	= JText::_('COM_RSFIREWALL_SEF_ENABLED');
		} else {
			$data->result 	= false;
			$data->message 	= JText::_('COM_RSFIREWALL_SEF_DISABLED');
			$data->details	= JText::_('COM_RSFIREWALL_SEF_DETAILS');
		}

		$this->showResponse($success, $data);
	}

	public function checkConfigurationIntegrity() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if (($result = $model->isConfigurationModified()) !== false) {
			$data->result 	= false;
			$data->message 	= JText::_('COM_RSFIREWALL_CONFIGURATION_NOT_OK');
			$data->details  = array();
			foreach ($result as $line => $code) {
				$detail = new stdClass();
				$detail->line = $line;
				$detail->code = $code;

				$data->details[] = $detail;
			}
		} else {
			$data->result 	= true;
			$data->message 	= JText::_('COM_RSFIREWALL_CONFIGURATION_OK');
		}

		$this->showResponse($success, $data);
	}

	public function checkAdminPasswords() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		$results	= $model->checkAdminPasswords();

		if ($results) {
			$data->result  = false;
			$data->message = JText::plural('COM_RSFIREWALL_WEAK_PASSWORDS_N_FOUND', count($results));
			$data->details = $results;
		} else {
			$data->result  = true;
			$data->message = JText::_('COM_RSFIREWALL_WEAK_PASSWORDS_OK');
		}

		$this->showResponse($success, $data);
	}

	public function checkSession() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		$lifetime	= $model->getSessionLifetime();
		if ($lifetime > 15) {
			$data->result  = false;
			$data->message = JText::sprintf('COM_RSFIREWALL_SESSION_LIFETIME_NOT_OK', $lifetime);
			$data->details = JText::_('COM_RSFIREWALL_SESSION_LIFETIME_DETAILS');
		} else {
			$data->result = true;
			$data->message = JText::sprintf('COM_RSFIREWALL_SESSION_LIFETIME_OK', $lifetime);
		}

		$this->showResponse($success, $data);
	}

	public function checkTemporaryFiles() {
		$model 	 = $this->getModel('check');
		$folder  = $model->getTemporaryFolder();
		$files   = $model->getFiles(realpath($folder), false, true, false, array('index.html'));
		$folders = $model->getFolders($folder, false, true, false);

		if ($files === false || $folders === false) {
			$success 		= false;
			$data 			= new stdClass();
			$data->message 	= $model->getError();
		} else {
			$success 	= true;
			$data 		= new stdClass();
			if ($files || $folders) {
				$data->result = false;
				$data->message = JText::_('COM_RSFIREWALL_TEMPORARY_FILES_NOT_OK');
				$data->details = new stdClass();
				$data->details->tmp		= $folder;
				$data->details->message	= JText::sprintf('COM_RSFIREWALL_TEMPORARY_FILES_DETAILS', $folder);
				$data->details->files 	= $files;
				$data->details->folders = $folders;
			} else {
				$data->result = true;
				$data->message = JText::_('COM_RSFIREWALL_TEMPORARY_FILES_OK');
			}
		}

		$this->showResponse($success, $data);
	}

	public function checkHtaccess() {
		$model 	 = $this->getModel('check');
		$file	 = $model->getAccessFile();
		$default = $model->getDefaultAccessFile();
		$success = true;
		$data 	 = new stdClass();
		if ($model->hasHtaccess()) {
			$data->result = true;
			$data->message = JText::sprintf('COM_RSFIREWALL_HTACCESS_FOUND', $file);
		} else {
			$data->result = false;
			$data->message = JText::sprintf('COM_RSFIREWALL_HTACCESS_NOT_FOUND', $file);
			$data->details = JText::sprintf('COM_RSFIREWALL_HTACCESS_DETAILS', $file, $default, $file);
		}

		$this->showResponse($success, $data);
	}

	public function checkSessionHandler() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();
		if (($handler = $model->getSessionHandler()) != 'database') {
			$data->result  = true;
			$data->message = JText::sprintf('COM_RSFIREWALL_SESSION_HANDLER_OK', $handler);
		} else {
			$data->result  = false;
			$data->message = JText::_('COM_RSFIREWALL_SESSION_HANDLER_NOT_OK');
			$data->details = JText::_('COM_RSFIREWALL_SESSION_HANDLER_DETAILS');
		}

		$this->showResponse($success, $data);
	}

	public function checkRegisterGlobals() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();

		$data->result  = !$model->compareINI('register_globals');
		$data->message = $data->result ? JText::_('COM_RSFIREWALL_REGISTER_GLOBALS_OFF') : JText::_('COM_RSFIREWALL_REGISTER_GLOBALS_ON');

		$this->showResponse($success, $data);
	}

	public function checkSafeMode() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();

		$data->result  = !$model->compareINI('safe_mode');
		$data->message = $data->result ? JText::_('COM_RSFIREWALL_SAFE_MODE_OFF') : JText::_('COM_RSFIREWALL_SAFE_MODE_ON');

		$this->showResponse($success, $data);
	}

	public function checkAllowURLInclude() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();

		$data->result  = !$model->compareINI('allow_url_include');
		$data->message = $data->result ? JText::_('COM_RSFIREWALL_ALLOW_URL_INCLUDE_OFF') : JText::_('COM_RSFIREWALL_ALLOW_URL_INCLUDE_ON');

		$this->showResponse($success, $data);
	}

	public function checkOpenBasedir() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();

		$data->result  = !$model->compareINI('open_basedir', '');
		$data->message = $data->result ? JText::_('COM_RSFIREWALL_OPEN_BASEDIR_ON') : JText::_('COM_RSFIREWALL_OPEN_BASEDIR_OFF');

		$this->showResponse($success, $data);
	}

	public function checkDisableFunctions() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();

		$disable_functions = $model->getINI('disable_functions');
		$recommended_functions = $model->getDisableFunctions();
		$used_functions = array();

		if ($disable_functions) {
			$disable_functions = explode(',', $disable_functions);
			foreach ($disable_functions as $disable_function) {
				$disable_function = strtolower(trim($disable_function));
				if (in_array($disable_function, $recommended_functions)) {
					$used_functions[] = $disable_function;
				}
			}

			$used_functions = array_unique($used_functions);

			if ($used_functions && count($used_functions) == count($recommended_functions)) {
				$data->result  = true;
				$data->message = JText::_('COM_RSFIREWALL_DISABLE_FUNCTIONS_ON');
			} else {
				$unused_functions = array_diff($recommended_functions, $used_functions);
				$data->result  = false;
				$data->message = JText::sprintf('COM_RSFIREWALL_DISABLE_FUNCTIONS_INCOMPLETE', implode(', ', $unused_functions));
			}
		} else {
			$data->result  = false;
			$data->message = JText::sprintf('COM_RSFIREWALL_DISABLE_FUNCTIONS_OFF', implode(', ', $recommended_functions));
		}

		$this->showResponse($success, $data);
	}
	
	public function checkExposePHP() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data		= new stdClass();

		$data->result  = !$model->compareINI('expose_php');
		$data->message = $data->result ? JText::_('COM_RSFIREWALL_EXPOSE_PHP_OFF') : JText::_('COM_RSFIREWALL_EXPOSE_PHP_ON');

		$this->showResponse($success, $data);
	}

	public function checkCoreFilesIntegrity() {
		$model 		= $this->getModel('check');
		$success 	= true;
		$data 		= new stdClass();

		$input = JFactory::getApplication()->input;
		$start = $input->get('fstart', 0, 'int');
		$limit = $model->getConfig()->get('offset');

		$result = $model->checkHashes($start, $limit);

		if ($result === false) {
			$success = false;
			$data->message = $model->getError();
		} else {
			$progress = number_format(100*$result->fstop/$result->size, 2, '.', '');
			$data->files = array();

			if (count((array) $result->ignored)){
				$data->ignored = true;
			}

			// wrong and missing files
			foreach ($result->wrong as $path) {
				$file = new stdClass();
				$file->path = $path;
				$file->type = 'wrong';
				
				if ($time = @filemtime(JPATH_SITE.'/'.$path))
				{
					$file->time = JHtml::_('date.relative', gmdate('Y-m-d H:i:s', $time));
				}
				
				$data->files[] = $file;
			}
			foreach ($result->missing as $path) {
				$file = new stdClass();
				$file->path = $path;
				$file->type = 'missing';
				$data->files[] = $file;
			}

			// progress message
			$data->message = JText::sprintf('COM_RSFIREWALL_SCANNING_JOOMLA_HASHES_PROGRESS', $progress);
			// if we've reached the end of the file don't send fstart anymore (this will stop the recurring ajax call)
			if ($result->fstop < $result->size) {
				$data->fstart  = $result->fstop;
			}

		}

		$this->showResponse($success, $data);
	}

	public function checkFolderPermissions() {
		$model  	= $this->getModel('check');
		$success 	= true;
		$data 		= new stdClass();

		$root = JPATH_SITE;
		// workaround to grab the correct root
		if ($root == '') {
			$root = '/';
		}

		// grab the current folder for the input
		// if it's not specified, use the root folder
		$input  = RSInput::create();
		$folder = $input->get('folder', $root, 'none');
		$limit  = $model->getConfig()->get('offset');

		$model->setOffsetLimit($limit);

		// this function returns the folders
		if ($folders = $model->getFoldersRecursive($folder)) {
			if (is_array($folders)) {
				$data->folders = array();
				foreach ($folders as $folder) {
					if (($perms = $model->checkPermissions($folder)) > $this->folder_permissions) {
						$tmp 		= new stdClass();
						$tmp->path  = substr_replace($folder, '', 0, strlen(JPATH_SITE.DIRECTORY_SEPARATOR));
						$tmp->perms = $perms;

						$data->folders[] = $tmp;
					}
				}
				if ($next_folder = end($folders)) {
					$data->next_folder = $next_folder;
					$data->next_folder_stripped = substr_replace($next_folder, '', 0, strlen(JPATH_SITE.DIRECTORY_SEPARATOR));
				}
			} else {
				$data->stop = true;
			}
		} else {
			$success = false;
			$data->message = $model->getError();
		}

		$this->showResponse($success, $data);
	}

	public function checkFilePermissions() {
		$model  	= $this->getModel('check');
		$success 	= true;
		$data 		= new stdClass();

		$root = JPATH_SITE;
		// workaround to grab the correct root
		if ($root == '') {
			$root = '/';
		}

		$input  = RSInput::create();
		$start 	= $input->get('file', $root, 'none');
		$limit  = $model->getConfig()->get('offset');

		$model->setOffsetLimit($limit);

		// this is so we know where to stop
		$last_file = $model->getLastFile($root);
		if (is_file($start) && $last_file === $start) {
			$data->stop = true;
		} else {
			if ($files = $model->getFilesRecursive($start)) {
				$data->files = array();
				foreach ($files as $file) {
					if (($perms = $model->checkPermissions($file)) > $this->file_permissions) {
						$tmp 		= new stdClass();
						$tmp->path  = substr_replace($file, '', 0, strlen(JPATH_SITE.DIRECTORY_SEPARATOR));
						$tmp->perms = $perms;

						$data->files[] = $tmp;
					}
				}

				$file = end($files);
				if ($file == $last_file) {
					$data->stop = true;
				} else {
					$data->next_file 			= $file;
					$data->next_file_stripped 	= substr_replace($file, '', 0, strlen(JPATH_SITE.DIRECTORY_SEPARATOR));
				}
			} else {
				$success = false;
				$data->message = $model->getError();
			}
		}

		$this->showResponse($success, $data);
	}

	public function checkSignatures() {
		$model  	= $this->getModel('check');
		$success 	= true;
		$data 		= new stdClass();

		$root = JPATH_SITE;
		// workaround to grab the correct root
		if ($root == '') {
			$root = '/';
		}

		$input  = RSInput::create();
		$start 	= $input->get('file', $root, 'none');
		$limit  = $model->getConfig()->get('offset');

		$model->setOffsetLimit($limit);

		// this is so we know where to stop
		$last_file = $model->getLastFile($root);
		if (is_file($start) && $last_file === $start) {
			$data->stop = true;
		} else {
			if ($files = $model->getFilesRecursive($start)) {
				$data->files = array();

				jimport('joomla.filesystem.file');

				try {
					foreach ($files as $file) {
						if ($result = $model->checkSignatures($file)) {
							$tmp 			= new stdClass();
							$tmp->path  	= substr_replace($file, '', 0, strlen(JPATH_SITE.DIRECTORY_SEPARATOR));
							$tmp->match 	= $result['match'];
							$tmp->reason 	= $result['reason'];
							
							if ($time = @filemtime($file))
							{
								$tmp->time = JHtml::_('date.relative', gmdate('Y-m-d H:i:s', $time));
							}

							$data->files[] = $tmp;
						}
					}

					$file = end($files);
					if ($file == $last_file) {
						$data->stop = true;
					} else {
						$data->next_file 			= $file;
						$data->next_file_stripped 	= substr_replace($file, '', 0, strlen(JPATH_SITE.DIRECTORY_SEPARATOR));
					}
				} catch (Exception $e) {
					$success = false;
					$data->message = $e->getMessage();
				}

			} else {
				$success = false;
				$data->message = $model->getError();
			}
		}

		$this->showResponse($success, $data);
	}

	public function saveGrade() {
		$app 	= JFactory::getApplication();
		$model 	= $this->getModel('check');

		$model->saveGrade();

		$app->close();
	}

}