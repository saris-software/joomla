<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

defined('JPATH_PLATFORM') or die;

/**
 * Pagination Class. Provides a common interface for content pagination for the
 * Joomla! Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Pagination
 * @since       11.1
 */
class JEFAQPagination
{
	/**
	 * @var    integer  The record number to start_faq displaying from.
	 * @since  11.1
	 */
	public $limitstart_faq = null;

	/**
	 * @var    integer  Number of rows to display per page.
	 * @since  11.1
	 */
	public $limit_faq = null;

	/**
	 * @var    integer  Total number of rows.
	 * @since  11.1
	 */
	public $total = null;

	/**
	 * @var    integer  Prefix used for request variables.
	 * @since  11.1
	 */
	public $prefix = null;

	/**
	 * @var    integer
	 * @since  12.2
	 */
	public $pagesStart;

	/**
	 * @var    integer
	 * @since  12.2
	 */
	public $pagesStop;

	/**
	 * @var    integer
	 * @since  12.2
	 */
	public $pagesCurrent;

	/**
	 * @var    integer
	 * @since  12.2
	 */
	public $pagesTotal;

	/**
	 * @var    boolean  View all flag
	 * @since  12.1
	 */
	protected $viewall = false;

	/**
	 * Additional URL parameters to be added to the pagination URLs generated by the class.  These
	 * may be useful for filters and extra values when dealing with lists and GET requests.
	 *
	 * @var    array
	 * @since  12.1
	 */
	protected $additionalUrlParams = array();

	/**
	 * Constructor.
	 *
	 * @param   integer  $total       The total number of items.
	 * @param   integer  $limitstart_faq  The offset of the item to start_faq at.
	 * @param   integer  $limit_faq       The number of items to display per page.
	 * @param   string   $prefix      The prefix used for request variables.
	 *
	 * @since   11.1
	 */
	public function __construct($total, $limitstart_faq, $limit_faq, $prefix = '')
	{
		// Value/type checking.
		$this->total = (int) $total;
		$this->limitstart_faq = (int) max($limitstart_faq, 0);
		$this->limit_faq = (int) max($limit_faq, 0);
		$this->prefix = $prefix;

		if ($this->limit_faq > $this->total)
		{
			$this->limitstart_faq = 0;
		}

		if (!$this->limit_faq)
		{
			$this->limit_faq = $total;
			$this->limitstart_faq = 0;
		}

		/*
		 * If limitstart_faq is greater than total (i.e. we are asked to display records that don't exist)
		 * then set limitstart_faq to display the last natural page of results
		 */
		if ($this->limitstart_faq > $this->total - $this->limit_faq)
		{
			$this->limitstart_faq = max(0, (int) (ceil($this->total / $this->limit_faq) - 1) * $this->limit_faq);
		}

		// Set the total pages and current page values.
		if ($this->limit_faq > 0)
		{
			$this->pagesTotal = ceil($this->total / $this->limit_faq);
			$this->pagesCurrent = ceil(($this->limitstart_faq + 1) / $this->limit_faq);
		}

		// Set the pagination iteration loop values.
		$displayedPages = 10;
		$this->pagesStart = $this->pagesCurrent - ($displayedPages / 2);
		if ($this->pagesStart < 1)
		{
			$this->pagesStart = 1;
		}
		if ($this->pagesStart + $displayedPages > $this->pagesTotal)
		{
			$this->pagesStop = $this->pagesTotal;
			if ($this->pagesTotal < $displayedPages)
			{
				$this->pagesStart = 1;
			}
			else
			{
				$this->pagesStart = $this->pagesTotal - $displayedPages + 1;
			}
		}
		else
		{
			$this->pagesStop = $this->pagesStart + $displayedPages - 1;
		}

		// If we are viewing all records set the view all flag to true.
		if ($limit_faq == 0)
		{
			$this->viewall = true;
		}
	}

