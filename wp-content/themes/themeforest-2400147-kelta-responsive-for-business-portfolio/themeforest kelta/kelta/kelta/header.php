<?php
/**
* @package WordPress
* @subpackage zenden
*/
?><!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]> <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]> <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-ie no-js"> <!--<![endif]-->
	
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php wpv_title() ?></title> 
		
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="shortcut icon" type="image/x-icon" href="<?php wpvge('favicon_url')?>"/>
	
	<script>
		WPV_THEME_URI = '<?php echo WPV_THEME_URI ?>';  
	</script>
	
	<?php wp_head(); ?>
	<?php
		global $post;

		if(is_object($post) && get_post_meta($post->ID, 'use-global-options', true) === 'false') {
			$bgcolor = get_post_meta($post->ID, 'background-color', true);
			$bgimage = get_post_meta($post->ID, 'background-image', true);
			$bgrepeat = get_post_meta($post->ID, 'background-repeat', true);
			$bgattachment = get_post_meta($post->ID, 'background-attachment', true);
			$bgposition = get_post_meta($post->ID, 'background-position', true);
			
			$page_style = '';
			if(!empty($bgcolor)) {
				$page_style .= "background-color:$bgcolor;";
			}
			if(!empty($bgimage)) {
				$page_style .= "background-image:url('$bgimage');";
			}
			if(!empty($bgrepeat)) {
				$page_style .= "background-repeat:$bgrepeat;";
			}
			if(!empty($bgattachment)) {
				$page_style .= "background-attachment:$bgattachment;";
			}
			if(!empty($bgposition)) {
				$page_style .= "background-position:$bgposition;";
			}
			
			if(!empty($page_style) && (is_single() || is_page())) {
				echo "<style>body{ $page_style }</style>";
			} 
		}
	?>
	<style><?php
		include WPV_THEME_CSS_DIR.'variable-accents.php';
		if(stristr($_SERVER['HTTP_USER_AGENT'], "msie 8"))
			include WPV_THEME_CSS_DIR.'ie8.php';
	?></style>
</head>
	<?php
		global $wpv_has_header_sidebars;
	
		$has_header_slider = !is_404() && wpv_post_default('show_header_slider', 'has-header-slider');
		$wpv_has_header_sidebars = wpv_post_default('show_header_sidebars', 'has-header-sidebars');
		$has_page_header = is_singular(array('post', 'portfolio')) || (is_page() && wpv_post_default('show_page_header', 'has-page-header') || is_category() || is_archive() || is_search() || is_home());

		$bg_portfolio_cats = is_page() ? unserialize(get_post_meta($post->ID, 'bg-portfolio-categories', true)) : array();
			
		$body_class = array();

		$body_class[] = wpv_get_option('site-layout-type');
		
		$body_class_conditions = array(
			'no-page-header' => !$has_page_header,
			'has-page-header' => $has_page_header, 
			'cbox-share-twitter' => wpv_get_option('share-lightbox-twitter'),
			'cbox-share-facebook' => wpv_get_option('share-lightbox-facebook'),
			'cbox-share-gplus' => wpv_get_option('share-lightbox-gplus'),
			'cbox-share-pinterest' => wpv_get_option('share-lightbox-pinterest'),
			'fixed-header' => wpv_get_option('fixed-header'),
			'has-header-slider' => $has_header_slider,
			'has-header-sidebars' => $wpv_has_header_sidebars,
			'no-header-slider' => !$has_header_slider,
			'no-header-sidebars' => !$wpv_has_header_sidebars,
			'no-footer-sidebars' => !wpv_get_option('has-footer-sidebars'),
			'responsive-layout' => WPV_RESPONSIVE,
			'full-bg-slider' => !empty($bg_portfolio_cats),
			'fast-slider' => !empty($bg_portfolio_cats),
			'accent-override' => !wpv_get_option('disable_accent_override'),
		);
		
		foreach($body_class_conditions as $class=>$cond) {
			if($cond) {
				$body_class[] = $class;
			}
		}

		if(is_archive() || is_search() || get_query_var('format_filter'))
			define('WPV_ARCHIVE_TEMPLATE', true);
		
		$slider_style = '';
		$slider_effect = '';
	?>
