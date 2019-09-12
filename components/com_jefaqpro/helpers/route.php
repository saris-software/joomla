<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * JE FAQPro Component Route Helper
 */
abstract class jefaqproHelperRoute
{
	protected static $lookup;

	/**
	 * @param	int	The route of the content item
	 */
	public static function getFaqRoute($id, $catid = 0)
	{
		$needles 						= array(
											'faqs'  => array((int) $id)
										);
		//Create the link
		$link 							= 'index.php?option=com_jefaqpro&view=faqs&id='. $id;
		if ((int)$catid > 1) {
			$categories					= JCategories::getInstance('jefaqpro');
			$category					= $categories->get((int)$catid);
			if($category) {
				$needles['category']	= array_reverse($category->getPath());
				$needles['categories']	= $needles['category'];
				$link					.= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles)) {
			$link 						.= '&Itemid='.$item;
		} elseif ($item = self::_findItem()) {
			$link						.= '&Itemid='.$item;
		}

		return $link;
	}

	public static function getCategoryRoute($catid)
	{

		if ($catid instanceof JCategoryNode) {
			$id 						= $catid->id;
			$category					= $catid;
		} else {
			$id							= (int) $catid;
			$category					= JCategories::getInstance('jefaqpro')->get($id);
		}

		if($id < 1) {
			$link						= '';
		} else {


			$needles 					= array(
											'category' => array($id)
										);
			if ($item = self::_findItem($needles)) {
				$link					= 'index.php?Itemid='.$item;
			} else {
				//Create the link
					$link 				= 'index.php?option=com_jefaqpro&view=category&id='.$id;
				if($category) {
					$catids				= array_reverse($category->getPath());
					$needles			= array(
											'category' => $catids,
											'categories' => $catids
										);
					if ($item = self::_findItem($needles)) {
						$link 			.= '&Itemid='.$item;
					} elseif ($item = self::_findItem()) {
						$link 			.= '&Itemid='.$item;
					}
				}
			}
		}

		return $link;
	}

	public static function getFormRoute($id)
	{
		//Create the link
		if ($id) {
			$link						= 'index.php?option=com_jefaqpro&task=faq.edit&a_id='. $id;
		} else {
			$link						= 'index.php?option=com_jefaqpro&task=faq.edit&a_id=0';
		}

		return $link;
	}

	protected static function _findItem($needles = null)
	{
		$app							= JFactory::getApplication();
		$menus							= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null) {
			self::$lookup				= array();

			$component					= JComponentHelper::getComponent('com_jefaqpro');
			$items						= $menus->getItems('component_id', $component->id);

			if($items != ''){
				foreach ($items as $item) {
					if (isset($item->query) && isset($item->query['view'])) {
						$view				= $item->query['view'];
						if (!isset(self::$lookup[$view])) {
							self::$lookup[$view]		= array();
						}

						if (isset($item->query['id'])) {
							self::$lookup[$view][$item->query['id']]	= $item->id;
						}
					}
				}
			}
		}

		if ($needles) {
			foreach ($needles as $view => $ids) {
				if (isset(self::$lookup[$view])) {
					foreach($ids as $id) {
						if (isset(self::$lookup[$view][(int)$id])) {
							return self::$lookup[$view][(int)$id];
						}
					}
				}
			}
		} else {
			$active						= $menus->getActive();
			if ($active && $active->component == 'com_jefaqpro') {
				return $active->id;
			}
		}

		return null;
	}


		/**
	 * Method to get the menu items for the component.
	 *
	 * @return	array		An array of menu items.
	 * @since	1.6
	 */
	public static function &getItems()
	{
		static $items;

		// Get the menu items for this component.
		if (!isset($items)) {
			$app	= JFactory::getApplication();
			$menu	= $app->getMenu();
			$com	= JComponentHelper::getComponent('com_jefaqpro');
			$items	= $menu->getItems('component_id', $com->id);

			// If no items found, set to empty array.
			if (!$items) {
				$items = array();
			}
		}

		return $items;
	}

	/**
	 * Method to get a route configuration for the login view.
	 *
	 * @return	mixed		Integer menu id on success, null on failure.
	 * @since	1.6
	 * @static
	 */
	public static function getaddFormRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'form') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}


	/**
	 * Method to get a route configuration for the login view.
	 *
	 * @return	mixed		Integer menu id on success, null on failure.
	 * @since	1.6
	 * @static
	 */
	public static function getBacktoRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'categories') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}
}