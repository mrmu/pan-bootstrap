<?php
/**
 * Declaring widgets
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'widgets_init', 'pan_bootstrap_widgets_init' );

// Remove wp_head() injected Recent Comment styles
function pan_bootstrap_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

function pan_bootstrap_widgets_init() {

	// Define Sidebar Widget Area 1
	register_sidebar(array(
		'name' => __('Widget Area 1', 'pan-bootstrap'),
		'description' => __('Description for this widget-area...', 'pan-bootstrap'),
		'id' => 'widget-area-1',
		'before_widget' => '<div id="%1$s" class="%2$s mb-2"><div class="card-body">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="card-title">',
		'after_title' => '</h3>'
	));

	// Define Sidebar Widget Area 2
	register_sidebar(array(
		'name' => __('Widget Area 2', 'pan-bootstrap'),
		'description' => __('Description for this widget-area...', 'pan-bootstrap'),
		'id' => 'widget-area-2',
		'before_widget' => '<div id="%1$s" class="%2$s mb-2"><div class="card-body">',
		'after_widget' => '</div></div>',
		'before_title' => '<h3 class="card-title">',
		'after_title' => '</h3>'
	));

	// Remove inline Recent Comment Styles from wp_head()
	pan_bootstrap_remove_recent_comments_style();
}
