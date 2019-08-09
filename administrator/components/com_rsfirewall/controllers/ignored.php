<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

class RsfirewallControllerIgnored extends JControllerLegacy
{
	public function __construct($config = array())
	{
		parent::__construct($config);

		$user = JFactory::getUser();
		if (!$user->authorise('check.run', 'com_rsfirewall'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->redirect(JRoute::_('index.php?option=com_rsfirewall', false));
		}
	}

	public function removeFromIgnored()
	{
		$app       = JFactory::getApplication();
		$model     = $this->getModel('ignored');
		$id      = $app->input->get('ignoredFileId', '', 'path');

		$model->remove($id);

		$app->close();
	}
}