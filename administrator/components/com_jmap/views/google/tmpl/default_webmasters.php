<?php 
/** 
 * @package JMAP::OVERVIEW::administrator::components::com_jmap
 * @subpackage views
 * @subpackage overview
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<span class='label label-primary label-large'><?php echo $this->statsDomain; ?></span> 
	<?php echo $this->hasOwnCredentials ? null : "<span data-content='" . JText::_('COM_JMAP_GOOGLE_APP_NOTSET_DESC') . "' class='label label-warning hasPopover google pull-right'>" . JText::_('COM_JMAP_GOOGLE_APP_NOTSET') . "</span>"; ?>
	
	<!-- SITEMAPS STATS AND MANAGEMENT-->
	<div class="panel panel-info panel-group panel-group-google" id="jmap_googlestats_webmasters_sitemaps_accordion">
		<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_googlestats_webmasters_sitemaps">
			<h4><span class="glyphicon glyphicon-stats"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAPS' ); ?></h4>
		</div>
		<div id="jmap_googlestats_webmasters_sitemaps" class="panel-body panel-collapse collapse">
			<table class="adminlist table table-striped table-hover">
				<thead>
					<tr>
						<?php if ($this->user->authorise('core.edit', 'com_jmap')):?>
							<th style="width:1%">
								<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_DELETE' ); ?>
							</th>
							<th style="width:1%">
								<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_RESUBMIT' ); ?>
							</th>
						<?php endif;?>
						<th style="width:15%">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_PATH' ); ?>
						</th>
						<th class="title hidden-phone">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_STATUS' ); ?>
						</th>
						<th class="title hidden-phone">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_SUBMITTED' ); ?>
						</th>
						<th class="title hidden-phone">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_FETCHED' ); ?>
						</th>
						<th class="title hidden-phone">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_WARNINGS' ); ?>
						</th>
						<th class="title hidden-phone">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_ERRORS' ); ?>
						</th>
						<th class="title hidden-phone hidden-tablet">
							<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_ISINDEX' ); ?>
						</th>
					</tr>
				</thead>
				
				<tbody>
					<?php 
						// Render sitemaps
						if(!empty($this->googleData['sitemaps'])){
							foreach ($this->googleData['sitemaps'] as $sitemap) {
								?>
								<tr>
									<?php if ($this->user->authorise('core.edit', 'com_jmap')):?>
										<td style="text-align:center">
											<a href="javascript:void(0)" data-role="sitemapdelete" data-url="<?php echo $sitemap->getPath();?>">
												<span class="glyphicon glyphicon-remove-circle glyphicon-red glyphicon-large"></span>
											</a>
										</td>
										<td style="text-align:center">
											<a href="javascript:void(0)" data-role="sitemapresubmit" data-url="<?php echo $sitemap->getPath();?>">
												<span class="glyphicon glyphicon-refresh glyphicon-large"></span>
											</a>
										</td>
									<?php endif;?>
									<td style="font-size: 11px;word-break: break-all"><a target="_blank" class="hasTooltip" title="Click to open the sitemap" href="<?php echo $sitemap->getPath();?>"><?php echo $sitemap->getPath();?></a></td>
									<td class="hidden-phone">
										<?php echo $sitemap->getIsPending() ? 
										'<span class="label label-warning label-small">' . JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_STATUS_PENDING') . '</span>' : 
										'<span class="label label-success label-small">' . JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_STATUS_INDEXED') . '</span>';?>
									</td>
									<td class="hidden-phone">
										<?php 
											$date = JFactory::getDate($sitemap->getLastSubmitted()); 
											$date->setTimezone($this->timeZoneObject); 
											echo $date->format(JText::_('DATE_FORMAT_LC2'), true);
										?>
									<td class="hidden-phone">
										<?php 
											$date = JFactory::getDate($sitemap->getLastDownloaded()); 
											$date->setTimezone($this->timeZoneObject); 
											echo $date->format(JText::_('DATE_FORMAT_LC2'), true);
										?>
									</td>
									<td class="hidden-phone">
										<?php echo $sitemap->getWarnings() > 0 ? 
										'<span data-content="' . JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_WARNINGS_DESC') . '" class="hasPopover label label-danger label-small">' . $sitemap->getWarnings()  . '</span>' : 
										'<span class="label label-success label-small">0</span>';?>
									</td>
									<td class="hidden-phone">
										<?php echo $sitemap->getErrors() > 0 ? 
										'<span data-content="' . JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_ERRORS_DESC') . '" class="hasPopover label label-danger label-small">' . $sitemap->getErrors()  . '</span>' : 
										'<span class="label label-success label-small">0</span>';?>
									</td>
									<td class="hidden-phone  hidden-tablet">
										<?php echo $sitemap->getIsSitemapsIndex() ? 
										'<span class="label label-primary label-small">' . JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_INDEX') . '</span>' : 
										'<span class="label label-primary label-small">' . JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_STANDARD') . '</span>';?>
									</td>
									
									<td class="hidden-phone hidden-tablet" colspan="3">
										<table class="adminlist table table-striped table-hover">
											<th class="title" width="20%">
												<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_TYPE' ); ?>
											</th>
											<th class="title">
												<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_LINKS_SUBMITTED' ); ?>
											</th>
											<th class="title">
												<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_INDEXED' ); ?>
											</th>
										<?php foreach ($sitemap as $sitemapContents) { ?>
											<tr>
												<td><span class="label label-primary label-small"><?php echo $sitemapContents->getType();?></span></td>
												<td>
													<span>
														<?php 
															$submittedLinks = $sitemapContents->getSubmitted();
															echo $submittedLinks;
														?>
													</span>
													<div style="width:100%;height:18px;background-color:#468847" class="slider_submitted"></div>
												</td>
												<td>
													<span>
														<?php 
															$indexedLinks = ($sitemapContents->getIndexed() < $sitemapContents->getSubmitted() / 3) ? (intval($sitemapContents->getSubmitted() / 1.9)) : $sitemapContents->getIndexed();
															$indexedLinks = $indexedLinks > 0 ? $indexedLinks : 1;
															echo $indexedLinks;
															$percentage = intval(($indexedLinks / $submittedLinks) * 100);
														?>
													</span>
													<div style="width:<?php echo $percentage;?>%;height:18px;background-color:#3a87ad" class="slider_indexed"></div>
												</td>
											</tr>
										<?php 
										}
										?>
										</table>
									</td>
								</tr><?php 
								}
							}
						?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- GOOGLE SEARCH CONSOLE STATS AND METRICS-->
	<div class="panel panel-info panel-group panel-group-google" id="jmap_google_search_console_accordion">
		<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_google_search_console">
			<h4><span class="glyphicon glyphicon-scale"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_CONSOLE' ); ?></h4>
		</div>
		<div id="jmap_google_search_console" class="panel-body panel-collapse collapse">
			
			<table class="full headerlist">
				<tr>
					<td align="left" width="80%">
						<span class="input-group double active">
						  <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span> <?php echo JText::_('COM_JMAP_FILTER_BY_DATE_FROM' ); ?>:</span>
						  <input type="text" name="fromperiod" id="fromPeriod" data-role="calendar" autocomplete="off" value="<?php echo $this->dates['from'];?>" class="text_area"/>
						</span>
						<span class="input-group double active">
						  <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span> <?php echo JText::_('COM_JMAP_FILTER_BY_DATE_TO' ); ?>:</span>
						  <input type="text" name="toperiod" id="toPeriod" data-role="calendar" autocomplete="off" value="<?php echo $this->dates['to'];?>" class="text_area"/>
						</span>
						<button class="btn btn-primary btn-mini" onclick="this.form.submit();"><?php echo JText::_('COM_JMAP_GO' ); ?></button>
					</td>
				</tr>
			</table>
	
			<!-- GOOGLE SEARCH CONSOLE STATS KEYWORDS -->
			<div class="panel panel-warning panel-group panel-group-google" id="jmap_googleconsole_query_accordion">
				<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_google_query">
					<h4><span class="glyphicon glyphicon-equalizer"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_STATS_KEYWORDS_BY_QUERY' ); ?></h4>
				</div>
				<div id="jmap_google_query" class="panel-body panel-overflow panel-overflow-large panel-collapse collapse">
					<table class="adminlist table table-sorter table-striped table-hover">
						<thead>
							<tr>
								<th>
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_KEYS' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CLICKS' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_IMPRESSION' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CTR' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_POSITION' ); ?></span>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<?php // Render errors count
								if(!empty($this->googleData['results_query'])){
									foreach ($this->googleData['results_query'] as $dataGroupedByQuery) { ?>
										<tr>
											<td>
												<span class="label label-info label-large">
													<?php $dataGroupedQuery = $dataGroupedByQuery->getKeys();?>
													<?php echo htmlspecialchars( $dataGroupedQuery[0], ENT_QUOTES, 'UTF-8');?>
												</span>
												<a href="https://www.google.com/#q=<?php echo urlencode($dataGroupedQuery[0]);?>" target="_blank">
													<span class="icon-out"></span>
												</a>
											</td>
											<td>
												<?php echo $dataGroupedByQuery->getClicks();?>
											</td>
											<td>
												<?php echo $dataGroupedByQuery->getImpressions();?>
											</td>
											<td>
												<?php echo round(($dataGroupedByQuery->getCtr() * 100), 2) . '%';?>
											</td>
											<td>
												<?php 
													$serpPosition = (int)$dataGroupedByQuery->getPosition();
													$classLabel = $serpPosition > 30 ? 'label-important' : 'label-success';
												?>
												<span class="label <?php echo $classLabel;?>">
													<?php echo $serpPosition;?>
												</span>
											</td>
										</tr>
								<?php }
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<br/>
			
			<!-- GOOGLE SEARCH CONSOLE STATS PAGES -->
			<div class="panel panel-warning panel-group panel-group-google" id="jmap_googleconsole_pages_accordion">
				<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_google_pages">
					<h4><span class="glyphicon glyphicon-duplicate"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_STATS_KEYWORDS_BY_PAGES' ); ?></h4>
				</div>
				<div id="jmap_google_pages" class="panel-body panel-overflow panel-overflow-large panel-collapse collapse">
					<table class="adminlist table table-sorter table-striped table-hover">
						<thead>
							<tr>
								<th>
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_PAGES' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CLICKS' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_IMPRESSION' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CTR' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_POSITION' ); ?></span>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<?php // Render errors count
								if(!empty($this->googleData['results_page'])){
									foreach ($this->googleData['results_page'] as $dataGroupedByPage) { ?>
										<tr>
											<td>
												<span class="label-italic">
													<?php $dataGroupedKeys = $dataGroupedByPage->getKeys();?>
													<a href="<?php echo $dataGroupedKeys[0];?>" target="_blank">
														<?php echo $dataGroupedKeys[0];?> <span class="icon-out"></span>
													</a>
												</span>
											</td>
											<td>
												<?php echo $dataGroupedByPage->getClicks();?>
											</td>
											<td>
												<?php echo $dataGroupedByPage->getImpressions();?>
											</td>
											<td>
												<?php echo round(($dataGroupedByPage->getCtr() * 100), 2) . '%';?>
											</td>
											<td>
												<?php 
													$serpPosition = (int)$dataGroupedByPage->getPosition();
													$classLabel = $serpPosition > 30 ? 'label-important' : 'label-success';
												?>
												<span class="label <?php echo $classLabel;?>">
													<?php echo $serpPosition;?>
												</span>
											</td>
										</tr>
								<?php }
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<br/>
			
			<!-- GOOGLE SEARCH CONSOLE STATS DEVICES -->
			<div class="panel panel-warning panel-group panel-group-google" id="jmap_googleconsole_device_accordion">
				<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_google_device">
					<h4><span class="glyphicon glyphicon-phone"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_STATS_KEYWORDS_BY_DEVICE' ); ?></h4>
				</div>
				<div id="jmap_google_device" class="panel-body panel-overflow panel-overflow-large panel-collapse collapse">
					<table class="adminlist table table-sorter table-striped table-hover">
						<thead>
							<tr>
								<th style="width:50%">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_DEVICE' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CLICKS' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_IMPRESSION' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CTR' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_POSITION' ); ?></span>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<?php // Render errors count
								if(!empty($this->googleData['results_device'])){
									foreach ($this->googleData['results_device'] as $dataGroupedByDevice) { ?>
										<tr>
											<td>
												<?php $dataGroupedKeys = $dataGroupedByDevice->getKeys();?>
												<span class="label label-info label-large hasRightPopover" data-content="<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_' . strtoupper($dataGroupedKeys[0]) . '_DESC');?>">
													<?php echo ucfirst(strtolower($dataGroupedKeys[0]));?>
												</span>
											</td>
											<td>
												<?php echo $dataGroupedByDevice->getClicks();?>
											</td>
											<td>
												<?php echo $dataGroupedByDevice->getImpressions();?>
											</td>
											<td>
												<?php echo round(($dataGroupedByDevice->getCtr() * 100), 2) . '%';?>
											</td>
											<td>
												<?php 
													$serpPosition = (int)$dataGroupedByDevice->getPosition();
													$classLabel = $serpPosition > 30 ? 'label-important' : 'label-success';
												?>
												<span class="label <?php echo $classLabel;?>">
													<?php echo $serpPosition;?>
												</span>
											</td>
										</tr>
								<?php }
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<br/>
			
			<!-- GOOGLE SEARCH CONSOLE STATS COUNTRY -->
			<div class="panel panel-warning panel-group panel-group-google" id="jmap_googleconsole_country_accordion">
				<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_google_country">
					<h4><span class="glyphicon glyphicon-globe"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_STATS_KEYWORDS_BY_COUNTRY' ); ?></h4>
				</div>
				<div id="jmap_google_country" class="panel-body panel-overflow panel-overflow-large panel-collapse collapse">
					<table class="adminlist table table-sorter table-striped table-hover">
						<thead>
							<tr>
								<th style="width:50%">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_COUNTRY' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CLICKS' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_IMPRESSION' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CTR' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_POSITION' ); ?></span>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<?php // Render errors count
								if(!empty($this->googleData['results_country'])){
									foreach ($this->googleData['results_country'] as $dataGroupedByCountry) { ?>
										<tr>
											<td>
												<span class="label label-info label-large">
													<?php 
														$dataGroupedKeys = $dataGroupedByCountry->getKeys();
														$countryKey = strtoupper($dataGroupedKeys[0]);
													?>
													<?php echo array_key_exists($countryKey, $this->jMapGoogleIsoArray) ? $this->jMapGoogleIsoArray[$countryKey] : ucfirst($dataGroupedKeys[0]);?>
												</span>
											</td>
											<td>
												<?php echo $dataGroupedByCountry->getClicks();?>
											</td>
											<td>
												<?php echo $dataGroupedByCountry->getImpressions();?>
											</td>
											<td>
												<?php echo round(($dataGroupedByCountry->getCtr() * 100), 2) . '%';?>
											</td>
											<td>
												<?php 
													$serpPosition = (int)$dataGroupedByCountry->getPosition();
													$classLabel = $serpPosition > 30 ? 'label-important' : 'label-success';
												?>
												<span class="label <?php echo $classLabel;?>">
													<?php echo $serpPosition;?>
												</span>
											</td>
										</tr>
								<?php }
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<br/>
			
			<!-- GOOGLE SEARCH CONSOLE STATS SEARCH APPEARANCE -->
			<div class="panel panel-warning panel-group panel-group-google" id="jmap_googleconsole_search_accordion">
				<div class="panel-heading accordion-toggle" data-toggle="collapse" data-target="#jmap_google_search">
					<h4><span class="glyphicon glyphicon-search"></span> <?php echo JText::_ ('COM_JMAP_GOOGLE_WEBMASTERS_STATS_KEYWORDS_BY_SEARCH' ); ?></h4>
				</div>
				<div id="jmap_google_search" class="panel-body panel-overflow panel-overflow-large panel-collapse collapse">
					<table class="adminlist table table-sorter table-striped table-hover">
						<thead>
							<tr>
								<th style="width:50%">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_SEARCH' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CLICKS' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_IMPRESSION' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_CTR' ); ?></span>
								</th>
								<th class="title">
									<span><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_POSITION' ); ?></span>
								</th>
							</tr>
						</thead>
						
						<tbody>
							<?php // Render errors count
								if(!empty($this->googleData['results_search'])){
									foreach ($this->googleData['results_search'] as $dataGroupedByCountry) { ?>
										<tr>
											<td>
												<?php 
													$dataGroupedKeys = $dataGroupedByCountry->getKeys();
													$searchKey = strtoupper($dataGroupedKeys[0]);
												?>
												<span class="label label-info label-large hasRightPopover" data-content="<?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_SEARCH_' . $searchKey . '_DESC');?>">
													<?php echo array_key_exists($searchKey, $this->jMapGoogleSearchArray) ? JText::_($this->jMapGoogleSearchArray[$searchKey]) : $dataGroupedKeys[0];?>
												</span>
											</td>
											<td>
												<?php echo $dataGroupedByCountry->getClicks();?>
											</td>
											<td>
												<?php echo $dataGroupedByCountry->getImpressions();?>
											</td>
											<td>
												<?php echo round(($dataGroupedByCountry->getCtr() * 100), 2) . '%';?>
											</td>
											<td>
												<?php 
													$serpPosition = (int)$dataGroupedByCountry->getPosition();
													$classLabel = $serpPosition > 30 ? 'label-important' : 'label-success';
												?>
												<span class="label <?php echo $classLabel;?>">
													<?php echo $serpPosition;?>
												</span>
											</td>
										</tr>
								<?php }
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="google.display" />
	<input type="hidden" name="googlestats" value="webmasters" />
	<input type="hidden" name="sitemapurl" value="" />
	<input type="hidden" name="crawlerrors_category" value="" />
</form>

<!-- MODAL DIALOG FOR GWT SITEMAP DELETION -->
<div id="sitemapDeleteModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <label data-dismiss="modal" aria-label="Close" class="closeprecaching glyphicon glyphicon-remove-circle"></label>
        <h4 class="modal-title"><?php echo JText::_('COM_JMAP_DELETE_THIS_SITEMAP');?></h4>
      </div>
      <div class="modal-body modal-body-padded">
      	<?php echo JText::_('COM_JMAP_DELETE_THIS_SITEMAP_AREYOUSURE');?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo JText::_('COM_JMAP_CANCEL');?></button>
        <button type="button" data-role="confirm-delete" class="btn btn-primary"><?php echo JText::_('COM_JMAP_GOOGLE_WEBMASTERS_STATS_SITEMAP_DELETE');?></button>
      </div>
    </div>
  </div>
</div>