	/**
	 * Method to set an additional URL parameter to be added to all pagination class generated
	 * links.
	 *
	 * @param   string  $key    The name of the URL parameter for which to set a value.
	 * @param   mixed   $value  The value to set for the URL parameter.
	 *
	 * @return  mixed  The old value for the parameter.
	 *
	 * @since   11.1
	 */
	public function setAdditionalUrlParam($key, $value)
	{
		// Get the old value to return and set the new one for the URL parameter.
		$result = isset($this->additionalUrlParams[$key]) ? $this->additionalUrlParams[$key] : null;

		// If the passed parameter value is null unset the parameter, otherwise set it to the given value.
		if ($value === null)
		{
			unset($this->additionalUrlParams[$key]);
		}
		else
		{
			$this->additionalUrlParams[$key] = $value;
		}

		return $result;
	}

	/**
	 * Method to get an additional URL parameter (if it exists) to be added to
	 * all pagination class generated links.
	 *
	 * @param   string  $key  The name of the URL parameter for which to get the value.
	 *
	 * @return  mixed  The value if it exists or null if it does not.
	 *
	 * @since   11.1
	 */
	public function getAdditionalUrlParam($key)
	{
		$result = isset($this->additionalUrlParams[$key]) ? $this->additionalUrlParams[$key] : null;

		return $result;
	}

	/**
	 * Return the rationalised offset for a row with a given index.
	 *
	 * @param   integer  $index  The row index
	 *
	 * @return  integer  Rationalised offset for a row with a given index.
	 *
	 * @since   11.1
	 */
	public function getRowOffset($index)
	{
		return $index + 1 + $this->limitstart_faq;
	}

	/**
	 * Return the pagination data object, only creating it if it doesn't already exist.
	 *
	 * @return  object   Pagination data object.
	 *
	 * @since   11.1
	 */
	public function getData()
	{
		static $data;
		if (!is_object($data))
		{
			$data = $this->_buildDataObject();
		}
		return $data;
	}

	/**
	 * Create and return the pagination pages counter string, ie. Page 2 of 4.
	 *
	 * @return  string   Pagination pages counter string.
	 *
	 * @since   11.1
	 */
	public function getPagesCounter()
	{
		$html = null;
		if ($this->pagesTotal > 1)
		{
			$html .= JText::sprintf('JLIB_HTML_PAGE_CURRENT_OF_TOTAL', $this->pagesCurrent, $this->pagesTotal);
		}
		return $html;
	}

	/**
	 * Create and return the pagination result set counter string, e.g. Results 1-10 of 42
	 *
	 * @return  string   Pagination result set counter string.
	 *
	 * @since   11.1
	 */
	public function getResultsCounter()
	{
		$html = null;
		$fromResult = $this->limitstart_faq + 1;

		// If the limit_faq is reached before the end of the list.
		if ($this->limitstart_faq + $this->limit_faq < $this->total)
		{
			$toResult = $this->limitstart_faq + $this->limit_faq;
		}
		else
		{
			$toResult = $this->total;
		}

		// If there are results found.
		if ($this->total > 0)
		{
			$msg = JText::sprintf('JLIB_HTML_RESULTS_OF', $fromResult, $toResult, $this->total);
			$html .= "\n" . $msg;
		}
		else
		{
			$html .= "\n" . JText::_('JLIB_HTML_NO_RECORDS_FOUND');
		}

		return $html;
	}