<body <?php body_class(implode(' ', $body_class)); ?>>
	<?php do_action('wpv_body') ?>
	<div id="container" class="main-container">
		<div class="boxed-layout">
			<div class="page-dash-wrapper">
				
					<div class="fixed-header-box">
						<div class="top-nav-box clearfix <?php echo wpv_get_option('header-helper-style')?> <?php if(wpv_get_option('header-helper-classic')) echo 'classic'?>">
							<nav class="top-nav">
								<?php wp_nav_menu(array('fallback_cb' => '', 'theme_location' => 'menu-top' )); ?>
							</nav>
							<?php if(wpv_get_option('phone-num-top') != ''): ?>
							<div id="phone-num"><?php echo wpv_clean_do_shortcode(wpv_get_option('phone-num-top'))?></div>
							<?php endif ?> 
						</div>
						<header class="main-header header-helper <?php echo wpv_get_option('header-helper-style')?> <?php if(wpv_get_option('header-helper-classic')) echo 'classic'?>">
							<div class="limit-wrapper">
								<div class="header-overline"></div>
								<div class="header-inner">
									<div class="fl logo-wrapper">
									<?php $logo = wpv_get_option('custom-header-logo') ?>
										<a href="<?php echo home_url() ?>/" title="<?php bloginfo( 'name' ) ?>" class="logo a-reset"><?php 
											if($logo):
											?>
												<img src="<?php echo $logo;?>" alt="<?php bloginfo('name')?>"/>
											<?php
											else:
												bloginfo( 'name' );
											endif;
											?>
										</a>
									</div>
									<div class="main-menu fr">
										
										<nav>
											<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
											<a href="#content" title="<?php esc_attr_e( 'Skip to content', 'wpv' ); ?>" class="visuallyhidden"><?php _e( 'Skip to content', 'wpv' ); ?></a>
											<?php
												if(has_nav_menu('menu-header')) {
													wp_nav_menu(array('theme_location' => 'menu-header', 'walker' => new description_walker() ));
												} else {
													wp_page_menu();
												}
											?>
											<!--div class="search-extend fr">
												<?php get_search_form(); ?>
											</div-->
										</nav>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</header>

						<?php
							if(!empty($bg_portfolio_cats)):
								$query = array(
									'post_type' => 'portfolio',
									'orderby'=>'menu_order', 
									'order'=>'ASC',
									'posts_per_page' => -1,
									'tax_query' => array(
										array(
											'taxonomy' => 'portfolio_category',
											'field' => 'slug', 
											'terms' => $bg_portfolio_cats,
										)
									),
								);

								$posts = get_posts($query);
								$data = array();

								foreach($posts as $p) {
									$img = wp_get_attachment_image_src(get_post_thumbnail_id($p->ID), 'full');
									if(isset($img[3]))
										unset($img[3]);

									$gallery = array();

									if(get_post_meta($p->ID, 'portfolio_type', true) == 'gallery') {
										$image_ids = array_keys(get_children(array(
											'post_parent' => $p->ID,
											'post_status' => 'inherit',
											'post_type' => 'attachment',
											'post_mime_type' => 'image',
										)));

										array_unshift($image_ids, get_post_thumbnail_id());
										$image_ids = array_values(array_unique($image_ids));
										unset($image_ids[0]);

										foreach($image_ids as $image_id) {
											$sub_img = wp_get_attachment_image_src($image_id,'full');
											if(isset($sub_img[3]))
												unset($sub_img[3]);
											$gallery[] = $sub_img;
										}
									}

									$item = array(
										'href' => get_permalink($p->ID),
										'img' => $img,
										'title' => get_the_title($p->ID),
									);
									
									if (!empty($gallery)) {
										$item['img'] = array($item['img']);
										foreach($gallery as $galleryImage) {
											if ($galleryImage[0] != $item['img'][0][0]) {
												$item['img'][] = $galleryImage;
											}
										}
									}

									$data[] = $item;
								}

								wp_enqueue_script('front-wpvbgslider');

								?>
									<script>wpvBgSlides = <?php echo json_encode($data)?>;</script>
								</div> <!-- fixed-header-box -->
								</div> <!-- page-dash-wrapper -->
								</div> <!-- boxed-layout -->
								</div> <!-- #container -->
								
								<!-- Fullscreen slider controls -->
								<div class="fast-slider-caption"></div>
								<div class="fast-slider-navbar">
									<div class="fast-slider-prev button icon"><?php wpv_icon('angle-left')?></div>
									<div class="fast-slider-next button icon"><?php wpv_icon('angle-right')?></div>
									<?php
										$view_all = wpv_get_option('portfolio-all-items');
										if(!empty($view_all)):
									?>
										<a href="<?php echo $view_all?>" class="fast-slider-view-all button icon"><?php wpv_icon('grid-3')?></a>
									<?php endif ?>
									<div class="fast-slider-gall-next button icon"><?php wpv_icon('angle-bottom')?></div>
									<div class="fast-slider-gall-prev button icon"><?php wpv_icon('angle-top')?></div>
								</div>
								
								<?php wp_footer() ?>
								</body>
								</html>

								<?php
								exit;
							endif;
						?>

						<div id="sub-header">
							<?php
							/*
							 * some pages may not have a slider enabled, check for that
							 */
							if( $has_header_slider ):
								$slider_effect = wpv_post_default('slider-effect', 'header-slider-effect');
								$slider_style = get_slider_design($slider_effect);
								
								$visiblegrid = wpv_get_option('header-slider-visiblegrid') ? ' visiblegrid' : '';
							?>

								<?php
									$sub_header = wpv_get_option('sub-header');
									if(!empty($sub_header)):
								?>
									<div id="sub-header-inner">
										<?php echo apply_filters('the_content', $sub_header) ?>
									</div>
								<?php endif ?>
							
								<div class="clearfix">
									<div id="header-slider-container" class="<?php echo $visiblegrid?>">
										<div class="header-slider-wrapper style-<?php echo $slider_style ?> animation-<?php echo $slider_effect?> <?php echo $visiblegrid?> slider-helper <?php echo wpv_get_option('slider-helper-style')?> <?php if(wpv_get_option('slider-helper-classic')) echo 'classic'?>">
											<?php 
												if(in_array($slider_effect, array('apple'))) {
													get_template_part('slider', 'sequence');
												} else {
													get_template_part('slider', 'header');
												}
											?>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div><!-- / .fixed-header-box -->
					
					
					
					<div class="pane-wrapper clearfix">
						<!-- #main (do not remove this comment) -->
						<div id="main" role="main" class="body-helper <?php echo wpv_get_option('body-helper-style')?> <?php if(wpv_get_option('body-helper-classic')) echo 'classic'?>">
							<div class="limit-wrapper">
