<?php
/**
 * Menu Initialization
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_Menu' ) ) {

	class PB_Theme_Menu {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            add_action( 'after_setup_theme', array( $this, 'setup_theme_menu' ), 5 );

            // Remove surrounding <div> from WP Navigation
            add_filter( 'wp_nav_menu_args', array( $this, 'wp_nav_menu_args') ); 
        }

        // Add Menu
        public function setup_theme_menu( $wp_customize ) {
            // Add Menu Support
            add_theme_support('menus');            
            register_nav_menus(array( // Using array to specify more menus if needed
                'primary-menu' => __('Primary Menu', 'pan-bootstrap'), // Main Navigation
                'footer-menu' => __('Footer Menu', 'pan-bootstrap'), // Sidebar Navigation
                // 'extra-menu' => __('Extra Menu', 'pan-bootstrap') // Extra Navigation if needed (duplicate as many as you need!)
            ));
        }

        // Remove the <div> surrounding the dynamic navigation to cleanup markup
        public function wp_nav_menu_args($args = '') {
            $args['container'] = false;
            return $args;
        }
    }
}

PB_Theme_Menu::get_instance();