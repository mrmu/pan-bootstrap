<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
		<!-- footer -->
		<footer class="footer">
			<div class="container">
				<div class="text-center p-2">
					<!-- copyright -->
					<p class="copyright">
						&copy; <?php echo date('Y'); ?> Copyright <?php bloginfo('name'); ?>.
					</p>
					<!-- /copyright -->
				</div><!-- /.row -->
			</div><!-- /.container -->
		</footer>
		<!-- /footer -->

		<?php wp_footer(); ?>

	</body>
</html>