	/**
	 * Create and return the pagination page list string, ie. Previous, Next, 1 2 3 ... x.
	 *
	 * @return  string  Pagination page list string.
	 *
	 * @since   11.1
	 */
	public function getPagesLinks()
	{
		$app = JFactory::getApplication();

		// Build the page navigation list.
		$data = $this->_buildDataObject();

		$list = array();
		$list['prefix'] = $this->prefix;

		$itemOverride = false;
		$listOverride = false;

//		$chromePath = JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';
//		if (file_exists($chromePath))
//		{
//			include_once $chromePath;
//			if (function_exists('pagination_item_active') && function_exists('pagination_item_inactive'))
//			{
//				$itemOverride = true;
//			}
//			if (function_exists('pagination_list_render'))
//			{
//				$listOverride = true;
//			}
//		}

		// Build the select list
		if ($data->all->base !== null)
		{
			$list['all']['active'] = true;
			$list['all']['data'] = ($itemOverride) ? pagination_item_active($data->all) : $this->_item_active($data->all);
		}
		else
		{
			$list['all']['active'] = false;
			$list['all']['data'] = ($itemOverride) ? pagination_item_inactive($data->all) : $this->_item_inactive($data->all);
		}

		if ($data->start_faq->base !== null)
		{
			$list['start_faq']['active'] = true;
			$list['start_faq']['data'] = ($itemOverride) ? pagination_item_active($data->start_faq) : $this->_item_active($data->start_faq);
		}
		else
		{
			$list['start_faq']['active'] = false;
			$list['start_faq']['data'] = ($itemOverride) ? pagination_item_inactive($data->start_faq) : $this->_item_inactive($data->start_faq);
		}
		if ($data->previous->base !== null)
		{
			$list['previous']['active'] = true;
			$list['previous']['data'] = ($itemOverride) ? pagination_item_active($data->previous) : $this->_item_active($data->previous);
		}
		else
		{
			$list['previous']['active'] = false;
			$list['previous']['data'] = ($itemOverride) ? pagination_item_inactive($data->previous) : $this->_item_inactive($data->previous);
		}

		// Make sure it exists
		$list['pages'] = array();
		foreach ($data->pages as $i => $page)
		{
			if ($page->base !== null)
			{
				$list['pages'][$i]['active'] = true;
				$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_active($page) : $this->_item_active($page);
			}
			else
			{
				$list['pages'][$i]['active'] = false;
				$list['pages'][$i]['data'] = ($itemOverride) ? pagination_item_inactive($page) : $this->_item_inactive($page);
			}
		}

		if ($data->next->base !== null)
		{
			$list['next']['active'] = true;
			$list['next']['data'] = ($itemOverride) ? pagination_item_active($data->next) : $this->_item_active($data->next);
		}
		else
		{
			$list['next']['active'] = false;
			$list['next']['data'] = ($itemOverride) ? pagination_item_inactive($data->next) : $this->_item_inactive($data->next);
		}

		if ($data->end->base !== null)
		{
			$list['end']['active'] = true;
			$list['end']['data'] = ($itemOverride) ? pagination_item_active($data->end) : $this->_item_active($data->end);
		}
		else
		{
			$list['end']['active'] = false;
			$list['end']['data'] = ($itemOverride) ? pagination_item_inactive($data->end) : $this->_item_inactive($data->end);
		}

		if ($this->total > $this->limit_faq)
		{
			return ($listOverride) ? pagination_list_render($list) : $this->_list_render($list);
		}
		else
		{
			return '';
		}
	}

	/**
	 * Return the pagination footer.
	 *
	 * @return  string   Pagination footer.
	 *
	 * @since   11.1
	 */
	public function getListFooter()
	{
		$app = JFactory::getApplication();

		$list = array();
		$list['prefix'] = $this->prefix;
		$list['limit_faq'] = $this->limit_faq;
		$list['limitstart_faq'] = $this->limitstart_faq;
		$list['total'] = $this->total;
		$list['limit_faqfield'] = $this->getLimitBox();
		$list['pagescounter'] = $this->getPagesCounter();
		$list['pageslinks'] = $this->getPagesLinks();

//		$chromePath = JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';
//		if (file_exists($chromePath))
//		{
//			include_once $chromePath;
//			if (function_exists('pagination_list_footer'))
//			{
//				return pagination_list_footer($list);
//			}
//		}
		return $this->_list_footer($list);
	}

