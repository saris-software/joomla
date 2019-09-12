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
defined('_JEXEC') or die('Restricted access'); ?>
<ul class="ja-login<?php echo $params->get('moduleclass_sfx','')?>">
<?php if($type == 'logout') : ?>
	<li>
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="form-login" id="login-form">
<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<?php if($params->get('name') == 0) :
		echo JText::sprintf('HINAME', $user->get('username'));
	 else :
		echo JText::sprintf('HINAME', $user->get('name'));
	 endif; ?>
	</div>
<?php endif; ?>
	<div class="logout-button">
		<input type="submit" name="Submit" class="button btn" value="<?php echo JText::_('JLOGOUT'); ?>" />
	</div>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_('form.token');?>
</form>
	</li>
<?php else : ?>
	<li>
		<a class="login-switch" href="<?php echo JRoute::_('index.php?option=com_users&view=login');?>" onclick="showBox('ja-user-login','mod_login_username',this, window.event || event);return false;" title="<?php echo JText::_('TXT_LOGIN');?>"><span><?php echo JText::_('TXT_LOGIN');?></span></a>

	<!--LOFIN FORM content-->
	<div id="ja-user-login">
	<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
        <?php JHTML::_('script', 'openid.js'); ?>
    <?php endif; ?>
	  <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="form-login" id="login-form" >
			<div class="pretext">
				<?php echo $params->get('pretext'); ?>
			</div>
			<fieldset class="userdata">
				<p id="form-login-username">
					<label for="modlgn-username"><?php echo JText::_('JAUSERNAME') ?></label>
					<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
				</p>
				<p id="form-login-password">
					<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
					<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
				</p>
				<?php if (!is_null($tfa) && $tfa != array()):?>
				<p class="login-input secretkey">
					<label class="" for="secretkey" id="secretkey-lbl" aria-invalid="false"><?php echo JText::_('JASECRETKEY') ?></label>
					<input type="text" size="25" value="" id="secretkey" name="secretkey">
				</p>
				<?php endif; ?>
				<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
				<p id="form-login-remember">
					<label for="modlgn-remember"><?php echo JText::_('JAREMEMBER_ME') ?></label>
					<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
				</p>
				<?php endif; ?>
				<input type="submit" name="Submit" class="button btn" value="<?php echo JText::_('JABUTTON_LOGIN'); ?>" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.login" />
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<?php echo JHTML::_('form.token'); ?>
			</fieldset>
			<ul>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
					<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
				</li>
				<?php
				$usersConfig = JComponentHelper::getParams('com_users');
				if ($usersConfig->get('allowUserRegistration')) : ?>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
						<?php echo JText::_('REGISTER'); ?></a>
				</li>
				<?php endif; ?>
			</ul>
	        <?php echo $params->get('posttext'); ?>
	    </form>
    </div>

	</li>
	<?php
		$jinput = JFactory::getApplication()->input;
		$option = $jinput->get('option', '', 'CMD');
		$task = $jinput->get('task', '', 'CMD');
		if($option!='com_user' && $task != 'register' && $params->get('show_register_form', 1)) { ?>
	<li>
		<a class="register-switch" href="<?php echo JRoute::_("index.php?option=com_users&task=registration");?>" onclick="showBox('ja-user-register','namemsg',this, window.event || event);return false;" >
			<span><?php echo JText::_('REGISTER');?></span>
		</a>
		<!--Register FORM content-->
		<div id="ja-user-register" <?php if(!empty($captchatext)) echo "class='hascaptcha'"; ?> >
			<?php
			JHTML::_('behavior.keepalive');
			JHTML::_('behavior.formvalidation');
			?>

			<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate">
				<fieldset>
				<?php if (isset($fieldset->label)):// If the fieldset has a label set, display it as the legend.?>
					<legend><?php echo JText::_($fieldset->label);?></legend>
				<?php endif;?>
					<dl>
						<dt>
							<label  class="required" for="jform_name" id="jform_name-lbl" title=""><?php echo JText::_( 'JANAME' ); ?>:</label>
							<em> (*)</em>
						</dt>						
						<dd><input type="text" size="30" class="required" value="" id="jform_name" name="jform[name]"></dd>

						<dt>
							<label title="" class="required" for="jform_username" id="jform_username-lbl"><?php echo JText::_( 'JAUSERNAME' ); ?>:</label>
							<em> (*)</em>	
						</dt>						
						<dd><input type="text" size="30" class="validate-username required" value="" id="jform_username" name="jform[username]"></dd>

						<dt>
							<label title="" class="required" for="jform_password1" id="jform_password1-lbl"><?php echo JText::_( 'JGLOBAL_PASSWORD' ); ?>:</label>
							<em> (*)</em>
						</dt>						
						<dd><input type="password" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password1" name="jform[password1]"></dd>
						
						<dt>
							<label title="" class="required" for="jform_password2" id="jform_password2-lbl"><?php echo JText::_( 'JGLOBAL_REPASSWORD' ); ?>:</label>
							<em> (*)</em>
						</dt>						
						<dd><input type="password" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password2" name="jform[password2]"></dd>
						
						<dt>
							<label title="" class="required" for="jform_email1" id="jform_email1-lbl"><?php echo JText::_( 'JAEMAIL' ); ?>:</label>
							<em> (*)</em>	
						</dt>						
						<dd><input type="text" size="30" class="validate-email required" value="" id="jform_email1" name="jform[email1]"></dd>
						
						<dt>
							<label title="" class="required" for="jform_email2" id="jform_email2-lbl"><?php echo JText::_( 'JACONFIRM_EMAIL_ADDRESS'); ?>:</label>
							<em> (*)</em>	
						</dt>
						<dd><input type="text" size="30" class="validate-email required" value="" id="jform_email2" name="jform[email2]"></dd>
						
						<?php if(!empty($captchatext)): ?>
                            <?php if (!$captchaType): ?>
                                <dt>
                                    <label title="" class="required"  id="jform_captcha-lbl"><?php echo JText::_( 'JACAPTCHA'); ?>:</label>
                                    <em> (*)</em>
                                </dt>
                            <?php endif; ?>
                            <dd><?php echo $captchatext; ?></dd>
						<?php endif; ?>
						
						<?php
						    $privacy = JPluginHelper::getPlugin( 'system', 'privacyconsent' );
						    if (!empty($privacy)) {
                                JFormHelper::addFieldPath(JPATH_SITE . '/plugins/system/privacyconsent/field');
                                JForm::addFormPath(JPATH_SITE . '/plugins/system/privacyconsent/privacyconsent');
                                $form2 = new JForm('jform');
                                $form2->loadFile('privacyconsent');
                                $fields = $form2->getFieldset('privacyconsent');
                            
                                $params = new JRegistry($privacy->params);
                                $privacyArticleId = $params->get('privacy_article');
                                $privacynote      = $params->get('privacy_note');
                                $form2->setFieldAttribute('privacy', 'article', $privacyArticleId, 'privacyconsent');
                                $form2->setFieldAttribute('privacy', 'note', $privacynote, 'privacyconsent');
                                foreach ($fields as $kf => $field) {
                                    echo str_replace('privacyconsent[privacy]','jform[privacyconsent][privacy]',$field->renderField());
                                }
						    }
						?>

						<?php
						    $term = JPluginHelper::getPlugin( 'user', 'terms' );
						    if (!empty($term)) {
                                JFormHelper::addFieldPath(JPATH_SITE . '/plugins/user/terms/field');
                                JForm::addFormPath(JPATH_SITE . '/plugins/user/terms/terms');
                                $form3 = new JForm('jform');
                                $form3->loadFile('terms');
                                $fields = $form3->getFieldset('terms');
                            
                                $params = new JRegistry($term->params);
                                $termsarticle = $params->get('terms_article');
                                $termsnote    = $params->get('terms_note');
                                $form3->setFieldAttribute('terms', 'article', $termsarticle, 'terms');
                                $form3->setFieldAttribute('terms', 'note', $termsnote, 'terms');
                                foreach ($fields as $kf => $field) {
                                    echo str_replace('terms[terms]','jform[terms][terms]',$field->renderField());
                                }
						    }
						?>
					</dl>
				</fieldset>
				<br/>
				<p><?php echo JText::_("DESC_REQUIREMENT"); ?></p>
				<button type="submit" class="validate btn"><?php echo JText::_('JAREGISTER');?></button>
				<div>
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="registration.register" />
					<?php echo JHTML::_('form.token');?>
				</div>
			</form>
				<!-- Old code -->
		</div>
	</li>
	<?php } ?>
		<!--LOFIN FORM content-->
<?php endif; ?>
</ul>