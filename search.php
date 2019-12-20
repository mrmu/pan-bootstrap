<?php get_header(); ?>
<!-- wrapper -->
<div class="wrapper">
<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>
					<h1 class="page-header"><?php echo sprintf( __( '%1$s Search Results for "%2$s" ', 'pan-bootstrap' ), $wp_query->found_posts, get_search_query() ); ?></h1>
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
<!-- /wrapper -->

<?php get_footer(); ?>
