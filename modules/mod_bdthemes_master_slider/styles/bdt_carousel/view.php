<?php

/**
* BDTheme Master Slider - view file
* @package Joomla!
* @Copyright (C) 2011-2014 bdthemes.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ version $Revision: Master Slider 1.0 $
**/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.utilities.string');

$slider_text_block_width = ($this->config['config']->bdt_carousel->bdt_carousel_text_block_width) ? 'width: '.$this->config['config']->bdt_carousel->bdt_carousel_text_block_width.'%;' : '' ;  


$text_block_position_top = ($this->config['config']->bdt_carousel->bdt_carousel_text_block_position_top) ? 'top: '.$this->config['config']->bdt_carousel->bdt_carousel_text_block_position_top.'px;' : '' ; 

$text_block_position_left = ($this->config['config']->bdt_carousel->bdt_carousel_text_block_position_left) ? 'left: '.$this->config['config']->bdt_carousel->bdt_carousel_text_block_position_left.'px;' : '' ; 

$content_style = ($this->config['config']->bdt_carousel->bdt_carousel_content_style) ? $this->config['config']->bdt_carousel->bdt_carousel_content_style : '' ; 


?>

<div class="ms-caro3d-template">
<div id="ms-<?php echo $this->config['module_id'];?>" class="master-slider ms-skin-<?php echo $this->config['config']->bdt_carousel->bdt_carousel_skins_style; ?>" >
	
	<?php for($i = 0; $i < count($this->config['image_show_data']); $i++) : ?>
	<?php if($this->config['image_show_data'][$i]->published) : ?>
		<?php 
			unset($path, $title, $link, $content);
			// creating slide path
			$path = '';
			$bdt_image_resize = new bdt_image_resize();
			// check if the slide have to be generated or not
			if($this->config['generate_thumbnails'] == 1) {
				$path = BDT_MS_URL.'cache/'.$bdt_image_resize->translateName($this->config['image_show_data'][$i]->image, $this->config['module_id']);
			} else {
				$path = $uri->root();
				$path .= $this->config['image_show_data'][$i]->image;
			}


			if($this->config['image_show_data'][$i]->type == "k2") {
			  	if(isset($this->articlesK2[$this->config['image_show_data'][$i]->artK2_id])) {
			  		$title = htmlspecialchars($this->articlesK2[$this->config['image_show_data'][$i]->artK2_id]["title"]);
			    	$link =  $this->articlesK2[$this->config['image_show_data'][$i]->artK2_id]["link"];
			    	$content = $this->articlesK2[$this->config['image_show_data'][$i]->artK2_id]["introtext"];
			    } else {
			    	$title = 'Selected k2 article doesn\'t exist!';
			    	$link = '#';
			    	$content = '';
			    }
			} else {
			    // creating slide title
				$title = htmlspecialchars(($this->config['image_show_data'][$i]->type == "text") ? $this->config['image_show_data'][$i]->name : $this->articles[$this->config['image_show_data'][$i]->art_id]["title"]);
				// creating slide link
				$link = ($this->config['image_show_data'][$i]->type == "text") ? $this->config['image_show_data'][$i]->url : $this->articles[$this->config['image_show_data'][$i]->art_id]["link"];	
				$content = ($this->config['image_show_data'][$i]->type == "text") ? $this->config['image_show_data'][$i]->content : $this->articles[$this->config['image_show_data'][$i]->art_id]["introtext"];
			}

			
            // creating slide title
			$title = preg_replace('/__(.*?)__/i', '<strong>${1}</strong>', $title);
			// creating slide content
			$content = str_replace(array('[ampersand]', '[leftbracket]', '[rightbracket]'), array('&', '<', '>'), $content);
			$content_sub = false;
			$subcontent_match_text = array();
			if(preg_match('/__(.*?)__/mis', $content, $subcontent_match_text) == 1) {
				$content_sub = $subcontent_match_text[1];
				$content = preg_replace('/__(.*?)__/mis', '', $content);
			}
			//
			// creating slide link
			//
			$link = ($this->config['image_show_data'][$i]->type == "text") ? $this->config['image_show_data'][$i]->url : '#';
			$link_text = '';
			// parsing custom texts
			$link_match_text = array();
			
			if(preg_match('@^\[(.*?)\]@mis', $link, $link_match_text) == 1) {
				$link = preg_replace('@^\[.*?\]@mis', '', $link);
				$link_text = $link_match_text[1];
				
				if(stripos($link_text, ',') !== FALSE) {
					$icon_match = array();
					preg_match('@icon-(.*?),@mis', $link_text, $icon_match);
					$link_text = preg_replace('@icon-(.*?),@mis', '<i class="icon-${1}"></i> ', $link_text);
				}
			} else {
				$link_text = JText::_('MOD_BDTHEMES_MASTER_SLIDER_ROCKWALL_READMORE');
			}
		?>
		<div class="ms-slide">			
			<img src="<?php echo BDT_MS_STYLE_URL.$this->config['styles']; ?>/blank.gif" data-src="<?php echo $path; ?>" alt="lorem ipsum dolor sit"/>
			
			<?php if($link) : ?>
				<a href="<?php echo $link; ?>" data-type="video"><?php echo $link_text; ?></a>
			<?php endif; ?>

		</div>
	<?php endif; ?>
	<?php endfor; ?>
</div>
</div>
<script type="text/javascript">		
    var slider = new MasterSlider();
    slider.setup('ms-<?php echo $this->config['module_id'];?>' , {
		width: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_image_width; ?>,    // slider standard width
		height: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_image_height; ?>,   // slider standard height
		space: 0,

		grabCursor: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_grab_cursor; ?>,
		swipe: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_mouse_drag; ?>,
		wheel: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_mouse_wheel; ?>,
		
		autoplay: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_animation_autoplay; ?>,
		loop: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_animation_loop; ?>,
		speed: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_animation_speed; ?>,
		view: <?php echo '"'.$this->config['config']->bdt_carousel->bdt_carousel_layout_view.'"'; ?>,

		smoothHeight: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_smooth_height; ?>,
		autoHeight: <?php echo $this->config['config']->bdt_carousel->bdt_carousel_auto_height; ?>,
    });
    
    // adds Arrows navigation control to the slider.
    <?php echo ($this->config['config']->bdt_carousel->bdt_carousel_navigation == 1) ? "slider.control('arrows');" : ""; ?>

    // adds pagination control to the slider.    
    <?php echo ($this->config['config']->bdt_carousel->bdt_carousel_pagination == 1) ? "slider.control('bullets');" : ""; ?>

    <?php echo ($this->config['config']->bdt_carousel->bdt_carousel_circletimer == 1) ? "slider.control('circletimer' , {color:'#FFFFFF' , stroke:9});": ""; ?>
    
</script>