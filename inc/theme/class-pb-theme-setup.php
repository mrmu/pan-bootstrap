<?php
/**
 * Functions and definitions.
 * Text Domain: pan-bootstrap
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_Setup' ) ) {

	class PB_Theme_Setup {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            add_action( 'after_setup_theme', array( $this, 'setup_theme' ), 2 );
            add_filter( 'body_class', array( $this, 'add_slug_to_body_class' ) );

            // 設定支援搜尋的 post type
            add_action('pre_get_posts', array( $this, 'frontend_search_filter') );

            // Enable Threaded Comments
            add_action( 'get_header', array( $this, 'enable_threaded_comments') ); 

            // Allow shortcodes in Dynamic Sidebar & Excerpt (Manual Excerpts only)
            add_filter( 'widget_text', 'do_shortcode' ); 
            add_filter( 'the_excerpt', 'do_shortcode' );

            // Remove auto <p> tags in Dynamic Sidebars & Excerpt (Manual Excerpts only)
            add_filter( 'widget_text', 'shortcode_unautop' ); 
            add_filter( 'the_excerpt', 'shortcode_unautop' ); 

            // Remove <p> tags from Excerpt altogether
            remove_filter( 'the_excerpt', 'wpautop' ); 

            // Custom Gravatar in Settings > Discussion
            add_filter( 'avatar_defaults', array( $this, 'add_custom_gravatar') );

            // // Remove Admin bar
            // add_filter( 'show_admin_bar', '__return_false' );

            // Remove invalid rel attribute
            add_filter( 'the_category', array( $this, 'remove_category_rel_from_category_list') ); 

            // Add 'View Article' button instead of [...] for Excerpts
            add_filter( 'excerpt_more', array( $this, 'excerpt_more_link' ) ); 

            // ------ Perfomance Improvment ------ //

            // Add attributes to CDN script tag
            add_filter( 'script_loader_tag', array( $this, 'add_script_tag_attributes' ), 10, 2 ); 

            // Remove 'text/css' from enqueued stylesheet
            add_filter( 'style_loader_tag', array( $this, 'style_remove'), 10 );

            // Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
            // add_action( 'get_header', array( $this, 'conditionally_remove_wc_assets'), 99 );
            // add_action( 'wp_enqueue_scripts', array( $this, 'manage_woocommerce_styles'), 99 );

            // Remove width and height dynamic attributes to thumbnails
            add_filter( 'post_thumbnail_html', array( $this, 'remove_thumbnail_dimensions'), 10 ); 

            // Remove width and height dynamic attributes to post images
            add_filter( 'image_send_to_editor', array( $this, 'remove_thumbnail_dimensions'), 10 ); 

            // ------ Remove Actions ------ //

            // Remove Emoji
            remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
            remove_action( 'wp_print_styles', 'print_emoji_styles' );
            remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
            remove_action( 'admin_print_styles', 'print_emoji_styles' );

            // Display the links to the extra feeds such as category feeds
            remove_action('wp_head', 'feed_links_extra', 3); 

            // Display the links to the general feeds: Post and Comment Feed
            remove_action('wp_head', 'feed_links', 2); 

            // Display the link to the Really Simple Discovery service endpoint, EditURI link
            remove_action('wp_head', 'rsd_link'); 

            // Display the link to the Windows Live Writer manifest file.
            remove_action('wp_head', 'wlwmanifest_link'); 

            // Index link
            remove_action('wp_head', 'index_rel_link'); 

            // Prev link
            remove_action('wp_head', 'parent_post_rel_link', 10, 0);
            
            // Start link
            remove_action('wp_head', 'start_post_rel_link', 10, 0); 

            // Display relational links for the posts adjacent to the current post.
            remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); 

            // Display the XHTML generator that is generated on the wp_head hook, WP version
            remove_action('wp_head', 'wp_generator'); 
            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
            remove_action('wp_head', 'rel_canonical');
            remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        }

        public function setup_theme() {

            // Localisation Support
            load_theme_textdomain('pan-bootstrap', PB_THEME_DIR . '/languages');

			// Gutenberg wide images.
			add_theme_support( 'align-wide' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

            // Add Thumbnail Theme Support
            add_theme_support( 'post-thumbnails' );
            // add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

            // Add Custom Logo
            add_theme_support( 
                'custom-logo', 
                array(
                    'height'      => 168,
                    'width'       => 558,
                    'flex-height' => true,
                    'flex-width'  => true,
                    'header-text' => array( 'site-title', 'site-description' ),
                )
            );

			// Switch default core markup for search form, comment form, and comments.
			// to output valid HTML5.
			// Added a new value in HTML5 array 'navigation-widgets' as this was introduced in WP5.5 for better accessibility.
			add_theme_support(
				'html5',
				array(
					'navigation-widgets',
					'search-form',
					'gallery',
					'caption',
					'style',
					'script',
				)
            );

			// Post formats.
			add_theme_support(
				'post-formats',
				array(
					'gallery',
					'image',
					'link',
					'quote',
					'video',
					'audio',
					'status',
					'aside',
				)
            );

			// Customize Selective Refresh Widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

        }

        // Add attributes to the script tag
        // async or defer
        // *** for CDN integrity and crossorigin attributes ***
        public function add_script_tag_attributes($tag, $handle) {
            switch ($handle) {
                // adding async to main js bundle
                // for defer, replace async="async" with defer="defer"
                case ('pan_bootstrap_scripts'):
                    return str_replace( ' src', ' async="async" src', $tag );
                break;

                // example adding CDN integrity and crossorigin attributes
                // Note: popper.js is loaded into the main.bundle.js from npm
                // This is just an example
                case ('popper-js'):
                    return str_replace( ' min.js', 'min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"', $tag );
                break;

                // example adding CDN integrity and crossorigin attributes
                // Note: bootstrap.js is loaded into the main.bundle.js from npm
                // This is just an example
                case ('bootstrap-js'):
                    return str_replace( ' min.js', 'min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"', $tag );
                break;

                default:
                    return $tag;

            } // /switch
        }

        // Remove 'text/css' from our enqueued stylesheet
        public function style_remove($tag) {
            return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
        }

        // Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
        public function conditionally_remove_wc_assets() {
            // if WooCommerce is not active, abort.
            if ( ! class_exists( 'WooCommerce' ) ) {
                return;
            }

            // if this is a WooCommerce related page, abort.
            if ( is_woocommerce() || is_cart() || is_checkout() || is_page( array( 'my-account' ) ) ) {
                return;
            }

            // custom pages need load wc staff
            $load_wc_assets_or_not = apply_filters( 'pb_load_wc_assets_or_not', false );
            if ( $load_wc_assets_or_not ) {
                return;
            }

            remove_action( 'wp_enqueue_scripts', [ WC_Frontend_Scripts::class, 'load_scripts' ] );
            remove_action( 'wp_print_scripts', [ WC_Frontend_Scripts::class, 'localize_printed_scripts' ], 5 );
            remove_action( 'wp_print_footer_scripts', [ WC_Frontend_Scripts::class, 'localize_printed_scripts' ], 5 );
        }

        public function manage_woocommerce_styles() {
            if (!class_exists('woocommerce')) {
                return;
            }

            //remove generator meta tag
            if (isset($GLOBALS['woocommerce'])) {
                remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
            }

            //first check that woo exists to prevent fatal errors
            if ( function_exists( 'is_woocommerce' ) ) {
                //dequeue scripts and styles
                if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
                    wp_dequeue_style( 'wc-block-style' );
                }
            }
        }

        // Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
        public function remove_thumbnail_dimensions( $html ) {
            $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
            return $html;
        }

        // Add page slug to body class, love this - Credit: Starkers Wordpress Theme
        public function add_slug_to_body_class($classes) {
            global $post;
            if (is_home()) {
                $key = array_search('blog', $classes);
                if ($key > -1) {
                    unset($classes[$key]);
                }
            } elseif (is_page()) {
                $classes[] = sanitize_html_class($post->post_name);
            } elseif (is_singular()) {
                $classes[] = sanitize_html_class($post->post_name);
            }

            return $classes;
        }

        // Remove invalid rel attribute values in the categorylist
        public function remove_category_rel_from_category_list($thelist) {
            return str_replace('rel="category tag"', 'rel="tag"', $thelist);
        }

        // Custom View Article link to Post 閱讀更多
        public function excerpt_more_link($more) {
            global $post;
            return '
                ... 
                <p>
                    <a 
                        class="view-article btn btn-secondary" 
                        href="' . get_permalink($post->ID) . '" 
                        role="button"
                    >' . 
                    __('Read more', 'pan-bootstrap') . 
                    '
                    </a>
                </p>
            ';
        }

        // Custom Gravatar in Settings > Discussion
        public function add_custom_gravatar ($avatar_defaults) {
            // $myavatar = PB_THEME_URI . '/img/gravatar.jpg';
            // $avatar_defaults[$myavatar] = "Custom Gravatar";
            return $avatar_defaults;
        }

        // Threaded Comments
        public function enable_threaded_comments() {
            if (!is_admin()) {
                if (
                    is_singular() && 
                    comments_open() && 
                    (get_option('thread_comments') == 1)
                ) {
                    wp_enqueue_script('comment-reply');
                }
            }
        }

        // 前台搜尋支援的 post types
        public function frontend_search_filter($query) {
            if (!is_admin()) {
                // $support_post_types = array('post', 'product');
                // if ($query->is_search) {
                //     $query->set('posts_per_page', 12);
                //     $query->set('post_type', $support_post_types);
                //     $sort = (isset($_GET['sort_search']))? sanitize_text_field( $_GET['sort_search'] ):'by_time';
                //     if ($sort === 'by_sales') {
                //         $query->set('meta_key', 'total_sales');
                //         $query->set('orderby', 'meta_value');
                //         $query->set('order', 'DESC');
                //     }
                // }
            }
        }
    }
}

PB_Theme_Setup::get_instance();