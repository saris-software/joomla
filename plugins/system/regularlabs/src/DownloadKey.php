<?php
/**
 * @package         Regular Labs Library
 * @version         17.5.25583
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\LibraryPlugin;

defined('_JEXEC') or die;

use JFactory;

class DownloadKey
{
	public static function update()
	{
		// Save the download key from the Regular Labs Extension Manager config to the update sites
		if (
			JFactory::getApplication()->isSite()
			|| JFactory::getApplication()->input->get('option') != 'com_config'
			|| JFactory::getApplication()->input->get('task') != 'config.save.component.apply'
			|| JFactory::getApplication()->input->get('component') != 'com_regularlabsmanager'
		)
		{
			return;
		}

		$form = JFactory::getApplication()->input->post->get('jform', [], 'array');

		if (!isset($form['key']))
		{
			return;
		}

		$key = $form['key'];

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->update('#__update_sites')
			->set($db->quoteName('extra_query') . ' = ' . $db->quote(''))
			->where($db->quoteName('location') . ' LIKE ' . $db->quote('%download.regularlabs.com%'));
		$db->setQuery($query);
		$db->execute();

		$query->clear()
			->update('#__update_sites')
			->set($db->quoteName('extra_query') . ' = ' . $db->quote('k=' . $key))
			->where($db->quoteName('location') . ' LIKE ' . $db->quote('%download.regularlabs.com%'))
			->where($db->quoteName('location') . ' LIKE ' . $db->quote('%&pro=1%'));
		$db->setQuery($query);
		$db->execute();
	}
}
