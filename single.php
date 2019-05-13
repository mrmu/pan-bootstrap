<?php get_header(); ?>

<!-- wrapper -->
<div class="wrapper">

<!-- header -->
<header class="header clear">

	<nav class="navbar navbar-expand-sm fixed-top navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="<?php echo home_url(); ?>">
				<?php bloginfo('name'); ?>
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<?php pan_bootstrap_nav(); ?>
			</div>
		</div>
		<!-- /.container -->
	</nav>

</header>
<!-- /header -->

<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>

				<?php if (have_posts()): while (have_posts()) : the_post(); ?>


					<!-- article -->
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<!-- post title -->
						<h1>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</h1>
						<!-- /post title -->
						<!-- Author -->
						<p class="lead">
							<span class="author"><?php _e( 'Published by', 'pan-bootstrap' ); ?> <?php the_author_posts_link(); ?></span>
						</p>
						<hr>
						<!-- Date -->
						<p>
							<span class="date">
								<?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?>
							</span>
							<span class="text-muted">|</span>
							<span class="comments"><?php if (comments_open( get_the_ID() ) ) comments_popup_link( __( 'Leave your thoughts', 'pan-bootstrap' ), __( '1 Comment', 'pan-bootstrap' ), __( '% Comments', 'pan-bootstrap' )); ?></span>
						</p>
						<!-- /post details -->
						<hr>

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
						<p>
							<?php the_tags( __( 'Tags: ', 'pan-bootstrap' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>
						</p>

						<p>
							<?php _e( 'Categorised in: ', 'pan-bootstrap' ); the_category(', '); // Separated by commas ?>
						</p>

						<p class="text-muted"><?php _e( 'This post was written by ', 'pan-bootstrap' ); the_author(); ?></p>

						<?php edit_post_link(); // Always handy to have Edit Post Links available ?>

						<?php comments_template(); ?>

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
			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>

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

</div>
<!-- /wrapper -->

<?php get_footer(); ?>
