<?php defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.filesystem.file' );
//$image['width'] = $image['height'] = 100;
//array('width' => $image['width'], 'height' => $image['height'])
?><div class="phocafavicon-box-file-i">
	<center>
		<div class="phocafavicon-box-file-first-i">
			<div class="phocafavicon-box-file-second">
				<div class="phocafavicon-box-file-third">
					<center>
					<a href="#" onclick="if (window.parent) window.parent.<?php echo $this->fce; ?>('<?php echo $this->_tmp_img->path_with_name_relative_no; ?>');">
	<?php
	echo JHTML::_( 'image', $this->_tmp_img->linkthumbnailpath, '', array(), '', null); ?></a>
					</center>
				</div>
			</div>
		</div>
	</center>
	
	<div class="name"><?php echo $this->_tmp_img->name; ?></div>
		<div class="detail" style="text-align:right">
			<a href="#" onclick="if (window.parent) window.parent.<?php echo $this->fce; ?>('<?php echo $this->_tmp_img->path_with_name_relative_no; ?>');"><?php echo JHTML::_( 'image', 'media/com_phocafavicon/images/administrator/icon-insert.gif', JText::_('COM_PHOCAFAVICON_INSERT_IMAGE'), array('title' => JText::_('COM_PHOCAFAVICON_INSERT_IMAGE'))); ?></a>
		</div>
	<div style="clear:both"></div>
</div>
