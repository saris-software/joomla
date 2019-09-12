<?php
/**
 * ------------------------------------------------------------------------
 * JA Login module for J25 & J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * JA Login Module Helper
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.7
 */
class modJALoginHelper
{


    /**
     * Get url return after login or logout
     * @param object param of ja module
     * @param string $type
     * @return string url redirect
     */
    public static function getReturnUrl($params, $type)
    {
        $app  = JFactory::getApplication();
        $item = $app->getMenu()->getItem($params->get($type));

        // Stay on the same page
        $url = JUri::getInstance()->toString();

        if ($item)
        {
            $lang = '';

            if ($item->language !== '*' && JLanguageMultilang::isEnabled())
            {
                $lang = '&lang=' . $item->language;
            }

            $url = 'index.php?Itemid=' . $item->id . $lang;
        }

        return base64_encode($url);
    }

    /**
     * Get type user action
     * @return string type
     */
    public static function getType()
    {
        $user = JFactory::getUser();
        return (!$user->get('guest')) ? 'logout' : 'login';
    }
}
