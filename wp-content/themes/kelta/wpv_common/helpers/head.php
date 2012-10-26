<?php

// put all javascript that has to be included in a separate <script> tag here
// everything else put in combine.php
function wpv_enqueue_scripts() {
	$move_bottom = true;
	
	if(!is_admin()) {

		if(!wpv_is_login()) {
			// modernizr should be on top
			wp_enqueue_script( 'modernizr', WPV_JS .'modernizr.min.js');
			
			if(wpv_get_option('gmap_api_key')) {
				wp_enqueue_script('gmap-api', 'http://maps.googleapis.com/maps/api/js?sensor=false&amp;key=' . wpv_get_option('gmap_api_key'), array(), false, $move_bottom);
				wp_enqueue_script('jquery-gmap', WPV_JS .'jquery.gmap.js', array('jquery', 'gmap-api'), THEME_VERSION, $move_bottom);
			}

			if ( is_singular() && comments_open() ) {
	  			wp_enqueue_script( 'comment-reply', false, false, false, $move_bottom );
	  		}

			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-tabs');

			wp_register_script('video-js', 'http://vjs.zencdn.net/c/video.js', array('jquery'), THEME_VERSION, $move_bottom);

			$wpv_js = array(
				'jquery.animate-enhanced' => array(
					true,
					array('jquery'),
				),
				'wpvslider.uncompressed' => array(
					false,
					array('jquery', 'front-jquery.easing'),
				),
				'jquery.sequence' => array(
					false,
					array('jquery'),
				),
				'wpvslider.fx' => array(
					false,
					array('jquery'),
				),
				'jquery.jplayer.min' => array(
					false,
					array('jquery'),
				),
				'jquery.easing' => array(
					false,
					array('jquery')
				),
				'jquery.tweet' => array(
					false,
					array('jquery')
				),
				'wpvbgslider' => array(
					false,
					array('jquery')
				),
				'jquery.colorbox-min' => array(
					true,
					array('jquery'),
				),
				'validator' => array(
					true,
					array('jquery'),
				),
				'jail' => array(
					true,
					array('jquery'),
				),
				'widgets/contact_form' => array(
					true,
					array('jquery'),
				),
				'jquery.corner' => array(
					false,
					array('jquery'),
				),
				'wpv_common' => array(
					true,
					array('jquery'),
				),
				'pixastic.core' => array(
					false,
					array('jquery'),
				),
				'pixastic.blurfast' => array(
					false,
					array('front-pixastic.core'),
				),
				'pixastic.desaturate' => array(
					false,	
					array('front-pixastic.core'),
				),
			);

			foreach($wpv_js as $file=>$opts) {
				if($opts[0]) {
					wp_enqueue_script( 'front-'.$file, WPV_JS .$file.'.js', $opts[1], THEME_VERSION, $move_bottom);
				} else {
					wp_register_script( 'front-'.$file, WPV_JS .$file.'.js', $opts[1], THEME_VERSION, $move_bottom);
				}
			}

			$wpv_theme_js = array(
				'wpvslider.fx-theme' => array(
					false,
					array('jquery', 'front-wpvslider.uncompressed'),
				),
				'wpv_theme' => array(
					true,
					apply_filters('wpv_theme_js_deps', array('jquery', 'front-wpv_common')),
				),
			);

			foreach($wpv_theme_js as $file=>$opts) {
				if($opts[0]) {
					wp_enqueue_script( 'front-'.$file, WPV_THEME_JS .$file.'.js', $opts[1], THEME_VERSION, $move_bottom);
				} else {
					wp_register_script( 'front-'.$file, WPV_THEME_JS .$file.'.js', $opts[1], THEME_VERSION, $move_bottom);
				}
			}
		}
	}
	else {
		wp_enqueue_script( 'jquery-colorbox', WPV_JS .'jquery.colorbox-min.js', array('jquery'), THEME_VERSION, $move_bottom);
		
		wp_enqueue_script( 'common');
		wp_enqueue_script( 'editor');
		wp_enqueue_script( 'jquery-ui-sortable');
		wp_enqueue_script( 'jquery-ui-draggable');
		wp_enqueue_script( 'jquery-ui-tabs');
		wp_enqueue_script( 'jquery-ui-range', WPV_ADMIN_ASSETS_URI .'js/jquery.ui.range.js', array('jquery'), THEME_VERSION, $move_bottom);
		wp_enqueue_script( 'jquery-ui-slider');
		wp_enqueue_script( 'thickbox');
		wp_enqueue_script( 'jquery-layout', WPV_ADMIN_ASSETS_URI .'js/jquery.layout-latest.js', array('jquery'), THEME_VERSION, $move_bottom);

		if(WPV_RESPONSIVE) {
			wp_enqueue_script( 'general-layout-editor', WPV_ADMIN_ASSETS_URI .'js/general-layout-responsive.js', array('jquery'), THEME_VERSION, $move_bottom);
		} else {
			wp_enqueue_script( 'general-layout-editor', WPV_ADMIN_ASSETS_URI .'js/general-layout.js', array('jquery'), THEME_VERSION, $move_bottom);
		}

		wp_enqueue_script( 'wpv_admin', WPV_ADMIN_ASSETS_URI .'js/wpv_admin.js', array('jquery'), THEME_VERSION, $move_bottom);
		wp_enqueue_script( 'ibutton', WPV_ADMIN_ASSETS_URI .'js/jquery.ibutton.js', array('jquery'), THEME_VERSION, $move_bottom);
		wp_enqueue_script( 'shortcode', WPV_ADMIN_ASSETS_URI . 'js/shortcode.js', array('jquery'), THEME_VERSION, $move_bottom);
		wp_enqueue_script( 'farbtastic' );
		
		if (isset($_GET['gallery_edit_image'])) {
			wp_enqueue_script('theme-gallery-edit-image', WPV_ADMIN_ASSETS_URI . 'js/gallery-edit-image.js', array('jquery', 'wpv-back'), THEME_VERSION, $move_bottom);
		}
	}
	
}
add_action('init', 'wpv_enqueue_scripts');

