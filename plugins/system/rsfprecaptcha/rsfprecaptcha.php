<?php
/**
* @version 1.3.0
* @package RSform!Pro 1.3.0
* @copyright (C) 2007-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * RSForm! Pro system plugin
 */

class plgSystemRSFPReCaptcha extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatibility we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->newComponents = array(24);
	}
	
	function loadLibrary()
	{
		require_once JPATH_SITE.'/plugins/system/rsfprecaptcha/relib.php';
	}
	
	function rsfp_bk_onAfterShowComponents()
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_system_rsfprecaptcha', JPATH_ADMINISTRATOR);
		
		$formId = JFactory::getApplication()->input->getInt('formId');
		$components = RSFormProHelper::componentExists($formId, 24);
		$link = "displayTemplate('24')";
		if (!empty($components))
			$link = "displayTemplate('24', '".$components[0]."')";
		
		?>
		<li class="rsform_navtitle"><?php echo JText::_('RSFP_RECAPTCHA_LABEL'); ?></li>
		<li><a href="javascript: void(0);" onclick="<?php echo $link;?>;return false;" id="rsfpc24"><span class="rsficon rsficon-rotate"></span><span class="inner-text"><?php echo JText::_('RSFP_RECAPTCHA_SPRODUCT'); ?></span></a></li>
		<?php
	}
	
	function rsfp_bk_onAfterCreateComponentPreview($args = array())
	{
		if ($args['ComponentTypeName'] == 'recaptcha')
		{
			JFactory::getLanguage()->load('plg_system_rsfprecaptcha', JPATH_ADMINISTRATOR);
			JFactory::getApplication()->enqueueMessage(JText::_('PLG_SYSTEM_RSFPRECAPTCHA_WARNING'), 'warning');
			$args['out'] ='<td>'.$args['data']['CAPTION'].'</td>';
			$args['out'] .='<td>{reCAPTCHA field}</td>';
		}
	}
	
	function rsfp_bk_onAfterShowConfigurationTabs($tabs)
	{
		$lang = JFactory::getLanguage();
		$lang->load('plg_system_rsfprecaptcha', JPATH_ADMINISTRATOR);
		
		$tabs->addTitle(JText::_('RSFP_RECAPTCHA_LABEL'), 'form-recaptcha');
		$tabs->addContent($this->recaptchaConfigurationScreen());
	}
	
	function rsfp_f_onAJAXScriptCreate($args) {
		$script =& $args['script'];
		$formId = $args['formId'];
		
		if ($componentId = RSFormProHelper::componentExists($formId, 24)) {
			static $added;
			if (!$added) {
				RSFormProAssets::addScript(JURI::root(true).'/components/com_rsform/assets/js/recaptcha.js?v='._RSFORM_REVISION);
				$added = true;
			}
		
			$args['script'] .= 'ajaxValidationRecaptcha(task, formId, data, '.$componentId[0].');'."\n";
		}
	}
	
	function rsfp_bk_onAfterCreateFrontComponentBody($args)
	{		
		$lang = JFactory::getLanguage();
		$lang->load('plg_system_rsfprecaptcha', JPATH_ADMINISTRATOR);
		
		$this->loadLibrary();
		
		RSFormProHelper::readConfig();
		$config  = JFactory::getConfig();
		$u 		 = JFactory::getURI();
		$use_ssl = $config->get('force_ssl') == 2 || $u->isSSL();
		
		if ($args['r']['ComponentTypeId'] == 24)
		{
			// Get a key from http://recaptcha.net/api/getkey
			$publickey = RSFormProHelper::getConfig('recaptcha.public.key');
			
			// the response from reCAPTCHA
			$resp = null;
			// the error code from reCAPTCHA, if any
			$error = null;
			$args['out']  = '<script type="text/javascript">'."\n";
			$args['out'] .= 'var RecaptchaOptions = {'."\n";
			$args['out'] .= "\t"."theme : '".RSFormProHelper::getConfig('recaptcha.theme')."',"."\n";
			
			$tag = $lang->getTag();
			$tag = explode('-', $tag);
			$tag = strtolower($tag[0]);
			
			//$known_languages = array('en', 'nl', 'fr', 'de', 'pt', 'ru', 'es', 'tr');
			$known_languages = array('en');
			if (in_array($tag, $known_languages))
			{
				$args['out'] .= "\t"."lang : '".$tag."'"."\n";
			}
			else
			{
				$args['out'] .= "\t"."lang : '".$tag."',"."\n";
				$args['out'] .= "\t"."custom_translations : {"."\n"
							   ."\t"."\t".'instructions_visual : \''.JText::_('RSFP_INSTRUCTIONS_VISUAL', true).'\','."\n"
							   ."\t"."\t".'instructions_audio : \''.JText::_('RSFP_INSTRUCTIONS_AUDIO', true).'\','."\n"
							   ."\t"."\t".'play_again : \''.JText::_('RSFP_PLAY_AGAIN', true).'\','."\n"
							   ."\t"."\t".'cant_hear_this : \''.JText::_('RSFP_CANT_HEAR_THIS', true).'\','."\n"
                        	   ."\t"."\t".'visual_challenge : \''.JText::_('RSFP_VISUAL_CHALLENGE', true).'\','."\n"
                        	   ."\t"."\t".'audio_challenge : \''.JText::_('RSFP_AUDIO_CHALLENGE', true).'\','."\n"
                        	   ."\t"."\t".'refresh_btn : \''.JText::_('RSFP_REFRESH_BTN', true).'\','."\n"
                        	   ."\t"."\t".'help_btn : \''.JText::_('RSFP_HELP_BTN', true).'\','."\n"
                        	   ."\t"."\t".'incorrect_try_again : \''.JText::_('RSFP_INCORRECT_TRY_AGAIN', true).'\''."\n"
							   ."\t".'}'."\n";
			}
			
			$args['out'] .= '};'."\n";
			$args['out'] .= '</script>';
			$args['out'] .= '<div id="recaptchaContainer'.$args['componentId'].'">';
			$args['out'] .= rsform_recaptcha_get_html($publickey, $error, $use_ssl);
			$args['out'] .= '</div>';
			
			// clear the token on page refresh
			$session = JFactory::getSession();
			$session->clear('com_rsform.recaptchaToken'.$args['formId']);
		}
	}
	
	/*
		Task Functions
	*/
	
	function rsfp_f_onBeforeFormValidation($args) {
		$formId 	= $args['formId'];
		$invalid 	=& $args['invalid'];
		$post		=& $args['post'];
		$form       = RSFormProHelper::getForm($formId);
		if ($form->RemoveCaptchaLogged) {
			$logged     = JFactory::getUser()->id;
		} else {
			$logged = false;
		}
		
		$privatekey = RSFormProHelper::getConfig('recaptcha.private.key');
		
		// validation:
		// if there's no session token
		// validate based on challenge & response codes
		// if valid, set the session token
		
		// session token gets cleared after form processes
		// session token gets cleared on page refresh as well
		
		if (($componentId = RSFormProHelper::componentExists($formId, 24)) && $privatekey && !$logged) {
			$challenge  = JRequest::getVar('recaptcha_challenge_field');
			$response   = JRequest::getVar('recaptcha_response_field');
			
			$task 	= strtolower(JRequest::getVar('task'));
			$option = JRequest::getVar('option');
			
			$session = JFactory::getSession();
			// already validated, move on
			if ($session->get('com_rsform.recaptchaToken'.$formId)) {
				return true;
			}
			
			$this->loadLibrary();
			
			// the response from reCAPTCHA
			$resp = null;
			
			$resp = rsform_recaptcha_check_answer($privatekey, isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '', $challenge, $response);
			
			if (empty($resp->is_valid)) {
				$invalid[] = $componentId[0];
			} elseif ($option == 'com_rsform' && $task == 'ajaxvalidate') {
				$session->set('com_rsform.recaptchaToken'.$formId, md5(uniqid($response)));
			}
		}
	}
	
	function rsfp_f_onAfterFormProcess($args) {
		$formId = $args['formId'];
		
		if (RSFormProHelper::componentExists($formId, 24)) {
			$session = JFactory::getSession();
			$session->clear('com_rsform.recaptchaToken'.$formId);
		}
	}
	
	function recaptchaConfigurationScreen()
	{
		ob_start();
		
		$themes[] = JHTML::_('select.option', 'red', JText::_( 'RSFP_RED_THEME' ) );
		$themes[] = JHTML::_('select.option', 'white', JText::_( 'RSFP_WHITE_THEME' ) );
		$themes[] = JHTML::_('select.option', 'clean', JText::_( 'RSFP_CLEAN_THEME' ) );
		$themes[] = JHTML::_('select.option', 'blackglass', JText::_( 'RSFP_BLACKGLASS_THEME' ) );
		$theme = JHTML::_('select.genericlist', $themes, 'rsformConfig[recaptcha.theme]', 'size="1" class="inputbox"', 'value', 'text', RSFormProHelper::getConfig('recaptcha.theme'));
		?>
		<div id="page-recaptcha">
			<table class="admintable">
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="public"><?php echo JText::_( 'RSFP_RECAPTCHA_PBKEY' ); ?></label></td>
					<td><input type="text" name="rsformConfig[recaptcha.public.key]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('recaptcha.public.key')); ?>" size="100" maxlength="100"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="private"><?php echo JText::_( 'RSFP_RECAPTCHA_PRKEY' ); ?></label></td>
					<td><input type="text" name="rsformConfig[recaptcha.private.key]" value="<?php echo RSFormProHelper::htmlEscape(RSFormProHelper::getConfig('recaptcha.private.key'));  ?>" size="100" maxlength="100"></td>
				</tr>
				<tr>
					<td width="200" style="width: 200px;" align="right" class="key"><label for="theme"><?php echo JText::_( 'RSFP_RECAPTCHA_THEME' ); ?></label></td>
					<td><?php echo $theme; ?></td>
				</tr>
			</table>
		</div>
		<?php
		
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}	
}