	/**
	 * Creates a dropdown box for selecting how many records to show per page.
	 *
	 * @return  string  The HTML for the limit_faq # input box.
	 *
	 * @since   11.1
	 */
	public function getLimitBox()
	{
		$app = JFactory::getApplication();
		$limit_faqs = array();

		// Make the option list.
		for ($i = 5; $i <= 30; $i += 5)
		{
			$limit_faqs[] = JHtml::_('select.option', "$i");
		}
		$limit_faqs[] = JHtml::_('select.option', '50', JText::_('J50'));
		$limit_faqs[] = JHtml::_('select.option', '100', JText::_('J100'));
		$limit_faqs[] = JHtml::_('select.option', '0', JText::_('JALL'));

		$selected = $this->viewall ? 0 : $this->limit_faq;

		// Build the select list.
		if ($app->isAdmin())
		{
			$html = JHtml::_(
				'select.genericlist',
				$limit_faqs,
				$this->prefix . 'limit_faq',
				'class="inputbox input-mini" size="1" onchange="Joomla.submitform();"',
				'value',
				'text',
				$selected
			);
		}
		else
		{
			$html = JHtml::_(
				'select.genericlist',
				$limit_faqs,
				$this->prefix . 'limit_faq',
				'class="inputbox input-mini" size="1" onchange="this.form.submit()"',
				'value',
				'text',
				$selected
			);
		}
		return $html;
	}

	/**
	 * Return the icon to move an item UP.
	 *
	 * @param   integer  $i          The row index.
	 * @param   boolean  $condition  True to show the icon.
	 * @param   string   $task       The task to fire.
	 * @param   string   $alt        The image alternative text string.
	 * @param   boolean  $enabled    An optional setting for access control on the action.
	 * @param   string   $checkbox   An optional prefix for checkboxes.
	 *
	 * @return  string   Either the icon to move an item up or a space.
	 *
	 * @since   11.1
	 */
	public function orderUpIcon($i, $condition = true, $task = 'orderup', $alt = 'JLIB_HTML_MOVE_UP', $enabled = true, $checkbox = 'cb')
	{
		if (($i > 0 || ($i + $this->limitstart_faq > 0)) && $condition)
		{
			return JHtml::_('jgrid.orderUp', $i, $task, '', $alt, $enabled, $checkbox);
		}
		else
		{
			return '&#160;';
		}
	}

	/**
	 * Return the icon to move an item DOWN.
	 *
	 * @param   integer  $i          The row index.
	 * @param   integer  $n          The number of items in the list.
	 * @param   boolean  $condition  True to show the icon.
	 * @param   string   $task       The task to fire.
	 * @param   string   $alt        The image alternative text string.
	 * @param   boolean  $enabled    An optional setting for access control on the action.
	 * @param   string   $checkbox   An optional prefix for checkboxes.
	 *
	 * @return  string   Either the icon to move an item down or a space.
	 *
	 * @since   11.1
	 */
	public function orderDownIcon($i, $n, $condition = true, $task = 'orderdown', $alt = 'JLIB_HTML_MOVE_DOWN', $enabled = true, $checkbox = 'cb')
	{
		if (($i < $n - 1 || $i + $this->limitstart_faq < $this->total - 1) && $condition)
		{
			return JHtml::_('jgrid.orderDown', $i, $task, '', $alt, $enabled, $checkbox);
		}
		else
		{
			return '&#160;';
		}
	}

	/**
	 * Create the HTML for a list footer
	 *
	 * @param   array  $list  Pagination list data structure.
	 *
	 * @return  string  HTML for a list footer
	 *
	 * @since   11.1
	 */
	protected function _list_footer($list)
	{
		$html = "<div class=\"list-footer\">\n";

		//$html .= "\n<div class=\"limit_faq\">" . $list['limit_faqfield'] . "</div>";
		$html .= $list['pageslinks'].$list['limit_faqfield'];
		//$html .= "\n<div class=\"counter\">" . $list['pagescounter'] . "</div>";

		$html .= "\n<input type=\"hidden\" name=\"" . $list['prefix'] . "limitstart_faq\" value=\"" . $list['limitstart_faq'] . "\" />";
		$html .= "\n</div>";

		return $html;
	}