// put all css that has to be included in a separate <link> tag here
// everything else put in combine.php
function wpv_enqueue_styles() {
	if(!is_admin()) {
		if(!wpv_is_login()) {
			$external_fonts = wpv_get_option('external-fonts');
			if(!empty($external_fonts)) {
				foreach($external_fonts as $name=>$url) {
					wp_enqueue_style( 'wpv-'.$name, $url, array(), THEME_VERSION);	
				}
			}

			$css_files = include WPV_THEME_CSS_DIR . 'list.php';

			foreach($css_files as $file) {
				if(is_multisite() && basename($file) === 'configurable')
					$file .= $GLOBALS['blog_id'];
				
				wp_enqueue_style( 'front-'.basename($file), wpv_prepare_url(WPV_THEME_CSS . $file . '.css'), array(), THEME_VERSION);
			}

			wp_enqueue_style('videojs', 'http://vjs.zencdn.net/c/video-js.css');
		}
	}
	else {
		wp_enqueue_style( 'thickbox');
		wp_enqueue_style( 'colorbox', WPV_ADMIN_ASSETS_URI . 'css/colorbox.css');
		wp_enqueue_style( 'wpv_admin', WPV_ADMIN_ASSETS_URI . 'css/wpv_admin.css');
		wp_enqueue_style( 'farbtastic' );
		
		if(stristr($_SERVER['HTTP_USER_AGENT'], "msie 7") || stristr($_SERVER['HTTP_USER_AGENT'], "msie 8") ) {
			wp_enqueue_style( 'wpv-ie78', WPV_ADMIN_ASSETS_URI . 'css/ie78.css');
		}
	}
	
}
add_action('init', 'wpv_enqueue_styles');
