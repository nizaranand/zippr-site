[raw]
<div class="services has-more <?php echo "$class $background"?>">
	<div class="closed services-inside">
		<div class="services-content-wrapper">
			<?php if (!empty($image)) wpv_lazy_load($image, '', array('class'=> (empty($before)?'aligncenter':'alignleft'))) ?>
[/raw]
			<?php echo wpv_clean_do_shortcode($before) ?>
[raw]
		</div>
	</div>
	<div class="open services-inside">
		<div class="row">
[/raw]
			<?php if($title != ''):?>
				<div class="services-inset mb">
					<h3><?php echo $title ?></h3>
				</div>
			<?php endif ?>
			<div class="services-content">
				<?php echo wpv_clean_do_shortcode($content)?>
			</div>
[raw]
		</div>
	</div>
</div>
[/raw]
