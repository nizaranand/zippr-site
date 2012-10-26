<?php 
	// this file generates the admin options css cache
	
	ob_start();

	$left_sidebar = (int)wpv_get_option('left-sidebar-width');
	$right_sidebar = (int)wpv_get_option('right-sidebar-width');
?>

aside.left { width: <?php echo $left_sidebar?>% }
aside.right { width: <?php echo $right_sidebar?>% }

.left-only { width: <?php echo 94-$left_sidebar ?>% }
.right-only { width: <?php echo 94-$right_sidebar ?>%}
.left-right { width: <?php echo 88-$left_sidebar-$right_sidebar ?>%}

/* -------------------------------------------------------------------------- */
/*                              Backgrounds                                   */
/* -------------------------------------------------------------------------- */

html {
	<?php wpv_background('body-background')?>
}
#main {
	<?php wpv_background('main-background') ?> 
}
.ie8 #main {
	<?php wpv_background_ie8('main-background') ?>
}
.services.has-more .open {
	background-color: <?php wpvge('main-background-color') ?>;
}

.boxed #container-after {
	<?php 
	$mainBg = wpv_get_option('main-background-color');
	if ($mainBg != 'transparent') {
		$c = WpvColor::createFromWpOption('main-background-color');
		$c->setAlpha(wpv_get_option('main-background-opacity'));
		echo wpv_linear_gradient($c, $c);
	}
	?>
}

footer.main-footer {
	<?php wpv_background('subfooter-background') ?>
}
.footer-sidebars-wrapper {
	<?php wpv_background('footer-background') ?>
}
.ie8 footer.main-footer > .footer-sidebars-wrapper {
	<?php wpv_background_ie8('footer-background') ?>
}
.copyrights {
	<?php wpv_background('subfooter-background') ?>
}

.header-slider-wrapper,
.wpv-loading-mask {
	background-color: <?php wpvge('main-background-color')?>;
}

<?php
	$slider_bgcolor = wpv_get_option('slider-background-color');
	$slider_bgimage =  wpv_get_option('slider-background-image');
?>

.header-slider-wrapper, .slider-helper {
	<?php if( ( !empty($slider_bgcolor) && $slider_bgcolor != 'transparent') || !empty($slider_bgimage)): ?>
		<?php wpv_background('slider-background') ?>
	<?php endif ?>
}

<?php if($slider_bgcolor != 'transparent'): ?>
	.header-slider-wrapper .wpv-loading-mask {
		background-color: <?php echo $slider_bgcolor?>;
	}
<?php endif ?>


<?php $headerHeight = (int)wpv_get_option('header-height'); ?>

header.main-header,
.main-menu .menu > li > a {
	min-height: <?php echo $headerHeight ?>px !important;
	line-height: <?php echo $headerHeight ?>px !important;
}
header.main-header {
	<?php wpv_background('header-background') ?>
}
.ie8 header.main-header {
	<?php wpv_background_ie8('header-background') ?>
}
.icons-top {
	top: <?php echo $headerHeight?>px !important;
}

body.admin-bar .icons-top {
	top: <?php echo $headerHeight+27?>px !important;
}

.visiblegrid .wpv-fx-grid-mask .wpv-fx-grid-facet {
	border-color: <?php wpvge('header-slider-gridcolor') ?> !important;
}

/* -----------------------------------------------------------------------------
	Main Navigation
----------------------------------------------------------------------------- */
<?php $bg = WpvColor::createFromWpOption('css_submenu_hover_background'); ?>

.main-menu .menu .sub-menu .menu-item:hover > a,
.main-menu .menu .sub-menu .menu-item a:hover,
.main-menu .menu .sub-menu .current_page_item > a,
.main-menu .menu .sub-menu .current-menu-item > a {
	background: <?php echo $bg->toCssString() ?>;
}

.main-menu .menu .sub-menu {
	background-color: <?php wpvge('css_submenu_background') ?>;
}

.main-menu .menu .sub-menu li {
	border-top: 1px solid <?php echo WpvColor::createFromWpOption('css_submenu_background')->lighten(0.1)->toCssString() ?>;
}

/* ------------------------------------------------------
	Header Sliders
------------------------------------------------------ */

.wpv-loaded #header-slider {
	height: <?php wpvge('header-slider-height') ?>px;
}

/*****************************************************************
						Helper elements and colors
******************************************************************/

<?php
	$primary_text_color = WpvColor::createFromWpOption('primary-font-color');
	$fdivider_text_color = WpvColor::createFromWpOption('lcolumn-text-color');
	$mainbg = WpvColor::createFromWpOption('main-background-color');
	$accent2 = WpvColor::createFromWpOption('accent-color-2');
	$accent3 = WpvColor::createFromWpOption('accent-color-3');
	$accent4 = WpvColor::createFromWpOption('accent-color-4');
