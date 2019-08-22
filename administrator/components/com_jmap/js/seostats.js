/**
 * Precaching client, this is the main application that interacts with server
 * side code for sitemap incremental generation and precaching process
 * 
 * @package JMAP::AJAXPRECACHING::administrator::components::com_jmap
 * @subpackage js
 * @author Joomla! Extensions Store
 * @copyright (C) 2015 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
//'use strict';
(function($) {
	var SeoStats = function() {
		/**
		 * The first operation is get informations about published data sources
		 * and start cycle over all the records using promises and recursion
		 * 
		 * @access private
		 * @return Void
		 */
		var getSeoStatsData = function() {
			// Object to send to server
			var ajaxparams = {
				idtask : 'fetchSeoStats',
				template : 'json',
				param: {}
			};

			// Unique param 'data'
			var uniqueParam = JSON.stringify(ajaxparams);
			// Request JSON2JSON
			var seoStatsPromise = $.Deferred(function(defer) {
				$.ajax({
					type : "POST",
					url : "../administrator/index.php?option=com_jmap&task=ajaxserver.display&format=json",
					dataType : 'json',
					context : this,
					data : {
						data : uniqueParam
					}
				}).done(function(data, textStatus, jqXHR) {
					if(data === null) {
						// Error found
						defer.reject(COM_JMAP_NULL_RESPONSEDATA, 'notice');
						return false;
					}
					
					if(!data.result) {
						// Error found
						defer.reject(data.exception_message, data.errorlevel, data.seostats, textStatus);
						return false;
					}
					
					// Check response all went well
					if(data.result && data.seostats) {
						defer.resolve(data.seostats);
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					// Error found
					var genericStatus = textStatus[0].toUpperCase() + textStatus.slice(1) + ': ' + jqXHR.status ;
					defer.reject(COM_JMAP_ERROR_HTTP + '-' + genericStatus + '- ' + errorThrown, 'error');
				});
			}).promise();

			seoStatsPromise.then(function(responseData) {
				// SEO stats correctly retrieved from the model layer, now updates the view accordingly
				formatSeoStats(responseData);
				
				// We have SEO stats, if sessionStorage is supported store data and avoid unuseful additional requests
				if( window.sessionStorage !== null ) {
					sessionStorage.setItem('seostats', JSON.stringify(responseData));
					sessionStorage.setItem('seostats_service', responseData.service);
					sessionStorage.setItem('seostats_targeturl', responseData.targeturl);
				}
			}, function(errorText, errorLevel, responseData, error) {
				// Do stuff and exit
				if(responseData) {
					formatSeoStats(responseData);
				}
				
				// Show little user error notification based on fatal php circumstances
				$('#seo_stats').append('<div class="alert alert-' + errorLevel + '">' + errorText + '</div>');
			}).always(function(){
				// Async request completed, now remove waiters info
				$('*.waiterinfo').remove();
			})
		};

		/**
		 * Format the Alexa Seo Stats
		 * 
		 * @access private
		 * @return Void
		 */
		var formatAlexaSeoStats = function(seoStats) {
			// Format stats
			$('li[data-bind=\\{google_pagerank\\}]').html('<span>' + seoStats.googlerank + '</span>');
			
			// Alexa rank
			var alexaRankFontSize = '';
			var alexaRankFormatted = seoStats.alexarank;
			if(!isNaN(parseInt(alexaRankFormatted))) {
				alexaRankFormatted = parseInt(alexaRankFormatted).toLocaleString().replace(/,/g, '.');
				if(alexaRankFormatted.length > 9) {
					alexaRankFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{alexa_rank\\}]').html('<span' + alexaRankFontSize + '>' + alexaRankFormatted + '</span>');
			
			// Alexa Site Backlinks
			var alexaBackLinksFontSize = '';
			var alexaBackLinksFormatted = seoStats.alexabacklinks;
			if(!isNaN(parseInt(alexaBackLinksFormatted))) {
				alexaBackLinksFormatted = parseInt(alexaBackLinksFormatted).toLocaleString().replace(/,/g, '.');
				if(alexaBackLinksFormatted.length > 9) {
					alexaBackLinksFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{alexa_backlinks\\}]').html('<span' + alexaBackLinksFontSize + '>' + alexaBackLinksFormatted + '</span>');
			
			// Alexa Page Load Time
			$('li[data-bind=\\{alexa_pageload_time\\}]').html('<span>' + seoStats.alexapageloadtime + '</span>');

			// Google SERP indexed links
			var indexedFontSize = '';
			if(seoStats.googleindexedlinks.length > 9) {
				indexedFontSize = ' style="font-size:18px"';
			}
			$('li[data-bind=\\{google_indexed_links\\}]').html('<span' + indexedFontSize + '>' + seoStats.googleindexedlinks + '</span>');

			// Alexa chart - Extract image link for the alexa chart
			var imageLink = $(seoStats.alexagraph).attr('src');
			$('li[data-bind=\\{alexa_graph\\}]').html('<a class="alexa_chart" href="' + imageLink + '">' + seoStats.alexagraph + '</a>');
			
			// SemRush Rank
			if(typeof(seoStats.semrushrank) !== 'undefined') {
				var semrushRankFontSize = '';
				var semrushRankFormatted = seoStats.semrushrank;
				if(!isNaN(parseInt(semrushRankFormatted))) {
					semrushRankFormatted = parseInt(semrushRankFormatted).toLocaleString().replace(/,/g, '.');
					if(semrushRankFormatted.length > 9) {
						semrushRankFontSize = ' style="font-size:18px"';
					}
				}
				$('li[data-bind=\\{semrush_rank\\}]').html('<span' + semrushRankFontSize + '>' + semrushRankFormatted + '</span>');
			}
			
			// SemRush Keywords
			if(typeof(seoStats.semrushkeywords) === 'object' && seoStats.semrushkeywords) {
				var semrushKeywords = '';
				$.each(seoStats.semrushkeywords.data, function(index, keywordObject){
					// Limit top 30 keywords
					if((index + 1) > 30) {
						return false;
					}
					
					if(typeof(keywordObject.Ph) !== 'undefined') {
						semrushKeywords += ('<div>' + keywordObject.Ph + '</div>');
					}
				});
				$('li[data-bind=\\{semrush_keywords\\}]')
							.attr('data-content', semrushKeywords)
							.popover({trigger:'click', placement:'left', html:1})
							.addClass('clickable');
			}
			
			// SemRush competitors
			if(typeof(seoStats.semrushcompetitors) === 'object' && seoStats.semrushcompetitors) {
				var semrushCompetitors = '';
				$.each(seoStats.semrushcompetitors.data, function(index, domainObject){
					// Limit top 10 domains
					if((index + 1) > 10) {
						return false;
					}
					
					if(typeof(domainObject.Dn) !== 'undefined') {
						semrushCompetitors += ('<div>' + domainObject.Dn + '</div>');
					}
				});
				$('li[data-bind=\\{semrush_competitors\\}]')
							.attr('data-content', semrushCompetitors)
							.popover({trigger:'click', placement:'left', html:1})
							.addClass('clickable');
			}
			
			// Alexa Daily Page Views
			if(seoStats.alexadailypageviews) {
				$('li[data-bind=\\{alexa_daily_pageviews\\}]').html('<span>' + seoStats.alexadailypageviews + '</span>');
			}
			
			// SemRush Chart - Extract image link for the semrush chart
			if(typeof(seoStats.semrushgraph) !== 'undefined') {
				var semrushImageLink = $(seoStats.semrushgraph).attr('src');
				$('li[data-bind=\\{semrush_graph\\}]').html('<a class="semrush_chart" href="' + semrushImageLink + '">' + seoStats.semrushgraph + '</a>');
			}
			
			// Now bind fancybox effect on the newly appended alexa chart image
			$('li.fancybox-image a.alexa_chart')
				.attr('title', COM_JMAP_ALEXA_GRAPH)
				.fancybox({
					type: 'image',
			    	openEffect	: 'elastic',
			    	closeEffect	: 'elastic'
			});
			$('li.fancybox-image a.semrush_chart')
				.attr('title', COM_JMAP_SEMRUSH_GRAPH)
				.fancybox({
					type: 'image',
			    	openEffect	: 'elastic',
			    	closeEffect	: 'elastic'
			});
		};
		
		/**
		 * Format the Siterankdata Seo Stats
		 * 
		 * @access private
		 * @return Void
		 */
		var formatSiterankdataSeoStats = function(seoStats) {
			// Siterankdata rank
			var rankFontSize = '';
			var rankFormatted = seoStats.rank;
			if(!isNaN(parseInt(rankFormatted))) {
				rankFormatted = parseInt(rankFormatted).toLocaleString().replace(/,/g, '.');
				if(rankFormatted.length > 9) {
					rankFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{siterankdata_rank\\}]').html('<span' + rankFontSize + '>' + rankFormatted + '</span>');
			
			var dailyVisitorsFontSize = '';
			var dailyVisitorsFormatted = seoStats.dailyvisitors;
			if(!isNaN(parseInt(dailyVisitorsFormatted))) {
				dailyVisitorsFormatted = parseInt(dailyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyVisitorsFormatted.length > 9) {
					dailyVisitorsFontSize = ' style="font-size:18px"';
				}
				if(dailyVisitorsFormatted.length > 11) {
					dailyVisitorsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{daily_unique_visitors\\}]').html('<span' + dailyVisitorsFontSize + '>' + dailyVisitorsFormatted + '</span>');
			
			var monthlyVisitorsFontSize = '';
			var monthlyVisitorsFormatted = seoStats.monthlyvisitors;
			if(!isNaN(parseInt(monthlyVisitorsFormatted))) {
				monthlyVisitorsFormatted = parseInt(monthlyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(monthlyVisitorsFormatted.length > 9) {
					monthlyVisitorsFontSize = ' style="font-size:18px"';
				}
				if(monthlyVisitorsFormatted.length > 11) {
					monthlyVisitorsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{monthly_visitors\\}]').html('<span' + monthlyVisitorsFontSize + '>' + monthlyVisitorsFormatted + '</span>');
			
			var yearlyVisitorsFontSize = '';
			var yearlyVisitorsFormatted = seoStats.yearlyvisitors;
			if(!isNaN(parseInt(yearlyVisitorsFormatted))) {
				yearlyVisitorsFormatted = parseInt(yearlyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(yearlyVisitorsFormatted.length > 9) {
					yearlyVisitorsFontSize = ' style="font-size:18px"';
				}
				if(yearlyVisitorsFormatted.length > 11) {
					yearlyVisitorsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{yearly_visitors\\}]').html('<span' + yearlyVisitorsFontSize + '>' + yearlyVisitorsFormatted + '</span>');
			
			// Siterankdata competitors
			// SemRush competitors
			if(typeof(seoStats.siterankdatacompetitors) === 'object' && seoStats.siterankdatacompetitors) {
				var siterankdataCompetitors = '';
				$.each(seoStats.siterankdatacompetitors.data, function(index, domainObject){
					// Limit top 10 domains
					if((index + 1) > 15) {
						return false;
					}
					
					if(typeof(domainObject.competitor) !== 'undefined') {
						siterankdataCompetitors += ('<div>' + domainObject.competitor + '</div>');
					}
				});
				$('li[data-bind=\\{siterankdata_competitors\\}]')
							.attr('data-content', siterankdataCompetitors)
							.popover({trigger:'click', placement:'left', html:1})
							.addClass('clickable');
			}
			
			// Website screen
			var imageLink = $(seoStats.websitescreen).attr('src');
			$('li[data-bind=\\{website_screen\\}]').html('<a class="siterankdata_website_screen" href="' + imageLink + '">' + seoStats.websitescreen + '</a>');
			
			// Now bind fancybox effect on the newly appended alexa chart image
			$('li.fancybox-image a.siterankdata_website_screen')
				.attr('title', COM_JMAP_WEBSITE_SCREEN)
				.fancybox({
					type: 'image',
			    	openEffect	: 'elastic',
			    	closeEffect	: 'elastic'
			});
		};
		
		/**
		 * Format the Hypestat Seo Stats
		 * 
		 * @access private
		 * @return Void
		 */
		var formatHypestatSeoStats = function(seoStats) {
			// Siterankdata rank
			var rankFontSize = '';
			var rankFormatted = seoStats.rank;
			if(!isNaN(parseInt(rankFormatted))) {
				rankFormatted = parseInt(rankFormatted).toLocaleString().replace(/,/g, '.');
				if(rankFormatted.length > 9) {
					rankFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{hypestat_rank\\}]').html('<span' + rankFontSize + '>' + rankFormatted + '</span>');
			
			var dailyVisitorsFontSize = '';
			var dailyVisitorsFormatted = seoStats.dailyvisitors;
			if(!isNaN(parseInt(dailyVisitorsFormatted))) {
				dailyVisitorsFormatted = parseInt(dailyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyVisitorsFormatted.length > 9) {
					dailyVisitorsFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{daily_unique_visitors\\}]').html('<span' + dailyVisitorsFontSize + '>' + dailyVisitorsFormatted + '</span>');
			
			var monthlyVisitorsFontSize = '';
			var monthlyVisitorsFormatted = seoStats.monthlyvisitors;
			if(!isNaN(parseInt(monthlyVisitorsFormatted))) {
				monthlyVisitorsFormatted = parseInt(monthlyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(monthlyVisitorsFormatted.length > 9) {
					monthlyVisitorsFontSize = ' style="font-size:18px"';
				}
				if(monthlyVisitorsFormatted.length > 11) {
					monthlyVisitorsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{monthly_visitors\\}]').html('<span' + monthlyVisitorsFontSize + '>' + monthlyVisitorsFormatted + '</span>');
			
			$('li[data-bind=\\{pages_per_visit\\}]').html(seoStats.pagespervisit);
			
			var dailyPageviewsFontSize = '';
			var dailyPageviewsFormatted = seoStats.dailypageviews;
			if(!isNaN(parseInt(dailyPageviewsFormatted))) {
				dailyPageviewsFormatted = parseInt(dailyPageviewsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyPageviewsFormatted.length > 9) {
					dailyPageviewsFontSize = ' style="font-size:18px"';
				}
				if(dailyPageviewsFormatted.length > 11) {
					dailyPageviewsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{daily_pageviews\\}]').html('<span' + dailyPageviewsFontSize + '>' + dailyPageviewsFormatted + '</span>');
			
			$('li[data-bind=\\{backlinks\\}]').html(seoStats.backlinks.toLocaleString().replace(/,/g, '.'));
			
			// Website screen
			var imageLink = $(seoStats.websitescreen).attr('src');
			$('li[data-bind=\\{website_screen\\}]').html('<a class="hypestat_website_screen" href="' + imageLink + '">' + seoStats.websitescreen + '</a>');
			
			// Now bind fancybox effect on the newly appended alexa chart image
			$('li.fancybox-image a.hypestat_website_screen')
				.attr('title', COM_JMAP_WEBSITE_SCREEN)
				.fancybox({
					type: 'image',
			    	openEffect	: 'elastic',
			    	closeEffect	: 'elastic'
			});
			
			$('div[data-bind=\\{website_report_text\\}]').html(seoStats.reporttext).removeClass('well-hidden');
			if(!seoStats.reporttext){
				$('div[data-bind=\\{website_report_text\\}]').addClass('well-empty');
			}
		};
		
		/**
		 * Format the Website Informer Seo Stats
		 * 
		 * @access private
		 * @return Void
		 */
		var formatWebsiteinformerSeoStats = function(seoStats) {
			// Siterankdata rank
			var rankFontSize = '';
			var rankFormatted = seoStats.rank;
			if(!isNaN(parseInt(rankFormatted))) {
				rankFormatted = parseInt(rankFormatted).toLocaleString().replace(/,/g, '.');
				if(rankFormatted.length > 9) {
					rankFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{websiteinformer_rank\\}]').html('<span' + rankFontSize + '>' + rankFormatted + '</span>');
			
			var dailyVisitorsFontSize = '';
			var dailyVisitorsFormatted = seoStats.dailyvisitors;
			if(!isNaN(parseInt(dailyVisitorsFormatted))) {
				dailyVisitorsFormatted = parseInt(dailyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyVisitorsFormatted.length > 9) {
					dailyVisitorsFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{daily_visitors\\}]').html('<span' + dailyVisitorsFontSize + '>' + dailyVisitorsFormatted + '</span>');
			
			var dailyPageviewsFontSize = '';
			var dailyPageviewsFormatted = seoStats.dailypageviews;
			if(!isNaN(parseInt(dailyPageviewsFormatted))) {
				dailyPageviewsFormatted = parseInt(dailyPageviewsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyPageviewsFormatted.length > 9) {
					dailyPageviewsFontSize = ' style="font-size:18px"';
				}
				if(dailyPageviewsFormatted.length > 11) {
					dailyPageviewsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{daily_pageviews\\}]').html('<span' + dailyPageviewsFontSize + '>' + dailyPageviewsFormatted + '</span>');
			
			// Website screen
			var imageLink = $(seoStats.websitescreen).attr('src');
			$('li[data-bind=\\{website_screen\\}]').html('<a class="websiteinformer_website_screen" href="' + imageLink + '">' + seoStats.websitescreen + '</a>');
			
			// Now bind fancybox effect on the newly appended alexa chart image
			$('li.fancybox-image a.websiteinformer_website_screen')
				.attr('title', COM_JMAP_WEBSITE_SCREEN)
				.fancybox({
					type: 'image',
			    	openEffect	: 'elastic',
			    	closeEffect	: 'elastic'
			});
			
			$('div[data-bind=\\{website_report_text\\}]').html(seoStats.reporttext).removeClass('well-hidden');
			if(!seoStats.reporttext){
				$('div[data-bind=\\{website_report_text\\}]').addClass('well-empty');
			}
		};
		
		/**
		 * Format the Zigstat Seo Stats
		 * 
		 * @access private
		 * @return Void
		 */
		var formatZigstatSeoStats = function(seoStats) {
			// Siterankdata rank
			$('li[data-bind=\\{zigstat_mozrank\\}]').html(seoStats.mozrank);
			
			$('li[data-bind=\\{zigstat_mozdomainauth\\}]').html(seoStats.mozdomainauth);
			
			$('li[data-bind=\\{zigstat_mozpageauth\\}]').html(seoStats.mozpageauth);
			
			$('li[data-bind=\\{zigstat_pagespeed\\}]').html(seoStats.pagespeed);
			
			// Moz Backlinks
			var mozBackLinksFontSize = '';
			var mozBackLinksFormatted = seoStats.backlinks;
			if(!isNaN(parseInt(mozBackLinksFormatted))) {
				mozBackLinksFormatted = parseInt(mozBackLinksFormatted).toLocaleString().replace(/,/g, '.');
				if(mozBackLinksFormatted.length > 9) {
					mozBackLinksFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{zigstat_backlinks\\}]').html('<span' + mozBackLinksFontSize + '>' + mozBackLinksFormatted + '</span>');
			
			// Alexa Site Backlinks
			var alexaBackLinksFontSize = '';
			var alexaBackLinksFormatted = seoStats.alexarank;
			if(!isNaN(parseInt(alexaBackLinksFormatted))) {
				alexaBackLinksFormatted = parseInt(alexaBackLinksFormatted).toLocaleString().replace(/,/g, '.');
				if(alexaBackLinksFormatted.length > 9) {
					alexaBackLinksFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{zigstat_alexarank\\}]').html('<span' + alexaBackLinksFontSize + '>' + alexaBackLinksFormatted + '</span>');
			
			var dailyVisitorsFontSize = '';
			var dailyVisitorsFormatted = seoStats.dailyvisitors;
			if(!isNaN(parseInt(dailyVisitorsFormatted))) {
				dailyVisitorsFormatted = parseInt(dailyVisitorsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyVisitorsFormatted.length > 9) {
					dailyVisitorsFontSize = ' style="font-size:18px"';
				}
			}
			$('li[data-bind=\\{zigstat_dailyvisitor\\}]').html('<span' + dailyVisitorsFontSize + '>' + dailyVisitorsFormatted + '</span>');
			
			var dailyPageviewsFontSize = '';
			var dailyPageviewsFormatted = seoStats.dailypageviews;
			if(!isNaN(parseInt(dailyPageviewsFormatted))) {
				dailyPageviewsFormatted = parseInt(dailyPageviewsFormatted).toLocaleString().replace(/,/g, '.');
				if(dailyPageviewsFormatted.length > 9) {
					dailyPageviewsFontSize = ' style="font-size:18px"';
				}
				if(dailyPageviewsFormatted.length > 11) {
					dailyPageviewsFontSize = ' style="font-size:14px"';
				}
			}
			$('li[data-bind=\\{zigstat_dailypageviews\\}]').html('<span' + dailyPageviewsFontSize + '>' + dailyPageviewsFormatted + '</span>');
			
			// Backlinks list
			if(typeof(seoStats.backlinkslist) === 'object' && seoStats.backlinkslist) {
				var zigstatBacklinksList = '';
				$.each(seoStats.backlinkslist.data, function(index, domainObject){
					// Limit top 10 domains
					if((index + 1) > 20) {
						return false;
					}
					
					if(typeof(domainObject.backlink) !== 'undefined') {
						zigstatBacklinksList += ('<div>' + domainObject.backlink + '</div>');
					}
				});
				$('li[data-bind=\\{zigstat_backlinks_list\\}]')
							.attr('data-content', zigstatBacklinksList)
							.popover({trigger:'click', placement:'left', html:1})
							.addClass('clickable');
			}
			
			$('div[data-bind=\\{website_report_text\\}]').html(seoStats.reporttext).removeClass('well-hidden');
			if(!seoStats.reporttext){
				$('div[data-bind=\\{website_report_text\\}]').addClass('well-empty');
			}
		};
		
		/**
		 * The first operation is get informations about published data sources
		 * and start cycle over all the records using promises and recursion
		 * 
		 * @access private
		 * @return Void
		 */
		var formatSeoStats = function(seoStats) {
			switch(jmap_seostats_service) {
				case 'zigstat':
					formatZigstatSeoStats(seoStats);
				break;
				
				case 'hypestat':
					formatHypestatSeoStats(seoStats);
				break;
				
				case 'siterankdata':
					formatSiterankdataSeoStats(seoStats);
				break;
				
				case 'websiteinformer':
					formatWebsiteinformerSeoStats(seoStats);
				break;
				
				case 'alexa':
				default:
					formatAlexaSeoStats(seoStats);
				break;
			}
			
			// Show stats
			$('div.single_stat_rowseparator').fadeIn(200);
			$('#seo_stats div.single_stat_container').fadeIn(200);
		};
		
		/**
		 * Function dummy constructor
		 * 
		 * @access private
		 * @param String
		 *            contextSelector
		 * @method <<IIFE>>
		 * @return Void
		 */
		(function __construct() {
			var containerWidth = $('#seo_stats').width(); 

			// Append waiter and text fetching data in progress
			$('#seo_stats').append('<div class="waiterinfo"></div>')
				.children('div.waiterinfo')
				.text(COM_JMAP_SEOSTATS_LOADING)
				.css({
					'position': 'absolute',
		            'top': '125px',
		            'left': (containerWidth - (parseInt(containerWidth / 2) + 75)) + 'px',
		            'width': '150px'
	        });
			
			$('#seo_stats').append('<img/>')
				.children('img')
				.attr({
					'src': jmap_baseURI + 'administrator/components/com_jmap/images/loading.gif',
					'class': 'waiterinfo'})
				.css({
		            'position': 'absolute',
		            'top': '50px',
		            'left': (containerWidth - (parseInt(containerWidth / 2) + 32)) + 'px',
		            'width': '64px'
	        });
			
			// Check firstly if seostats are already in the sessionStorage
			if( window.sessionStorage !== null ) {
				var sessionSeoStats = sessionStorage.getItem('seostats');
				var sessionSeoStatsService = sessionStorage.getItem('seostats_service');
				var sessionSeoStatsTargeturl = sessionStorage.getItem('seostats_targeturl');
				
				// Check if there is no more matching between the session service and the current enabled service
				if((sessionSeoStatsService || (sessionSeoStats && !sessionSeoStatsService)) && (jmap_seostats_service != sessionSeoStatsService || jmap_seostats_targeturl != sessionSeoStatsTargeturl)) {
					// Reset all and reload
					sessionStorage.removeItem('seostats');
					sessionStorage.removeItem('seostats_service');
					sessionStorage.removeItem('seostats_targeturl');
					window.location.reload();
					return;
				}
				
				// Seo stats found in local session storage, go on to formatting data without a new request
				if(sessionSeoStats) {
					sessionSeoStats = JSON.parse(sessionSeoStats);
					
					// Format local data
					formatSeoStats(sessionSeoStats);
					
					// Remove waiter
					$('*.waiterinfo').remove();
					
					// Avoid to go on with a new async request
					return;
				}
			}
			
			// Get stats data from remote services using Promise, and populate user interface when resolved
			getSeoStatsData();

		}).call(this);
	}

	// On DOM Ready
	$(function() {
		window.JMapSeoStats = new SeoStats();
	});
})(jQuery);