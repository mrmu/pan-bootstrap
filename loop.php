<div class="row mt-3">
<?php 
if (have_posts()): 
	while (have_posts()) : 
	the_post();

	$p_s_post_excerpt = wp_html_excerpt($post->post_content, 80, '...');

	$p_img_html = '';
	$p_img = pb_get_post_thumbnail($post->ID, 'large');
	if (!empty($p_img)) {
		$p_img_html = '<img src="'.$p_img.'" class="img-fluid" alt="'.$post->post_title.'"/>';
	}
	?>

	<div class="col-12 col-lg-6">
		<article id="post-<?php the_ID(); ?>" <?php post_class('mt-5'); ?>>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<figure style="display: block; height: 190px; overflow: hidden; margin: 0; background-color: #efefef;">
					<?php echo $p_img_html; ?>
				</figure>
				<h2 class="title word-break mt-2 mb-2"><?php the_title(); ?></h2>
				<?php if ($post->post_type !== 'product'): ?>
				<p>
					<span class="date"> <i class="fa fa-calendar"></i> <?php the_time('Y/n/j'); ?> </span>
				</p>
				<?php endif; ?>
			</a>
			<div>
				<?php echo $p_s_post_excerpt; ?>
				<?php // edit_post_link(); ?>
			</div>
		</article>
	</div>

<?php endwhile; ?>
</div>

<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'pan-bootstrap' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
