[raw]
<div class="price-wrapper <?php echo $featured=='true'? 'featured':'' ?>">
	<h3 class="price-title"><?php echo $title ?></h3>
	<div class="price" style="text-align:<?php echo $text_align?>">
		<div class="value-box">
			<span class="value">
				<i><?php echo $currency?></i>
				<span class="number"><?php echo $price?></span>
			</span>
			<span class="meta"><?php echo $duration?></span>
		</div>
		
		<div class="content-box">
			<?php echo do_shortcode($content)?>
		</div>
		<div class="meta-box">
			<?php if(!!$summary):?><p class="description"><?php echo htmlspecialchars_decode($summary)?></p><?php endif?>
			<a href="<?php echo $button_link?>" class="button medium <?php echo $featured=='true'? 'accent1':'' ?>"><?php echo $button_text?></a>
		</div>
	</div>
</div>
[/raw]