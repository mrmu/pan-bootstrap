<?php
/**
 * Basic SEO Initialization
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'PB_Theme_SEO' ) ) {

	class PB_Theme_SEO {

		private static $instance;

		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
			add_action( 'after_setup_theme', array($this, 'add_theme_title_tag') );
			add_filter( 'document_title_parts', array($this, 'custom_doc_title', 10) );
			add_action( 'wp_head', array($this, 'insert_meta_in_head', 5) );
        }

		// Let WordPress manage the document title.
		public function add_theme_title_tag() {
			if (!is_admin()) {
				// auto gen <title>
				if (!current_theme_supports('title-tag')) {
					add_theme_support( 'title-tag' );
				}
			}
		}

		public function custom_doc_title( $title_parts ) {

			$title = $title_parts['title'];
			// $site_name = $title_parts['site'];

			// if ( is_tax( 'custom_tag' ) ) {
			// 	// $title = '';
			// }

			if (is_singular()) {
				// $title = 'Singular: ' . $title;
			}
			elseif (is_author()) {
				// $title = 'Author: ' . $title;
			}

			$title_parts['title'] = $title;

			return $title_parts;
		}

		public function insert_meta_in_head() {
			$metas = pb_get_metas();

			// Meta Tags
			echo '<meta name="description" content="'.$metas['desc'].'"/>'."\n";
			echo '<link rel="canonical" href="'.$metas['url'].'" />'."\n";

			// FB OG Tags
			// echo '<meta property="fb:admins" content=""/>'."\n";
			echo '<meta property="og:locale" content="zh_TW" />'."\n";
			echo '<meta property="og:title" content="' . $metas['title'] . '"/>'."\n";
			echo '<meta property="og:type" content="' . $metas['type'] . '"/>'."\n";
			echo '<meta property="og:url" content="' . $metas['url'] . '"/>'."\n";
			echo '<meta property="og:site_name" content="' . get_bloginfo('sitename') . '"/>'."\n";
			echo '<meta property="og:description" content="' . $metas['desc'] . '"/>'."\n";
			foreach ($metas['images'] as $img_url) {
				echo '<meta property="og:image" content="' . $img_url . '"/>'."\n";
			}
		}
    }
}

PB_Theme_SEO::get_instance();