<div class="team-member">
	<?php if(!empty($picture)): ?>
	<div class="thumbnail">
		<?php if(!empty($url)):?>
			<a href="<?php echo $url ?>" title="<?php echo $name?>">
		<?php endif ?>
			<?php wpv_lazy_load($picture, $name, array(
				'class' => 'desaturate',
			))?>
		<?php if(!empty($url)):?>
			</a>
		<?php endif ?>
	</div>
	<?php endif ?>
	<div class="team-member-info">
		<h4>
			<?php if(!empty($url)):?>
				<a href="<?php echo $url ?>" title="<?php echo $name?>">
			<?php endif ?>
				<?php echo $name?>
			<?php if(!empty($url)):?>
				</a>
			<?php endif ?>
		</h4>
		<?php if(!empty($position)): ?>
			<em><?php echo $position ?></em>
		<?php endif ?>
		<?php if(!empty($content)): ?>
			<div class="description"><?php echo trim(do_shortcode($content)) ?></div>
		<?php endif ?>
		<?php if($url_is_email):?>
			<div><a href="<?php echo $url ?>" title="<?php printf(__('email %s', 'wpv'), $name)?>"><?php echo do_shortcode('[icon style="mail" color="black"]'.preg_replace('/^mailto:/', '', $url).'[/icon]')?></a></div>
		<?php endif ?>
		<?php if(!empty($phone)):?>
			<div><?php echo do_shortcode('[icon style="phone" color="black"]'.$phone.'[/icon]')?></div>
		<?php endif ?>
	</div>
</div>
