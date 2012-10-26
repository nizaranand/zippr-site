<?php
global $wpv_sequence_shortcode_styles, $wpv_sequence_shortcode_effects;

$slides = array();

for($i=1; $i<=10; $i++) {
	$slides[] = array(
		'name' => sprintf(__('Slide %d', 'wpv') , $i) ,
		'id' => $i,
		'type' => 'slide'
	);
}

return array(
	"name" => __("Sliders", 'wpv') ,
	"value" => "slider",
	"options" => array_merge(array(
		array(
			"name" => __("Number of slides", 'wpv') ,
			"id" => "number",
			"min" => 1,
			"max" => 10,
			"step" => 1,
			"default" => 2,
			"type" => "range"
		) ,
		array(
			"name" => __("Width", 'wpv') ,
			"desc" => __("Set it to 0 for maximum width", 'wpv') ,
			"id" => "width",
			"min" => 0,
			"max" => 1200,
			"default" => 0,
			"type" => "range"
		) ,
		array(
			"name" => __("Height", 'wpv') ,
			"id" => "height",
			"min" => 0,
			"max" => 1200,
			"default" => 225,
			"type" => "range"
		) ,
		array(
			"name" => __("Effect", 'wpv') ,
			"id" => "effect",
			"default" => "random",
			"type" => "select",
			'options' => $wpv_sequence_shortcode_effects,
		) ,
		array(
			"name" => __("Pause time", 'wpv') ,
			"id" => "pausetime",
			"min" => 0,
			"max" => 60000,
			"default" => 5000,
			"type" => "range"
		) ,
		array(
			"name" => __("Slider style", 'wpv') ,
			"id" => "style",
			"default" => 'gallery',
			"type" => "select",
			'options' => $wpv_sequence_shortcode_styles
		) ,
		array(
			"name" => __("Annotation", 'wpv') ,
			'desc' => __('Some styles may use this instead of or in addition to a caption', 'wpv'),
			"id" => "annotation",
			"type" => "textarea"
		) ,
	), $slides) ,
);