	/**
	 * Create the html for a list footer
	 *
	 * @param   array  $list  Pagination list data structure.
	 *
	 * @return  string  HTML for a list start_faq, previous, next,end
	 *
	 * @since   11.1
	 */
	protected function _list_render($list)
	{
		// Reverse output rendering for right-to-left display.
		$html = '<ul>';
		$html .= '<li class="pagination-start_faq">' . $list['start_faq']['data'] . '</li>';
		$html .= '<li class="pagination-prev">' . $list['previous']['data'] . '</li>';
		foreach ($list['pages'] as $page)
		{
			$html .= '<li>' . $page['data'] . '</li>';
		}
		$html .= '<li class="pagination-next">' . $list['next']['data'] . '</li>';
		$html .= '<li class="pagination-end">' . $list['end']['data'] . '</li>';
		$html .= '</ul>';

		return $html;
	}

	/**
	 * Method to create an active pagination link to the item
	 *
	 * @param   JEFAQPaginationObject  $item  The object with which to make an active link.
	 *
	 * @return   string  HTML link
	 *
	 * @since    11.1
	 */
	protected function _item_active(JEFAQPaginationObject $item)
	{
		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			if ($item->base > 0)
			{
				return "<a title=\"" . $item->text . "\" onclick=\"document.adminForm." . $this->prefix . "limitstart_faq.value=" . $item->base
					. "; Joomla.submitform();return false;\">" . $item->text . "</a>";
			}
			else
			{
				return "<a title=\"" . $item->text . "\" onclick=\"document.adminForm." . $this->prefix
					. "limitstart_faq.value=0; Joomla.submitform();return false;\">" . $item->text . "</a>";
			}
		}
		else
		{
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"pagenav\">" . $item->text . "</a>";
		}
	}

	/**
	 * Method to create an inactive pagination string
	 *
	 * @param   JEFAQPaginationObject  $item  The item to be processed
	 *
	 * @return  string
	 *
	 * @since   11.1
	 */
	protected function _item_inactive(JEFAQPaginationObject $item)
	{
		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			return "<span>" . $item->text . "</span>";
		}
		else
		{
			return "<span class=\"pagenav\">" . $item->text . "</span>";
		}
	}

	/**
	 * Create and return the pagination data object.
	 *
	 * @return  object  Pagination data object.
	 *
	 * @since   11.1
	 */
	protected function _buildDataObject()
	{
		$data = new stdClass;

		// Build the additional URL parameters string.
		$params = '';
		if (!empty($this->additionalUrlParams))
		{
			foreach ($this->additionalUrlParams as $key => $value)
			{
				$params .= '&' . $key . '=' . $value;
			}
		}

		$data->all = new JEFAQPaginationObject(JText::_('JLIB_HTML_VIEW_ALL'), $this->prefix);
		if (!$this->viewall)
		{
			$data->all->base = '0';
			$data->all->link = JRoute::_($params . '&' . $this->prefix . 'limitstart_faq=');
		}

		// Set the start_faq and previous data objects.
		$data->start_faq = new JEFAQPaginationObject(JText::_('JLIB_HTML_START'), $this->prefix);
		$data->previous = new JEFAQPaginationObject(JText::_('JPREV'), $this->prefix);

		if ($this->pagesCurrent > 1)
		{
			$page = ($this->pagesCurrent - 2) * $this->limit_faq;

			// Set the empty for removal from route
			// @todo remove code: $page = $page == 0 ? '' : $page;

			$data->start_faq->base = '0';
			$data->start_faq->link = JRoute::_($params . '&' . $this->prefix . 'limitstart_faq=0');
			$data->previous->base = $page;
			$data->previous->link = JRoute::_($params . '&' . $this->prefix . 'limitstart_faq=' . $page);
		}

		// Set the next and end data objects.
		$data->next = new JEFAQPaginationObject(JText::_('JNEXT'), $this->prefix);
		$data->end = new JEFAQPaginationObject(JText::_('JLIB_HTML_END'), $this->prefix);

		if ($this->pagesCurrent < $this->pagesTotal)
		{
			$next = $this->pagesCurrent * $this->limit_faq;
			$end = ($this->pagesTotal - 1) * $this->limit_faq;

			$data->next->base = $next;
			$data->next->link = JRoute::_($params . '&' . $this->prefix . 'limitstart_faq=' . $next);
			$data->end->base = $end;
			$data->end->link = JRoute::_($params . '&' . $this->prefix . 'limitstart_faq=' . $end);
		}

		$data->pages = array();
		$stop = $this->pagesStop;
		for ($i = $this->pagesStart; $i <= $stop; $i++)
		{
			$offset = ($i - 1) * $this->limit_faq;

			// Set the empty for removal from route
			// @todo remove code: $offset = $offset == 0 ? '' : $offset;

			$data->pages[$i] = new JEFAQPaginationObject($i, $this->prefix);
			if ($i != $this->pagesCurrent || $this->viewall)
			{
				$data->pages[$i]->base = $offset;
				$data->pages[$i]->link = JRoute::_($params . '&' . $this->prefix . 'limitstart_faq=' . $offset);
			}
			elseif ($i = $this->pagesCurrent)
			{
				$data->pages[$i]->active = true;
			}
		}
		return $data;
	}

	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $value     The value of the property to set.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 * @deprecated  13.3  Access the properties directly.
	 */
	public function set($property, $value = null)
	{
		JLog::add('JEFAQPagination::set() is deprecated. Access the properties directly.', JLog::WARNING, 'deprecated');

		if (strpos($property, '.'))
		{
			$prop = explode('.', $property);
			$prop[1] = ucfirst($prop[1]);
			$property = implode($prop);
		}
		$this->$property = $value;
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $default   The default value.
	 *
	 * @return  mixed    The value of the property.
	 *
	 * @since   12.2
	 * @deprecated  13.3  Access the properties directly.
	 */
	public function get($property, $default = null)
	{
		JLog::add('JEFAQPagination::get() is deprecated. Access the properties directly.', JLog::WARNING, 'deprecated');

		if (strpos($property, '.'))
		{
			$prop = explode('.', $property);
			$prop[1] = ucfirst($prop[1]);
			$property = implode($prop);
		}
		if (isset($this->$property))
		{
			return $this->$property;
		}
		return $default;
	}
}




