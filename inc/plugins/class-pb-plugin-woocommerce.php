<?php
/**
 * Plugin-WooCommerce - 客製 WooCommerce 的前後台版面與行為
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Plugin_WooCommerce' ) ) {

	class PB_Plugin_WooCommerce {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {            
            add_action( 'after_setup_theme', array($this, 'add_wc_support') );
            // woocommerce admin header 會蓋住上方頁面標題
            // add_action( 'admin_head', array($this, 'fix_wc_admin_page_style') );
        }

        public function add_wc_support() {
            add_theme_support( 'woocommerce' );
    
            // Add New Woocommerce 3.0.0 Product Gallery support.
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-slider' );
    
            // hook in and customizer form fields.
            add_filter( 'woocommerce_form_field_args', array($this, 'wc_form_field_args'), 10, 3 );
        }

        // 讓 wc form 欄位的輸出符合 bootstrap 樣式
        public function wc_form_field_args( $args, $key, $value = null ) {
            // Start field type switch case.
            switch ( $args['type'] ) {
                /* Targets all select input type elements, except the country and state select input types */
                case 'select':
                    // Add a class to the field's html element wrapper - woocommerce
                    // input types (fields) are often wrapped within a <p></p> tag.
                    $args['class'][] = 'form-group';
                    // Add a class to the form input itself.
                    $args['input_class']       = array( 'form-control', 'input-lg' );
                    $args['label_class']       = array( 'control-label' );
                    $args['custom_attributes'] = array(
                        'data-plugin'      => 'select2',
                        'data-allow-clear' => 'true',
                        'aria-hidden'      => 'true',
                        // Add custom data attributes to the form input itself.
                    );
                    break;
                // By default WooCommerce will populate a select with the country names - $args
                // defined for this specific input type targets only the country select element.
                case 'country':
                    $args['class'][]     = 'form-group single-country';
                    $args['label_class'] = array( 'control-label' );
                    break;
                // By default WooCommerce will populate a select with state names - $args defined
                // for this specific input type targets only the country select element.
                case 'state':
                    // Add class to the field's html element wrapper.
                    $args['class'][] = 'form-group';
                    // add class to the form input itself.
                    $args['input_class']       = array( '', 'input-lg' );
                    $args['label_class']       = array( 'control-label' );
                    $args['custom_attributes'] = array(
                        'data-plugin'      => 'select2',
                        'data-allow-clear' => 'true',
                        'aria-hidden'      => 'true',
                    );
                    break;
                case 'password':
                case 'text':
                case 'email':
                case 'tel':
                case 'number':
                    $args['class'][]     = 'form-group';
                    $args['input_class'] = array( 'form-control', 'input-lg' );
                    $args['label_class'] = array( 'control-label' );
                    break;
                case 'textarea':
                    $args['input_class'] = array( 'form-control', 'input-lg' );
                    $args['label_class'] = array( 'control-label' );
                    break;
                case 'checkbox':
                    $args['label_class'] = array( 'custom-control custom-checkbox' );
                    $args['input_class'] = array( 'custom-control-input', 'input-lg' );
                    break;
                case 'radio':
                    $args['label_class'] = array( 'custom-control custom-radio' );
                    $args['input_class'] = array( 'custom-control-input', 'input-lg' );
                    break;
                default:
                    $args['class'][]     = 'form-group';
                    $args['input_class'] = array( 'form-control', 'input-lg' );
                    $args['label_class'] = array( 'control-label' );
                    break;
            } // end switch ($args).
            return $args;
        }

        public function fix_wc_admin_page_style() {
            echo '<style>.woocommerce-embed-page .wrap{margin-top: 50px;}</style>';
        }
    }
}

PB_Plugin_WooCommerce::get_instance();