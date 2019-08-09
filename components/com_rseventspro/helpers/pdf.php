<?php
/**
* @package RSEvents!Pro
* @copyright (C) 2015 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JFactory::getLanguage()->load('plg_system_rsepropdf', JPATH_ADMINISTRATOR);

class RSEventsProPDF
{
	protected $pdf;
	
	public function __construct() {
		if (!isset($this->pdf)) {
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/pdf/dompdf/dompdf_config.inc.php';
			$this->pdf = new DOMPDF();
		}
	}
	
	public function getInstance() {
		return new RSEventsProPDF();
	}
	
	public function write($html) {
		$pdf	  = $this->pdf;
		
		if (preg_match_all('#[^\x00-\x7F]#u', $html, $matches)) {
			foreach ($matches[0] as $match) {
				$html = str_replace($match, $this->_convertASCII($match), $html);
			}
		}
		
		$pdf->load_html(utf8_decode($html), 'utf-8');
		$pdf->render();
		
		return $pdf->output();
	}
	
	public function output($html, $name) {
		$pdf	  = $this->pdf;
		
		if (preg_match_all('#[^\x00-\x7F]#u', $html, $matches)) {
			foreach ($matches[0] as $match) {
				$html = str_replace($match, $this->_convertASCII($match), $html);
			}
		}
		
		$pdf->load_html(utf8_decode($html), 'utf-8');
		$pdf->render();
		
		return $pdf->stream($name);
	}
	
	protected function _convertASCII($str) {
		$count	= 1;
		$out	= '';
		$temp	= array();
		
		for ($i = 0, $s = strlen($str); $i < $s; $i++) {
			$ordinal = ord($str[$i]);
			if ($ordinal < 128) {
				$out .= $str[$i];
			}
			else
			{
				if (count($temp) == 0) {
					$count = ($ordinal < 224) ? 2 : 3;
				}
			
				$temp[] = $ordinal;
			
				if (count($temp) == $count) {
					$number = ($count == 3) ? (($temp['0'] % 16) * 4096) + (($temp['1'] % 64) * 64) + ($temp['2'] % 64) : (($temp['0'] % 32) * 64) + ($temp['1'] % 64);

					$out .= '&#'.$number.';';
					$count = 1;
					$temp = array();
				}
			}
		}
		
		return $out;
	}
	
	protected function generateCode($ids, $tid, $pos) {
		$code	= md5($ids.$tid.$pos);
		$code	= substr($code,0,4).substr($code,-4);
		
		return rseventsproHelper::getConfig('barcode_prefix', 'string', 'RST-').$ids.'-'.$code;
	}
	
	public function ticket($ids, $tid = null) {
		$app	= JFactory::getApplication();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$ide	= $app->input->getInt('ide',0);
		$tid	= !is_null($tid) ? (int) $tid : $app->input->getInt('tid',0);
		$pos	= $app->input->getInt('position',0);
		
		// Get subscriber details
		$query->clear()
			->select($db->qn('ide'))->select($db->qn('name'))->select($db->qn('discount'))
			->select($db->qn('early_fee'))->select($db->qn('late_fee'))->select($db->qn('tax'))
			->select($db->qn('state'))->select($db->qn('gateway'))->select($db->qn('ip'))
			->select($db->qn('coupon'))->select($db->qn('email'))->select($db->qn('SubmissionId'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('id').' = '.(int) $ids);
		
		$db->setQuery($query);
		$subscription = $db->loadObject();
			
		if ($subscription->state != 1) {
			throw new Exception(JText::_('RSEPRO_PDF_ERROR_TICKET_SUBSCRIBER'));
			return false;
		}
		
		// Set the event ID
		$ide = !empty($ide) ? $ide : $subscription->ide;
		
		// Get event details
		$query->clear()
			->select($db->qn('id'))->select($db->qn('icon'))
			->from($db->qn('#__rseventspro_events'))
			->where($db->qn('id').' = '.(int) $ide);
		
		$db->setQuery($query);
		$event = $db->loadObject();
		
		if (empty($event)) {
			throw new Exception(JText::sprintf('RSEPRO_PDF_ERROR_TICKET_NO_EVENT', $ide));
		}
		
		// Get ticket layout
		$query->clear()
			->select($db->qn('name'))->select($db->qn('layout'))
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('id').' = '.$db->q($tid));
		$db->setQuery($query);
		$ticket = $db->loadObject();
		
		$layout 	= $ticket->layout;
		$name		= $ticket->name;
		$hasLayout 	= rseventsproHelper::hasPDFLayout($layout,$subscription->SubmissionId);
		
		if (!$hasLayout) {
			throw new Exception(JText::_('RSEPRO_PDF_ERROR_TICKET_NO_LAYOUT'));
		}
		
		$app->triggerEvent('rseproTicketPDFLayout',array(array('ids' => $ids, 'ide' => $ide, 'idt' => $idt, 'layout' => &$layout)));
		
		// Create the barcode text
		$barcode = $this->generateCode($ids, $tid, $pos);
		
		// Get tickets
		$tickets	= rseventsproHelper::getUserTickets($ids);
		$info		= '';
		$total		= 0;
		$cart		= false;
		JFactory::getApplication()->triggerEvent('rsepro_isCart', array(array('cart' => &$cart)));
		
		if (!empty($tickets)) {
			foreach ($tickets as $ticket) {
				if ($ticket->price > 0) {
					$price = $ticket->price * (int) $ticket->quantity;
					$total += $price;
					
					$info .= $ticket->quantity . ' x ' .$ticket->name.' ('.rseventsproHelper::currency($ticket->price).') '.rseventsproHelper::getSeats($ids,$ticket->id).' <br />';
				} else {
					$info .= $ticket->quantity . ' x ' .$ticket->name.' ('.JText::_('COM_RSEVENTSPRO_GLOBAL_FREE').') <br />';
				}
			}
		}
		
		if (!empty($subscription->discount) && !empty($total))
			$total = $total - $subscription->discount;
		
		if (!empty($subscription->early_fee) && !empty($total))
			$total = $total - $subscription->early_fee;
		
		if (!empty($subscription->late_fee) && !empty($total))
			$total = $total + $subscription->late_fee;
		
		if (!empty($subscription->tax) && !empty($total))
			$total = $total + $subscription->tax;
		
		
		$ticketstotal		= rseventsproHelper::currency($total);
		$ticketsdiscount	= !empty($subscription->discount) ? rseventsproHelper::currency($subscription->discount) : '';
		$subscriptionTax	= !empty($subscription->tax) ? rseventsproHelper::currency($subscription->tax) : '';
		$lateFee			= !empty($subscription->late_fee) ? rseventsproHelper::currency($subscription->late_fee) : '';
		$earlyDiscount		= !empty($subscription->early_fee) ? rseventsproHelper::currency($subscription->early_fee) : '';
		$gateway			= rseventsproHelper::getPayment($subscription->gateway);
		$IP					= $subscription->ip;
		$coupon				= !empty($subscription->coupon) ? $subscription->coupon : '';
		$optionals			= array($info, $ticketstotal, $ticketsdiscount, $subscriptionTax, $lateFee, $earlyDiscount, $gateway, $IP, $coupon);
		
		$app->triggerEvent('rsepro_beforeReplacePDFLayout', array(array('layout' => &$layout, 'ids' => $ids, 'ide' => $ide, 'idt' => $tid, 'position' => $pos)));
		
		$layout = rseventsproEmails::placeholders($layout, $ide, $subscription->name, $optionals);
		$layout = str_replace(array('{sitepath}', '{useremail}'), array(JPATH_SITE, $subscription->email), $layout);
		
		if (strpos($layout,'{barcode}') !== FALSE) {
			jimport('joomla.filesystem.file');
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/pdf/barcodes.php';
			$barcodePDF = new TCPDFBarcode($barcode, rseventsproHelper::getConfig('barcode'));
			
			ob_start();
			$barcodePDF->getBarcodePNG();
			$thecode = ob_get_contents();
			ob_end_clean();
			
			$file = JPATH_SITE.'/components/com_rseventspro/assets/barcode/rset-'.md5($barcode).'.png';
			$upload = JFile::write($file,$thecode);
			$barcodeHTML = $upload ? '<img src="'.$file.'" alt="" />' : '';
			
			$layout = str_replace('{barcode}', $barcodeHTML, $layout);
		}
		
		// Event Icon
		$small = $big = $normal = '';
		
		if ($event->icon) {
			// Original event icon
			if (strpos($layout,'{EventIconPdf}') !== FALSE) {
				$normal = JPATH_SITE.'/components/com_rseventspro/assets/images/events/'.$event->icon;
			}
			
			// Small event icon
			if (strpos($layout,'{EventIconSmallPdf}') !== FALSE) {
				$small = rseventsproHelper::thumb($event->id, rseventsproHelper::getConfig('icon_small_width','int'));
				$small = str_replace(JUri::root(), JPATH_SITE.'/', $small);
			}
			
			// Big event icon
			if (strpos($layout,'{EventIconBigPdf}') !== FALSE) {
				$big = rseventsproHelper::thumb($event->id, rseventsproHelper::getConfig('icon_big_width','int'));
				$big = str_replace(JUri::root(), JPATH_SITE.'/', $big);
			}
		}
		
		$layout = str_replace(array('{EventIconPdf}', '{EventIconSmallPdf}', '{EventIconBigPdf}', '{barcodetext}'), array($normal, $small, $big, $barcode), $layout);
		
		// Output PDF content
		$buffer = $this->output($layout, $name.'.pdf');
		
		if ($file && file_exists($file)) {
			JFile::delete($file);
		}
		
		if ($small && file_exists($small)) {
			JFile::delete($small);
		}
		
		if ($big && file_exists($big)) {
			JFile::delete($big);
		}
		
		return $buffer;
	}
	
	public function tickets($id) {
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		// Check to see if this event has tickets
		$query->clear()
			->select('COUNT('.$db->qn('id').')')
			->from($db->qn('#__rseventspro_tickets'))
			->where($db->qn('ide').' = '.(int) $id);
		
		$db->setQuery($query);
		if (! (int) $db->loadResult()) {
			throw new Exception(JText::_('RSEPRO_PDF_ERROR_NO_TICKETS'));
		}
		
		// Get subscriptions having $id as the event ID
		$query->clear()
			->select($db->qn('id'))->select($db->qn('name'))
			->select($db->qn('email'))->select($db->qn('date'))
			->from($db->qn('#__rseventspro_users'))
			->where($db->qn('ide').' = '.(int) $id)
			->where($db->qn('state').' = 1');
		
		$db->setQuery($query);
		$subscriptions = $db->loadObjectList();
		
		JFactory::getApplication()->triggerEvent('rsepro_subscriptionsPDF', array(array('subscriptions' => &$subscriptions, 'ide' => $id)));
		
		if (!$subscriptions) {
			throw new Exception(JText::_('RSEPRO_PDF_ERROR_NO_SUBSCRIBERS'));
		}
		
		if (!empty($subscriptions)) {
			$query->clear()->select($db->qn('name'))->from($db->qn('#__rseventspro_events'))->where($db->qn('id').' = '.(int) $id);
			$db->setQuery($query);
			$event = $db->loadResult();
			
			jimport('joomla.filesystem.file');
			require_once JPATH_SITE.'/components/com_rseventspro/helpers/pdf/barcodes.php';
			
			$i		 = 1;
			$files	 = array();
			$type	 = rseventsproHelper::getConfig('barcode');
			$layout  = '';
			
			$layout .= '<style type="text/css">table { border-collapse:collapse } .rsepro_bottom { border-bottom: 3px solid black; }</style>';
			$layout .= '<table width="100%" cellspacing="0" cellpadding="10" border="1">';
			
			foreach ($subscriptions as $subscription) {
				$query->clear()
					->select($db->qn('t.id'))->select($db->qn('t.name'))
					->select($db->qn('t.price'))->select($db->qn('ut.quantity'))
					->from($db->qn('#__rseventspro_tickets','t'))
					->join('LEFT', $db->qn('#__rseventspro_user_tickets','ut').' ON '.$db->qn('t.id').' = '.$db->qn('ut.idt'))
					->where($db->qn('t.ide').' = '.$db->q($id))
					->where($db->qn('ut.ids').' = '.$db->q($subscription->id));
				$db->setQuery($query);
				if ($tickets = $db->loadObjectList()) {
					foreach ($tickets as $ticket) {
						for ($j = 1; $j <= $ticket->quantity; $j++) {
							$code = $this->generateCode($subscription->id, $ticket->id, $j);
							$barcode = new TCPDFBarcode($code, $type);
							
							ob_start();
							$barcode->getBarcodePNG();
							$thecode = ob_get_contents();
							ob_end_clean();
							
							$file	= JPATH_SITE.'/components/com_rseventspro/assets/barcode/rset-'.md5($subscription->id.$ticket->id.$j).'.png';
							$upload = JFile::write($file,$thecode);
							$width	= $type != 'qrcode' ? 'width="500"' : 'width="100"';
							$barcodeHTML = $upload ? '<img src="'.$file.'" '.$width.' alt="" /> <br />'.$code : '';
							
							$layout .= '<tr class="rsepro_bottom">';
							$layout .= '<td width="1%">'.$i.'</td>';
							
							$layout .= '<td>';
							$layout .= '<table width="100%" cellspacing="0" cellpadding="10">';
							$layout .= '<tr>';
							$layout .= '<td align="center">'.$barcodeHTML.'</td>';
							$layout .= '</tr>';
							$layout .= '<tr>';
							$layout .= '<td>'.$subscription->name . ' (' .$subscription->email.')<br />';
							$layout .= $ticket->name. ' ('.($ticket->price ? rseventsproHelper::currency($ticket->price) : JText::_('COM_RSEVENTSPRO_GLOBAL_FREE')).') <br />';
							$layout .= JText::_('COM_RSEVENTSPRO_SUBSCRIBED_ON').' '.rseventsproHelper::showdate($subscription->date).'</td>';
							$layout .= '</tr>';
							
							$layout .= '</table>';
							$layout .= '</td>';
							$layout .= '</tr>';
							
							$files[] = $file;
							$i++;
						}
					}
				}
			}
			
			$layout .= '</table>';
			
			$buffer = $this->output($layout, 'Tickets.pdf');
			
			if (!empty($files)) {
				foreach ($files as $file) {
					if (JFile::exists($file)) {
						JFile::delete($file);
					}
				}
			}
			
			return $buffer;
		}
		
		return false;
	}
}