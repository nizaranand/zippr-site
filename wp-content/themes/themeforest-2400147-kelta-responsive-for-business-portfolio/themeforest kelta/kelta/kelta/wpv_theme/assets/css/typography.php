html {
	color: <?php wpvge('primary-font-color') ?>;
}

/* primary font */
*,
p,
.main-container,
.contact_form input[type="text"], 
.contact_form input[type="email"], 
.contact_form textarea {
	<?php wpv_font('primary-font') ?>
}

/* em font */
.cite,
.cite *,
.page-header .desc,
blockquote,
blockquote p,
em {
	<?php wpv_font('em') ?>
	color: <?php wpvge('em-color') ?>;
}

.logo {
	<?php wpv_font('logo') ?>
}
.logo,
.logo:hover {
	color: <?php wpvge('logo-color') ?> !important;
}

<?php 
for($i=1; $i<=6; $i++):
	$h = "h$i";
?>
	<?php echo "$h, $h a, $h a:visited"?> {
		<?php wpv_font($h) ?>
		color: <?php wpvge("$h-color")?>;
	}
<?php endfor; ?>

<?php $lcolumn = WpvColor::createFromWpOption('lcolumn-text-color') ?>

body.fast-slider .fast-slider-caption,
body.fast-slider .fast-slider-caption a,
body.fast-slider .fast-slider-caption a:hover,
.post-article header .entry-date,
.full-width-divider .divider-content,
.full-width-divider .divider-content > * {
	background: <?php echo $lcolumn->toCssString() ?> !important;
	color: <?php echo $lcolumn->readable()->toCssString() ?> !important;
}

<?php $bodybg = WpvColor::createFromWpOption('body-background-color') ?>

#sub-header-inner {
	color: <?php echo $bodybg->readable()->toCssString() ?> !important;
}

.boxed.has-left-column .post-article header .entry-date {
	font-size: <?php echo intval(wpv_get_option('h2-size')*0.8)?>px;
	font-family: <?php wpv_font_family('h2');?>
}

#footer-sidebars,
#footer-sidebars p,
#footer-sidebars * {
	<?php wpv_font('footer-sidebars-font') ?>
	color: <?php wpvge('footer-sidebars-font-color') ?>;
}

#footer-sidebars h4 {
	<?php wpv_font('footer-sidebars-titles', true) ?>
	color: <?php wpvge('footer-sidebars-titles-color')?>;
}

.copyrights,
.copyrights * {
	<?php wpv_font('sub-footer') ?>
	color: <?php wpvge('sub-footer-color') ?>;
}

<?php 
$links = array(
	'' => '',
	'#footer-sidebars ' => 'footer_',
);
$underlineLinks = (bool) wpv_get_option('css_link_underline');

foreach($links as $selector=>$substr): ?>
	
<?php echo $selector ?> a,
<?php echo $selector ?> .comments-link a b {
	color: <?php wpvge('css_'.$substr.'link_color')?>;
}

<?php echo $selector ?> a:visited {
	color: <?php wpvge('css_'.$substr.'link_visited_color')?>;
}

<?php echo $selector ?> a:hover,
<?php echo $selector ?> .more-btn:hover span,
<?php echo $selector ?> .next-btn:hover span,
<?php echo $selector ?> .prev-btn:hover span,  
<?php echo $selector ?> .comments-link a:hover b, 
<?php echo $selector ?> .sortable a.active {
	color: <?php wpvge('css_'.$substr.'link_hover_color')?> !important;
} 

<?php endforeach ?>

/* ------------------------------------------------------
	Forms
------------------------------------------------------ */
input[type=text],
input[type=email],
input[type=password],
textarea {
	background: <?php echo $accent2->lighten(0.6, false)->toCssString() ?>;
	color: <?php echo $accent2->lighten(0.6, false)->readable()->toCssString() ?>;
}

input[type=text]:focus,
input[type=email]:focus,
input[type=password]:focus,
textarea:focus {
	background: <?php echo $accent2->lighten(0.8, false)->toCssString() ?>;
	color: <?php echo $accent2->lighten(0.8, false)->readable()->toCssString() ?>;
}

/*--------------------------------------
              accents
---------------------------------------*/

.accent-2 {
	color: <?php wpvge('accent-color-2')?>;
}

.accent-3 {
	color: <?php wpvge('accent-color-3')?>;
}

/* ----------------------------------------
              M  E  N  U  S
----------------------------------------- */

/*   top navigation  ------------------------------------------ */

.top-nav-box,
#phone-num {
	color: <?php wpvge('css_tophead_color')?>;
}

.top-nav-box a {
	color: <?php wpvge('css_tophead_link_color')?> !important;
}

.top-nav-box a:hover {
	color: <?php wpvge('css_tophead_link_hover_color')?> !important;
}

.top-nav-box .current_page_item > a,
.top-nav-box .current-menu-item > a {
	color: <?php wpvge('css_tophead_current_link_color')?> !important;
}

