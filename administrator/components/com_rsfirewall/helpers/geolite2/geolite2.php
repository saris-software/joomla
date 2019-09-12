<?php
/**
 * @package    RSFirewall!
 * @copyright  (c) 2009 - 2019 RSJoomla!
 * @link       https://www.rsjoomla.com
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl-3.0.en.html
 */

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/vendor/autoload.php';

use GeoIp2\Database\Reader;

class RSFirewallGeolite2
{
    protected $reader;

    public function __construct()
    {
        $database = JPATH_ADMINISTRATOR . '/components/com_rsfirewall/assets/geoip/GeoLite2-Country.mmdb';

        // No point going further
        if (!file_exists($database))
        {
            return false;
        }

        try
        {
            $this->reader = new Reader($database);
        }
        catch (Exception $e)
        {
            $app = JFactory::getApplication();
            if ($app->isAdmin())
            {
                $app->enqueueMessage($e->getMessage(), 'warning');
            }
        }
    }

    public static function getInstance()
    {
        static $inst;

        if (!$inst)
        {
            $inst = new RSFirewallGeolite2();
        }

        return $inst;
    }

    public function getCountryCode($ip)
    {
        if ($this->reader)
        {
            try
            {
                $record = $this->reader->country($ip);
                return $record->country->isoCode;
            }
            catch (Exception $e)
            {

            }
        }

        return '';
    }
}