<?php 
/** 
 * @package JMAP::PINGOMATIC::administrator::components::com_jmap
 * @subpackage views
 * @subpackage pingomatic
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div id="accordion_pingomatic_details" class="panel panel-info panel-group adminform">
		<div class="panel-heading" data-target="#pingomatic_details"><h4><?php echo JText::_('COM_JMAP_PINGOMATIC_DETAILS' ); ?></h4></div>
		<div class="panel-body panel-collapse collapse" id="pingomatic_details">
			<table class="admintable">
				<tbody>
					<tr>
						<td class="key left_title">
							<label for="title">
								<?php echo JText::_('COM_JMAP_LINKTITLE' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" class="inputbox" name="title" id="title" data-validation="required" value="<?php echo $this->record->title;?>" />
						</td>
					</tr>
					<tr>
						<td class="key left_title">
							<label for="linkurl">
								<?php echo JText::_('COM_JMAP_LINKURL' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" class="inputbox" name="blogurl" id="linkurl" data-validation="required url" value="<?php echo $this->record->blogurl;?>" />
							<label class="as label label-primary hasClickPopover" data-title="<?php echo JText::_('COM_JMAP_PICKURL_DESC');?>"><?php echo JText::_('COM_JMAP_PICKURL');?></label>
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label for="rssurl">
								<?php echo JText::_('COM_JMAP_RSSURL' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<input type="text" class="inputbox" name="rssurl" id="rssurl" data-validation="url" value="<?php echo $this->record->rssurl;?>" />
						</td>
					</tr> 
					<tr>
						<td class="key left_title">
							<label>
								<?php echo JText::_('COM_JMAP_LASTPING' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<?php if($this->record->lastping):?>
								<label class="label label-warning" id="lastping">
									<?php echo JHtml::_('date', $this->record->lastping, JText::_('DATE_FORMAT_LC2')); ?>
								</label>
							<?php else:?>
								<label class="label label-warning" id="lastping"></label>
							<?php endif;?>
						</td>
					</tr> 
				</tbody>
			</table>
		</div>
	</div>
	
	<div id="accordion_pingomatic_services" class="panel panel-info panel-group adminform">
		<div class="panel-heading" data-target="#pingomatic_services"><h4><?php echo JText::_('COM_JMAP_PINGOMATIC_SERVICES' ); ?></h4></div>
		<div class="panel-body panel-collapse collapse" id="pingomatic_services">	
			<table class="admintable">
				<tbody>
					<tr>
						<td class="key left_title">
							<label for="description">
								<?php echo JText::_('COM_JMAP_SERVICES_LIST' ); ?>:
							</label>
						</td>
						<td class="right_details">
							<div class="panel panel-success">
								<div class="panel-heading">
							  		<?php echo JText::_('COM_JMAP_COMMON_SERVICES'); ?>
							  	</div>
								<div class="panel-body">
								    <div id="common">
								    	<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_twingly'];?></fieldset><a href="https://www.twingly.com/" target="_blank"><label class="as label label-info">Twingly</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_pingomatic'];?></fieldset><a href="https://pingomatic.com/" target="_blank"><label class="as label label-info">Pingomatic</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_bing'];?></fieldset><a href="https://www.bing.com/" target="_blank"><label class="as label label-info">Bing</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_blogwith2net'];?></fieldset><a href="https://blog.with2.net/" target="_blank"><label class="as label label-info">Blog with2</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_blogs'];?></fieldset><a href="http://blo.gs/" target="_blank"><label class="as label label-info">Blog.gs</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_fc2'];?></fieldset><a href="https://fc2.com/" target="_blank"><label class="as label label-info">Fc2</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_pingoo'];?></fieldset><a href="http://pingoo.jp/" target="_blank"><label class="as label label-info">Pingoo</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_blogpeople'];?></fieldset><a href="http://www.blogpeople.net/" target="_blank"><label class="as label label-info">Blog people</label></a></div>
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['ajs_feedburner'];?></fieldset><a href="https://feedburner.google.com" target="_blank"><label class="as label label-info">Feed Burner</label></a></div>
									</div>
								</div>
							</div>
							<div class="panel panel-success">
								<div class="panel-heading">
							  		<?php echo JText::_('COM_JMAP_SPECIALIZED_SERVICES');?>
							  	</div>
								<div class="panel-body">
									<div id="specialized">
										<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['chk_blogs'];?></fieldset><a href="http://blo.gs/" target="_blank"><label class="as label label-info">Blo.gs</label></a></div>
								    	<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['chk_feedburner'];?></fieldset><a href="http://feedburner.com/" target="_blank"><label class="as label label-info">Feed Burner</label></a></div>
								    	<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['chk_tailrank'];?></fieldset><a href="http://spinn3r.com/" target="_blank"><label class="as label label-info">Spinn3r</label></a></div>
								    	<div class="service_control"><fieldset class="radio btn-group"><?php echo $this->lists['chk_superfeedr'];?></fieldset><a href="http://superfeedr.com/" target="_blank"><label class="as label label-info">Superfeedr</label></a></div>
									</div>
								</div>
							</div>
						</td>
					</tr> 
				</tbody>
			</table>
		</div>
	</div>
		
	<input type="hidden" name="option" value="<?php echo $this->option?>" />
	<input type="hidden" name="id" value="<?php echo $this->record->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>

<iframe src="<?php echo $this->urischeme;?>://pingomatic.com/" id="pingomatic_iframe" name="pingomatic_iframe"></iframe>
<div id="pingomatic_ajaxloader"></div>