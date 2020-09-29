<?php
/**
 * Admin general Initialization
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_Admin_General' ) ) {

	class PB_Theme_Admin_General {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
            // custom post types
            add_action( 'init', array($this, 'reg_custom_post_types' ), 10);
            add_action( 'init', array($this, 'reg_custom_taxonomy' ), 10);

            // enqueue styles & scripts
            add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_styles' ));
            add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts' ));

            // admin profile
            add_action( 'admin_head-user-edit.php', array($this, 'user_profile_css' ));
            add_action( 'admin_head-profile.php', array($this, 'user_profile_css' ));

            // wp-login
            add_action( 'login_enqueue_scripts', array($this, 'pb_login_logo' ));
            add_action( 'login_enqueue_scripts', array($this, 'pb_login_stylesheet' ));
            add_filter( 'login_headerurl', array($this, 'pb_login_logo_url' ));
            add_filter( 'login_headertext', array($this, 'pb_login_logo_url_title' ));
        }

        public function reg_custom_post_types(){

            // $labels = array(
            //     'name' => 'Case',
            //     'singular_name' => 'Case',
            //     'menu_name'          => 'Case',
            //     'name_admin_bar'     => 'Case',
            //     'add_new'            => 'Add case',
            //     'add_new_item'       => 'Add case',
            //     'new_item'           => 'New case',
            //     // 'edit_item'          => 'Edit Case',
            //     // 'view_item'          => 'View Case',
            //     // 'all_items'          => 'All Cases',
            //     // 'search_items'       => 'Search Cases',
            //     // 'not_found'          => 'Not found',
            //     // 'not_found_in_trash' => 'Not found in trash'
            // );
            // $args = array(
            //     'labels' => $labels,
            //     'public' => true,
            //     'has_archive' => true,
            //     'rewrite' => array('slug' => 'case'),
            //     //'menu_icon' => PB_THEME_URI .'/images/linkicon_cfg.png',
            //     'taxonomies' => array('case_cate'),
            //     'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments' )
            //     //'exclude_from_search' => true
            // );
            // register_post_type( 'case', $args);
        }

        // 自訂taxonomy
        public function reg_custom_taxonomy(){
        
            // $labels = array(
            //     'name'                       => 'Case category',
            //     'singular_name'              => 'Case category',
            //     'menu_name'                  => 'Case category',
            //     'all_items'                  => 'All Case category',
            //     // 'parent_item'                => '父分類',
            //     // 'parent_item_colon'          => '父分類:',
            //     // 'new_item_name'              => '新建分類名稱',
            //     // 'add_new_item'               => '新建Case category',
            //     'edit_item'                  => 'Edit case category',
            //     // 'update_item'                => '更新Case category',
            //     // 'separate_items_with_commas' => '以小寫逗號區隔分類',
            //     // 'search_items'               => '搜尋分類',
            //     // 'add_or_remove_items'        => '新增或刪除分類',
            //     // 'choose_from_most_used'      => '從最常使用的分類來選擇',
            // );
        
            // $args = array(
            //     'labels'                     => $labels,
            //     'hierarchical'               => true,
            //     'public'                     => true,
            //     'show_ui'                    => true,
            //     'show_admin_column'          => true,
            //     'show_in_nav_menus'          => true,
            //     'show_tagcloud'              => true,
            // );
        
            // register_taxonomy( 'case_cate', 'case', $args );

        }

        private function is_enqueue_pages($apply_pages, $hook, $post_type) {
            foreach ($apply_pages as $pg => $pts) {
                if ($pg === $hook) {
                    if (!empty($pts) && is_array($pts)) {
                        if (in_array($post_type, $pts)) {
                            return true;
                        }
                    }
                    return true;
                }else if ($pts === $hook) {
                    return true;
                }
            }
            return false;
        }
        
        public function admin_enqueue_styles($hook) {
            global $post, $post_type;

            if (empty($post_type)){
                if (!empty($post)) {
                    $post_type = $post->post_type;
                }
            }
        
            $load_post_types = array( 'post' );
            $apply_pages = array(
                'post-new.php' => $load_post_types, 
                'edit.php' => $load_post_types,
                'post.php' => $load_post_types,
                // $hook
            );
        
            // echo 'hook:['.$hook.'] ';
            // echo 'post_type:['.$post_type.'] ';

            if ( $this->is_enqueue_pages($apply_pages, $hook, $post_type) ) {
                // wp_enqueue_style( 
                //     'theme_admin_css', 
                //     PB_THEME_URI . '/dist/theme-admin.min.css', 
                //     array(), 
                //     filemtime( PB_THEME_DIR . '/dist/theme-admin.min.css' ),
                //     'all'
                // );
            }
        }
        
        public function admin_enqueue_scripts($hook) {
            global $post, $post_type;
            if (empty($post_type)){
                if (!empty($post)) {
                    $post_type = $post->post_type;
                }
            }
        
            $load_post_types = array( 'post' );
            $apply_pages = array(
                'post-new.php' => $load_post_types, 
                'edit.php' => $load_post_types,
                'post.php' => $load_post_types,
                // $hook
            );

            // echo 'hook:['.$hook.'] ';
            // echo 'post_type:['.$post_type.'] ';
        
            if ( $this->is_enqueue_pages($apply_pages, $hook, $post_type) ) {
                // wp_register_script(
                //     'theme_admin_js', 
                //     PB_THEME_URI . '/dist/theme-admin.min.js', 
                //     array('jquery'), 
                //     filemtime( PB_THEME_DIR . '/dist/theme-admin.min.js' ),
                //     true
                // );
                // wp_enqueue_script('theme_admin_js');
                // wp_localize_script(
                //     'theme_admin_js', 
                //     'theme_admin_obj', 
                //     array( 
                //         'ajax_url' => admin_url( 'admin-ajax.php' )
                //     )
                // );	
            }
        }

        // 客製後台 user profile 頁
        public function user_profile_css() {
            ?>
            <style>/*
            tr.user-rich-editing-wrap{ display: none; }
            tr.user-admin-color-wrap{ display: none;}
            tr.user-comment-shortcuts-wrap{ display:none;}
            */</style>
            <?php
        }

        // 客製預設登入頁
        public function pb_login_logo() {
            ?>
            <style>
                /*
                body.login{
                    background: #aaa;
                }
                body.login div#login h1 a {
                    background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png');
                    padding-bottom: 10px;
                    background-size: 90%;
                    width: 100%;
                    background-color: transparent;
                    background-position-y: 10px;
                    height: 110px;
                }
                */
            </style>
            <?php 
        }

        public function pb_login_stylesheet() {
            //wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/custom-wp-admin.css' );
        }

        public function pb_login_logo_url() {
            return home_url();
        }

        public function pb_login_logo_url_title() {
            return '返回首頁';
        }
        
    }
}

PB_Theme_Admin_General::get_instance();