<?php
/**
 * multiple color inputs
 */
?>
<div class="wpv-config-row color-row top-desc">
	<p class="description"><?php echo $desc?></p>
	
	<div class="content clearfix">
		<?php foreach($inputs as $id=>$name): ?>
			<div class="single-color">
				<div>
					<input name="<?php echo $id ?>" id="<?php echo $id ?>" type="color" data-hex="true" value="<?php echo wpv_get_option($id, $default) ?>" class="<?php wpv_static($value)?>" />
				</div>
				<div class="single-desc"><?php echo $name ?></div>
			</div>
		<?php endforeach ?>
	</div>
</div>