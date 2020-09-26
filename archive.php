<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Pan-Bootstrap
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header(); ?>

<div class="wrapper">
	<main>
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-8">
					<section>
						<h1 class="page-header"><?php _e( 'Archives', 'pan-bootstrap' ); ?></h1>
						<?php get_template_part('loop'); ?>
						<?php get_template_part('pagination'); ?>
					</section>
				</div><!-- /.col-lg-8 -->
				<?php get_sidebar(); ?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</main>
</div>

<?php get_footer(); ?>
