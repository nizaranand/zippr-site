<?php
	wp_enqueue_script('front-jquery.sequence');

	global $post;

	$slider_effect = wpv_post_default('slider-effect', 'header-slider-effect');
	$slider_style = get_slider_design($slider_effect);

	$layout = wpv_get_option('site-layout-type');
	$max_width = 0;
	if($layout == 'boxed has-left-column') {
		$max_width = 900;
	} elseif($layout == 'boxed no-left-column') {
		$max_width = 1080;
	}

	$height = wpv_get_option('header-slider-height');

	// nasty way to fix a minor Safari/Win css transition problem
	$is_winsafari = preg_match('/safari/i', $_SERVER['HTTP_USER_AGENT']) &&
					preg_match('/windows/i', $_SERVER['HTTP_USER_AGENT']) &&
					!preg_match('/chrome/i', $_SERVER['HTTP_USER_AGENT']);
?> 

<div id="header-slider" class="sequence" style="height:<?php echo $height ?>px" data-height="<?php echo $height ?>" data-width="<?php echo $max_width ?>">
	<nav>
		<span class="prev button icon <?php echo $is_winsafari ? 'invisible' : ''?>" title="<?php _e('Previous', 'wpv') ?>"><?php wpv_icon('angle-left') ?></span>
		<span class="next button icon" title="<?php _e('Next', 'wpv') ?>"><?php wpv_icon('angle-right') ?></span>
	</nav>
	<ul>
	<?php
		$query = array(
			'post_type' => 'slideshow',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'menu_order',
		);
		
		global $post;
		$cat = wpv_post_default('slider-category' , 'default-header-slider');
		$query['tax_query'] = array(
			array(
				'taxonomy' => 'slideshow_category',
				'field' => 'slug',
				'terms' => $cat,
				'operator' => 'IN',
			)
		);
		
		query_posts($query);
		while(have_posts()): the_post();
			
				$thumbnail_id = get_post_thumbnail_id();
				$html_slide = $thumbnail_id <= 0;

				$image = '';
				if(!$html_slide) {
					$image = wp_get_attachment_image_src( $thumbnail_id, 'full');
					$slide_width = $image[1];
					$slide_height = $image[2];
					$image = $image[0];
				}

				$background = get_post_meta(get_the_id(), 'background', true);
				
				$style = '';
				if(!empty($background)) {
					$style .= "background:$background; ";
				}
				
				$style = "style='$style'";
	?>
		<li class="slide <?php echo $html_slide ? 'no-image' : 'has-image'?>" <?php echo $style?>>
	<?php
				$content = apply_filters('the_content', get_post_meta(get_the_id(), 'first-caption', true));
				if(!$html_slide):
	?>
					<div class="image crop">
						<img src="<?php echo $image?>"/>
					</div>
					<div class="content">
						<div class="limit-wrapper">
							<?php echo $content ?>
							<?php echo apply_filters('the_content', get_post_meta(get_the_id(), 'second-caption', true)) ?>
							<?php echo apply_filters('the_content', get_post_meta(get_the_id(), 'third-caption', true)) ?>
						</div>
					</div>
				<?php else: ?>
					<div class="content">
						<?php if($thumbnail_id == -2): ?>
							<?php
								$type = get_post_meta(get_the_id(), 'slide-type', true);
								$image1 = get_post_meta(get_the_id(), 'image1', true);
								$image2 = get_post_meta(get_the_id(), 'image2', true);
								$image3 = get_post_meta(get_the_id(), 'image3', true);
							?>
							<div class="slide<?php echo $type?>">
								<?php echo $content ?>
							</div>
							<img src="<?php echo $image1?>" class="slide<?php echo $type?>-image" />
							<?php if(!empty($image2)): ?>
								<img src="<?php echo $image2?>" class="slide<?php echo $type?>-image2" />
								<img src="<?php echo $image3?>" class="slide<?php echo $type?>-image3" />
							<?php endif ?>
						<?php else: ?>
							<?php echo $content ?>
						<?php endif ?>
					</div>
				<?php endif ?>				
		</li>
	<?php
		endwhile;
		wp_reset_query();
	?>
	</ul>
</div>

<?php $direction = wpv_get_option('header-slider-direction') ?>
<script>
	jQuery(function($) {
	    $('#header-slider').sequence({
	    	autoPlay: '<?php echo in_array($direction, array('right', 'left')) ?>',
	    	autoPlayDirection: '<?php echo $direction==='right' ? 1 : -1?>',
        	autoPlayDelay: <?php wpvge('header-slider-pausetime')?>,
        	nextButton: '#header-slider .next',
        	prevButton: '#header-slider .prev',
        	delayDuringOutInTransitions: false,
        	keysNavigate: true,
        	preloader: false
	    });
	});
</script>
