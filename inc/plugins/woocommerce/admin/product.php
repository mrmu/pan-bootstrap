<?php
/**
 * admin/product.php : 客製後台商品欄位
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */


// 加入商品自訂script
add_action( 'admin_enqueue_scripts', 'pm_admin_product_enqueue' );
function pm_admin_product_enqueue($hook) {
	global $post_type;

	// 不在後台的商品新增和編輯頁，就不處理
    if ('product' == $post_type && ('post.php' == $hook || 'post-new.php' == $hook )) {

		wp_enqueue_script(
			'pm_admin_product_script',
			get_bloginfo('stylesheet_directory').'/src/js/admin/pm-product.js',  //child theme dir
			array('jquery'),
			filemtime( get_template_directory().'/src/js/admin/pm-product.js' ), false
		);
	}else{
		return;
	}
}
/*
// 單一商品頁 - 調整頁籤
add_filter( 'woocommerce_product_tabs', 'pm_adjust_woocommerce_product_tabs', 90 );
function pm_adjust_woocommerce_product_tabs( $tabs )
{
	if ( isset( $tabs['description'] ) && is_array( $tabs['description'] ) ) {
		$tabs['description']['title'] = '商品描述';
	}

	if( isset( $tabs['additional_information'] ) && is_array( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['title'] = '款式規格';
	}

	unset( $tabs['reviews'] );

	return $tabs;
}
*/
// 後台商品編輯時，供應商選單呈現方式改為供應商名稱
add_filter( 'acf/fields/user/result/name=lecturer', 'pm_adjust_admin_product_vendor_selector', 10, 4);
function pm_adjust_admin_product_vendor_selector ($result, $user, $field, $post_id) {
	$vendor_com_name = get_user_meta($user->ID, 'lecturer_name', true);
	return $vendor_com_name;
}
add_filter( 'acf/fields/user/search_columns/name=lecturer', 'pm_adjest_admin_product_vendor_selector_filter', 10, 4);
function pm_adjest_admin_product_vendor_selector_filter ($columns, $search, $WP_User_Query, $field) {
	$columns[] = 'lecturer_name';
	return $columns;
}

// //客製商品頁搜尋講師
// add_action( 'pre_get_posts', 'pm_shop_admin_product_search' );
// function pm_shop_admin_product_search( $query ) {

// 	if( !empty( $_GET['lecturer_id'] ) ) {
// 		$query->set( 'meta_value', $_GET['lecturer_id'] );
// 		$query->set('post__in', '');
// 	}
// }
// //渲染商品頁講師filter
// add_filter( 'woocommerce_product_filters', 'pm_shop_admin_product_search_select' );
// function pm_shop_admin_product_search_select( $output ) {

// 	global $wp_query;
// 	$args = array(
// 		'role'    => 'pa_lecturer',
// 		'orderby' => 'user_nicename',
// 		'order'   => 'ASC'
// 	);
// 	$lecturer = get_users( $args );

//   	$output .= '<select class="lecturer-select" name="lecturer_id"  placeholder="test">';
//   	$output .= '<option value="0">依照講師篩選</option>';
//   	foreach ($lecturer as $key => $value) {
//   		// print_r($value);
//   		$lecturer_name = get_user_meta( $value->ID, 'lecturer_name', true );

//   		$output .= '<option value="' . $value->ID . '">' . $lecturer_name . '</option>';
//   	}
// 		$output .= '</select>';
// 		$output .= '<script>$(\'.lecturer-select\').selectWoo();</script>';

//   return $output;
// }

/*
// 限制單次能夠購買的商品數量
add_filter( 'woocommerce_quantity_input_max', 'pm_quantity_input_max', 10, 2 );
function pm_quantity_input_max( $max_value, $product ) {
	if ( $max_value === -1
		|| $max_value >= SINGLE_PRODUCT_QUANTITY_MAX_VALUE ) {
		return SINGLE_PRODUCT_QUANTITY_MAX_VALUE;
	}

	return $max_value;
}

// 後台商品列表 - 自訂搜尋結果：供應商
add_filter( 'pre_get_posts', 'pm_shop_admin_product_search' );
function pm_shop_admin_product_search( $query ) {

	global $pagenow, $typenow;

	// 若非在後台的商品頁，就不處理
	if ( $pagenow !== 'edit.php'
		|| $typenow !== 'product') {
		return $query;
	}
	// 主要是讓 sql 查詢會 select 到 postmeta table
	$query->set( 'meta_key', 'vendor_account' );

	// 客製 sql where 描述
	add_filter( 'posts_where', 'pm_shop_admin_product_search_filter_where' );

	return $query;
}
// 客製 admin product search 的 sql where 描述
function pm_shop_admin_product_search_filter_where($where = '') {

	global $wpdb;

	// 當使用關鍵字查詢時
	if (isset($_GET['s']) && $_GET['s']) {
		$s = sanitize_text_field( $_GET['s'] );

		// 找出公司名符合關鍵字的供應商 user ids
		$user_meta_sql = <<<SQL
SELECT user_id FROM {$wpdb->usermeta} WHERE
	meta_key = 'vendor_company_name' AND
	meta_value LIKE '%{$s}%'
SQL;
		$rst = $wpdb->get_results($user_meta_sql);
		$uids = array();
		foreach( $rst as $row ){
			$uids[] = $row->user_id;
		}

		$in_desc = '';
		$find_vendor_desc = '';

		// 若有找到就將之合併到 where 條件
		if (!empty($uids)) {
			$in_desc = "'".implode("','", $uids)."'";
			$find_vendor_desc = <<<SQL
OR CAST({$wpdb->prefix}postmeta.meta_value AS SIGNED) IN ({$in_desc})
SQL;
		}

		$where = <<<SQL
AND (
		(
			({$wpdb->prefix}posts.post_title LIKE '%{$s}%') OR
			({$wpdb->prefix}posts.post_excerpt LIKE '%{$s}%') OR
			({$wpdb->prefix}posts.post_content LIKE '%{$s}%') {$find_vendor_desc}
		)
	)
AND {$wpdb->prefix}posts.post_type = 'product'
AND (
	{$wpdb->prefix}posts.post_status = 'publish' OR
	{$wpdb->prefix}posts.post_status = 'acf-disabled' OR
	{$wpdb->prefix}posts.post_status = 'future' OR
	{$wpdb->prefix}posts.post_status = 'draft' OR
	{$wpdb->prefix}posts.post_status = 'pending' OR
	{$wpdb->prefix}posts.post_status = 'private'
)
SQL;
	}

	return $where;
}
*/
