<?php
// Adding svg extension
function pb_add_mime_types($mime_types){
    $mime_types['svg'] = 'image/svg+xml';
    return $mime_types;
}
add_filter('upload_mimes', 'pb_add_mime_types', 1, 1);

// 特定頁面停用 Gutenburg/Classic Editors，方便 metabox 操作
function pb_disable_editor_on_pages($post_id) {
	$post = get_post($post_id); 
	$slug = $post->post_name;
	$ary_page_slugs_stop = array('home');
	if (in_array($slug, $ary_page_slugs_stop)) {
		return true;
	}
	return false;
}

// add_action( 'admin_head', 'pb_disable_classic_editor' );
// function pb_disable_classic_editor() {

// 	$screen = get_current_screen();
// 	if( 'page' !== $screen->id || ! isset( $_GET['post']) ) {
// 		return;
// 	}
//	$post_id = absint($_GET['post']);
// 	if( pb_disable_editor_on_pages( $post_id ) ) {
// 		remove_post_type_support( 'page', 'editor' );
// 	}

// }

add_filter( 'gutenberg_can_edit_post_type', 'pb_disable_gutenberg_on_pages', 10, 2 );
add_filter( 'use_block_editor_for_post_type', 'pb_disable_gutenberg_on_pages', 10, 2 );
function pb_disable_gutenberg_on_pages( $can_edit, $post_type ) {
	if( ! ( is_admin() && !empty( $_GET['post'] ) ) ) {
		return $can_edit;
	}

	$post_id = absint($_GET['post']);

	if( pb_disable_editor_on_pages( $post_id ) ) {
		$can_edit = false;
	}

	return $can_edit;
}
