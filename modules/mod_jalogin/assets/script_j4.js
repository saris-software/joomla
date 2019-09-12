/**
 * ------------------------------------------------------------------------
 * JA Login module for J25 & J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2018 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
var jaboxes = [];
var jaboxoverlay = null;
showBox = function (box,focusobj, caller, e) {
	//Add overlay
	if (!jaboxoverlay) {
		jaboxoverlay = jQuery('<div/>', {id:"jabox-overlay"}).prependTo($('#'+box).parent());
		jaboxoverlay.css ('opacity', 0.01);
		jaboxoverlay.on ('click', function (e) {
			for (let box of jaboxes) {
				if (box.status=='show') {
					box.status = 'hide';
					box.css('visibility','hidden');
					  box.animate({
						opacity: 0
					  }, 400, function() {
						// Animation complete.
					  });
					if (box._caller) box._caller.removeClass ('show');
					
					if($('#system-message')) {
						$('#system-message').removeClass('alert');
						$('#system-message').removeClass('alert-error');
						$('#system-message').html('');
					}
					if($('#system-message-container')) $('#system-message-container').css('display', 'block');
				}
			}
			jaboxoverlay.css ('display', 'none');
		});
	} else {
		jaboxoverlay.prependTo ($('#'+box).parent());
	}

	caller.blur();
	//new Event(e).stop ();
	box=$('#'+box);	
	if (!box) return;
	if ($(caller)) box._caller = $(caller);
	if (!$.contains( box, jaboxes )) {
		jaboxes.push (box);
		//box.addEvent ('click', function (e) {/*new Event(e).stop ();*/});
	}
	
	if (box.css('display') == 'none') {
		box.css({
			display: 'block',
			opacity: 0
		});
	}
	if (box.status == 'show') {
		//hide
		box.status = 'hide';
		box.css('visibility','hidden');
		if (box._caller) box._caller.removeClass ('show');
		jaboxoverlay.css ('display', 'none');
	} else {
		for (let box1 of jaboxes) {
			if (box1!=box && box1.status=='show') {
				box1.status = 'hide';
				box1.css('visibility','hidden');
				  box1.animate({
					opacity: 0
				  }, 400, function() {
					// Animation complete.
				  });
				if (box1._caller) box1._caller.removeClass ('show');
			}
		}
		box.status = 'show';
		box.css('visibility','visible');
		box.animate({
			opacity: 1
		}, 400, function() {
			if($('#'+focusobj))$('#'+focusobj).focus();
		});
		
		if (box._caller) box._caller.addClass ('show');
		jaboxoverlay.css ('display', 'block');
		if($('#system-message-container')) $('#system-message-container').css('display', 'none');
	}
}