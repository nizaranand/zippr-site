<?php

function klt_no_blog_split($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if($opt['id'] == 'split' || $opt['id'] == 'img_style') {
			unset($shortcode['options'][$i]);
		}
	}
	unset($opt);
	return $shortcode;
}
add_filter('wpv_shortcode_blog', 'klt_no_blog_split');

function klt_no_mini_accordion($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if($opt['id'] == 'mini') {
			unset($shortcode['options'][$i]);
		}
	}
	unset($opt);
	return $shortcode;
}
add_filter('wpv_shortcode_accordion', 'klt_no_mini_accordion');

function klt_no_vertical_tabs($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if($opt['id'] == 'vertical') {
			unset($shortcode['options'][$i]);
		}
	}
	unset($opt);
	return $shortcode;
}
add_filter('wpv_shortcode_tabs', 'klt_no_vertical_tabs');

function klt_simple_portfolio($shortcode) {
	foreach($shortcode['options'] as &$opt) {
		if($opt['id'] == 'column') {
			$opt['min'] = 3;
		}
	}
	unset($opt);
	return $shortcode;
}
add_filter('wpv_shortcode_portfolio', 'klt_simple_portfolio');

function klt_simple_slogan($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if(in_array($opt['id'], array('nopadding', 'carved', 'text_color'))) {
			unset($shortcode['options'][$i]);
		} elseif($opt['id'] == 'background') {
			$opt['type'] = 'select';
			$opt['default'] = 'transparent';
			$opt['options'] = array(
				'transparent' => __('Transparent', 'wpv'),
				'accent1' => __('Accent 1', 'wpv'),
				'accent2' => __('Accent 2', 'wpv'),
				'accent3' => __('Accent 3', 'wpv'),
				'accent4' => __('Accent 4', 'wpv'),
			);
		}
	}
	unset($opt);
	return $shortcode;
}
add_filter('wpv_shortcode_slogan', 'klt_simple_slogan');

function klt_toggle_counters($shortcode) {
	$shortcode['options'][] = array(
		'name' => __('Show counter', 'wpv'),
		'id' => 'counter',
		'default' => true,
		'type' => 'toggle',
	);
	return $shortcode;
}
add_filter('wpv_shortcode_toggle', 'klt_toggle_counters');

function klt_text_divider_more($shortcode) {
	$shortcode['options'][] = array(
		'name' => __('More info link', 'wpv'),
		'id' => 'more',
		'default' => '',
		'type' => 'text',
	);
	return $shortcode;
}
add_filter('wpv_shortcode_text_divider', 'klt_text_divider_more');

function klt_typography_shortcodes($shortcode) {
	foreach($shortcode['options'] as &$code) {
		if($code['value'] == 'blockquote') {
			foreach ($code['options'] as $i=>&$opt) {
				if($opt['id'] == 'align') {
					unset($code['options'][$i]);
				}
			}
			unset($opt);
		} elseif($code['value'] == 'dropcap1' || $code['value'] == 'dropcap2') {
			foreach ($code['options'] as $i=>&$opt) {
				if($opt['id'] == 'color') {
					unset($code['options'][$i]);
				}
			}
			unset($opt);
		} elseif($code['value'] == 'icon') {
			foreach ($code['options'] as $i=>&$opt) {
				if($opt['id'] == 'style') {
					$opt['options'] = wpv_get_icons_extended();
				}
			}
			unset($opt);
		}
	}
	return $shortcode;
}
add_filter('wpv_shortcode_typography', 'klt_typography_shortcodes');

function klt_boxes_shortcodes($shortcode) {
	foreach($shortcode['options'] as &$code) {
		if($code['value'] == 'framed_box') {
			foreach ($code['options'] as $i=>&$opt) {
				if(in_array($opt['id'], array('bgColor', 'textColor', 'rounded'))) {
					unset($code['options'][$i]);
				}
			}
			unset($opt);
		}
	}
	return $shortcode;
}
add_filter('wpv_shortcode_styled_boxes', 'klt_boxes_shortcodes');

function klt_simple_price($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if(in_array($opt['id'], array('title_size', 'title_color', 'title_background', 'price_color', 'price_background', 'price_size', 'description_color', 'description_background', 'text_align'))) {
			unset($shortcode['options'][$i]);
		}
	}
	return $shortcode;
}
add_filter('wpv_shortcode_price', 'klt_simple_price');

function klt_simple_services($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if(in_array($opt['id'], array('title_size', 'description_size', 'fullimage', 'image_height'))) {
			unset($shortcode['options'][$i]);
		}
	}
	return $shortcode;
}
add_filter('wpv_shortcode_services', 'klt_simple_services');

function klt_multiwidget_tab_title($title, $slug) {
	$icon = ''; // date
	switch($slug) {
		case 'comment_count':
			$icon = 'love';
		break;
		case 'date':
			$icon = 'pencil';
		break;
		case 'comments':
			$icon = 'comment';
		break;
		case 'tweets':
			$icon = 'twitter';
		break;
		case 'tags':
			$icon = 'tag';
		break;
	}

	$icon = wpv_get_icon($icon);

	if(!empty($icon))
		return "<span class='visuallyhidden'>$title</span><span class='icon'>$icon</span>";

	return $title;
}
add_filter('wpv_multiwidget_tab_title', 'klt_multiwidget_tab_title', 10, 2);

function klt_advanced_button($shortcode) {
	foreach($shortcode['options'] as $i=>&$opt) {
		if($opt['id'] == 'color') {
			unset($shortcode['options'][$i]);
		} elseif($opt['id'] == 'bgColor') {
			$opt['options'] = array(
				'accent1' => 'accent1',
				'accent2' => 'accent2',
				'accent3' => 'accent3',
				'accent4' => 'accent4',
			);
		}
	}
	return $shortcode;
}
add_filter('wpv_shortcode_button', 'klt_advanced_button');