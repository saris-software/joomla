<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocafavicon-upload">';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<form action="'. $this->t['su_url'] .'" id="uploadFormU" method="post" enctype="multipart/form-data">';
if ($this->t['ftp']) { echo PhocaFaviconHelper::renderFTPaccess();}  
echo '<h4>'; 
echo JText::_( 'COM_PHOCAFAVICON_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCAFAVICON_MAX_SIZE' ).':&nbsp;'.$this->t['uploadmaxsizeread'].','
	.' '.JText::_('COM_PHOCAFAVICON_MAX_RESOLUTION').':&nbsp;'. $this->t['uploadmaxreswidth'].' x '.$this->t['uploadmaxresheight'].' px ]';
echo ' </h4>';
echo $this->t['su_output'];
echo '</form>';	 
echo '</div>';