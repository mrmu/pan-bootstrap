<?php
/**
 * The main template file.
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
				<div class="col-lg-8">
					<!-- section -->
					<section>
						<?php get_template_part('loop'); ?>
						<?php get_template_part('pagination'); ?>
					</section>
					<!-- /section -->
				</div><!-- /.col-lg-8 -->
				<?php get_sidebar(); ?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</main>
</div>

<?php get_footer(); ?>
