<div id="shortcode_<?php echo $this->shortcode['value']?>" class="wpv-config-group metabox shortcode_wrap clearfix">
	<div class="shortcode_content">
		<p class="shortcode_buttons top">
			<input type="button" class="button-primary shortcode_send" value="<?php _e('Send Shortcode to Editor »', 'wpv') ?>"/>
		</p>
				
		<?php if(isset($this->shortcode['sub'])): ?>
			<div class="shortcode_sub_selector wpv-config-row">
				<h4><?php _e('Type', 'wpv')?></h4>

				<div class="content">
					<select name="sc_<?php echo $this->shortcode['value']?>_selector">
						<?php foreach($this->shortcode['options'] as $i=>$sub_shortcode): ?>
							<option value="<?php echo $sub_shortcode['value']?>" <?php selected($i, 0)?>>
								<?php echo $sub_shortcode['name']?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<?php foreach($this->shortcode['options'] as $i=>$sub_shortcode): ?>
				<div id="sub_shortcode_<?php echo $sub_shortcode['value']?>" class="sub_shortcode_wrap" <?php if($i==0):?>style="display:block"<?php endif?>>
					<?php 
						foreach($sub_shortcode['options'] as $option) {
							$option['id'] = 'sc_'.$this->shortcode['value'].'_'.$sub_shortcode['value'].'_'.$option['id'];
							$this->tpl($option['type'], $option);
						}
					?>
				</div>
			<?php endforeach ?>
		<?php else:
			
			foreach($this->shortcode['options'] as $option) {
				$option['id'] = 'sc_'.$this->shortcode['value'].'_'.$option['id'];
				$this->tpl($option['type'], $option);
			}
		
		endif ?>
			
		<p class="shortcode_buttons">
			<a href="#shortcode_preview" class="button"><?php _e('Preview', 'wpv') ?></a>
			<input type="button" class="button-primary shortcode_send" value="<?php _e('Send Shortcode to Editor »', 'wpv') ?>"/>
		</p>
	</div>
	<div id="shortcode_preview">
		<h4><?php _e('Preview', 'wpv')?></h4>
		<div class="the_preview">
			<iframe name="shortcode_preview_iframe"></iframe>
		</div>
		<form action="<?php echo WPV_ADMIN_AJAX ?>shortcode_preview.php" method="post" target="shortcode_preview_iframe">
			<input type="hidden" name="data" id="shortcode_preview_content" />
		</form>
	</div>
</div>
