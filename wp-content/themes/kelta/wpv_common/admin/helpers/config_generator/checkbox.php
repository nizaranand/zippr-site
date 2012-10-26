<?php
/**
 * single checkbox
 */

$option = $value;
$value = wpv_get_option($id, $default);
if($value == 'true')
	$value = true;
elseif($value == 'false')
	$value = false;
?>

<div class="wpv-config-row <?php if(isset($class)) echo $class?>">
	
	<p class="description"><?php echo $desc?></p>
	
	<div class="content clearfix">
		<label>
			<input type="checkbox" name="<?php echo $id?>" id="<?php echo $id?>" value="true" class="<?php wpv_static($option)?>" <?php checked($value, true) ?> />
			<?php echo $name ?>
		</label>
	</div>
</div>
