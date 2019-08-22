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

/**
 * JE FAQPro Component HTML Helper
 */
class JHTMLIcon
{
	static function create($faqs, $params)
	{
		$uri					= JFactory::getURI();
		$url					= 'index.php?option=com_jefaqpro&task=faq.add&return='.base64_encode($uri).'&a_id=0';

		if ($params->get('show_icons')) {
			$text				= JHTML::_('image','system/new.png', JText::_('JNEW'), NULL, true);
		} else {
			$text				= JText::_('JNEW').'&#160;';
		}

		$button					=  JHTML::_('link',JRoute::_($url), $text);
		$output					= '<span class="hasTip" title="'.JText::_('COM_CONTENT_CREATE_ARTICLE').'">'.$button.'</span>';

		return $output;
	}

	static function email($faqs, $params, $attribs = array())
	{
		$uri					= JURI::getInstance();
		$base					= $uri->toString(array('scheme', 'host', 'port'));
		$template				= JFactory::getApplication()->getTemplate();
		$link					= $base.JRoute::_(ContentHelperRoute::getArticleRoute($faqs->id, $faqs->catid) , false);
		$url					= 'index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.base64_encode($link);

		$status					= 'width=400,height=350,menubar=yes,resizable=yes';

		if ($params->get('show_icons')) {
			$text				= JHTML::_('image','system/emailButton.png', JText::_('JGLOBAL_EMAIL'), NULL, true);
		} else {
			$text				= '&#160;'.JText::_('JGLOBAL_EMAIL');
		}

		$attribs['title']		= JText::_('JGLOBAL_EMAIL');
		$attribs['onclick'] 	= "window.open(this.href,'win2','".$status."'); return false;";

		$output					= JHTML::_('link',JRoute::_($url), $text, $attribs);
		return $output;
	}

	/**
	 * Display an edit icon for the Faq.
	 */
	static function edit($faqs, $params, $attribs = array())
	{
		// Initialise variables.
			$user				= JFactory::getUser();
			$userId				= $user->get('id');
			$uri				= JFactory::getURI();

		// Ignore if in a popup window.
			if ($params && $params->get('popup')) {
				return;
			}

		// Ignore if the state is negative (trashed).
			if ($faqs->published < 0) {
				return;
			}

		JHtml::_('behavior.tooltip');

		$url					= 'index.php?task=faq.edit&a_id='.$faqs->id.'&return='.base64_encode($uri);
		$icon					= $faqs->published ? 'edit.png' : 'edit_unpublished.png';
		$text					= JHTML::_('image','system/'.$icon, JText::_('JGLOBAL_EDIT'), NULL, true);

		if ($faqs->published == 0) {
			$overlib			= JText::_('JUNPUBLISHED');
		}
		else {
			$overlib			= JText::_('JPUBLISHED');
		}

		$date					= JHTML::_('date',$faqs->posted_date);
		$author					= $faqs->posted_by;

		$overlib				.= '&lt;br /&gt;';
		$overlib				.= $date;
		$overlib				.= '&lt;br /&gt;';
		$overlib				.= JText::sprintf('COM_JEFAQPRO_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		$button					= JHTML::_('link',JRoute::_($url), $text);

		$output					= '<span class="hasTip" title="'.JText::_('COM_JEFAQPRO_EDIT_ITEM').' :: '.$overlib.'">'.$button.'</span>';

		return $output;
	}


	static function print_popup($faqs, $params, $attribs = array())
	{
		$url 					= ContentHelperRoute::getArticleRoute($faqs->id, $faqs->catid);
		$url					.= '&tmpl=component&print=1&layout=default&page='.@ $request->limitstart;

		$status					= 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		// checks template image directory for image, if non found default are loaded
			if ($params->get('show_icons')) {
				$text			= JHTML::_('image','system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
			} else {
				$text			= JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JGLOBAL_PRINT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
			}

		$attribs['title']		= JText::_('JGLOBAL_PRINT');
		$attribs['onclick'] 	= "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']			= 'nofollow';

		return JHTML::_('link',JRoute::_($url), $text, $attribs);
	}

	static function print_screen($faqs, $params, $attribs = array())
	{
		// checks template image directory for image, if non found default are loaded
			if ($params->get('show_icons')) {
				$text			= JHTML::_('image','system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
			} else {
				$text			= JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JGLOBAL_PRINT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
			}

		return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
	}

}