<?php
/*
 * ------------------------------------------------------------------------
 * JA Alumni Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

if ($this->params->get('presentation_style')=='sliders'):?>
<div class="accordion-group">
	<div class="accordion-heading">
		<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#slide-contact" href="#display-links">
			<span class="marker">
	        	<span class="marker-close"><span class="fa fa-plus"></span></span>
	        	<span class="marker-open"><span class="fa fa-minus"></span></span>
	        </span>

		<?php echo JText::_('COM_CONTACT_LINKS');?>
		</a>
	</div>
	<div id="display-links" class="panel-collapse collapse">
		<div class="accordion-inner">
<?php endif; ?>
<?php if ($this->params->get('presentation_style') == 'tabs') : ?>
<div id="display-links" class="tab-pane">
<?php endif; ?>


			<div class="contact-links">
				<ul class="nav">
					<?php
					foreach (range('a', 'e') as $char) :// letters 'a' to 'e'
						$link = $this->contact->params->get('link'.$char);
						$label = $this->contact->params->get('link'.$char.'_name');

						if (!$link) :
							continue;
						endif;

						// Add 'http://' if not present
						$link = (0 === strpos($link, 'http')) ? $link : 'http://'.$link;

						// If no label is present, take the link
						$label = ($label) ? $label : $link;
						?>
						<li>
							<a href="<?php echo $link; ?>" title="<?php echo $link; ?>" class="<?php echo str_replace(" ","-",strtolower($label));?>">
								<?php echo $label; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

<?php if ($this->params->get('presentation_style')=='sliders'):?>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if ($this->params->get('presentation_style') == 'tabs') : ?>
</div>
<?php endif; ?>
