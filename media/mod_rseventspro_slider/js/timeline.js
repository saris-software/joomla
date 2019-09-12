(function($) {
	'use strict';

	$.fn.rseproslider = function(options) {
		var theli		= $(this).find('ul > li:first');
		var theborder	= parseInt($(theli).css('borderLeftWidth')) * 2;
		var thepadding	= parseInt($(theli).css('padding-right')) * 2;
		var themargin	= parseInt($(theli).css('margin-right'));
		var thewidth	= Math.floor((parseInt(options.width) - theborder * parseInt(options.events) - themargin * (parseInt(options.events) - 1) - thepadding * parseInt(options.events)) / parseInt(options.events));
		var f			= $(this).find('ul > li');
		var panes		= Math.ceil(f.length / parseInt(options.events));
		
		$(this).parent().parent().css('width', parseInt(options.width) + 'px');
		$(this).parent().parent().css('height', parseInt(options.height) + 'px');
		$(this).find('ul > li').css('width', parseInt(thewidth) + 'px');
		
		var d = $(this).find('ul');
		var size = 0;
		
		$(d).find('li').each(function(i,el) {
			var ml = parseInt($(el).css('margin-left'));
			var mr = parseInt($(el).css('margin-right'));
			var pl = parseInt($(el).css('padding-right'));
			var pr = parseInt($(el).css('padding-right'));
			var bl = parseInt($(el).css('borderLeftWidth'));
			var br = parseInt($(el).css('borderRightWidth'));
			size += $(el).width();
			size += ml + mr + pl + pr + bl + br;
		});
		
		d.css('width', size);
		
		if (panes > 1) {
			var h			= $(this).parent().find('.rsepropanes .rsepropane');
			var dates		= $(this).parent().find('.rseprodates .rseprodate');
			var paneSize	= h.length ? ($(h).first().width() / 2) : 0;
			var knobSize	= ($(h).first().width() / 2);
			var half		= ($(h).first().outerWidth() / 2);
			var full		= $(this).parent().find('.rsepropanes').outerWidth() - $(h).first().outerWidth();
			var min			= 0;
			var max			= panes - 1;
			var range		= max - min;
			var thesteps	= (panes - 1) || full;
			var stepSize	= Math.abs(range) / thesteps;
			var stepWidth	= Number((stepSize * full / Math.abs(range)).toFixed(4));
			
			h.each(function(i,a) {
				var j	 = stepWidth * i + knobSize - paneSize;
				var spansize = stepWidth + knobSize - paneSize - 3;
				
				$(a).css('left', j);
				$(a).find('span').css({'width': spansize, 'left': -(spansize / 2)});
				
				$(a).on('click', function() {
					h.each(function(i,elm) {
						$(elm).removeClass('active');
					});
					
					dates.each(function (i,elem) {
						$(elem).removeClass('active');
					});
					
					$(a).addClass('active');
					$(dates[i]).addClass('active');
					$(d).animate({left : '-' + $(f[i * options.events]).position().left}, options.duration, 'linear');
				});
			});
			
			dates.each(function (i, a) {
				var j = stepWidth * i + knobSize - paneSize;
				$(a).css('left', j);
				
				$(a).on('click', function() {
					dates.each(function(i,elm) {
						$(elm).removeClass('active');
					});
					$(a).addClass('active');
					
					h.each(function (i,el) {
						$(el).removeClass('active');
					});
					
					$(h[i]).addClass('active');
					$(d).animate({left : '-' + $(f[i * options.events]).position().left}, options.duration, 'linear');
				});
			});
		}
	};
})(jQuery);