.top-nav ul > li.current_page_item > a,
.top-nav ul > li.current_page_item > ul > li > a:hover,
.top-nav ul > li.current-menu-parent > a,
.top-nav ul > li.current-menu-ancestor > a {
	color: <?php wpvge('css_header_link_hover_color')?>;
	background-color: transparent !important;
}

/*   main menu  ----------------------------------------------- */

.main-menu .menu > .menu-item > a,
.main-menu .menu > .menu-item > a:visited {
	<?php wpv_font('menu-font')?>
	color: <?php wpvge('menu-font-color')?>;
}

.main-menu .menu .sub-menu a {
	color: <?php wpvge('css_submenu_color') ?> !important;
}


/* Menu items :hover ------------------------------------------ */

.main-menu .menu > .menu-item:hover > a,
.main-menu .menu > .menu-item > a:hover,
.main-menu .menu > .current_page_item > a,
.main-menu .menu > .current-menu-item > a,
.main-menu .menu > .current_page_ancestor > a,
.main-menu .menu > .current-menu-ancestor > a {
	color: <?php wpvge('css_menu_hover_color') ?> !important;
	background-color: <?php wpvge('css_menu_hover_background') ?> !important;
}

/* Menu items :hover (nested for the main menu) ---------------- */
.main-menu .menu .sub-menu .menu-item:hover > a,
.main-menu .menu .sub-menu .menu-item a:hover,
.main-menu .menu .sub-menu .current_page_item > a,
.main-menu .menu .sub-menu .current-menu-item > a {
	color: <?php wpvge('css_submenu_hover_color') ?> !important;
}

<?php for($i=1; $i<=6; $i++): ?>
	<?php
		$override = wpv_get_option("accent-override-$i");
		if(!empty($override)):

			$bg = WpvColor::createFromString($override);
	?>
			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>):hover > a,
			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) > a:hover,
			body.accent-override .main-menu .menu > .current_page_item:nth-child(<?php echo $i?>) > a,
			body.accent-override .main-menu .menu > .current-menu-item:nth-child(<?php echo $i?>) > a,
			body.accent-override .main-menu .menu > .current_page_ancestor:nth-child(<?php echo $i?>) > a,
			body.accent-override .main-menu .menu > .current-menu-ancestor:nth-child(<?php echo $i?>) > a {
				background: <?php echo $bg->toCssString() ?> !important;
				color: <?php echo $bg->readable()->toCssString() ?> !important;
			}

			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) .sub-menu a {
				background: <?php echo $bg->toCssString() ?> !important;
				color: <?php echo $bg->readable()->toCssString() ?> !important;	
			}

			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) .sub-menu a:hover,
			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) .sub-menu .menu-item:hover a,
			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) .sub-menu .current_page_item > a,
			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) .sub-menu .current-menu-item > a {
				background: <?php echo $bg->darken(0.1, false)->toCssString() ?> !important;
				color: <?php echo $bg->darken(0.1, false)->readable()->toCssString() ?> !important;
			}

			body.accent-override .main-menu .menu > .menu-item:nth-child(<?php echo $i?>) .sub-menu li {
				border-top-color: <?php echo $bg->lighten(0.1, false)->toCssString() ?> !important;  
			}
	<?php endif ?>
<?php endfor ?>

/* =============================================================================
                   MOBILES AND OTHER SMALL SCREENS
============================================================================= */
<?php $zoom = 1.5; ?>

@media only screen and (max-device-width: 10cm), 
	   only screen and (min-resolution: 160dpi) { 

	*,
	p,
	.main-container,
	.contact_form input[type="text"], 
	.contact_form input[type="email"], 
	.contact_form textarea {
		font-size: <?php echo ceil(wpv_get_option('primary-font-size') * $zoom) ?>px;
	}

	.cite,
	.cite *,
	.page-header .desc,
	blockquote,
	blockquote p,
	em {
		font-size: <?php echo ceil(wpv_get_option('em-size') * $zoom) ?>px;
	}

	.logo {
		font-size: <?php echo ceil(wpv_get_option('logo-size') * $zoom) ?>px;
	}

	<?php 
	for($i=1; $i<=6; $i++):
		$h = "h$i";
	?>
<?php echo "$h, $h a, $h a:visited"?> {
		font-size: <?php echo ceil(wpv_get_option("$h-size") * $zoom) ?>px;
	}
	<?php endfor; ?>

	.boxed.has-left-column .post-article header .entry-date {
		font-size: <?php echo intval(wpv_get_option('h2-size')*0.8 * $zoom)?>px;
	}

	#footer-sidebars,
	#footer-sidebars p,
	#footer-sidebars * {
		font-size: <?php echo ceil(wpv_get_option('footer-sidebars-font-size') * $zoom) ?>px;
	}

	#footer-sidebars h4 {
		font-size: <?php echo ceil(wpv_get_option('footer-sidebars-titles-size') * $zoom) ?>px;
	}

	.copyrights, .copyrights * {
		font-size: <?php echo ceil(wpv_get_option('sub-footer-size') * $zoom) ?>px;
	}
}
