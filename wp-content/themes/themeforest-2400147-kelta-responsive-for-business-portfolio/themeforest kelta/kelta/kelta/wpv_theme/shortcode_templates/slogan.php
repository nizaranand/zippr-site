[raw]
<div class="slogan clearfix <?php echo $background?>">
	<div class="slogan-content <?php echo !empty($button_text) ? 'grid-3-4' : ''?>">
		<?php echo wpv_clean_do_shortcode($content);?>
	</div> 
	<?php if(!empty($button_text)): ?>
	<div class="button-wrp grid-1-4 last">
		<a href="<?php echo $link?>" class="button large alignright" title="<?php echo esc_attr($button_text)?>"><?php echo $button_text?></a>
	</div> 
	<?php endif ?>
</div>
[/raw]