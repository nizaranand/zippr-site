<?php

/*
 * text divider shortcode
 */

function wpv_shortcode_text_divider($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'vertical' => 'false',
		'more' => '',
	), $atts));
	
	$has_html = preg_match('/^\s*</', $content);

	$link = '';
	$class = 'single';
	if(!empty($more)) {
		$class = 'has-more';
		$link = "<a href='$link' title='".__('Read more', 'wpv')."' class='more'>".__('More', 'wpv').'</a>';
	}
	$content = "<div>$content</div>";

	if($has_html)
	   return '[raw]<div class="title-wrap '.$class.'">' . $content . $link .'</div>[/raw]';

	return '[raw]<div class="title-wrap '.$class.'"><h4>' . $content . '</h4>'. $link .'</div>[/raw]';
}
add_shortcode('text_divider','wpv_shortcode_text_divider');
