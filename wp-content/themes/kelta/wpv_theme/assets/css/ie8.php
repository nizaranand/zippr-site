<?php

function stm_nth_child($item, $i) {
	echo "$item:first-child".($i>1 ? ' + '.implode('+', array_fill(0, $i-1, $item)) : '');
}

function stm_ie8_current_page($item, $i) {
	if($i == 1) {
		echo "$item:first-child";
		return;
	}

	echo stm_nth_child('li', $i-1)."+$item";
}

for($i=1; $i<=8; $i++): ?>
	<?php
		$override = wpv_get_option("accent-override-$i");
		if(!empty($override)):
			$bg = WpvColor::createFromString($override);
	?>
			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?>:hover > a,
			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> > a:hover,
			body.accent-override .main-menu .menu > <?php stm_ie8_current_page('.current_page_item', $i) ?> > a,
			body.accent-override .main-menu .menu > <?php stm_ie8_current_page('.current-menu-item', $i) ?> > a,
			body.accent-override .main-menu .menu > <?php stm_ie8_current_page('.current_page_ancestor', $i) ?> > a,
			body.accent-override .main-menu .menu > <?php stm_ie8_current_page('.current-menu-ancestor', $i) ?> > a {
				background: <?php echo $bg->toCssString() ?> !important;
				color: <?php echo $bg->readable()->toCssString() ?> !important;
			}

			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> .sub-menu a {
				background: <?php echo $bg->toCssString() ?> !important;
				color: <?php echo $bg->readable()->toCssString() ?> !important;	
			}

			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> .sub-menu a:hover,
			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> .sub-menu .menu-item:hover a,
			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> .sub-menu .current_page_item > a,
			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> .sub-menu .current-menu-item > a {
				background: <?php echo $bg->darken(0.1, false)->toCssString() ?> !important;
				color: <?php echo $bg->darken(0.1, false)->readable()->toCssString() ?> !important;
			}

			body.accent-override .main-menu .menu > <?php stm_nth_child('.menu-item', $i) ?> .sub-menu li {
				border-top-color: <?php echo $bg->lighten(0.1, false)->toCssString() ?> !important;  
			}
	<?php endif ?>
<?php endfor ?>