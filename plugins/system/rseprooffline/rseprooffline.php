<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemRseproOffline extends JPlugin
{
	//set the value of the payment option
	var $rsprooption = 'offline';
	
	public function __construct( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}
	
	/*
	*	Is RSEvents!Pro installed
	*/
	
	protected function canRun() {
		$helper = JPATH_SITE.'/components/com_rseventspro/helpers/rseventspro.php';
		if (file_exists($helper)) {
			require_once $helper;
			JFactory::getLanguage()->load('plg_system_rseprooffline',JPATH_ADMINISTRATOR);
			
			return true;
		}
		
		return false;
	}
	
	/*
	*	Add the current payment option to the Payments List
	*/

	public function rsepro_addOptions() {
		if ($this->canRun())
			return JHTML::_('select.option', $this->rsprooption, JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_NAME'));
		else return JHTML::_('select.option', '', '');
	}
	
	/*
	*	Add optional fields for the payment plugin. Example: Credit Card Number, etc.
	*	Please use the syntax <form method="post" action="index.php?option=com_rseventspro&task=process" name="paymentForm">
	*	The action provided in the form will actually run the rsepro_processForm() of your payment plugin.
	*/
	
	public function rsepro_showForm($vars) {
		
		if (JFactory::getApplication()->isClient('administrator') || !$this->canRun() || !JPluginHelper::isEnabled('system', 'rseprooffline')) {
			return;
		}
		
		
		if (isset($vars['method']) && $vars['method'] == $this->rsprooption) {
			JFactory::getLanguage()->load('com_rseventspro',JPATH_SITE);
			
			// Load variables
			$details	= $vars['details'];
			$tickets	= $vars['tickets'];
			$currency	= $vars['currency'];
			$total		= $vars['total'];
			$info		= $vars['info'];
			
			// Do we have a valid payment request ?
			if (empty($details) && empty($tickets)) {
				return;
			}
			
			// Get months
			$months[] = JHTML::_('select.option', '01', '01-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH1'));
			$months[] = JHTML::_('select.option', '02', '02-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH2'));
			$months[] = JHTML::_('select.option', '03', '03-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH3'));
			$months[] = JHTML::_('select.option', '04', '04-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH4'));
			$months[] = JHTML::_('select.option', '05', '05-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH5'));
			$months[] = JHTML::_('select.option', '06', '06-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH6'));
			$months[] = JHTML::_('select.option', '07', '07-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH7'));
			$months[] = JHTML::_('select.option', '08', '08-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH8'));
			$months[] = JHTML::_('select.option', '09', '09-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH9'));
			$months[] = JHTML::_('select.option', '10', '10-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH10'));
			$months[] = JHTML::_('select.option', '11', '11-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH11'));
			$months[] = JHTML::_('select.option', '12', '12-'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_MONTH12'));
			
			$exp_month = JHTML::_('select.genericlist', $months, 'cc_exp_m', 'class="rs_select" size="1"','value','text',date('m'));
			
			$startYear	= JFactory::getDate()->format('Y');
			$stopYear	= $startYear + 10;
			$years		= array();
			
			for ($i = $startYear; $i <= $stopYear; $i++) {
				$years[] = JHTML::_('select.option', $i, $i);
			}
			
			$exp_year = JHTML::_('select.genericlist', $years, 'cc_exp_y', 'class="rs_select" size="1"','value','text',date('Y'));
			
			$formURL = $this->params->get('use_ssl',0) ? JRoute::_('index.php?option=com_rseventspro&task=process',false,true) : rseventsproHelper::route('index.php?option=com_rseventspro&task=process',false);
			
			$html = '';
			$html .= '<h2 class="componentheading">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_NAME').'</h2>'."\n";
			$html .= '<div class="rs_offline">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_INFO').'</div>'."\n";
			$html .= '<form method="post" action="'.$formURL.'" onsubmit="return rs_cc_form();" autocomplete="off">'."\n";
			$html .= '<div class="rs_payment_offline">'."\n";
			$html .= '<fieldset>'."\n";
			$html .= '<legend>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_TICKETS_INFO').'</legend>'."\n";
			$html .= '<table cellspacing="10" cellpadding="0" class="table table-bordered rs_table">'."\n";
			$html .= '<tr>'."\n";
			$html .= '<td>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_TICKETS').'</td>'."\n";
			$html .= '<td>'."\n";
			$html .= implode('<br />',$info);
			$html .= '</td>'."\n";
			$html .= '</tr>'."\n";
			
			if (!empty($details->discount)) {
				$html .= '<tr>'."\n";
				$html .= '<td>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_TICKETS_DISCOUNT').'</td>'."\n";
				$html .= '<td>'.rseventsproHelper::currency($details->discount).'</td>'."\n";
				$html .= '</tr>'."\n";
			}
			
			if ($details->early_fee) {
				$html .= '<tr>'."\n";
				$html .= '<td>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_EARLY_FEE').'</td>'."\n";
				$html .= '<td>'."\n";
				$html .= rseventsproHelper::currency($details->early_fee);
				$html .= '</td>'."\n";
				$html .= '</tr>'."\n";
			}
			
			if ($details->late_fee) {
				$html .= '<tr>'."\n";
				$html .= '<td>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_LATE_FEE').'</td>'."\n";
				$html .= '<td>'."\n";
				$html .= rseventsproHelper::currency($details->late_fee);
				$html .= '</td>'."\n";
				$html .= '</tr>'."\n";
			}
			
			if (!empty($details->tax)) {
				$html .= '<tr>'."\n";
				$html .= '<td>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_TICKETS_TAX').'</td>'."\n";
				$html .= '<td>'.rseventsproHelper::currency($details->tax).'</td>'."\n";
				$html .= '</tr>'."\n";
			}
			
			$html .= '<tr>'."\n";
			$html .= '<td>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_TICKETS_TOTAL').'</td>'."\n";
			$html .= '<td>'.rseventsproHelper::currency($total).'</td>'."\n";
			$html .= '</tr>'."\n";
			
			$html .= '</table>'."\n";
			$html .= '</fieldset>'."\n";
			
			$html .= '<fieldset>'."\n";
			$html .= '<legend>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_CC_INFO').'</legend>'."\n";
			$html .= '<table width="100%" cellspacing="10" cellpadding="0" border="0" class="rs_table">'."\n";
			$html .= '<tr>'."\n";
			$html .= '<td height="40">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_CARD_NUMBER').'</td>'."\n";
			$html .= '<td><input type="text" onkeyup="return rs_check_card(this);" onkedown="return rs_check_card(this);" maxlength="19" size="40" value="" id="cc_number" name="cc_number" class="rs_textbox"> '.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_CARD_CSC').' <input type="text" onkeyup="return rs_check_card(this);" onkedown="return rs_check_card(this);" maxlength="4" style="width: 45px; text-align: center;" size="40" value="" id="cc_csc" name="cc_csc" class="rs_textbox"> <span onmouseout="rs_tooltip.hide();" onmouseover="rs_tooltip.show(\'rs_tooltip\');" id="rs_whats_csc">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_CARD_CSC_WHATS_THIS').'</span></td>'."\n";
			$html .= '</tr>'."\n";
			$html .= '<tr>'."\n";
			$html .= '<td height="40">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_CARD_EXP_DATE').'</td>'."\n";
			$html .= '<td>'.$exp_month.'  '.$exp_year.'</td>'."\n";
			$html .= '</tr>'."\n";
			$html .= '<tr>'."\n";
			$html .= '<td height="40">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_FIRST_NAME').'</td>'."\n";
			$html .= '<td><input type="text" class="rs_textbox" id="cc_fname" name="cc_fname" value="" /></td>'."\n";
			$html .= '</tr>'."\n";
			$html .= '<tr>'."\n";
			$html .= '<td height="40">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_LAST_NAME').'</td>'."\n";
			$html .= '<td><input type="text" class="rs_textbox" id="cc_lname" name="cc_lname" value="" /></td>'."\n";
			$html .= '</tr>'."\n";
			$html .= '<tr>'."\n";
			$html .= '<td colspan="2"><button type="submit" class="btn btn-primary">'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_PAY').'</button></td>'."\n";
			$html .= '</tr>'."\n";
			$html .= '</table>'."\n";
			$html .= '</fieldset>'."\n";
			$html .= '</div>'."\n";
			$html .= '<input type="hidden" name="ids" value="'.$details->id.'" />'."\n";
			$html .= '</form>'."\n";

			$html .= '<div id="rs_tooltip" style="display: none;">'."\n"; 
			$html .= '<div>'.JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_CARD_CSC_DESC').'</div>'."\n";
			$html .= '<div align="center">'.JHtml::image('com_rseventspro/cc_csc.gif', 'CSC', array(), true).'</div>';
			$html .= '</div>'."\n";
			
			echo $html;			
		}
	}
	
	/*
	*	Process the form
	*/
	public function rsepro_processForm($vars) {
		// Can we run this ?
		if (!$this->canRun()) {
			return;
		}
		
		require_once JPATH_SITE.'/components/com_rseventspro/helpers/crypt.php';
		
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$app	= JFactory::getApplication();
		
		$data = $vars['data'];
		
		$cc_number	= $data->get('cc_number');
		$cc_csc		= $data->get('cc_csc');
		$cc_exp_m	= $data->get('cc_exp_m');
		$cc_exp_y	= $data->get('cc_exp_y');
		$cc_fname	= $data->get('cc_fname');
		$cc_lname	= $data->get('cc_lname');
		
		
		$cc_number	= isset($cc_number) && !empty($cc_number) ? $cc_number : '';
		$cc_csc		= isset($cc_csc) && !empty($cc_csc) ? $cc_csc : '';
		$cc_exp_m	= isset($cc_exp_m) && !empty($cc_exp_m) ? $cc_exp_m : '';
		$cc_exp_y	= isset($cc_exp_y) && !empty($cc_exp_y) ? $cc_exp_y : '';
		$f_name		= isset($cc_fname) && !empty($cc_fname) ? $cc_fname : '';
		$l_name		= isset($cc_lname) && !empty($cc_lname) ? $cc_lname : '';
		$ids		= $data->getInt('ids');
		$cc_exp		= $cc_exp_m.' / '.$cc_exp_y;
		$name		= $f_name. ' ' .$l_name; 
		
		$query->clear()
			->select($db->qn('gateway'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.(int) $ids);
		
		$db->setQuery($query);
		$gateway = $db->loadResult();
		
		if ($gateway != $this->rsprooption) return;
		
		if (empty($ids)) {
			$app->enqueueMessage(JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_INVALID_SUBSCRIPTION'));
			$app->redirect(rseventsproHelper::route('index.php?option=com_rseventspro&layout=default',false));
		}
		
		$crypt = new RseventsproCryptHelper($cc_number,$cc_csc,$name);
		
		$cc_number	= $crypt->get('cc_number');
		$cc_csc		= $crypt->get('cc_csc');
		
		$query->clear()
			->insert($db->qn('#__rseventspro_cards'))
			->set($db->qn('ids').' = '.(int) $ids)
			->set($db->qn('card_number').' = '.$db->q($cc_number))
			->set($db->qn('card_csc').' = '.$db->q($cc_csc))
			->set($db->qn('card_exp').' = '.$db->q($cc_exp))
			->set($db->qn('card_fname').' = '.$db->q($f_name))
			->set($db->qn('card_lname').' = '.$db->q($l_name));
		
		$db->setQuery($query);
		$db->execute();
		
		$query->clear()
			->select($db->qn('e.id'))->select($db->qn('e.name'))
			->from($db->qn('#__rseventspro_events','e'))
			->join('left', $db->qn('#__rseventspro_users','u').' ON '.$db->qn('u.ide').' = '.$db->qn('e.id'))
			->where($db->qn('u.id').' = '.(int) $ids);
		
		$app->enqueueMessage(JText::_('COM_RSEVENTSPRO_PLG_PLUGIN_OFFLINE_PAYMENT_RECEIVED'));
		
		$db->setQuery($query);
		if ($event = $db->loadObject()) {
			$app->redirect(rseventsproHelper::route('index.php?option=com_rseventspro&layout=show&id='.rseventsproHelper::sef($event->id,$event->name),false));
		} else {
			$app->redirect(rseventsproHelper::route('index.php?option=com_rseventspro',false));
		}
	}
	
	public function rsepro_tax($vars) {
		if (!$this->canRun()) return;
		
		if (isset($vars['method']) && $vars['method'] == $this->rsprooption) {
			$total		= isset($vars['total']) ? $vars['total'] : 0;
			$tax_value	= $this->params->get('tax_value',0);
			$tax_type	= $this->params->get('tax_type',0);
			
			return rseventsproHelper::setTax($total,$tax_type,$tax_value);
		}
	}
	
	public function rsepro_info($vars) {
		if (!$this->canRun()) return;
		
		if (isset($vars['method']) && $vars['method'] == $this->rsprooption) {
			$data = $vars['data'];
			
			if (!empty($data)) {
				echo '<table width="100%" border="0" class="adminform rs_table table table-striped">';
				echo '<thead><tr><th colspan="2">'.JText::_('COM_RSEVENTSPRO_TRANSACTION_PAYMENT_DETAILS').'</th></tr></thead>';
				echo '<tr>';
				echo '<td width="25%" align="right"><b>'.JText::_('COM_RSEVENTSPRO_TRANSACTION_CC_NAME').'</b></td>';
				echo '<td>'.$data->name.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td width="25%" align="right"><b>'.JText::_('COM_RSEVENTSPRO_TRANSACTION_CC_NUMBER').'</b></td>';
				echo '<td>'.$data->card_number.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td width="25%" align="right"><b>'.JText::_('COM_RSEVENTSPRO_TRANSACTION_CC_EXPIRATION').'</b></td>';
				echo '<td>'.$data->card_exp.'</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td width="25%" align="right"><b>'.JText::_('COM_RSEVENTSPRO_TRANSACTION_CC_CSC').'</b></td>';
				echo '<td>'.$data->card_csc.'</td>';
				echo '</tr>';
				echo '</table>';
			}
		}
	}
}