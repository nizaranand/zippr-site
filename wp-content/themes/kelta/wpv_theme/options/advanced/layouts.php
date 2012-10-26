<?php

global $wpv_hsidebars_widths;

return array(
array(
	'name' => __('Layout', 'wpv'),
	'type' => 'start',
),

array(
	'name' => __('Simple layout editor', 'wpv'),
	'type' => 'separator',
),

array(
	'text' => __('Below you can set the the width of the sidebars in the body, and the height of the header and slider area by dragging the blue gutters. You can enable or disable the slider, the body top widget areas, the body sidebar areas and the footer widget areas. Please note, that whether the slider, the body top widget areas or any of the body sidebars are displayed is just a default setting that can be overridden for newly created pages/posts/portfolio items.', 'wpv'),
	'class' => 'sticked',
	'type' => 'info',
),

array(
	'name' => __('General layout', 'wpv'),
	'type' => 'general-layout-responsive',
	'left' => 'left-sidebar-width',
	'right' => 'right-sidebar-width',
	'slider' => 'header-slider-height',
	'header' => 'header-height',
),

array(
	'name' => __('Sidebar layout for new pages', 'wpv'),
	'class' => 'hidden',
	'id' => 'default-body-layout',
	'type' => 'body-layout',
),

array(
	'name' => __('Enable header slider', 'wpv'),
	'id' => 'has-header-slider',
	'type' => 'checkbox',
	'class' => 'hidden',
),

array(
	'name' => __('Enable body top widget areas', 'wpv'),
	'id' => 'has-header-sidebars',
	'type' => 'checkbox',
	'class' => 'hidden',
),

array(
	'name' => __('Enable footer widget areas', 'wpv'),
	'id' => 'has-footer-sidebars',
	'type' => 'checkbox',
	'class' => 'hidden',
),

array(
	'name' => __('Layout type', 'wpv'),
	'id' => 'site-layout-type',
	'type' => 'select',
	'options' => array(
		'boxed has-left-column' => __('Boxed asymetrical', 'wpv'),
		'boxed no-left-column' => __('Boxed symetrical', 'wpv'),
		'wide no-left-column' => __('Full width', 'wpv'),
	),
),

/*
array(
	'name' => __('Enable fixed header', 'wpv'),
	'id' => 'fixed-header',
	'type' => 'toggle',
),
*/
array(
	'name' => __('Precise adjustment', 'wpv'),
	'type' => 'separator',
),

array(
	'name' => __('Header height', 'wpv'),
	'id' => 'header-height',
	'desc' => __('This is the area above the slider', 'wpv'),
	'type' => 'range',
	'min' => 30,
	'max' => 300,
),

array(
	'name' => __('Header slider height', 'wpv'),
	'desc' => __('In pixels', 'wpv'),
	'id' => 'header-slider-height',
	'type' => 'range',
	'min' => 100,
	'max' => 800,
),

array(
	'name' => __('Left sidebar width', 'wpv'),
	'id' => 'left-sidebar-width',
	'type' => 'range',
	'min' => 20,
	'max' => 50,
),
array(
	'name' => __('Right sidebar width', 'wpv'),
	'id' => 'right-sidebar-width',
	'type' => 'range',
	'min' => 20,
	'max' => 50,
),
		
	array(
		'type' => 'end'
	),
			
//----
array(
	'name' => __('Body Layout', 'wpv'),
	'type' => 'start',
	'sub' => 'Layout',
),

array(
	'name' => __('General', 'wpv'),
	'type' => 'separator',
),

array(
	'name' => __('Show page title', 'wpv'),
	'id' => 'has-page-header',
	'type' => 'toggle',
),

array(
	'name' => __('Basic sidebars', 'wpv'),
	'desc' => __('The widget areas bellow are placed between the slider and the body of the site. By default these areas are disabled. They can be enabled on every single page or post
You can either choose one of the predefined layouts or configure your own in the "Advanced" section', 'wpv'),
	'type' => 'separator',
),

array(
	'text' => __('The widget areas bellow are placed between the slider and the body of the site. By default these areas are disabled. They can be enabled on every single page or post. You can either choose one of the predefined layouts or configure your own in the "Advanced" section', 'wpv'),
	'class' => 'sticked',
	'type' => 'info',
),

array(
	'type' => 'autofill',
	'option_sets' => array(
		array(
			'name' => __('1/3 | 1/3 | 1/3', 'wpv'),
			'image' => WPV_ADMIN_ASSETS_URI . 'images/header-sidebars-3.png',
			'values' => array(
				'header-sidebars' => 3,
				'header-sidebars-1-width' => 'one_third',
				'header-sidebars-1-last' => 0,
				'header-sidebars-2-width' => 'one_third',
				'header-sidebars-2-last' => 0,
				'header-sidebars-3-width' => 'one_third',
				'header-sidebars-3-last' => 1,
				'header-sidebars-4-width' => 'full',
				'header-sidebars-4-last' => 0,
				'header-sidebars-5-width' => 'full',
				'header-sidebars-5-last' => 0,
				'header-sidebars-6-width' => 'full',
				'header-sidebars-6-last' => 0,
			),
		),
		array(
			'name' => __('1/4 | 1/4 | 1/4 | 1/4', 'wpv'),
			'image' => WPV_ADMIN_ASSETS_URI . 'images/header-sidebars-4.png',
			'values' => array(
				'header-sidebars' => 4,
				'header-sidebars-1-width' => 'one_fourth',
				'header-sidebars-1-last' => 0,
				'header-sidebars-2-width' => 'one_fourth',
				'header-sidebars-2-last' => 0,
				'header-sidebars-3-width' => 'one_fourth',
				'header-sidebars-3-last' => 0,
				'header-sidebars-4-width' => 'one_fourth',
				'header-sidebars-4-last' => 1,
				'header-sidebars-5-width' => 'full',
				'header-sidebars-5-last' => 0,
				'header-sidebars-6-width' => 'full',
				'header-sidebars-6-last' => 0,
			),
		),
		
		array(
			'name' => __('1/5 | 1/5 | 1/5 | 1/5 | 1/5', 'wpv'),
			'image' => WPV_ADMIN_ASSETS_URI . 'images/header-sidebars-5.png',
			'values' => array(
				'header-sidebars' => 5,
				'header-sidebars-1-width' => 'one_fifth',
				'header-sidebars-1-last' => 0,
				'header-sidebars-2-width' => 'one_fifth',
				'header-sidebars-2-last' => 0,
				'header-sidebars-3-width' => 'one_fifth',
				'header-sidebars-3-last' => 0,
				'header-sidebars-4-width' => 'one_fifth',
				'header-sidebars-4-last' => 0,
				'header-sidebars-5-width' => 'one_fifth',
				'header-sidebars-5-last' => 1,
				'header-sidebars-6-width' => 'full',
				'header-sidebars-6-last' => 0,
			),
		),
	),
),

array(
	'name' => __('Advanced sidebars', 'wpv'),
	'type' => 'separator',
),

array(
	'text' => __('Please choose the number of widget areas and adjust each widget area\'s settings. You can adjust the width of the widget area from the sixth predefined and place them in one to six rows.', 'wpv'),
	'class' => 'sticked',
	'type' => 'info',
),

array(
	'id_prefix' => 'header-sidebars',
	'type' => 'horizontal_blocks',
	'min' => 0,
	'max' => 6,
),

	array(
		'type' => 'end'
	),	
	
//----


array(
	'name' => __('Footer Layout', 'wpv'),
	'type' => 'start',
	'sub' => 'Layout',
),

array(
	'name' => __('Basic sidebars', 'wpv'),
	'type' => 'separator',
),

array(
	'text' => __('The footer widget areas are placed after the body of the site. You can either choose one of the predefined layouts or configure your own in the "Advanced" section', 'wpv'),
	'class' => 'sticked',
	'type' => 'info',
),

array(
	'type' => 'autofill',
	'option_sets' => array(
		array(
			'name' => __('1/3 | 1/3 | 1/3', 'wpv'),
			'image' => WPV_ADMIN_ASSETS_URI . 'images/footer-sidebars-3.png',
			'values' => array(
				'footer-sidebars' => 3,
				'footer-sidebars-1-width' => 'one_third',
				'footer-sidebars-1-last' => 0,
				'footer-sidebars-2-width' => 'one_third',
				'footer-sidebars-2-last' => 0,
				'footer-sidebars-3-width' => 'one_third',
				'footer-sidebars-3-last' => 1,
				'footer-sidebars-4-width' => 'full',
				'footer-sidebars-4-last' => 0,
				'footer-sidebars-5-width' => 'full',
				'footer-sidebars-5-last' => 0,
				'footer-sidebars-6-width' => 'full',
				'footer-sidebars-6-last' => 0,
			),
		),
		array(
			'name' => __('1/4 | 1/4 | 1/4 | 1/4', 'wpv'),
			'image' => WPV_ADMIN_ASSETS_URI . 'images/footer-sidebars-4.png',
			'values' => array(
				'footer-sidebars' => 4,
				'footer-sidebars-1-width' => 'one_fourth',
				'footer-sidebars-1-last' => 0,
				'footer-sidebars-2-width' => 'one_fourth',
				'footer-sidebars-2-last' => 0,
				'footer-sidebars-3-width' => 'one_fourth',
				'footer-sidebars-3-last' => 0,
				'footer-sidebars-4-width' => 'one_fourth',
				'footer-sidebars-4-last' => 1,
				'footer-sidebars-5-width' => 'full',
				'footer-sidebars-5-last' => 0,
				'footer-sidebars-6-width' => 'full',
				'footer-sidebars-6-last' => 0,
			),
		),
		
		array(
			'name' => __('1/5 | 1/5 | 1/5 | 1/5 | 1/5', 'wpv'),
			'image' => WPV_ADMIN_ASSETS_URI . 'images/footer-sidebars-5.png',
			'values' => array(
				'footer-sidebars' => 5,
				'footer-sidebars-1-width' => 'one_fifth',
				'footer-sidebars-1-last' => 0,
				'footer-sidebars-2-width' => 'one_fifth',
				'footer-sidebars-2-last' => 0,
				'footer-sidebars-3-width' => 'one_fifth',
				'footer-sidebars-3-last' => 0,
				'footer-sidebars-4-width' => 'one_fifth',
				'footer-sidebars-4-last' => 0,
				'footer-sidebars-5-width' => 'one_fifth',
				'footer-sidebars-5-last' => 1,
				'footer-sidebars-6-width' => 'full',
				'footer-sidebars-6-last' => 0,
			),
		),
	),
),

array(
	'name' => __('Advanced sidebars', 'wpv'),
	'type' => 'separator',
),

array(
	'text' => __('Please choose the number of widget areas and adjust each widget area\'s settings. You can adjust the width of the widget area from the sixth predefined and place them in one to six rows.', 'wpv'),
	'class' => 'sticked',
	'type' => 'info',
),
		
array(
	'id_prefix' => 'footer-sidebars',
	'type' => 'horizontal_blocks',
	'min' => 0,
	'max' => 6,
),

	array(
		'type' => 'end'
	),
	
);