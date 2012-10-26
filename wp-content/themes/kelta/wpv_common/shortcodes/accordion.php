<?php

/*
 * accordions and toggles
 */

function wpv_shortcode_accordions($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'style' => false,
		'mini' => 'true'
	), $atts));
	
	if (!preg_match_all("/(.?)\[(accordion)\b(.*?)(?:(\/))?\](?:(.+?)\[\/accordion\])?(.?)/s", $content, $matches))
		return do_shortcode($content);

	for($i=0; $i < count($matches[0]); $i++)
		$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
	
	$mini = ($mini == 'true') ? 'mini' : '';
	
	$output = '';
	for($i=0; $i < count($matches[0]); $i++)
		$output .= '<h4 class="tab"><div class="inner">' . $matches[3][$i]['title'] . '</div></h4>'
				. '<div class="pane"><div class="inner">' . do_shortcode(trim($matches[5][$i])) . '</div></div>';

	return '<div class="accordion '.$mini.'">' . $output . '</div>';
}
add_shortcode('accordions', 'wpv_shortcode_accordions');

function wpv_shortcode_toggle($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'title' => false,
		'hidden' => 'true',
		'counter' => 'false',
	), $atts));
	
	$load_hidden = ($hidden == 'true')? 'load_hidden' : '';
	$toggle_title_status = ($hidden != 'true')? 'toggle_active' : '';
	$toggle_status = ($hidden != 'true')? 'open' : '';
	$counter = ($counter=='true')?'counter':'';
	
	return "[raw]<div class='toggle clearfix $counter $toggle_status'>
				<h4 class='toggle_title $toggle_title_status'><b>$title</b></h4>
				<div class='toggle_content $load_hidden'>". wpv_clean_do_shortcode(trim($content)) . '</div>
			</div>[/raw]';
}
add_shortcode('toggle', 'wpv_shortcode_toggle');
