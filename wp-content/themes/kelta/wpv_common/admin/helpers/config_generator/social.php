<?php
/*
 * social share icons
 */

$contexts = array(
	'post' => __('Post', 'wpv'),
	'page' => __('Page', 'wpv'),
	'portfolio' => __('Portfolio', 'wpv'),
	'lightbox' => __('Lightbox', 'wpv'),
);

$networks = array(
	'twitter' => 'http://twitter.com/phoenix/favicon.ico',
	'facebook' => 'http://static.ak.fbcdn.net/rsrc.php/yi/r/q9U99v3_saj.ico', 
	'gplus' => '//ssl.gstatic.com/s2/oz/images/faviconr2.ico', 
	'pinterest' => 'http://passets-cdn.pinterest.com/images/favicon.png', 
);

?>

<div class="wpv-config-row social">
	<h4><?php echo $name?></h4>
	
	<p class="description"><?php echo $desc?></p>
	
	<div class="content">
		<table cellspacing="5px">
			<thead>
				<th>&nbsp;</th>
				<?php foreach($networks as $network=>$image): ?>
					<th><img src="<?php echo $image?>" alt="<?php echo ucfirst($network)?>" width="16" height="16"/></th>
				<?php endforeach ?>
			</thead>
			<tbody>
				<?php foreach($contexts as $context=>$context_translation): ?>
					<tr>
						<th><?php echo $context_translation ?></th>
						<?php foreach($networks as $network=>$image): ?>
							<td><input type="checkbox" name="share-<?php echo $context.'-'.$network?>" <?php checked(wpv_get_option("share-$context-$network"), true)?> value="true" class="<?php wpv_static($value)?>" /></td>
						<?php endforeach ?>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>