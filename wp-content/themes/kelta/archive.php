<?php
/**
 * @package WordPress
 * @subpackage zenden
 */

$title = __('Blog Archives', 'wpv');

if( is_day() ) {
	$title = sprintf( __( 'Daily Archives: <span>%s</span>', 'wpv' ), get_the_date() );
} elseif( is_month() ) {
	$title = sprintf( __( 'Monthly Archives: <span>%s</span>', 'wpv' ), get_the_date('F Y') );
} elseif( is_year() ) {
	$title = sprintf( __( 'Yearly Archives: <span>%s</span>', 'wpv' ), get_the_date('Y') );
} elseif( is_category() ) {
	$title = sprintf( __( 'Category: %s', 'wpv' ), '<span>' . single_cat_title( '', false ) . '</span>' );
} elseif( is_tag() ) {
	$title = sprintf( __( 'Tag Archives: %s', 'wpv' ), '<span>' . single_tag_title( '', false ) . '</span>' );
}

get_header(); ?>

<?php $page_header_placed = wpv_get_header_sidebars($title) ?>

<?php if ( have_posts() ): the_post(); ?>

<div class="pane main-pane">
	<div class="row">
		<?php wpv_page_header($page_header_placed, $title);	?>
		<div class="page-outer-wrapper">
			<div class="clearfix page-wrapper">
				<?php wpv_has_left_sidebar() ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(wpv_get_layout()); ?>>
					<div class="page-content">
						<?php rewind_posts() ?>
						<?php get_template_part('loop', 'archive') ?>
					</div>
				</article>

				<?php wpv_has_right_sidebar() ?>
			</div>
		</div>
	</div>
</div>
<?php endif ?>

<?php get_footer(); ?>
