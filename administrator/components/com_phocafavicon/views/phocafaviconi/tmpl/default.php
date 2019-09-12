<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo $this->loadTemplate('up'); ?>
<?php if (count($this->images) > 0 || count($this->folders) > 0) { ?>
<div>
		<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
			$this->setFolder($i);
			echo $this->loadTemplate('folder');
		endfor; ?>

		<?php for ($i=0,$n=count($this->images); $i<$n; $i++) :
			$this->setImage($i);
			echo $this->loadTemplate('image');
		endfor; ?>

</div>
<?php } else { ?>
<div>
	<center style="clear:both;font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
		<?php echo JText::_( 'COM_PHOCAFAVICON_THERE_IS_NO_IMAGE' ); ?>
	</center>
</div>
<?php } ?>

<div style="clear:both">
<div style="border-bottom:1px solid #cccccc;margin-bottom: 10px">&nbsp;</div>

<?php
	echo $this->loadTemplate('upload');
?>
