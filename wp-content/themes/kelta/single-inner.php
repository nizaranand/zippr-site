<?php
	extract(wpv_post_info());
	$format = get_post_format();
    $format = empty($format)? 'standard' : $format;

	if(defined('WPV_ARCHIVE_TEMPLATE'))
		$fullpost = false;

	list($has_media, $the_media) = wpv_post_media();
	$the_media = trim($the_media);
?>


<div class="post-article <?php echo $has_media?>-wrapper">
	<div class="<?php echo $format?>-post-format clearfix">
		
		<?php if(!is_single()) po_post_header($meta, $news); ?>
		<?php if (!empty($the_media)):?>
			<div class="post-media">
				<?php echo $the_media; ?>
			</div>
		<?php endif ?>
		<div class="post-content-outer <?php echo $has_media ?>">
			<?php
			$format = get_post_format();
			$format = empty($format)? 'standard' : $format;
			$content = get_the_content();
			if(!empty($content)):
			?>
			
				<div class="post-content the-content">
					<?php if(has_post_format('quote')): ?>
						<blockquote>
							<div>
								<?php the_content() ?>
								<div class="cite">
									<?php
										$post_link = get_post_meta(get_the_id(), 'post-link', true);
										$quote_author = get_post_meta(get_the_id(), 'quote-author', true);
									?>
									<a href="<?php echo $post_link?>" title="<?php echo $quote_author?>"><?php echo $quote_author?></a>
								</div>
							</div>
						</blockquote>
						<?php if(is_single()): ?>
							<?php
								wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpv' ), 'after' => '</div>' ) );
							?>
						<?php endif ?>
					<?php else: ?>
						<?php
							if(is_single()) {
								the_content();
								wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpv' ), 'after' => '</div>' ) );
							} else {
								if(!!$fullpost) {
									global $more;
									$more = 0;
									the_content(__("Read More", 'wpv'),false);
								} else {
									the_excerpt();
								}
							}
						?>
					
					<?php endif ?>

					<?php if(is_single()) wpv_share('post'); ?>
					
					<div class="sep-2"></div>
				</div>
			<?php elseif(is_single()):?>
				<?php wpv_share('post'); ?>
				<div class="sep-2"></div>
			<?php endif ?>
			
			

			<footer class="entry-utility">
			
				<?php if($meta && !wpv_get_option('hide-post-author')): ?>
					<span class="author icon-b bg" data-icon="<?php wpv_icon('user') ?>"><b><?php the_author_posts_link()?></b></span>
				<?php endif ?>

				<?php if($meta && wpv_get_option('meta_posted_on')): ?>
					<span class="date bg"><?php the_date() ?></span>
				<?php endif ?>
				
				<?php if($meta && wpv_get_option('meta_posted_in')): ?>
					<span class="visuallyhidden"><?php _e('Categories:', 'wpv')?></span> <span class="icon-b post-cats bg" data-icon="<?php wpv_icon('category') ?>"><?php the_category(', '); ?></span>
					<?php the_tags('<span class="the-tags bg"><span class="visuallyhidden">'.__('Tags:', 'wpv').'</span><span class="icon-b" data-icon="'.wpv_get_icon('tag').'">',', ','</span></span>')?>
				<?php endif ?>
				
				<?php if(wpv_get_option('meta_comment_count')): ?>
					<span class="comments-link bg"><?php comments_popup_link('0 comments', '1 comment', '% comments'); ?></span>
				<?php endif ?>
					
				<a class="post-format-pad bg" href="<?php echo add_query_arg( 'format_filter',$format,home_url()) ?>"><span class="icon"><?php echo wpv_get_post_format_icon($format) ?></span></a>
				
				<?php edit_post_link(__('Edit', 'wpv'), '<span class="bg">', '</span>') ?>
			</footer>
			
		</div>
	</div>
	
</div>
