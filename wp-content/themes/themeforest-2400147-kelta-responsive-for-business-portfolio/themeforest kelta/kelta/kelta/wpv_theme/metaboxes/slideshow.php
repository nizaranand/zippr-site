<?php

if(!isset($htmlslide))
	$htmlslide = false;

return apply_filters('wpv_slide_metabox', array(
array(
	'name' => __('Captions', 'wpv'),
	'type' => 'separator',
),

array(
	'name' => __('Type', 'wpv'),
	'id' => 'slide-type',
	'type' => 'select',
	'class' => $thumbnail_id != -2 ? 'hidden':'no-desc',
	'options' => array(
		1 => __('text and image', 'wpv'),
		2 => __('image and text', 'wpv'),
		3 => __('text and three images', 'wpv'),
	),
),

array(
	'name' => $htmlslide ? __('Slide content', 'wpv') : __('First caption', 'wpv'),
	'id' => 'first-caption',
	'type' => 'textarea',
	'class' => 'no-desc',
	'rows' => $htmlslide ? 15 : 3,
),

array(
	'name' => __('Second caption', 'wpv'),
	'id' => 'second-caption',
	'type' => 'textarea',
	'rows' => 3,
	'class' => $htmlslide ? 'hidden':'no-desc',
),
array(
	'name' => __('Third caption', 'wpv'),
	'id' => 'third-caption',
	'type' => 'textarea',
	'rows' => 3,
	'class' => $htmlslide ? 'hidden':'no-desc',
),
array(
	'name' => __('First image', 'wpv'),
	'id' => 'image1',
	'type' => 'upload',
	'class' => $thumbnail_id != -2 ? 'hidden':'no-desc',
),
array(
	'name' => __('Second image', 'wpv'),
	'id' => 'image2',
	'type' => 'upload',
	'class' => $thumbnail_id != -2 ? 'hidden':'no-desc',
),
array(
	'name' => __('Third image', 'wpv'),
	'id' => 'image3',
	'type' => 'upload',
	'class' => $thumbnail_id != -2 ? 'hidden':'no-desc',
),

));