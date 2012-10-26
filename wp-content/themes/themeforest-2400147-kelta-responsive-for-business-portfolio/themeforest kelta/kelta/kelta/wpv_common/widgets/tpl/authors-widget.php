<?php

if($count > 0):

echo $before_widget;

if($title)
	echo $before_title . $title . $after_title;
?>

<ul class="authors_list">
	<?php
		for($i=1; $i<=$count; $i++)
			if(isset($instance['author_id'][$i])):
				$id = $instance['author_id'][$i];
				?>
				<li class="clearfix">
					<div class="gravatar">
						<a href="<?php echo get_author_posts_url( $id, get_the_author_meta('user_nicename', $id) )?>">
							<?php echo get_avatar( get_the_author_meta('user_email', $id), '60' ) ?>
						</a>
					</div>
					<div class="author_info">
						<div class="author_name">
							<h3>
								<a href="<?php echo get_author_posts_url( $id, get_the_author_meta('user_nicename', $id) )?>">
									<?php echo get_the_author_meta('user_nicename', $id) ?>
								</a>
							</h3>						
						</div>
						<div class="author_desc">
							<?php 
								if(!empty($instance['author_desc'][$i]))
									echo $instance['author_desc'][$i];
								else
									echo get_the_author_meta('description',$id);
							?>
						</div>
					</div>
				</li>
			<?php endif; ?>
</ul>

<?php

echo $after_widget;

endif;