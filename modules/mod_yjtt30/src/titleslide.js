/**
 * YJ Title Ticker 
 * @version		1.0.0
 * @MooTools version 1.3 +
 * @author	Constantin Boiangiu <info [at] constantinb.com>
 */
var TitleTicker = new Class({
	initialize: function(container,options) {
		this.container = container;
		this.options = options;
		var automated;
        var direction;
        var hideshow_flag;

        this.hideshow_flag=true;

		if(this.options) this.direction = this.options.direction;
		else this.direction = "forward";

		//	Is it on autorun or manual?
		if (this.direction!="pause")
		{
		if(this.options.auto == "loop" || this.options.auto == "once") {

	 	   this.automated = this.slider.periodical(this.options.time,this,$(this.container));
		} else {
			this.slider($(this.container))
		}
        }
	},

    next: function(type) {
             if(this.options.auto == "loop" || this.options.auto == "once") {
               this.pause();
             };
              this.options.type=type;
              this.direction="forward";
              this.slider($(this.container));
    	},
    prev: function(type) {
               if(this.options.auto == "loop" || this.options.auto == "once") {
               this.pause();
             };
              this.options.type=type;
              this.direction="back";
              this.slider($(this.container));
    	},
    pause: function() {
              this.direction="pause";
              this.slider($(this.container));
    	},
	run: function() {
		   this.direction="forward";
	 	   this.automated = this.slider.periodical(this.options.time,this,$(this.container));
                             },
	slider: function(container) {
		if ((this.options.auto == "once" || this.options.auto == "loop") && this.direction == "pause")
		{
		   clearTimeout(this.automated);
		}else{
		var child;
		// Get all child nodes to scroll between.
		var children = container.getChildren().getChildren()[0];
		// Run through all child nodes to see if there is a tagged one.
		children.each(function(e) {
			// If there is, make it current child.
			if(e.id == "currentChild") {
				child = e;
			}
           // alert(e.id);
		});
		//alert(this.direction);

			if(!child) {
				// If there isn't, make the first one current child.
				if(this.direction == "forward") {
					child = children[0].getNext();
				}
				else if(this.direction == "back") {
					child = container.getChildren()[0].getLast();
				}
			} else {
			// Are we going to the next or previous node?
				if(this.direction == "forward") {
					var lastElement = container.getChildren()[0].getLast();
					// Stops the loop at the last element.
					if(lastElement == child.getNext() && this.options.auto == "once") clearTimeout(this.automated);
					// Is the current child the last node? Then set the first node as child, otherwise set the next node as child.
					if(lastElement == child) child = children[0];
					else child = child.getNext();
				}
				else if(this.direction == "back") {
					var firstElement = container.getChildren()[0].getFirst();
					// Stops the loop at the last element.
					if(firstElement == child.getPrevious() && this.options.auto == "once") clearTimeout(this.automated);
					// Is the current child the last node? Then set the first node as child, otherwise set the next node as child.
					if(firstElement == child) child = container.getChildren()[0].getLast();
					else child = child.getPrevious();}};
				
				
		// Is the child defined?
		if(child && this.direction != "pause") {
			// Which type of slider is defined?
			if(this.options.type == "scroll") this.scroll(container,children,child);
			else if(this.options.type == "fade") this.fade(container,children,child);
			else if(this.options.type == "scrollfade") this.scrollfade(container,children,child);
		}
		}
	},

    init_vertical: function() {

       $(this.container).setStyle('position','relative');
       var children = $(this.container).getChildren().getChildren()[0];
       var i;
       var s_h;
       var s_w;
       var b_w;

       b_w=$(this.container).getStyle('border-width').toInt();

       s_h=$(this.container).getSize().size.y-2*b_w;
       s_w=$(this.container).getSize().size.x-2*b_w;

       //s_h=100;
       //s_w=600;

       i=0;
       children.each(function(e) {
          if (i!=0)
          {
            e.id = "currentChild";
            $('currentChild').setStyle('position','relative');
            $('currentChild').setStyle('left',0-500);
            $('currentChild').setStyle('top',0+500);
            e.id = "";
          }
		  i++;
		});

        children.each(function(e) {
				e.id = "";
			});
    	},
    init_horizontal: function() {
 $(this.container).setStyle('position','relative');
       var children = $(this.container).getChildren().getChildren()[0];
       var i;
       var s_h;
       var s_w;
       var b_w;

       b_w=$(this.container).getStyle('border-width').toInt();

       s_h=$(this.container).getSize().size.y-2*b_w;
       s_w=$(this.container).getSize().size.x-2*b_w;

       //s_h=100;
       //s_w=600;

       i=0;
       children.each(function(e) {
          if (i!=0)
          {
            e.id = "currentChild";
            $('currentChild').setStyle('position','relative');
            $('currentChild').setStyle('left',0);
            $('currentChild').setStyle('top',0);
            e.id = "";
          }
		  i++;
		});
    	},
	scroll: function(container,children,child) {
		// Make it a scroll slide.
  	    var scroll = new Fx.Scroll(container,{duration: this.options.duration, onComplete: function() {
			// Remove tags from all child nodes.
			children.each(function(e) {
				e.id = "";
			});
			// Tag this child as current
			child.id = "currentChild";
		}}).toElement(child);
	},
	fade: function(container,children,child) {
		// Make it a fade slide
		var fade = new Fx.Tween(container,{duration: this.options.duration, property:'opacity', onComplete: function() {
			new Fx.Scroll(container,{duration: 1,onComplete: function() {
				// Remove tags from all child nodes.
				children.each(function(e) {
					e.id = "";
				});
				// Tag this child as current
				child.id = "currentChild";
				new Fx.Tween(container,{property:'opacity'}).start(0.01,1);
			}}).toElement(child);
		}})
		fade.start(1,0.01);
	},
	scrollfade: function(container,children,child) {
		// In case you input the miliseconds as a string instead of integer.
		var durationInt = this.options.duration.toInt();
		// Make it a scrollfade slide
		var fade = new Fx.Tween(container,{duration: (durationInt/2), property:'opacity'})
		fade.start(1,0.01).chain(function() {
			fade.start(0.01,1);
		});
		new Fx.Scroll(container,{duration: durationInt, onComplete: function() {
			// Remove tags from all child nodes.
			children.each(function(e) {
				e.id = "";
			});
			// Tag this child as current
			child.id = "currentChild";
		}}).toElement(child);
	},
	hide_and_show_fade:function()
	{
	   var fade = new Fx.Tween($(this.container),{duration: this.options.duration, property:'opacity'});
       if (this.hideshow_flag)
       {
         fade.start(1,0.01);
       }else
       {
         fade.start(0.01,1);
       }
       this.hideshow_flag=!this.hideshow_flag;
	}
});