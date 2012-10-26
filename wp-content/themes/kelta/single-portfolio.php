<?php
/**
 * @package WordPress
 * @subpackage studium
 */

if(!wpv_is_reduced_response()):

get_header(); ?>

<?php $page_header_placed = wpv_get_header_sidebars() ?>

<div class="pane main-pane">
	<div class="row">
		<?php wpv_page_header($page_header_placed) ?>

		<div class="page-outer-wrapper">
			<div class="clearfix page-wrapper">
<?php endif; // reduced response ?>
				<?php wpv_has_left_sidebar() ?>
					
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
					<?php $rel_group = 'portfolio_'.get_the_ID() ?>
					<?php extract(wpv_get_portfolio_options('true', $rel_group)) ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(wpv_get_layout().' '.$type); ?>>
						<?php
							$column_width = wpv_get_central_column_width();
							$size = ($type == 'gallery') ? intval(0.97/3*2 * $column_width) : $column_width;
						?>
						
						<div class="row">
						<?php if($type != 'document'): ?>
							<div class="portfolio_image_wrapper<?php echo ($type == 'gallery' ? " two_thirds first" : " fullwidth-folio");?>">
								<?php if($type != 'video'): ?>
								<?php wpv_lazy_load( $image[0], get_the_title(), array(
											'width' => $size,
											'height' => 0,
										)); ?>
								<?php else: ?>
									<?php wpv_post_video($size, null, $href) ?>
								<?php endif ?>
							</div>
						<?php endif ?>
						
						<?php if($type == 'gallery'): ?>
							<div class="portfolio_details project-info-pad folio single one_third last">
							<?php
								$image_ids = array_keys(get_children(array(
									'post_parent' => get_the_id(),
									'post_status' => 'inherit',
									'post_type' => 'attachment',
									'post_mime_type' => 'image',
								)));

								array_unshift($image_ids, get_post_thumbnail_id());
								$image_ids = array_values(array_unique($image_ids));

								$right_column_width = $column_width-$size;

								// these two set the gallery thumbnails' size and count
								$per_line = 3;
								$between = 0.03*$right_column_width;

								$small_size = intval(($right_column_width - $between*($per_line-1))/ $per_line);

								foreach($image_ids as $num=>$image_id):
									$image = wp_get_attachment_image_src($image_id,'full');
									$image = $image[0];
									$image_link = wpv_resize_image($image, $size, 0);

									?>
										<?php if($num%$per_line == 0) echo '<div class="row">';?>
										<div class="<?php echo "grid-1-$per_line".( ($num+1)%$per_line == 0 ? ' last':'') ?>">
											<a class="portfolio-small lightbox thumbnail" href="<?php echo $image_link ?>" <?php echo $rel?>>
												<?php
													wpv_lazy_load( wpv_resize_image($image, $small_size, $small_size), get_the_title(), array(
														'width' => $small_size,
														'height' => $small_size,
													));
												?>
											</a>
										</div>
										<?php if(($num+1)%$per_line == 0) echo '</div>';?>
								<?php endforeach ?>
							</div>
						<?php endif; ?>
						</div>
						<div class="clearfix"></div>
						
						<div class="page-content portfolio-text-content">
							<?php the_content()?>
						</div>
						
						<div class="clearboth">
							<?php comments_template(); ?>
						</div>
					</article>
					
				<?php endwhile ?>
				
				<?php wpv_has_right_sidebar() ?>
<?php if(!wpv_is_reduced_response()): ?>
			</div>
			<?php wpv_share('portfolio'); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
<?php else: wpv_reduced_footer(); ?>
<?php endif; // reduced ?>