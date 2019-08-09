<?php
/**
 * JE FAQPro package
 * @author J-Extension <contact@jextn.com>
 * @link http://www.jextn.com
 * @copyright (C) 2010 - 2011 J-Extension
 * @license GNU/GPL, see LICENSE.php for full license.
**/

// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

if($this->params->get('show_onlyregusers')) {
	if( $this->user->get('id') > 0 ) {
		$show_systems	= true;

	} else {
		$show_systems	= false;

	}
} else {
	$show_systems		= true;

}
if($this->params->get('show_expand_collapse',0)){
?>
<div id="jextn_d_exco">
	<span onclick="expandAll();" id="jextnfaq_span_exd"><?php echo JText::_('COM_JEXTN_FAQPRO_EXPAND_ALL'); ?></span>
	<span onclick="collapseAll();" id="jextnfaq_span_coll"><?php echo JText::_('COM_JEXTN_FAQPRO_COLLAPSE_ALL'); ?></span>
</div>
<?php } ?>

<div id="yui-skin-sam">
	 <div id="wrapper1">
		<ul id="mymenu<?php echo $this->settings->theme; ?>">
			<?php
			foreach( $this->items as $key=>$value) {
				$newCatId 	= $value->catid;
				$w			= $key+1;
				if($key==0){
					$ulid 	= 1;
					?>
					<div>
						<h3><?php echo $value->category_title; ?></h3>
						<?php if($this->params->get('show_des_cat',1)){ ?>
							<p><?php echo $value->category_description; ?></p>
						<?php } ?>
					</div>
					<ul id="mymenua<?php echo $this->settings->theme+$ulid; ?>" class="mymenu<?php echo $this->settings->theme; ?>">
					<?php
				} else {
					if($oldCatId!=$newCatId){
					?>
					</ul>
					<script type="text/javascript">
						var menu1 = new YAHOO.widget.AccordionView('mymenua<?php echo $this->settings->theme+$ulid; ?>', {<?php if($this->params->get('expand_first',1)) echo "expandItem : 0,"; ?>collapsible: true, width: '100%', margin : '0', animationSpeed: '0.3', animate: true, effect: YAHOO.util.Easing.easeBothStrong});
					</script>
					<div>
						<h3><?php echo $value->category_title; ?></h3>
						<?php if($this->params->get('show_des_cat',1)){ ?>
							<p><?php echo $value->category_description; ?></p>
						<?php } ?>
					</div>
					<?php $ulid++; ?>
					<ul id="mymenua<?php echo $this->settings->theme+$ulid; ?>" class="mymenu<?php echo $this->settings->theme; ?>">
					<?php

					}
				}
			?>
				<li>
					<p>	<?php echo $value->questions; ?> </p>
					<div>
						<div class="padded clearfix">
							<?php
							if (($this->params->get('show_postedby') || $this->params->get('show_posteddate')) && $show_systems == true) {

							 ?>
								<div id="je-posted" style="padding : 5px; text-align : right; font-style : italic;">
									<?php
									if ($this->params->get('show_posteddate')) {
									?>
										<span id="je-posteddate">
											<?php
												$date 	 = JFactory::getDate( $value->posted_date );
												$posted  = $date->format( $this->settings->date_format );
												echo $posted;
											?>
										</span>
									<?php
									}

									if ($this->params->get('show_postedby')) {
									?>
										<span id="je-author"> <?php echo '&nbsp;&nbsp;'.$value->posted_by; ?> </span>
									<?php
									}
									?>
								</div>
							<?php
							}

							// Answer texts
								echo $value->answers;
							?>

							<!-- Area for voting & hits -->
							<?php
							if($this->params->get('show_votes') && $show_systems == true) {

							?>
								<div style="padding : 5px 0px 5px 0px; ">
									<ul id="je-response-ul">
										<li id="je-response" >
											<span id="je-userlogin<?php echo $key; ?>"></span>
										</li>
										<li id="je-response" >
											<span class="editlinktip hasTip" title="<?php echo JText::_( 'JE_RESPONSE' )?> :: <?php echo JText::_( 'JE_LIKE' ); ?>">
												<span id="je-responsetop<?php echo $key; ?>">
													<?php
														$response_yes	= $this->model->getTotalresponse( $like=true, $value->id );
														echo $response_yes;
													?>
												</span>
												<a id="je-atagtop<?php echo $key; ?>" href="javascript:void(0);"  onclick="getResponselike('<?php echo $key; ?>','<?php echo $value->id; ?>')"  >
													<span id="je-top"> &nbsp; </span>
												</a>
											</span>
										</li>
										<li id="je-response" >
											<span class="editlinktip hasTip" title="<?php echo JText::_( 'JE_RESPONSE' )?> :: <?php echo JText::_( 'JE_DISLIKE' ); ?>">
												<span id="je-responsebot<?php echo $key; ?>">
													<?php
														$response_no	= $this->model->getTotalresponse( $like=false, $value->id );
														echo $response_no ;
													?>
												</span>
												<a id="je-atagbot<?php echo $key; ?>"  href="javascript:void(0);"  onclick="getResponsedislike('<?php echo $key; ?>','<?php echo $value->id; ?>')"  >
													<span id="je-bot"> &nbsp; </span>
												</a>
											</span>
										</li>
									</ul>
								</div>
							<?php
							}

							if($this->params->get('show_hits') && $show_systems == true) {
							?>
								<div id="je-hits<?php echo $w; ?>" style="text-align : right; padding : 2px; font-style : italic;" >
									<?php echo JText::_('JE_HITS').'&nbsp; '.$value->hits; ?>
								</div>
							<?php
							}
							?>
							<input type="hidden" name="ques_id<?php echo $w; ?>" id="ques_id<?php echo $w; ?>" value="<?php echo $value->id; ?>">
						</div>
					</div>
				</li>
			<?php
			$oldCatId = $value->catid;
			}
			?>
		</ul>
	</div>

	<script type="text/javascript">
		var menu1 = new YAHOO.widget.AccordionView('mymenua<?php echo $this->settings->theme+$ulid; ?>', {<?php if($this->params->get('expand_first',1)) echo "expandItem : 0,"; ?>collapsible: true, width: '100%', margin : '0', animationSpeed: '0.3', animate: true, effect: YAHOO.util.Easing.easeBothStrong});
	</script>
	<input type="hidden" name="site_path" id="site_path" value="<?php echo JURI::root(); ?>" />
</div>