<?php
$accent1 = WpvColor::createFromWpOption('accent-color');
$accent2 = WpvColor::createFromWpOption('accent-color-2');
?>

.tabs .ui-tabs-nav .ui-state-active a, 
.tabs .ui-tabs-nav .ui-state-selected a {
	border-top-color: <?php echo wpvge('accent-color') ?>;
	color: <?php echo WpvColor::createFromWpOption('primary-font-color')->darken(0.2)->toCssString() ?>;
	border-bottom: 1px solid <?php echo wpvge('main-background-color') ?>
}

.featured .price-title,
.slider-shortcode-wrapper.style-gallery .wpv-nav-pager li.active,
.services.has-more.accent1,
.slogan.accent1,
header.main-header .header-overline,
#ss-teaser,
footer.main-footer {
	background: <?php echo $accent1->toCssString()?>;
}

.header-slider-wrapper.animation-fadeMultipleCaptions .wpv-nav-pager li.active, 
.header-slider-wrapper.animation-slideMultipleCaptions .wpv-nav-pager li.active, 
.header-slider-wrapper.animation-slideAndFade .wpv-nav-pager li.active,
body.wide.has-page-header .page-header:before,
body.wide.has-page-header .page-header:after {
	background-color: <?php echo $accent1->toCssString()?>;
}

.featured .price-title,
.services.has-more.accent1 .closed,
.slogan.accent1 .slogan-content,
#ss-teaser {
	color: <?php echo $accent1->readable()->toCssString() ?>;
}

.featured .value-box {
	background: <?php echo $accent1->darken(0.1, false)->toCssString() ?>;
	color: <?php echo $accent1->darken(0.1, false)->readable()->toCssString() ?>;
}

.price-wrapper.featured .content-box,
.price-wrapper.featured .meta-box {
	border-color: <?php echo $accent1->toCssString() ?>
}

#ss-teaser:after {
	border-right-color: <?php echo $accent1->toCssString() ?> !important;
}

<?php
$generic_buttons = array(
	'.sort_by_cat .cat a',
	'.button',
	'.button:visited',
	'input[type=button]',
	'input[type=submit]',
	'#scroll-to-top',
	'.prev-post a',
	'.next-post a',
	'.all-items',
	'.slider-shortcode-wrapper .wpv-nav-prev',
	'.slider-shortcode-wrapper .wpv-nav-next',
	'#style-switcher a[name="Reset"]',
);

$primary_buttons = array(
	'.sort_by_cat .cat a.active',
	'#feedback',
	'#toggle-style-switcher',
	'.button.accent1',
	'.top-nav-toggle',
	'.top-nav-toggle.opened',
);

echo implode(', ', $generic_buttons)?>,
.dropcap2,
.widget_nav_menu .menu li.current_page_item a,
.widget_nav_menu .menu li a:hover {
	border: 0 !important;
	background-color: <?php echo $accent2->toCssString();?>;
	color: <?php echo $accent2->readable()->toCssString() ?> !important;
	border-color: <?php echo $accent2->border()->toCssString();?> !important;
}

<?php echo implode(':hover, ', $generic_buttons)?>:hover,
<?php echo implode(':focus, ', $generic_buttons)?>:focus,
<?php echo implode(':active, ', $generic_buttons)?>:active,
<?php echo implode(', ', $primary_buttons)?> {
	background-color: <?php echo $accent1->toCssString();?>;
	color: <?php echo $accent1->readable()->toCssString()?> !important;
	border-color: <?php echo $accent1->border()->toCssString();?> !important;
}

<?php echo implode(':hover, ', $primary_buttons)?>:hover,
<?php echo implode(':focus, ', $primary_buttons)?>:focus {
	background-color: <?php echo $accent1->hover()->toCssString();?>;
	color: <?php echo $accent1->hover()->readable()->toCssString() ?> !important;
	border-color: <?php echo $accent1->hover()->border()->toCssString();?> !important;
}

body.fast-slider .fast-slider-view-all:hover,
body.fast-slider .fast-slider-prev:hover,
body.fast-slider .fast-slider-next:hover,
body.fast-slider .fast-slider-gall-next:hover,
body.fast-slider .fast-slider-gall-prev:hover,
.prev-next-posts-links a:hover {
	background-color: <?php echo $accent1->toCssString();?> !important;
	color: <?php echo $accent1->readable()->toCssString()?> !important;
}

.accordion .tab.ui-state-active,
.toggle_active,
.dropcap-1,
.widget_nav_menu .menu li.current_page_item a,
.widget_nav_menu .menu li a:hover,
.accent-1,
a:hover,
h2,
h2 a,
h2 a:visited {
	color: <?php wpvge('accent-color')?> !important;
}

::selection {
	background: <?php wpvge('accent-color')?>;
	color: <?php echo WpvColor::createFromWpOption('accent-color')->readable()->toCssString()?>;
}

<?php
	global $wpv_original_accent;
	if($wpv_original_accent != wpv_get_option('accent-color')):
?>
	body.fast-slider .fast-slider-caption,
	body.fast-slider .fast-slider-caption a,
	body.fast-slider .fast-slider-caption a:hover,
	.post-article header .entry-date,
	.full-width-divider .divider-content,
	.full-width-divider .divider-content > *,
	.copyrights {
		background: <?php echo $accent1->toCssString() ?> !important;
		color: <?php echo $accent1->readable()->toCssString() ?> !important;
	}

	.copyrights * {
		color: <?php echo $accent1->readable()->toCssString() ?> !important;
	}
<?php endif ?>