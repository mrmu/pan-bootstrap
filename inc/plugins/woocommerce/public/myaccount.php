<?php
/**
 * frontend/myaccount.php : 客製 my account 相關頁面
 *
 * 客製登入表單：/woocommerce/myaccount/form-login.php
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

// include_once('myaccount-orders.php');	//訂單管理頁
// include_once('myaccount-returns-list.php');	//退貨紀錄列表頁
// include_once('myaccount-return-single.php');	//退貨紀錄內頁
// include_once('myaccount-my-edit-account.php'); //自訂個人資訊頁
// include_once('myaccount-serial-numbers.php'); //自訂序號兌換頁

// Hide the title of Login/Reg Page
// 所有頁面都會是：$wp_query->query_vars['pagename']==='my-account'
// 子頁會佔用：$wp_query->query_vars[ '子頁slug' ]
// add_filter( 'the_title', 'pm_login_reg_title', 10, 1 ); 
// function pm_login_reg_title( $wc_page_endpoint_title ) { 

// 	global $wp_query;
// 	if (isset($wp_query->query_vars['pagename']) && 
// 		$wp_query->query_vars['pagename']==='my-account' && 
// 		$wc_page_endpoint_title == '會員中心' 
// 	)
// 	{
// 		return '';
// 	}
//     return $wc_page_endpoint_title; 
// }; 

// 左側選項 Wording
add_filter ( 'woocommerce_account_menu_items', 'pm_account_menu_items' );
function pm_account_menu_items($items) {

	$items = array(
		'dashboard'       => __( 'Dashboard', 'woocommerce' ),
		'orders'          => __( 'Orders', 'woocommerce' ),
		// 'downloads'       => __( 'Downloads', 'woocommerce' ),
		'edit-address'    => __( 'Addresses', 'woocommerce' ),
		// 'payment-methods' => __( 'Payment methods', 'woocommerce' ),
		'edit-account'    => __( 'Account details', 'woocommerce' ),
		'customer-logout' => __( 'Logout', 'woocommerce' ),
	);
	return $items;
}

// 個人資訊：建立自訂頁籤
// Actions used to insert a new endpoint in the WordPress.
// add_action( 'init', 'pm_add_account_endpoints' );
// function pm_add_account_endpoints() 
// {
// 	add_rewrite_endpoint( 'my-edit-account', EP_ROOT | EP_PAGES );
// 	add_rewrite_endpoint( 'returns', EP_ROOT | EP_PAGES );
// 	add_rewrite_endpoint( 'return-single', EP_ROOT | EP_PAGES );
// 	add_rewrite_endpoint( 'serial-numbers', EP_ROOT | EP_PAGES );

// 	// add_rewrite_endpoint( 'qna', EP_ROOT | EP_PAGES );
// 	// add_rewrite_endpoint( 'wish-list', EP_ROOT | EP_PAGES );
// 	// flush_rewrite_rules();
// }
// add_filter( 'query_vars', 'pm_account_add_query_vars', 0 );
// function pm_account_add_query_vars( $vars ) 
// {
// 	$vars[] = 'my-edit-account';
// 	$vars[] = 'returns';
// 	$vars[] = 'return-single';
// 	$vars[] = 'serial-numbers';
// 	// $vars[] = 'qna';
// 	// $vars[] = 'wish-list';
// 	return $vars;
// }

// // 各子頁標題自訂
// add_filter( 'the_title', 'pm_account_endpoint_title' );
// function pm_account_endpoint_title( $title ) 
// {
// 	global $wp_query;

// 	$adds_endpoint = isset( $wp_query->query_vars[ 'edit-address' ] );
// 	$ords_endpoint = isset( $wp_query->query_vars[ 'orders' ] );
// 	$returns_endpoint = isset( $wp_query->query_vars[ 'returns' ] );
// 	$return_single_endpoint = isset( $wp_query->query_vars[ 'return-single' ] );
// 	$acct_endpoint = isset( $wp_query->query_vars[ 'edit-account' ] );
// 	$my_acct_endpoint = isset( $wp_query->query_vars[ 'my-edit-account' ] );
// 	$serial_numbers_endpoint = isset( $wp_query->query_vars[ 'serial-numbers' ] );
// 	// $qna_endpoint = isset( $wp_query->query_vars[ 'qna' ] );
// 	// $wish_list_endpoint = isset( $wp_query->query_vars[ 'wish-list' ] );
	
// 	if ( $adds_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 		$title = '地址設定';
// 		remove_filter( 'the_title', 'endpoint_title' );
// 	}
// 	elseif ( $ords_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 		$title = '訂單管理';
// 		remove_filter( 'the_title', 'endpoint_title' );
// 	}
// 	elseif ( $returns_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 		$title = '退貨紀錄';
// 		remove_filter( 'the_title', 'endpoint_title' );
// 	}
// 	elseif ( $return_single_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 		$title = '查詢退貨紀錄';
// 		remove_filter( 'the_title', 'endpoint_title' );
// 	}
// 	// elseif ( $acct_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 	// 	$title = '內建個人資訊';
// 	// 	remove_filter( 'the_title', 'endpoint_title' );
// 	// }
// 	elseif ( $my_acct_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 		$title = '我的資訊';
// 		remove_filter( 'the_title', 'endpoint_title' );
// 	}
// 	elseif ( $serial_numbers_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 		$title = '序號兌換';
// 		remove_filter( 'the_title', 'endpoint_title' );
// 	}
// 	// elseif ( $qna_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 	// 	$title = '問答紀錄';
// 	// 	remove_filter( 'the_title', 'endpoint_title' );
// 	// }
// 	// elseif ( $wish_list_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
// 	// 	$title = '願望清單';
// 	// 	remove_filter( 'the_title', 'endpoint_title' );
// 	// }

// 	return $title;
// }

// add_action ('woocommerce_customer_save_address', 'pm_redirect_after_edit_address');
// function pm_redirect_after_edit_address() {
// 	$profile_url = wc_get_endpoint_url('my-edit-account', '', wc_get_page_permalink( 'myaccount' ) );
// 	wp_safe_redirect ($profile_url);
// 	exit;
// }

/* 自訂欄位 */

