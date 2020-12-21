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
            add_action( 'wp_head', array($this, 'customizer_apply_css'), 30 );
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

            // Pirmary Color Hover
            $wp_customize->add_setting( 'primary_color_hover' , array(
                'default'     => '#666666',
                'transport'   => 'refresh',
            ) );

            $wp_customize->add_control( 
                new WP_Customize_Color_Control( 
                    $wp_customize, 
                    'primary_color_hover', 
                    array(
                        'label'      => __( 'Primary Color (Hover)', 'pan-bootstrap' ),
                        'section'    => 'theme_colors_section',
                        'settings'   => 'primary_color_hover',
                    )
                )
            );
        
        }

        // CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
        private function minify_css($input) {
            if(trim($input) === "") return $input;
            return preg_replace(
                array(
                    // Remove comment(s)
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                    // Remove unused white-space(s)
                    '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                    // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                    '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                    // Replace `:0 0 0 0` with `:0`
                    '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                    // Replace `background-position:0` with `background-position:0 0`
                    '#(background-position):0(?=[;\}])#si',
                    // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
                    '#(?<=[\s:,\-])0+\.(\d+)#s',
                    // Minify string value
                    '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
                    '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                    // Minify HEX color code
                    '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                    // Replace `(border|outline):none` with `(border|outline):0`
                    '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                    // Remove empty selector(s)
                    '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
                ),
                array(
                    '$1',
                    '$1$2$3$4$5$6$7',
                    '$1',
                    ':0',
                    '$1:0 0',
                    '.$1',
                    '$1$3',
                    '$1$2$4$5',
                    '$1$2$3',
                    '$1:0',
                    '$1$2'
                ),
            $input);
        }
    

        public function customizer_apply_css() {
            ob_start();
            $css_primary_color = get_theme_mod('primary_color'); 
            if (!empty($css_primary_color)) {
                ?>
                <style type="text/css">
                a{color: <?php echo $css_primary_color;?>;}
                .btn-primary{
                    background-color: <?php echo $css_primary_color;?>;
                    border-color: <?php echo $css_primary_color;?>;
                }
                h3.widget-title{border-left: 5px solid <?php echo $css_primary_color;?>;}
                header.main-header nav.navbar-light .navbar-collapse li .nav-link{
                    color: <?php echo $css_primary_color;?>;
                }
                </style>
                <?php
            }

            $css_primary_color_hover = get_theme_mod('primary_color_hover'); 
            if (!empty($css_primary_color)) {
                ?>
                <style type="text/css">
                a:hover{color: <?php echo $css_primary_color_hover;?>;}
                .btn-primary:hover{
                    background-color: <?php echo $css_primary_color_hover;?>;
                    border-color: <?php echo $css_primary_color_hover;?>;
                }
                header.main-header nav.navbar-light .navbar-collapse li .nav-link:hover{
                    color: <?php echo $css_primary_color_hover;?>;
                }
                </style>
                <?php
            }
            $results = ob_get_clean();
            echo $this->minify_css($results);
        }
    }
}

PB_Theme_Customizer::get_instance();