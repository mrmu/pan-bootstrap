<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Pan-Bootstrap
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* Define Constants */

define( 'PB_THEME_VERSION', '0.1.0' );
define( 'PB_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'PB_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );

/* Theme */
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-setup.php';
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-customizer.php';
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-menu.php';
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-widgets.php';
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-seo.php';
require_once PB_THEME_DIR . 'inc/theme/class-wp-bootstrap-navwalker.php';
require_once PB_THEME_DIR . 'inc/theme/utils.php';

/* Theme / Admin */
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-admin-general.php';

/* Theme / Public */
require_once PB_THEME_DIR . 'inc/theme/class-pb-theme-public-general.php';

/* Plugins: WooCommerce */
require_once PB_THEME_DIR . 'inc/plugins/class-pb-plugin-woocommerce.php';
