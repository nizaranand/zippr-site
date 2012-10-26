<?php

// register right/left, header and footer sidebars
// also provides a function which outputs the correct right/left sidebar

class wpv_sidebars {
		
	private $sidebars = array();
	private $places = array();
	
	public function __construct() {
 		$this->sidebars = array(
			'page' => __('Shared Page Widget Area', 'wpv'),
			'blog' => __('Blog Widget Area', 'wpv'),
			'portfolio' => __('Portfolio Widget Area', 'wpv'),
		);
		
		$this->places = array('left', 'right');
	}
	
	public function register_sidebars() {
		foreach($this->sidebars as $id=>$name) {
			foreach($this->places as $place) {
				register_sidebar(array(
					'id' => $id.'-'.$place,
					'name' => $name . " ($place)",
					'description' => $name . " ($place)",
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget' => '</section>',
					'before_title' => apply_filters('wpv_before_widget_title', '<h4 class="widget-title">', 'body'),
					'after_title' => apply_filters('wpv_after_widget_title', '</h4>', 'body'),
				));
			}
		}
		
		for($i=1; $i<=(int)wpv_get_option('footer-sidebars'); $i++) {
			register_sidebar(array(
				'id' => "footer-sidebars-$i",
				'name' => "Footer widget area $i",
				'description' => "Footer widget area $i",
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => apply_filters('wpv_before_widget_title', '<h4 class="widget-title">', 'footer'),
				'after_title' => apply_filters('wpv_after_widget_title', '</h4>', 'footer'),
			));
		}
		
		for($i=1; $i<=(int)wpv_get_option('header-sidebars'); $i++) {
			register_sidebar(array(
				'id' => "header-sidebars-$i",
				'name' => "Body top widget area $i",
				'description' => "Body top widget area $i",
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => apply_filters('wpv_before_widget_title', '<h4 class="widget-title">', 'header'),
				'after_title' => apply_filters('wpv_after_widget_title', '</h4>', 'header'),
			));
		}
		
		if(wpv_get_option('feedback-type') == 'sidebar') {
			register_sidebar(array(
				'id' => "feedback-sidebar",
				'name' => "Feedback widget area",
				'description' => "Slides out when the feedback button is clicked",
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' => '</section>',
				'before_title' => apply_filters('wpv_before_widget_title', '<h4 class="widget-title">', 'feedback'),
				'after_title' => apply_filters('wpv_after_widget_title', '</h4>', 'feedback'),
			));
		}
		
		$custom_sidebars = wpv_get_option('custom-sidebars');
		$custom_sidebars = explode(',', $custom_sidebars);
		
		foreach($custom_sidebars as $sidebar) {
			$name = str_replace('wpv_sidebar-', '', $sidebar);
			$sidebar = sanitize_title($sidebar);
			foreach($this->places as $place) {
				register_sidebar(array(
					'id' => $sidebar.'-'.$place,
					'name' => "$name ($place)",
					'description' => "$name ($place)",
					'before_widget' => '<section id="%1$s" class="widget %2$s">',
					'after_widget' => '</section>',
					'before_title' => apply_filters('wpv_before_widget_title', '<h4 class="widget-title">', 'body'),
					'after_title' => apply_filters('wpv_after_widget_title', '</h4>', 'body'),
				));
			}
		}
	}

	public function get_sidebar($place = 'left'){
		global $post;
		
		if(is_front_page() || is_home() || is_page()) {	
			$sidebar = $this->sidebars['page'];
		}

		if(is_singular('post'))
			$sidebar = $this->sidebars['blog'];
			
		elseif(is_singular('portfolio'))
			$sidebar = $this->sidebars['portfolio'];
		
		if(is_search() || is_archive())
			$sidebar = $this->sidebars['blog'];
		
		if( get_post_meta($post->ID, 'use-global-options', true) === 'false') {
			$custom_sidebar = get_post_meta($post->ID, $place.'_sidebar_type', true);
			if(is_active_sidebar($custom_sidebar . '-' . $place)) {
				$sidebar = $custom_sidebar;
			}
		}
		
		if(isset($sidebar))
			return dynamic_sidebar($sidebar.'-'.$place);

		return dynamic_sidebar($this->sidebars['blog'].'-'.$place);
	}
};

global $sidebars;
$sidebars = new wpv_sidebars;

add_action('widgets_init', array($sidebars, 'register_sidebars'));
