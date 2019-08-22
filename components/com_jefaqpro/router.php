<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Build the route for the com_newsfeeds component
  */
function jefaqproBuildRoute(&$query)
{
	$segments	= array();

	// get a menu item based on Itemid or currently active
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();
		$params		= JComponentHelper::getParams('com_jefaqpro');
		$advanced	= $params->get('sef_advanced_link', 0);

		if (empty($query['Itemid'])) {
			$menuItem = $menu->getActive();
	}
	else {
			$menuItem = $menu->getItem($query['Itemid']);
	}

	$mView							= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mCatid							= (empty($menuItem->query['catid'])) ? null : $menuItem->query['catid'];
	$mId							= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

		if (isset($query['view'])) {
			$view = $query['view'];
		if (empty($query['Itemid'])) {
			$segments[]				= $query['view'];
		}
		unset($query['view']);
	};

	// are we dealing with an newsfeed that is attached to a menu item?
		if (isset($query['view']) && ($mView == $query['view']) and (isset($query['id'])) and ($mId == intval($query['id']))) {
			unset($query['view']);
				unset($query['catid']);
			unset($query['id']);
        		return $segments;
		}

	//	exit;

	if (isset($view) and ($view == 'category')) {
		if ($mId != intval($query['id']) || $mView != $view) {
			if($view == 'category' && isset($query['catid'])) {
					$catid = $query['catid'];
			} elseif(isset($query['id'])) {
					$catid = $query['id'];
			}
			$menuCatid				= $mId;
			$categories				= JCategories::getInstance('jefaqpro');
			$category = $categories->get($catid);
			if ($category) {
				$path				= $category->getPath();
				$path				= array_reverse($path);

			$array = array();
				foreach($path as $id) {
					if((int) $id == (int)$menuCatid) {
					break;
				}

					if($advanced) {
				list($tmp, $id) = explode(':', $id, 2);
					}
				$array[] = $id;
			}
				$segments			= array_merge($segments, array_reverse($array));
			}

			if($view == 'faqs') {
				if ($advanced) {
					list($tmp, $id) = explode(':', $query['id'], 2);
				} else {
					$id = $query['id'];
				}
				$segments[] = $id;
			}
		}

			unset($query['id']);
			unset($query['catid']);
		}

		if (isset($query['layout'])) {
		if (!empty($query['Itemid']) && isset($menuItem->query['layout'])) {
				if ($query['layout'] == $menuItem->query['layout']) {
                 			unset($query['layout']);
				}
			} else {
				if ($query['layout'] == 'default') {
					unset($query['layout']);
				}
			}
	};

	return $segments;
}

/**
 * Parse the segments of a URL.
 */
function jefaqproParseRoute($segments)
{
	$vars = array();

	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_jefaqpro');
	$advanced = $params->get('sef_advanced_link', 0);

	// Count route segments
		$count = count($segments);

	// Standard routing for newsfeeds.
		if (!isset($item)) {
			$vars['view']	= $segments[0];
			$vars['id']		= $segments[$count - 1];
			return $vars;
		}

	// From the categories view, we can only jump to a category.
		$id							= (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
		$categories					= JCategories::getInstance('jefaqpro')->get($id)->getChildren();
		$vars['catid'] = $id;
		$vars['id'] = $id;
		$found = 0;
		foreach($segments as $segment) {
			$segment				= $advanced ? str_replace(':', '-',$segment) : $segment;
			foreach($categories as $category) {
				if ($category->slug == $segment || $category->alias == $segment) {
				$vars['id'] = $category->id;
				$vars['catid'] = $category->id;
				$vars['view'] = 'category';
				$categories = $category->getChildren();
				$found = 1;
				break;
			}
		}

		if ($found == 0) {
			if ($advanced) {
				$db = JFactory::getDBO();
				$query = 'SELECT id FROM #__jefaqpro_faq WHERE catid = '.$vars['catid'];
				$db->setQuery($query);
					$nid			= $db->loadResult();
			} else {
					$nid			= $segment;
			}
				$vars['id']			= $nid;
				$vars['view']		= 'faqs';
			}
		        $found = 0;
	}

	return $vars;
}