<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<link href="<?php echo get_template_directory_uri(); ?>/logo.png" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/logo.png" rel="apple-touch-icon-precomposed">
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" />
		
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>
		<?php
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		}
		?>
		<!-- header -->
		<header class="header main-header clear">
			<nav id="main_navbar" class="navbar navbar-expand-lg fixed-top navbar-light">
				<div class="container">
					<a class="navbar-brand-link" href="<?php echo home_url(); ?>">
						<div class="logo_wrap">
							<?php
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
							if ( has_custom_logo() ) {
									echo '<img src="' . esc_url( $logo ) . '" alt="' . get_bloginfo( 'name' ) . '">';
							} else {
									echo '<h1>'. get_bloginfo( 'name' ) .'</h1>';
							}
							?>
						</div>
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul id="menu-main-menu" class="navbar-nav ml-auto">
							<?php pan_bootstrap_nav(); ?>
						</ul>
					</div>
				</div>
				<!-- /.container -->
			</nav>
		</header>
		<!-- /header -->
