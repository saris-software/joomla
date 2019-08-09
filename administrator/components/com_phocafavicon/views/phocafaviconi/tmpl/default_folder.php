<?php defined('_JEXEC') or die('Restricted access'); ?>

<div class="phocafavicon-box-file-i">
	<center>
		<div class="phocafavicon-box-file-first-i">
			<div class="phocafavicon-box-file-second">
				<div class="phocafavicon-box-file-third">
					<center>
					<a href="index.php?option=com_phocafavicon&amp;view=phocafaviconi&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_with_name_relative_no . '&field='.$this->field; ?>"><?php echo JHTML::_( 'image', 'media/com_phocafavicon/images/administrator/icon-folder-images.gif', ''); ?></a>
					</center>
				</div>
			</div>
		</div>
	</center>
	
	<div class="name"><a href="index.php?option=com_phocafavicon&amp;view=phocafaviconi&amp;tmpl=component&amp;folder=<?php echo $this->_tmp_folder->path_with_name_relative_no . '&field='.$this->field; ?>"><span><?php echo PhocaFaviconHelper::WordDelete($this->_tmp_folder->name,15); ?></span></a></div>
		<div class="detail" style="text-align:right">&nbsp;</div>
	<div style="clear:both"></div>
</div>
