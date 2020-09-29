<?php get_header(); ?>

<!-- wrapper -->
<div class="wrapper">
<main>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<!-- section -->
				<section>
				<?php if (have_posts()): while (have_posts()) : the_post(); ?>
					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<!-- post title -->
						<h1 class="post_title"><?php the_title(); ?></h1>
						<!-- /post title -->
						<hr>
						<!-- /post details -->
						<?php pb_breadcrumb(); ?>
						<hr>
						<div class="container">
							<div class="row">
								<div class="col-md-8">
									<!-- post thumbnail -->
									<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
										<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
											<?php the_post_thumbnail('large', ['class' => 'img-fluid']); // Fullsize image for the single post ?>
										</a>
										<hr>
									<?php endif; ?>
									<!-- /post thumbnail -->

									<?php the_content(); // Dynamic Content ?>
									<hr>

									<?php edit_post_link(); // Always handy to have Edit Post Links available ?>

									<?php 
									// If comments are open or we have at least one comment, load up the comment template.
									if ( comments_open() || get_comments_number() ) {
										comments_template();
									}
									?>
								</div>
								<?php get_sidebar(); ?>
							</div>
						</div>

					</article>
					<!-- /article -->

				<?php endwhile; ?>

				<?php else: ?>

					<!-- article -->
					<article>

						<h1><?php _e( 'Sorry, nothing to display.', 'pan-bootstrap' ); ?></h1>

					</article>
					<!-- /article -->

				<?php endif; ?>

				</section>
				<!-- /section -->
			</div><!-- /.col-12 -->
			
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>

</div>
<!-- /wrapper -->

<?php get_footer(); ?>
