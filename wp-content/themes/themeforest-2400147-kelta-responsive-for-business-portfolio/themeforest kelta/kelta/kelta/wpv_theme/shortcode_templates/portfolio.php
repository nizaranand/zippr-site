<?php

if ($sortable != 'false'):
	$nopaging = 'true';
?>
	<section class="portfolios sortable">
		<nav class="sort_by_cat">
			<span><?php _e('Show:', 'wpv') ?></span>
			<span>
				<span class="cat"><a data-value="all" href="#" class="active"><?php _e('All', 'wpv')?></a></span>
				<?php
					// show the categories present in this listing
					$terms = array();
					if ($cat != '' && $cat != 'null') {
						foreach(explode(',', $cat) as $term_slug) {
							$terms[] = get_term_by('slug', $term_slug, 'portfolio_category');
						}
					} else {
						$terms = get_terms('portfolio_category', 'hide_empty=1');
					}
				?>
				<?php foreach($terms as $term): ?>
						 <span class="cat"><a data-value="<?php echo $term->slug?>" href="#"><?php echo $term->name?></a></span>
				<?php endforeach ?>
			</span>
		</nav>	
		<div class="clearboth"></div>
<?php else: ?>
	<section class="portfolios">
<?php endif ?>

		<ul class="inner_<?php echo $width?> portfolio_<?php echo $column_class?> clearfix" data-columns="<?php echo $column ?>">
		<?php
		
			// get the portfolio items
			
			$query = array(
				'post_type' => 'portfolio',
				'orderby'=>'menu_order', 
				'order'=>'ASC',
				'posts_per_page' => $max,
			);
			
			if(!empty($cat)) {
				$query['tax_query'] = array(
					array(
						'taxonomy' => 'portfolio_category',
						'field' => 'slug', 
						'terms' => explode(',', $cat),
					)
				);
			}
			
			if ($nopaging == 'false') {
				$query['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
			} else {
				$query['paged'] = 1;
			}
			
			if($ids && $ids != 'null') {
				$query['post__in'] = explode(',',$ids);
			}
			
			query_posts($query);
	
	if($column == 1) {
		global $more;
		$more = 0;
	}

	if($column)
	
	$i = 0;
	while(have_posts()): the_post(); $i++;
		
		$terms = get_the_terms(get_the_id(), 'portfolio_category');
		$terms_slug = $terms_name = array();
		if (is_array($terms)) {
			foreach($terms as $term) {
				$terms_slug[] = $term->slug;
				$terms_name[] = $term->name;
			}
		}
		
		$last = $clear = '';
		if($i % $column == 0) {
			$last = 'last';
		}
		if (($i-1) % $column == 0) {
			$clear = ' clearboth';
		}
		?>
		
		<li data-id="<?php the_id()?>" data-type="<?php echo implode(',', $terms_slug)?>" class="<?php echo $last.$clear?> grid-1-<?php echo $column ?> nomargin">
		
		<?php
		if (has_post_thumbnail()):
			extract(wpv_get_portfolio_options($group, $rel_group));
?>
			<div class="portfolio_image">
				<div class="thumbnail" style="max-height:<?php echo $size[1]?>px">
					<?php if($type=='gallery'): ?>
						<?php echo do_shortcode('[gallery style="gallery featured" raw="false" height="'.$size[1].'" width="'.$size[0].'"]');?>
					<?php else: ?>
						<a class="<?php echo $lightbox?> thumbnail-url <?php echo $type?>" <?php if(isset($link_target)) echo 'target="'.$link_target.'"'; ?> href="<?php echo $href?>" <?php echo $rel.$width.$height.$iframe?>>
							<?php
								wpv_lazy_load( wpv_resize_image($image[0], $size[0], $size[1]), get_the_title(), array(
									'width' => (int)$size[0],
									'height' => (int)$size[1],
									'class' => 'desaturate',
								));
							?>
						</a>
					<?php endif ?>
				</div><!-- / .thumbnail -->
			</div>
	<?php endif ?>

		<div class="portfolio_details project-info-pad folio">
			<?php if($title == 'true'): ?>
				<h4>
					<?php if($more == 'true'): ?>
						<a href="<?php echo ($type=='link' ? $href : get_permalink()) ?>" target="<?php echo $link_target?>">
					<?php endif ?>

					<?php the_title()?>

					<?php if($more == 'true'): ?>
						</a>
					<?php endif ?>
				</h4>
			<?php endif ?>
			<?php if(sizeof($terms_name) > 0):?>
				<em class="category"><?php echo implode(', ', $terms_name)?></em>
			<?php endif ?>
		</div>
			
	</li>
		
	<?php endwhile ?>
	
	</ul>
	<?php if ($nopaging == 'false' && function_exists('wpv_load_more'))	wpv_load_more(); ?>
</section>
<?php wp_reset_query(); ?>
