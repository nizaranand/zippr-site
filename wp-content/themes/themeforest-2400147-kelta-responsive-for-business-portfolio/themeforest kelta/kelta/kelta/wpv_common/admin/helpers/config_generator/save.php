<p class="submit save-wpv-config">
	<input type="hidden" name="page" value="<?php echo $_GET['page']?>" class="static" />
	<input type="hidden" name="action" value="wpv-save-options" class="static" />
	<input type="submit" name="save-wpv-config" class="button-primary autowidth static" value="<?php isset($_GET['allowreset']) ? _e('Delete options', 'wpv') : _e('Save Changes', 'wpv')?>" />
</p>