?>

.full-width-divider {
	<?php if($primary_text_color->isDark()): ?>
		border-top: 1px solid <?php echo $primary_text_color->lighten(0.7, false)->toCssString() ?>;
		box-shadow: 0 1px 0 <?php echo $primary_text_color->lighten(0.95, false)->toCssString() ?> inset;
	<?php else: ?>
		border-top: 1px solid <?php echo $primary_text_color->darken(0.8, false)->toCssString() ?>;
		box-shadow: 0 1px 0 <?php echo $primary_text_color->darken(0.5, false)->toCssString() ?> inset;
	<?php endif ?>
}

.price-title,
.price .meta-box,
.team-member,
.services.no-image,
.slider-shortcode-wrapper.style-gallery .wpv-nav-pager li,
.services.has-more.accent2,
.entry-utility .bg,
.slogan.accent2 {
	background: <?php echo $accent2->toCssString()?>;
}

.services.has-more.accent3,
.slogan.accent3 {
	background: <?php echo $accent3->toCssString()?>;
}

.services.has-more.accent4,
.slogan.accent4 {
	background: <?php echo $accent3->toCssString()?>;
}

.price-title,
.price .meta-box,
.services.has-more.accent2 .closed,
.slogan.accent2 .slogan-content {
	color: <?php echo $accent2->readable()->toCssString() ?>;
}

.services.has-more.accent3 .closed,
.slogan.accent3 .slogan-content {
	color: <?php echo $accent3->readable()->toCssString() ?>;
}

.services.has-more.accent4 .closed,
.slogan.accent4 .slogan-content {
	color: <?php echo $accent4->readable()->toCssString() ?>;
}

.value-box {
	background: <?php echo $accent2->darken(0.05, false)->toCssString() ?>;
	color: <?php echo $accent2->darken(0.05, false)->readable()->toCssString() ?>;
}

.price .content-box li,
.price-wrapper .content-box,
.price-wrapper .meta-box {
	border-color: <?php echo $accent2->darken(0.05)->toCssString() ?>
}

/* Buttons with generated colors -------------------------------------------- */
<?php
$buttons3 = array(
	'.button.accent3'
);

$buttons4 = array(
	'.button.accent4'
);

for($i=3; $i<=4; $i++): ?>

	<?php echo implode(', ', ${"buttons$i"})?> {
		background-color: <?php echo ${"accent$i"}->toCssString();?>;
		color: <?php echo ${"accent$i"}->readable()->toCssString()?> !important;
		border-color: <?php echo ${"accent$i"}->border()->toCssString();?> !important;
	}

	<?php echo implode(':hover, ', ${"buttons$i"})?>:hover,
	<?php echo implode(':focus, ', ${"buttons$i"})?>:focus {
		background-color: <?php echo ${"accent$i"}->hover()->toCssString();?>;
		color: <?php echo ${"accent$i"}->hover()->readable()->toCssString() ?> !important;
		border-color: <?php echo ${"accent$i"}->hover()->border()->toCssString();?> !important;
	}

<?php endfor ?>

a, * a {
	text-decoration: <?php echo !!wpv_get_option('css_link_underline') ? 'inherit' : 'none'?> !important;
}

/*--------------------------------------------------------------------------
	Internet Explorer
	Fixes requiring full path from html file to the used resources
--------------------------------------------------------------------------*/
.ie7 .light .style-caption-center #header-slider-caption-wrapper .wpv-caption,
.ie8 .light .style-caption-center #header-slider-caption-wrapper .wpv-caption,
.ie7 .dark .style-caption-center #header-slider-caption-wrapper .wpv-caption,
.ie8 .dark .style-caption-center #header-slider-caption-wrapper .wpv-caption  {
	background: transparent !important;
		filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='crop',src='<?php echo WPV_THEME_URI; ?>images/slider/caption-center/caption_bgr.png') !important;
}

.cboxIE #cboxTopLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderTopLeft.png, sizingMethod='scale');}
.cboxIE #cboxTopCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderTopCenter.png, sizingMethod='scale');}
.cboxIE #cboxTopRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderTopRight.png, sizingMethod='scale');}
.cboxIE #cboxBottomLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderBottomLeft.png, sizingMethod='scale');}
.cboxIE #cboxBottomCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderBottomCenter.png, sizingMethod='scale');}
.cboxIE #cboxBottomRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderBottomRight.png, sizingMethod='scale');}
.cboxIE #cboxMiddleLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderMiddleLeft.png, sizingMethod='scale');}
.cboxIE #cboxMiddleRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo WPV_THEME_CSS; ?>colorbox/images/internet_explorer/borderMiddleRight.png, sizingMethod='scale');}

<?php
	include WPV_THEME_CSS_DIR.'typography.php';
	return wpv_finalize_custom_css();
