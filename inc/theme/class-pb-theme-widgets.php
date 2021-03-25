<?php
/**
 * Widgets Initialization
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_Widgets' ) ) {

	class PB_Theme_Widgets {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            add_action( 'widgets_init', array($this, 'widgets_init') );
        }

        // Remove wp_head() injected Recent Comment styles
        private function remove_recent_comments_style() {
            global $wp_widget_factory;
            remove_action(
                'wp_head', 
                array(
                    $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
                    'recent_comments_style'
                )
            );
        }

        public function widgets_init() {
            // Define Sidebar Widget Area 1
            register_sidebar(array(
                'name' => __('Main sidebar', 'pan-bootstrap'),
                'description' => __('Description for this widget-area...', 'pan-bootstrap'),
                'id' => 'main-sidebar',
                'before_widget' => '<div id="%1$s" class="%2$s mb-2"><div class="card-body">',
                'after_widget' => '</div></div>',
                'before_title' => '<h3 class="card-title">',
                'after_title' => '</h3>'
            ));
        
            // Remove inline Recent Comment Styles from wp_head()
            $this->remove_recent_comments_style();
        }
        
    }
}

PB_Theme_Widgets::get_instance();