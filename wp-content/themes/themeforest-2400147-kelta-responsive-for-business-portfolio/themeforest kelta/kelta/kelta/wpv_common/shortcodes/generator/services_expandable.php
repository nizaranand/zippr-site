<?php
return array(
	'name' => __('Expandable services', 'wpv') ,
	'value' => 'services_expandable',
	'options' => array(
		array(
			'name' => __('Title', 'wpv') ,
			'id' => 'title',
			'default' => '',
			'type' => 'text',
		) ,
		array(
			'name' => __('Image', 'wpv') ,
			'id' => 'image',
			'default' => '',
			'type' => 'upload'
		) ,
		array(
			'name' => __('Content', 'wpv') ,
			'desc' => sprintf(__('Put <strong>%s</strong> where you want the open state to start', 'wpv'), '[split]'),
			'id' => 'content',
			'type' => 'textarea'
		) ,
		array(
			'name' => __('Background', 'wpv') ,
			'id' => 'background',
			'type' => 'select',
			'options' => array(
				'transparent' => __('Transparent', 'wpv'),
				'accent1' => __('Accent 1', 'wpv'),
				'accent2' => __('Accent 2', 'wpv'),
				'accent3' => __('Accent 3', 'wpv'),
				'accent4' => __('Accent 4', 'wpv'),
			),
		) ,
		array(
			'name' => __('Class', 'wpv') ,
			'id' => 'class',
			'type' => 'text'
		) ,		
	) ,
);
