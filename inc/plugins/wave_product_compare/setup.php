<?php
add_action('woocommerce_after_add_to_cart_button', 'pb_add_product_compare_btn');
function pb_add_product_compare_btn() {
	global $product;
	if ($product->get_type() !== 'variable') {
		// product compare btn
		do_action('wave_pc_single_product');
	}
}

add_filter('wave_pc_before_render_compare_fields', 'pb_custom_product_compare_fds');
function pb_custom_product_compare_fds($compare_fds) {
	$ary_new_fds = array( 
		// meta key: product_number
		'product_number' => array(
			'val' => 'P/N',
			'type' => 'meta'
		),
		// meta key: spec_list
		'specification' => array( 
			'val' => 'Spec list',
			'type' => 'meta'
		)
	);
	$fds_rst = array_merge($ary_new_fds, $compare_fds);

	// 顯示順序
	$ary_fds_order = array( 
		'image', 'title', 
		'product_number',
		'price', 'weight', 'dimensions',
		'specification' 
	);
	$fds_reorder = array();
	foreach($ary_fds_order as $key) {
		$fds_reorder[$key] = $fds_rst[$key];
	}
	// print_r($fds_reorder);
	return $fds_reorder;
}

// 在頁面底下顯示 tab
add_action('wp_footer', 'pb_display_wave_product_compare_tab');
function pb_display_wave_product_compare_tab() {
	// 首頁不顯示
	if (!is_home() && !is_front_page()) {
		echo do_shortcode('[wave_product_compare_tab]');
	}
}