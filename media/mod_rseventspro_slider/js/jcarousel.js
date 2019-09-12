/* ==========================================================
 * bootstrap-carousel.js v2.3.2
 * http://twitter.github.com/bootstrap/javascript.html#carousel
 * ==========================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */


!function ($) {

  "use strict"; // jshint ;_;


 /* CAROUSEL CLASS DEFINITION
  * ========================= */

  var RSEPROCarousel = function (element, options) {
    this.$element = $(element)
    this.$indicators = this.$element.find('.carousel-indicators')
    this.options = options
    this.options.pause == 'hover' && this.$element
	  .on('mouseenter, mouseover', $.proxy(this.pause, this)) 
      .on('mouseleave', $.proxy(this.cycle, this));
	 
	 this.setMaxHeight(); 
  }

  RSEPROCarousel.prototype = {
    cycle: function (e) {
	  var custom_direction = (this.options.direction == 'right' ? this.prev : this.next);
	  this.paused = false
		  if (this.interval) clearInterval(this.interval);
		  this.options.interval
			&& !this.paused
			&& (this.interval = setInterval($.proxy(custom_direction, this), this.options.interval))
		  return this
    }

  , getActiveIndex: function () {
      this.$active = this.$element.find('.item.active')
      this.$items = this.$active.parent().children()
      return this.$items.index(this.$active)
    }

  , to: function (pos) {
      var activeIndex = this.getActiveIndex()
        , that = this

      if (pos > (this.$items.length - 1) || pos < 0) return

      if (this.sliding) {
        return this.$element.one('slid', function () {
          that.to(pos)
        })
      }

      if (activeIndex == pos) {
        return this.pause().cycle()
      }

      return this.slide(pos > activeIndex ? 'next' : 'prev', $(this.$items[pos]))
    }

  , pause: function (e) {
      this.paused = true
	  clearInterval(this.interval)
	  this.interval = null
	  return this
    }

  , next: function () {
      if (this.sliding) return 
      return this.slide('next')
    }

  , prev: function () {
      if (this.sliding) return false
      return this.slide('prev')
    }

  , slide: function (type, next) {
      var $active = this.$element.find('.item.active')
        , $next = next || $active[type]()
        , isCycling = this.interval
        , direction = type == 'next' ? 'left' : 'right'
        , fallback  = type == 'next' ? 'first' : 'last'
        , that = this
        , e
		
      this.sliding = true
	  
      isCycling && this.pause()

      $next = $next.length ? $next : this.$element.find('.item')[fallback]()

      e = $.Event('slide', {
        relatedTarget: $next[0]
      , direction: direction
      })

      if ($next.hasClass('active'))	return

      if (this.$indicators.length) {
        this.$indicators.find('.active').removeClass('active')
        this.$element.one('slid', function () {
          var $nextIndicator = $(that.$indicators.children()[that.getActiveIndex()])
          $nextIndicator && $nextIndicator.addClass('active')
        })
      }
	  
	var $active_caption = $('#' + this.$element.attr('id') + ' #mod_slider_caption'+$active.attr('data_captions'));
	var $next_caption = $('#' + this.$element.attr('id') + ' #mod_slider_caption'+$next.attr('data_captions'));
	
	  if(this.$element.hasClass('slide')) {
			this.$element.trigger(e);
			if (e.isDefaultPrevented()) return
			
			$active_caption.fadeOut(300); 
			$next_caption.fadeIn(300); 

			if (this.options.effect == 'slide') {
				$active.animate({left: (direction == 'right' ? '100%' : '-100%')}, 600, 'linear', function(){
					$active.removeClass('active');
					that.sliding = false;
					setTimeout(function () { that.$element.trigger('slid') }, 0)
				});

				$next.addClass(type).css({left: (direction == 'right' ? '-100%' : '100%')}).animate({left: '0'}, 600, 'linear', function(){
					$next.removeClass(type).addClass('active');
				});
			}
			else if(this.options.effect == 'fade') {
				$next.css({opacity: '0'});
				$active.animate({opacity: '0'}, 300, function(){
					that.sliding = false;
					setTimeout(function () { that.$element.trigger('slid') }, 0)
				});
				$active.removeClass('active');
				$next.removeClass(type).addClass('active');
				$next.animate({opacity: '1'}, 300);
			}
			else if(this.options.effect == 'rotate') {
				
				$active.animateRotate((direction == 'right' ? 180 : -180), 300, 'linear', function(){
					$active.removeClass('active');
					that.sliding = false;
					setTimeout(function () { that.$element.trigger('slid') }, 0)
				});

				$next.addClass(type).animateRotate(0, 300, 'linear', function(){
					$next.removeClass(type).addClass('active');
				});
			}
      } else {
        this.$element.trigger(e)
        if (e.isDefaultPrevented()) return
        $active.removeClass('active')
        $next.addClass('active')
        this.sliding = false
        this.$element.trigger('slid')
      }

      isCycling && this.cycle()

      return this
    },
	
	getMaxHight: function () {
		var maxHeight = 0;
		jQuery('#'+ this.$element.attr('id') +' .mod_slider_caption_container > div').each(function() {
			if (jQuery(this).outerHeight() > maxHeight) {
				maxHeight = jQuery(this).outerHeight()
			}
		})
		
		return maxHeight;
	},
	
	setMaxHeight: function() {
		jQuery('#'+ this.$element.attr('id') +' .mod_slider_control').height(this.getMaxHight());
		jQuery('#'+ this.$element.attr('id') +' .mod_slider_control').css('line-height',this.getMaxHight()+'px');
		jQuery('#'+ this.$element.attr('id') +' .mod_slider_caption_container').height(this.getMaxHight());
		jQuery('#'+ this.$element.attr('id') +' .mod_slider_caption').height(this.getMaxHight());
	}
  }
  
  $.fn.animateRotate = function(angle, duration, easing, complete) {
		return this.each(function() {
			var $elem = $(this);

			$({deg: 0}).animate({deg: angle}, {
				duration: duration,
				easing: easing,
				step: function(now) {
					$elem.css({
						transform: 'rotate(' + now + 'deg)'
					});
				},
				complete: complete || $.noop
			});
		});
	};


 /* CAROUSEL PLUGIN DEFINITION
  * ========================== */

  var old = $.fn.rseprocarousel

  $.fn.rseprocarousel = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('rseprocarousel')
        , options = $.extend({}, $.fn.rseprocarousel.defaults, typeof option == 'object' && option)
        , action = typeof option == 'string' ? option : options.slide
      if (!data) $this.data('rseprocarousel', (data = new RSEPROCarousel(this, options)))
      if (typeof option == 'number') data.to(option)
      else if (action) data[action]()
      else if (options.interval) data.pause().cycle()
    })
  }

  $.fn.rseprocarousel.defaults = {
    interval: 5000,
  }

  $.fn.rseprocarousel.Constructor = RSEPROCarousel


 /* CAROUSEL NO CONFLICT
  * ==================== */

  $.fn.rseprocarousel.noConflict = function () {
    $.fn.rseprocarousel = old
    return this
  }

 /* CAROUSEL DATA-API
  * ================= */

  $(document).on('click.rseprocarousel.data-api', '[data-slide], [data-slide-to]', function (e) {
    var $this = $(this), href
      , $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
      , options = $.extend({}, $target.data(), $this.data())
      , slideIndex

    $target.rseprocarousel(options)

    if (slideIndex = $this.attr('data-slide-to')) {
      $target.data('rseprocarousel').pause().to(slideIndex).cycle()
    }

    e.preventDefault()
  })

}(window.jQuery);