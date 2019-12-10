<?php get_header(); ?>
<!-- wrapper -->
<div class="wrapper">
	<main>
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<!-- section -->
					<section>
						<?php get_template_part('loop'); ?>
						<?php get_template_part('pagination'); ?>
					</section>
					<!-- /section -->
				</div><!-- /.col-md-8 -->
				<?php get_sidebar(); ?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</main>
</div>
<?php get_footer(); ?>
