<?php

/*
 * services shortcode
 */

function wpv_shortcode_services($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'text_align' => 'justify',
		'icon' => '',
		'title' => '',
		'title_size' => '30',
		'description_size' => '12',
		'button_text' => '',
		'button_link' => '',
		'no_button' => 'false',
		'fullimage' => 'true',
		'class' => '',
		'image_height' => 0,
	), $atts));
	
	ob_start();

	$has_more = $before = $after = false;
	$content_split = explode('[moreinfo]', $content);
	if(count($content_split) == 2) {
		$has_more = true;
		$before = $content_split[0];
		$content = $content_split[1];
	} elseif(count($content_split) >= 3) {
		$has_more = true;
		$before = array_shift($content_split);
		$content = array_shift($content_split);
		$after = implode('[divider_2]', $content_split);
	}

	include WPV_SHORTCODE_TEMPLATES . 'services.php';

	return ob_get_clean();
}
add_shortcode('services','wpv_shortcode_services');
