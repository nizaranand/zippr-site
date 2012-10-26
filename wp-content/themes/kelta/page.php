<?php
/**
 * @package WordPress
 * @subpackage zenden
 */

if(!wpv_is_reduced_response()):
	get_header();
endif;

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php if(!wpv_is_reduced_response()): ?>
<?php $page_header_placed = wpv_get_header_sidebars() ?>
<div class="pane main-pane">
	<div class="row">
		<?php wpv_page_header($page_header_placed) ?>
		<div class="page-outer-wrapper">
			<div class="clearfix page-wrapper">
<?php endif; // reduced response ?>
				<?php wpv_has_left_sidebar() ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class(wpv_get_layout()); ?>>
					<?php $has_image = wpv_page_image() ?>
					<div class="page-content <?php echo $has_image?>">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wpv' ), 'after' => '</div>' ) ); ?>
					</div>

					<?php comments_template( '', true ); ?>
				</article>
				
				<?php wpv_has_right_sidebar() ?>
<?php if(!wpv_is_reduced_response()): ?>
				
			</div>
			<?php wpv_share('page'); ?>
		</div>
	</div>
</div>

<?php endif;
endwhile;

if(!wpv_is_reduced_response()) {
	get_footer();
} else {
	wpv_reduced_footer();
}