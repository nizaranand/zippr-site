<?php
/**
 * combobox
 */
?>

<?php
	if (isset($target)) {
		if (isset($options))
			$options = $options + Config_Generator::get_select_target_config($target);
		else
			$options = Config_Generator::get_select_target_config($target);
	}

	$selected = wpv_get_option($id, $default);
?>

<div class="wpv-config-row <?php echo $class?>">
	
	<h4><label for="<?php echo $id?>"><?php echo $name?></label></h4>
	
	<p class="description"><?php echo $desc?></p>
	
	<div class="content">
		<select name="<?php echo $id?>" id="<?php echo $id?>" class="<?php wpv_static($value)?>">
		
			<?php if(isset($prompt)): ?>
				<option value=""><?php echo $prompt?></option>
			<?php endif ?>
			
			<?php foreach($options as $key => $option): ?>
				<option value="<?php echo $key?>" <?php selected($selected, $key) ?>><?php echo $option?></option>
			<?php endforeach ?>
		
			<?php if (isset($page)): ?>
				<?php 
				$args = array(
					'depth' => $page,
					'child_of' => 0,
					'selected' => $selected,
					'echo' => 1,
					'name' => 'page_id',
					'id' => '',
					'show_option_none' => '',
					'show_option_no_change' => '',
					'option_none_value' => ''
				);
				$pages = get_pages($args);
			
				echo walk_page_dropdown_tree($pages,$depth,$args);
				?>
			<?php endif ?>
		
		</select>
		<br />
	
	</div>
</div>
