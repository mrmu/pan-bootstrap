<?php
/**
 * Public general Initialization
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_Public_General' ) ) {

	class PB_Theme_Public_General {

        private static $instance;
        private $gtm_id;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            $this->gtm_id = ''; //GTM-xxxxxxx

            add_action( 'init', array($this, 'reg_shortcodes'));
            // add_action( 'wp', array($this, 'move_jquery_to_footer'), 99); 
            add_action( 'wp_enqueue_scripts', array($this, 'move_jquery_to_footer') );

            // enqueue styles & scripts
            add_action( 'wp_enqueue_scripts', array($this, 'public_enqueue_styles' ), 10);
            add_action( 'wp_enqueue_scripts', array($this, 'public_enqueue_scripts' ), 10);

            // embeded code: GTM
            add_action( 'wp_head', array($this, 'embed_gtm_code_head'), 99);
            add_action( 'wp_body_open', array($this, 'embed_gtm_code_body'), 99);
        }

        public function reg_shortcodes() {
            // some schortcodes...
        }

        // public function move_jquery_to_footer() {
        //     wp_scripts()->add_data( 'jquery', 'group', 1 );
        //     wp_scripts()->add_data( 'jquery-core', 'group', 1 );
        //     wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
        // }

        public function move_jquery_to_footer() {
            if (!is_admin()) {
                wp_deregister_script( 'jquery' );
                wp_dequeue_script( 'jquery' );

                wp_register_script( 
                    'jquery', 
                    includes_url( '/js/jquery/jquery.min.js' ), 
                    false,  // deps
                    '3.6.0', // ver 
                    true  // footer
                );
                wp_enqueue_script( 'jquery' );
            }
        }
        
        public function public_enqueue_scripts() 
        {
            $src_dir = PB_THEME_DIR . 'src/';
            $dist_dir = PB_THEME_DIR . 'dist/';
        
            $site_lang = substr(strtolower(get_locale()), 0, 2); //site lang

            // Conditional script(s)
            if (is_home() || is_front_page()) {
                wp_register_script(
                    'home_js', 
                    PB_THEME_URI . 'dist/home.min.js', 
                    array('jquery'), 
                    filemtime( $dist_dir . 'home.min.js' ),
                    true
                );
                wp_enqueue_script('home_js');
                wp_localize_script(
                    'home_js', 
                    'home_obj', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' )
                    )
                );	
            }elseif (is_singular()) {
                global $post;

                wp_register_script(
                    'single_js', 
                    PB_THEME_URI . 'dist/single.min.js', 
                    array('jquery'), 
                    filemtime( $dist_dir . 'single.min.js' ),
                    true
                );
                wp_enqueue_script('single_js');
                wp_localize_script(
                    'single_js', 
                    'single_obj', 
                    array( 
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'i18n' => array(
                            'name_is_required' => __('Name is required', 'pan-bootstrap'),
                            'email_is_required' => __('Email is required', 'pan-bootstrap'),
                            'comment_is_required' => __('Comment is required', 'pan-bootstrap')
                        )
                    )
                );		
            }elseif (is_search() || is_archive()) {
                wp_register_script(
                    'archive_js', 
                    PB_THEME_URI . 'dist/archive.min.js', 
                    array('jquery'), 
                    filemtime( $dist_dir . 'archive.min.js' ),
                    true
                ); 
                wp_enqueue_script('archive_js');
                wp_localize_script(
                    'archive_js', 
                    'arch_obj', 
                    array( 
                        's' => get_search_query(),
                        'ajax_url' => admin_url( 'admin-ajax.php' )
                    )
                );	
            }else{
                wp_register_script(
                    'archive_js', 
                    PB_THEME_URI . 'dist/archive.min.js', 
                    array('jquery'), 
                    filemtime( $dist_dir . 'archive.min.js' ),
                    true
                ); 
                wp_enqueue_script('archive_js');
                wp_localize_script(
                    'archive_js', 
                    'arch_obj', 
                    array( 
                        's' => get_search_query(),
                        'ajax_url' => admin_url( 'admin-ajax.php' )
                    )
                );	
            }
        }
        
        public function public_enqueue_styles() {
        
            $dist_dir = PB_THEME_DIR . 'dist/';

            if (is_home() || is_front_page()) {
                wp_enqueue_style( 
                    'home_styles', 
                    PB_THEME_URI . 'dist/home.min.css', 
                    array(), 
                    filemtime( $dist_dir . 'home.min.css' ),
                    'all'
                );
            }elseif (is_singular()) {
                wp_enqueue_style( 
                    'single_styles', 
                    PB_THEME_URI . 'dist/single.min.css', 
                    array(), 
                    filemtime( $dist_dir . 'single.min.css' ),
                    'all'
                );
            }elseif (is_search() || is_archive()) {
                wp_enqueue_style( 
                    'archive_styles', 
                    PB_THEME_URI . 'dist/archive.min.css', 
                    array(), 
                    filemtime( $dist_dir . 'archive.min.css' ),
                    'all'
                );
            }else{
                wp_enqueue_style( 
                    'archive_styles', 
                    PB_THEME_URI . 'dist/archive.min.css', 
                    array(), 
                    filemtime( $dist_dir . 'archive.min.css' ),
                    'all'
                );
            }
        
        }

        private function get_gtm_id() {
            return $this->gtm_id;
        }

        // GTM code
        public function embed_gtm_code_head() {
            if (!empty($this->gtm_id)) {
            ?>
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo $this->get_gtm_id();?>');</script>
            <!-- End Google Tag Manager -->
            <?php
            }
        }
        public function embed_gtm_code_body() {
            if (!empty($this->gtm_id)) {
            ?>
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $this->get_gtm_id();?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
            <?php
            }
        }        
    }
}

PB_Theme_Public_General::get_instance();