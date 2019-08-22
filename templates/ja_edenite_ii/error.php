<?php
/**
 * ------------------------------------------------------------------------
 * JA Edenite II Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
$theme = JFactory::getApplication()->getTemplate(true)->params->get('theme', '');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/error.css" type="text/css" />
	<?php if($theme && is_file(T3_TEMPLATE_PATH . '/css/themes/' . $theme . '/error.css')):?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/themes/<?php echo $theme ?>/error.css" type="text/css" />
	<?php endif; ?>
	<?php 
	if ($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/error_rtl.css" type="text/css" />
	<?php endif; ?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
</head>
<body class="page-error">
	<div class="main">
		<div class="error">
			<div id="outline">
				<div id="errorboxoutline">
					<div class="error-code"><?php 
						$errcode = str_split($this->error->getCode());
						$i = 0;
						$lastclass='';
						foreach($errcode as $c){
	                        $firstclass = ($i==0)?'first':'';
							if($i==(count($errcode)-1)){
								$lastclass='last';
							}
							echo '<span class="'.$lastclass.$firstclass.'">'.$c.'</span>';
							$i++;
						}
						?>
					</div>
					<div class="wrap-text">
						<div class="detail-message has-des">
							<div class="error-message"><?php echo $this->error->getMessage(); ?></div>
							<div class="des-message"><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></div>
						</div>
					</div>
					
					<a class="button-home show" href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
				</div>
			</div>
		</div>
	</div>
	<script>
		/* (function($){
			// container is the DOM element;
			// userText is the textbox
			
			var container = $(".error-message"),
				alert = $(".des-message");
			
			// Shuffle the contents of container
			container.shuffleLetters();


			// Leave a 4 second pause

			setTimeout(function(){
				$(".detail-message").addClass("has-des");
				$(".button-home").addClass("show");
				// Shuffle the container with custom text
				alert.shuffleLetters({
					"text": "<?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?>"
				});
				
			},4000);
			
		})(jQuery); */
	</script>
</body>
</html>
