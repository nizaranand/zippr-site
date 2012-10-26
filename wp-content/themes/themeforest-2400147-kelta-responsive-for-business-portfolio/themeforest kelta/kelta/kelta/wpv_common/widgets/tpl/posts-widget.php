<?php

echo $before_widget;
if ($title)
	echo $before_title . $title . $after_title;

$class = ($disable_thumbnail && $desc_length==0) ? 'compact' : '';

ob_start();

echo count($orderby)>1 ? '[tabs style="clean" delay="0" vertical="false"] ' : '';

foreach($orderby as $current_order):
	echo count($orderby)>1 ? ' [tab title="'.$this->get_section_title($current_order).'" class="'.$current_order.'"] ' : '';

	if($current_order == 'comments'):
		$comments = get_comments(array(
			'status' => 'approve',
			'number' => $number,
		));

		?>
		<ul class="posts_list clearfix <?php echo $class?>">
			<?php
			foreach($comments as $i=>$c):
				$post = get_post($c->comment_post_ID);
			?>
				<li>
					<div class="clearfix">
						<?php if (!$disable_thumbnail): ?>
							<a class="thumbnail" href="<?php echo $c->comment_author_url ?>" title="<?php echo $c->comment_author ?>" rel="nofollow"><?php echo get_avatar($c->comment_author_email, $img_size, null, $c->comment_author) ?></a>
						<?php endif; ?>
						<div class="post_extra_info <?php if($disable_thumbnail) echo 'nothumb'?>">
							<h6 class="title">
								<a href="<?php echo $c->comment_author_url ?>" rel="nofollow"><?php echo $c->comment_author ?></a> <?php _e('on', 'wpv') ?> <a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_title ?></a>
							</h6>
							<em class="date"><?php echo date(get_option('date_format'), strtotime($c->comment_date))?></em>
						</div>
					</div>
					<?php if($i < count($comments)-1): ?>
						<div class="sep"></div>
					<?php endif ?>
				</li>
			<?php endforeach ?>
		</ul>
		<?php
	elseif($current_order == 'tweets'):
		echo '[twitter username="'.$twitter_user.'" query="'.$twitter_query.'" count="'.$number.'" avatarsize="'.($disable_thumbnail ? 0 : $img_size).'"]';
	elseif($current_order == 'tags'):
		echo '<div class="tagcloud">[raw]';
		wp_tag_cloud( apply_filters('widget_tag_cloud_args', array('taxonomy' => $tag_taxonomy) ) );
		echo '[/raw]</div>';
	else:
		$query = array(
			'showposts' => $number,
			'nopaging' => 0,
			'orderby' => $current_order,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
		);

		if(!empty($instance['cat']))
			$query['cat'] = implode(',', $instance['cat']);
			
		$r = new WP_Query($query);
		$i = 0;
		if ($r->have_posts()):
		?>
			<ul class="posts_list clearfix <?php echo $class?>">
				<?php while ($r->have_posts()):	$r->the_post(); ?>
					<li>
						<div class="clearfix">
							<?php if (!$disable_thumbnail && has_post_thumbnail()): ?>
								<a class="thumbnail" href="<?php echo get_permalink() ?>" title="<?php the_title(); ?>"><?php
										the_post_thumbnail(array($img_size, $img_size) , array(
											'title' => get_the_title() ,
											'alt' => get_the_title()
										));
								?></a>
							<?php endif; ?>
							<div class="post_extra_info <?php if($disable_thumbnail || !has_post_thumbnail()) echo 'nothumb'?>">
								<h6 class="title">
									<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title() ?></a>
								</h6>
								<em class="date"><?php the_date() ?></em>
							</div>
						</div>
						<?php if($i++ < $r->post_count-1): ?>
							<div class="sep"></div>
						<?php endif ?>
					</li>
				<?php endwhile; ?>
			</ul>

		<?php endif;

		wp_reset_query();
	endif;

	echo count($orderby)>1 ? ' [/tab] ' : '';

endforeach;

echo count($orderby)>1 ? '[/tabs]' : '';

echo wpv_clean_raw(do_shortcode(ob_get_clean()));

echo $after_widget; ?>