// // 個人資訊 - 自訂地址資訊
// add_filter( 'woocommerce_my_account_my_address_formatted_address', 'pm_account_custom_formatted_address', 10, 3);
// function pm_account_custom_formatted_address( $address, $user_id, $name )
// {
// 	$new_address['nickname'] = get_the_author_meta( 'nickname', $user_id ); 

// 	if ( empty(get_user_meta( $user_id, $name.'_name', true ) ) ) {
// 		$full_name = get_user_meta( $user_id, 'account_name', true ); // 個資裡的名字
// 	}else{
// 		$full_name = get_user_meta( $user_id, $name.'_name', true ); // 上次結帳使用的名字
// 	}

// 	$new_address['full_name'] = $full_name;
// 	$new_address['mobile'] = get_user_meta( $user_id, 'account_mobile', true );
// 	$new_address['email'] = get_user_meta( $user_id, $name.'_email', true );
// 	$new_address['phone'] = get_user_meta( $user_id, $name.'_phone', true );
// 	$new_address['state1'] = get_user_meta( $user_id, $name.'_state1', true );
// 	$new_address['city1'] = get_user_meta( $user_id, $name.'_city1', true );

// 	$new_address['postcode'] = $address['postcode'];
// 	$new_address['address_1'] = $address['address_1'];
     
//     return $new_address;
// }

// // 個人資訊 & Admin - 訂單明細 - 訂單地址欄位
// add_filter( 'woocommerce_order_formatted_billing_address' , 'pm_order_formatted_billing_address', 10,2 );
// function pm_order_formatted_billing_address( $fields, $order ) 
// {	
// 	//print_r($fields);
// 	$fields['full_name'] = get_post_meta( $order->get_id(), '_billing_name', true ); 
// 	$fields['state1'] = get_post_meta( $order->get_id(), '_billing_state1', true );
// 	$fields['city1'] = get_post_meta( $order->get_id(), '_billing_city1', true );
// 	$fields['phone'] = get_post_meta( $order->get_id(), '_billing_phone', true );
// 	return $fields;
// }
// // 個人資訊 & Admin - 訂單明細 - 運送地址欄位
// add_filter( 'woocommerce_order_formatted_shipping_address' , 'pm_order_formatted_shipping_address', 10,2 );
// function pm_order_formatted_shipping_address( $fields, $order ) 
// {	
// 	//print_r($fields);
// 	$fields['full_name'] = get_post_meta( $order->get_id(), '_shipping_name', true ); 
// 	$fields['state1'] = get_post_meta( $order->get_id(), '_shipping_state1', true );
// 	$fields['city1'] = get_post_meta( $order->get_id(), '_shipping_city1', true );
// 	$fields['phone'] = get_post_meta( $order->get_id(), '_shipping_phone', true );
// 	// $fields['email'] = get_post_meta( $order->get_id(), '_shipping_email', true );
// 	return $fields;
// }

// // 個人資訊 - 自訂地址資訊欄位
// add_filter( 'woocommerce_formatted_address_replacements', 'pm_formatted_address_replacements',10,2 );
// function pm_formatted_address_replacements( $replacements, $address ) 
// {
// 	$replacements['{full_name}'] = isset($address['full_name']) ? $address['full_name'] : '';
// 	$replacements['{email}'] = isset($address['email']) ? $address['email'] : '';
// 	$replacements['{nickname}'] = isset($address['nickname']) ? $address['nickname'] : '';
// 	$replacements['{mobile}'] = isset($address['mobile']) ? $address['mobile'] : '';
// 	$replacements['{phone}'] = isset($address['phone']) ? $address['phone'] : '';
// 	$replacements['{state1}'] = isset($address['state1']) ? $address['state1'] : '';
// 	$replacements['{city1}'] = isset($address['city1']) ? $address['city1'] : '';

// 	return $replacements;
// }

// // 個人資訊 & Admin - 地址資訊格式
// add_filter( 'woocommerce_localisation_address_formats', 'pm_localisation_address_formats' );
// function pm_localisation_address_formats( $formats ) 
// {
// 	// Rearrange these fields how you need, each country has an entry in the array like this:
// 	$formats['default'] = "{full_name}\n {email}\n {phone}\n {postcode} {state1}{city1}{address_1}";

// 	return $formats;
// }

// // 個人資訊 - 加入存放 select 預設值的元素，供 js 設定
// add_filter('woocommerce_form_field_select', 'pm_form_state_city_select', 10, 4 );
// function pm_form_state_city_select($field, $key, $args, $value)
// {
// 	if ($key === 'billing_state1' || $key === 'billing_city1' || 
// 		$key === 'shipping_state1' || $key === 'shipping_city1')
// 	{
// 		echo '<div class="default_value" style="display:none;" id="'.$key.'_default_value">'.$value.'</div>';
// 	}

// 	return $field;
// }

// // 個人資訊：問答紀錄
// add_action( 'woocommerce_account_qna_endpoint', 'pm_qna_endpoint_content' );
// function pm_qna_endpoint_content()
// {
// 	echo 'qna content';
// }

// // 個人資訊：願望清單
// add_action( 'woocommerce_account_wish-list_endpoint', 'pm_wish_list_endpoint_content' );
// function pm_wish_list_endpoint_content()
// {
// 	echo 'wish list content';
// }
