<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wpv'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('sortby'); ?>"><?php _e('Sort by:', 'wpv'); ?></label>
	<select name="<?php echo $this->get_field_name('sortby'); ?>" id="<?php echo $this->get_field_id('sortby'); ?>" class="widefat">
		<option value="menu_order"<?php selected($instance['sortby'], 'menu_order'); ?>><?php _e('Page order', 'wpv'); ?></option>
		<option value="post_title"<?php selected($instance['sortby'], 'post_title'); ?>><?php _e('Page title', 'wpv'); ?></option>
		<option value="ID"<?php selected($instance['sortby'], 'ID'); ?>><?php _e('Page ID', 'wpv'); ?></option>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e('Exclude:', 'wpv'); ?></label>
	<input type="text" value="<?php echo $exclude; ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" />
	
	<small><?php _e('Page IDs, separated by commas.', 'wpv'); ?></small>
</p>