<?php

// display full post/image or thumbs
if(!isset($called_from_shortcode)) {
	$image = $meta = 'true';
	$full = 'true';
	$nopaging = 'false';
	$img_style = 'full';
	$width = 'full';
	$news = 'false';
	$split = 1;
}

$img_style = $img_style.'image';

global $wpv_loop_vars;
$old_wpv_loop_vars = $wpv_loop_vars;
$wpv_loop_vars = array(
	'image' => $image,
	'meta' => $meta,
	'fullpost' => $full,
	'img_style' => $img_style,
	'width' => $width,
	'news' => $news,
);

?>
<div class="loop-wrapper clearfix <?php if($news=='true') echo 'news'?> force-full-width">
<?php
$i = 0;
if(have_posts()) while(have_posts()): the_post();

?>
	<div class="page-content post-head clearfix <?php echo get_post_type()?>">
		<div>
			<?php get_template_part('single', 'inner');	?>
		</div>
	</div>
<?php
	$i++;
endwhile;
?>
</div>

<?php $wpv_loop_vars = $old_wpv_loop_vars; ?>
<?php if($nopaging != 'true' && function_exists('wpv_load_more') && $news!='true') wpv_load_more() ?>
