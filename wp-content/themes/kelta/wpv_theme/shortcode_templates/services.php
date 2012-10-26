[raw]
<?php $has_image = !empty($icon) ?>
<div class="services <?php echo $has_image?'has-image':'no-image'?> clearfix <?php echo $class?>" style="text-align:<?php echo $text_align?>;">
	<div class="services-inside">
		<?php if($has_image): ?>
			<div class="thumbnail">
				<?php if($no_button != 'true' && !empty($button_link)): ?>
					<a href="<?php echo $button_link?>" title="<?php echo $title ?>"><?php wpv_lazy_load($icon, '') ?></a>
				<?php else: ?>
					<?php wpv_lazy_load($icon, '') ?>
				<?php endif ?>
			</div>
		<?php endif ?>
		<?php if($title != ''):?>
			<h4>
				<?php if($no_button != 'true' && !empty($button_link)): ?>
					<a href="<?php echo $button_link?>" title="<?php echo $title ?>"><?php echo $title?></a>
				<?php else: ?>
					<?php echo $title ?>
				<?php endif ?>
			</h4>
		<?php endif ?>
	</div>
	<div class="services-content">
		<?php echo wpv_clean_do_shortcode($content)?>
	</div>
	
	<?php if($button_text != ''): ?>
	<div class="services-inside">
		<a class="clearboth button" href="<?php echo $button_link?>"><span><?php echo $button_text?></span></a>
	</div>
	<?php endif ?>
</div>
[/raw] 