<?php

function wpv_shortcode_gallery( $output, $attr ) {
	global $post;

	static $cleaner_gallery_instance = 0;
	$cleaner_gallery_instance++;

	/* We're not worried abut galleries in feeds, so just return the output here. */
	if ( is_feed() )
		return $output;

	/* Orderby. */
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
	/* Default gallery settings. */
	$defaults = array(
		'order' => 'ASC',
		'orderby' => 'menu_order',
		'id' => $post->ID,
		'link' => '',
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'caption' => 'false',
		'size' => 'thumbnail',
		'include' => '',
		'exclude' => '',
		'numberposts' => -1,
		'offset' => '',
		'style' => 'gallery',
		'pausetime' => 3000,
		'raw' => 'true',
		'height' => false,
		'width' => false,
		'direction' => 'none',
	);
	
	/* Merge the defaults with user input. Make sure $id is an integer. */
	$attr = shortcode_atts( $defaults, $attr );
	extract( $attr );
	$id = intval( $id );

	/* Arguments for get_children(). */
	$children = array(
		'post_parent' => $id,
		'post_status' => 'inherit',
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'order' => $order,
		'orderby' => $orderby,
		'exclude' => $exclude,
		'include' => $include,
		'numberposts' => $numberposts,
		'offset' => $offset,
	);

	/* Get image attachments. If none, return. */
	$attachments = array_keys(get_children($children));
	array_unshift($attachments, get_post_thumbnail_id());
	$attachments = array_unique($attachments);

	if ( empty( $attachments ) )
		return '';

	$i = 0;

	/* Count the number of attachments returned. */
	$attachment_count = count( $attachments );

	ob_start();

	$width = $attr['width'] ? $attr['width'] : wpv_get_central_column_width();
	$height = $attr['height'] ? $attr['height'] : wpv_get_option('fullimage-height');

	echo '[slider width="'.$width.'" height="'.$height.'" style="'.$attr['style'].'" pausetime="'.$attr['pausetime'].'" effect="fade" direction="'.$attr['direction'].'"]';

	/* Loop through each attachment. */
	foreach ( $attachments as $id ) {

		$image = wp_get_attachment_image_src($id, 'full');

		if(!empty($image))
			echo ' [slide img="'.wpv_resize_image($image[0], $width, $height).'"] ';

		// links ?
	}

	echo '[/slider]'; //close shortcode

	if($attr['raw'] == 'true') {
		return '[raw]'.wpv_clean_do_shortcode(ob_get_clean()).'[/raw]';
	} else {
		return wpv_clean_do_shortcode(ob_get_clean());
	}
}
/* Filter the post gallery shortcode output. */
add_filter( 'post_gallery', 'wpv_shortcode_gallery', 10, 2 );