<?php

function wpv_shortcode_sequence($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'width' => 0,
		'height' => 300,
		'effect' => 'fade',
		'pausetime' => 5000,
		'style' => 'gallery',
		'annotation' => '',
	), $atts));
	
	if (!wpv_sub_shortcode('slide', $content, $params, $sub_contents))
		return 'error parsing slider shortcode';

	wp_enqueue_script('front-jquery.sequence');
	wp_enqueue_style('front-sequence');

	$css = '';
	if(!empty($width))
		$css .= "width:{$width}px;";
	if(!empty($height))
		$css .= "height:{$height}px;";
	
	$id = md5(uniqid('', true));
	ob_start();
?>
[raw]
	<script type="text/javascript"> 
	    jQuery(function($){
	        $("#sequence-<?php echo $id?>").sequence({
	        	autoPlay: true,
	        	autoPlayDelay: <?php echo $pausetime?>,
	        	nextButton: '#sequence-<?php echo $id?> .next',
	        	prevButton: '#sequence-<?php echo $id?> .prev',
	        	delayDuringOutInTransitions: false,
	        	keysNavigate: false,
	        	preloader: false,
	        }).data("sequence");
	    });
	</script>
	<div id="sequence-<?php echo $id?>" class="sequence sequence-shortcode <?php echo "$style $effect" ?>" style="<?php echo $css?>">
		<?php if(!empty($annotation)): ?>
			<div class="annotation"><?php echo $annotation?></div>
		<?php endif ?>
		<ul>
			<?php foreach($sub_contents as $i=>$c): ?>
				<?php $has_image = isset($params[$i]['img']) && !empty($params[$i]['img']) ?>
				<li class="slide <?php echo $has_image ? 'has-image' : 'no-image'?>">
					<?php if($has_image): ?>
						<div class="image crop">
							<img src="<?php echo $params[$i]['img']?>"/>
						</div>
					<?php endif ?>
					<?php if(!empty($c)): ?>
						<div class="content"><?php echo wpv_clean_do_shortcode($c) ?></div>
					<?php endif ?>
				</li>
			<?php endforeach ?>
		</ul>

		<nav>
			<span class="prev button icon size-medium" title="<?php _e('Previous', 'wpv') ?>"><?php wpv_icon('arrow-left') ?></span>
			<span class="next button icon size-medium" title="<?php _e('Next', 'wpv') ?>"><?php wpv_icon('arrow-right') ?></span>
		</nav>
	</div>
[/raw]
<?php
	return ob_get_clean();
}
add_shortcode('slider', 'wpv_shortcode_sequence');
