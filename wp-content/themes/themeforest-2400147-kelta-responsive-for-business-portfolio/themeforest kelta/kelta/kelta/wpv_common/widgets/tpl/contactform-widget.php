<?php
$suffix = md5(uniqid('', true));
echo $before_widget;
if ($title) 
	echo $before_title . $title . $after_title;
?>

<p class="success" style="display:none;">
	<?php _e('Your message was successfully sent. <strong>Thank You!</strong>', 'wpv'); ?>
</p>

<form class="contact_form" action="<?php echo WPV_INCLUDES; ?>sendmail.php" method="post">
	<input type="hidden" value="<?php echo $email; ?>" name="contact_to"/>
	<div>
		<label for="contact_name-<?php echo $suffix?>"><?php _e('Name (required)', 'wpv'); ?></label>
		<input type="text" required="required" id="contact_name-<?php echo $suffix?>" name="contact_name" class="text_input" value="" size="22" tabindex="5" placeholder="<?php _e('Name (required)', 'wpv'); ?>" />
	</div>
			
	<div>
		<label for="contact_email-<?php echo $suffix?>"><?php _e('Email (required)', 'wpv'); ?></label>
		<input type="email" required="required" id="contact_email-<?php echo $suffix?>" name="contact_email" class="text_input" value="" size="22" tabindex="6"  placeholder="<?php _e('Email (required)', 'wpv'); ?>" />
	</div>
			
	<div>
		<textarea required="required" name="contact_content" class="textarea" tabindex="7"></textarea>
	</div>
				
	<div class="wire-pad">
		<input type="submit" class="button" value="<?php _e('Submit', 'wpv'); ?>" />
	</div>
</form>
		
<?php
echo $after_widget;