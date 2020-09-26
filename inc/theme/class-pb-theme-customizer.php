<?php
/**
 * Customizer Initialization
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_Customizer' ) ) {

	class PB_Theme_Customizer {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            add_action( 'customize_register', array($this, 'customizer_reg') );
            add_action( 'wp_head', array($this, 'customizer_apply_css') );
        }

        // Register Controls
        public function customizer_reg( $wp_customize ) {

            // Header Image
            $wp_customize->add_setting('header_image');
            $wp_customize->add_section( 
                'home_header_banner_section', 
                array(
                    'title' => __( 'Home Header Banner', 'pan-bootstrap' ),
                    'priority' => 10,
                )
            );
            $wp_customize->add_control(
                new WP_Customize_Cropped_Image_Control(
                    $wp_customize, 
                    'header_image', 
                    array(
                        'label' => 'Add Image',
                        'section' => 'home_header_banner_section',
                        'settings' => 'header_image',
                        'width' => 2000,
                        'height' => 1400
                    )
                )
            );

            // Pirmary Color
            $wp_customize->add_setting( 'primary_color' , array(
                'default'     => '#116bb0',
                'transport'   => 'refresh',
            ) );
        
            $wp_customize->add_section( 
                'theme_colors_section', 
                array(
                    'title' => __( 'Theme Colors Settings', 'pan-bootstrap' ),
                    'priority' => 5,
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Color_Control( 
                    $wp_customize, 
                    'primary_color', 
                    array(
                        'label'      => __( 'Primary Color', 'pan-bootstrap' ),
                        'section'    => 'theme_colors_section',
                        'settings'   => 'primary_color',
                    )
                )
            );
        
        }

        public function customizer_apply_css() {
            $css_primary_color = get_theme_mod('primary_color'); 
            if (!empty($css_primary_color)) {
                ?>
                <style type="text/css">
                a{color: <?php echo $css_primary_color;?>;}
                a:hover{color: <?php echo $css_primary_color;?>;}
                .btn-primary{
                    background-color: <?php echo $css_primary_color;?>;
                    border-color: <?php echo $css_primary_color;?>;
                }
                .btn-primary:hover{
                    background-color: <?php echo $css_primary_color;?>;
                    border-color: <?php echo $css_primary_color;?>;
                }
                h3.widget-title{border-left: 5px solid <?php echo $css_primary_color;?>;}
                </style>
                <?php
            }
        }
    }
}

PB_Theme_Customizer::get_instance();