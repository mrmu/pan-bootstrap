<?php get_header(); ?>
<!-- wrapper -->
<div class="wrapper">
<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>

					<h1 class="page-header"><?php _e( 'Archives', 'pan-bootstrap' ); ?></h1>

					<?php get_template_part('loop'); ?>

					<?php get_template_part('pagination'); ?>

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