/**
 * Pagination object representing a particular item in the pagination lists.
 *
 * @package     Joomla.Platform
 * @subpackage  Pagination
 * @since       11.1
 */
class JEFAQPaginationObject
{
	/**
	 * @var    string  The link text.
	 * @since  11.1
	 */
	public $text;

	/**
	 * @var    integer  The number of rows as a base offset.
	 * @since  11.1
	 */
	public $base;

	/**
	 * @var    string  The link URL.
	 * @since  11.1
	 */
	public $link;

	/**
	 * @var    integer  The prefix used for request variables.
	 * @since  11.1
	 */
	public $prefix;

	/**
	 * @var    boolean  Flag whether the object is the 'active' page
	 * @since  12.2
	 */
	public $active;

	/**
	 * Class constructor.
	 *
	 * @param   string   $text    The link text.
	 * @param   integer  $prefix  The prefix used for request variables.
	 * @param   integer  $base    The number of rows as a base offset.
	 * @param   string   $link    The link URL.
	 * @param   boolean  $active  Flag whether the object is the 'active' page
	 *
	 * @since   11.1
	 */
	public function __construct($text, $prefix = '', $base = null, $link = null, $active = false)
	{
		$this->text   = $text;
		$this->prefix = $prefix;
		$this->base   = $base;
		$this->link   = $link;
		$this->active = $active;
	}

}

