<?php
/**
 * @package WordPress
 * @subpackage zenden
 */
?>


					</div> <!-- .limit-wrapper -->
				</div><!-- / #main (do not remove this comment) -->
			</div><!-- / .pane-wrapper -->
			<footer class="main-footer footer-helper <?php echo wpv_get_option('footer-helper-style')?> <?php if(wpv_get_option('footer-helper-classic')) echo 'classic'?>">
				<div class="limit-wrapper">
					<?php if(wpv_get_option('has-footer-sidebars')): ?>
						<div class="footer-sidebars-wrapper">
							<?php wpv_footer_sidebars(); ?>
						</div><!-- / .outset -->
					<?php endif ?>
					
					<div class="copyrights">
						<div class="row">
							<?php echo apply_filters('the_content', wpv_get_option( 'credits' )); ?>
						</div>
					</div><!-- / .copyrights -->
				</div>
			</footer>
		</div><!-- / .page-dash-wrapper -->
	</div><!-- / .boxed-layout -->
	<div id="container-after"></div>
	<div id="container-after-2"></div>
</div><!-- / #container -->

<?php wp_footer(); ?>

</body>
</html>
