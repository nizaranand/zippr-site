<?php

/**
 * tabs
 */

function wpv_shortcode_tabs($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'style' => '',
		'delay' => '0',
		'vertical' => 'false'
	), $atts));


	if (!wpv_sub_shortcode('tab', $content, $params, $sub_contents))
		return 'error parsing slider shortcode';
		
	global $tabs_shown;
	if($tabs_shown) {
		$tabs_shown++;
	} else {
		$tabs_shown = 1;
	}
	
	if($vertical == 'true') {
		$vertical = 'vertical';
	} else {
		$vertival = '';
	}
	
	$id = 'tabs-'.$tabs_shown.rand(0,10000);

	$output = '<ul class="ui-tabs-nav">';
	foreach($params as $i=>$p) {
		$class = isset($p['class']) ? " class='tab-{$p['class']}'" : '';
		$output .= '<li'.$class.'><a href="#'.$id.$i.'">' . $p['title'] . '</a></li>';
	}
	$output .= '</ul>';
	
	foreach($sub_contents as $i=>$c) {
		$class = isset($params[$i]['class']) ? ' tab-'.$params[$i]['class'] : '';
		//if ($i === 0) 
		$class .= ' ui-tabs-hide';
		$output .= '<div class="pane'.$class.'" id="'.$id.$i.'">' . do_shortcode(trim($c)) . '</div>';
	}
		
	return '<div class="tabs '.$style.' '.$vertical.'" data-delay="'.$delay.'">' . $output . '</div>';
}
add_shortcode('tabs', 'wpv_shortcode_tabs');
