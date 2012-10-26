<?php

/*
 * team_member shortcode
 */

function wpv_shortcode_team_member($atts, $content = null, $code) {
	global $wp_filter;
	
	$the_content_filter_backup = $wp_filter['the_content'];
	extract(shortcode_atts(array(
		'name' => '',
		'position' => '',
		'phone' => '',
		'email' => '',
		'picture' => '',
		'url' => '',
	), $atts));

	if(!empty($email)) {
		$url = $email;
	}

	$url_is_email = preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i', $url);

	if($url_is_email) {
		$url = 'mailto:'.$url;
	}
	
	ob_start();

	include WPV_SHORTCODE_TEMPLATES . 'team_member.php';

	return '[raw]'.ob_get_clean().'[/raw]';
}
add_shortcode('team_member','wpv_shortcode_team_member');
