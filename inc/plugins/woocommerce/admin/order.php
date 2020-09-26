<?php
/**
 * admin/order.php : 客製後台訂單欄位
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

// 自定時間篩選欄位 - 關閉預設欄位
add_filter( 'disable_months_dropdown', function ( $is_disable = false, $post_type ) {
	return ( $post_type === 'shop_order' ) ? true : false;
}, 10, 2 );

// 自定時間篩選欄位 - 清除預設欄位的內容
add_filter( 'months_dropdown_results', function ( array $months, $post_type ) {
	return ( $post_type === 'shop_order' ) ? array() : $months;
}, 10, 2 );

// 自定時間篩選欄位 - 加入日期篩選欄位
add_action( 'restrict_manage_posts', 'pm_date_picker_filter' );
function pm_date_picker_filter()
{
	if (isset( $_GET['post_type']) && $_GET['post_type'] === 'shop_order') {
		$date_from = ( isset( $_GET['_date_from'] ) ) ? sanitize_text_field( $_GET['_date_from'] ) : '';
		$date_to = ( isset( $_GET['_date_to'] ) ) ? sanitize_text_field( $_GET['_date_to'] ) : '';

		?>
		日期區間
		<label for="filter-by-date-from" class="screen-reader-text">開始日期</label>
		<input type="text" id="filter-by-date-from" name="_date_from" placeholder="請選擇開始日期" value="<?php esc_attr_e( $date_from ); ?>">
		<span> - </span>
		<label for="filter-by-date-to" class="screen-reader-text">結束日期</label>
		<input type="text" id="filter-by-date-to" name="_date_to" placeholder="請選擇結束日期" value="<?php esc_attr_e( $date_to ); ?>">
		<script>
			jQuery( document ).ready(function( $ ) {
				$( '#filter-by-date-from, #filter-by-date-to' ).datepicker({ dateFormat: 'yy-mm-dd' });
			});
		</script>
		<?php
	}
}

// 自定時間篩選欄位 - 修改訂單篩選 query
add_action( 'pre_get_posts', 'pm_date_picker_filter_query' );
function pm_date_picker_filter_query( WP_Query $query )
{
	global $pagenow, $typenow;

	if ( $pagenow !== 'edit.php' || $typenow !== 'shop_order' ) {
		return false;
	}

	if ( ! isset( $_GET['_date_from'] ) && ! isset( $_GET['_date_to'] ) ) {
		return false;
	}

	$query->set(
		'date_query',
		array(
			'after' => sanitize_text_field( $_GET['_date_from'] ),
			'before' => sanitize_text_field( $_GET['_date_to'] ),
			'inclusive' => true,
			'column' => 'post_date',
		)
	);

	return $query;
}

// 自訂清單上 `order_actions` 欄位的按鈕配置
add_filter( 'woocommerce_admin_order_actions', 'pm_admin_order_actions' );
function pm_admin_order_actions( array $actions )
{
	// 移除清單上更新訂單狀態用的按鈕
	unset( $actions['processing'], $actions['complete'] );
	return $actions;
}

// 更改訂單管理頁面的上方選單
add_filter( 'views_edit-shop_order', 'pm_shop_order_list_views' );
function pm_shop_order_list_views( array $views )
{
	$post_type = 'shop_order';
	$shop_order_counts = wp_count_posts( $post_type, 'readable' );
	$edit_link_template = '<a href="%s">%s <span class="count">(%d)</span></a>';

	foreach ( $views as $status_name => $edit_link ) {
		$url = add_query_arg( array(
			'post_status' => $status_name,
			'post_type' => $post_type,
		), 'edit.php' );

		switch ( $status_name ) {

			// 「處理中」的狀態文字改為「已付款」
			case 'wc-processing':
				$views[$status_name] = sprintf(
					$edit_link_template,
					esc_url( $url ),
					'已付款',
					esc_attr( $shop_order_counts->{'wc-processing'} )
				);
				break;

			// 「已取消」的狀態文字改為「付款過期」
			case 'wc-cancelled':
				$views[$status_name] = sprintf(
					$edit_link_template,
					esc_url( $url ),
					'付款過期',
					esc_attr( $shop_order_counts->{'wc-cancelled'} )
				);
				break;

			// 其他的狀態文字維持不變
			case 'all':
			case 'wc-pending':
			case 'wc-on-hold':
			case 'wc-failed':
			case 'wc-completed':
			case 'wc-refunded':
				break;

			// // 「已完成」、「已退費」不顯示
			// case 'wc-completed':
			// case 'wc-refunded':
			// 	unset( $views[$status_name] );
			// 	break;

			default:
				break;
		}
	}

	return $views;
}

// 後台訂單列表 - 更改日期格式
add_filter( 'woocommerce_admin_order_date_format', 'pm_admin_order_date_format' );
function pm_admin_order_date_format($date_format) {
	return 'Y/m/d H:i';
}

// 後台訂單列表 - 自訂欄位樣式
add_action( 'admin_print_styles', 'pm_wc_cogs_add_order_return_itmes_column_style' );
function pm_wc_cogs_add_order_return_itmes_column_style() {

    $css = <<<CSS
		.widefat .column-cb {width: 3% }
		.widefat .column-order_status {width: 5% }
		.widefat .column-customer_info {width: 7% }
		.widefat .column-order_number {width: 7%; }
		.widefat .column-customer_email {width: 10%; }
		.widefat .column-customer_message {width: 5%; }
		.widefat .column-order_date {width: 10%;}
		.widefat .column-order_date {width: 10%;}
		.widefat .column-order_total {width: 13%; }
		.widefat .column-ship_products {width: 25%; }
		.widefat .column-order_actions {width: 5%;}
		.shipped{color:#2ea2cc;}
		.tbs{color:#73a724;}
		.returning{color:#a00;}
		.back_verified{color:#888;}
		table.wp-list-table .status_head { text-indent: 0; width: 100%; }
		table.wp-list-table .status_head::after { content: ''; }
		.widefat .column-order_status mark.tips { display: inline-block; font-size: 1em; text-indent: 0; width: 100%; margin-bottom: 16px; }
		.widefat .column-order_status mark.tips::after { content: ''; }
CSS;

    wp_add_inline_style( 'woocommerce_admin_styles', $css );
}

// 後台訂單列表 - 增加自訂欄位
add_filter( 'manage_edit-shop_order_columns', 'pm_edit_shop_order_cols' );
function pm_edit_shop_order_cols($columns) {

    $new_columns = array();
    foreach ( $columns as $column_name => $column_info ) {
        $new_columns[ $column_name ] = $column_info;
		if ( 'order_title' === $column_name ) {
			$new_columns['order_number'] = '訂單編號';
			$new_columns['customer_info'] = '顧客';
		}elseif ( 'shipping_address' === $column_name) {
			$new_columns['customer_email'] = 'E-mail';
		}elseif ( 'order_total' === $column_name ) {
			// $new_columns['shop_coupon'] = "折價券編號";
			// $new_columns['order_source'] = "訂單來源<br>utm_source<br>utm_medium<br>utm_campaign<br>";
			// $new_columns['ship_products'] = '
			// 	<table style="width:100%">
			// 		<tr>
			// 			<th style="padding:0; border:0px solid; width:70%;">商品</th>
			// 			<th style="padding:0; border:0px solid; width:30%; text-align:center;">出貨狀態</th>
			// 		</tr>
			// 	</table>';
        }
    }
	unset($new_columns['order_title']);
	unset($new_columns['shipping_address']);
	unset($new_columns['order_notes']);

    return $new_columns;
}

// // 後台訂單列表 - 加上出貨狀態篩選
// add_action( 'restrict_manage_posts', 'pm_restrict_manage_posts' );
// function pm_restrict_manage_posts() {

// 	global $typenow;

// 	if ( $typenow !== 'shop_order') {
// 		return false;
// 	}

// 	$option_values = array(
// 		'all' => '出貨狀態',
// 		'tbs' => pm_get_ship_status_wording( 'tbs' ),
// 		'shipped' => pm_get_ship_status_wording( 'shipped' ),
// 		'returning' => pm_get_ship_status_wording( 'returning' ),
// 		'back_verified' => pm_get_ship_status_wording( 'back_verified' ),
// 		'wc-refunded' => wc_get_order_status_name( 'wc-refunded' ),
// 	);

// 	$selected_value = ( ! isset( $_GET['_return_status'] ) || ! in_array( $_GET['_return_status'], array_keys( $option_values ), true ) ) ? 'all' : sanitize_text_field( $_GET['_return_status'] );

// 	echo '<select name="_return_status" id="_return_status">';

// 	foreach ( $option_values as $option_value => $option_text ) {
// 		if ( $option_value === $selected_value ) {
// 			echo '<option value="' . $option_value . '" selected>' . $option_text . '</option>';
// 		} else {
// 			echo '<option value="' . $option_value . '">' . $option_text . '</option>';
// 		}
// 	}

// 	echo '</select>';

// }

// // 後台訂單列表 - 自訂搜尋結果：出貨狀態
// add_filter( 'pre_get_posts', 'pm_shop_order_search_custom_fields' );
// function pm_shop_order_search_custom_fields( $query ) {

// 	global $pagenow, $typenow;

// 	// 篩選和搜尋都會用到 post__in 欄位，若使用關鍵字搜尋，post__in 欄位會先有值，
// 	// 後續就不再使用出貨狀態欄位作篩選
// 	if ( $pagenow !== 'edit.php'
// 		|| $typenow !== 'shop_order'
// 		|| ! isset( $_GET['_return_status'] )
// 		|| ! empty($query->get( 'post__in')) 
// 		|| ! $query->is_main_query()) {
// 		return $query;
// 	}

// 	$return_status = sanitize_text_field( $_GET['_return_status'] );
// 	$order_ids = array();

// 	if ( $return_status === 'wc-refunded' ) {
// 		$parent_ids = pm_get_order_ids_refunded_parent();
// 		$refunded_ids = pm_get_order_ids_refunded( $parent_ids );

// 		// 也要包含 refund 的 ID，不然 `WC_Order::get_refunds` 會因為設定了 `post__in` 的關係而傳回空值
// 		$order_ids = array_merge( $parent_ids, $refunded_ids );

// 	} elseif ( $return_status === 'all' ) {
// 		// 預設列出所有狀態就不要介入，有些訂單還沒有出貨狀態會列不出來

// 	} else {
// 		$order_ids = pm_get_order_ids_by_ship_status( $return_status );
// 	}

// 	if ( empty( $order_ids ) ) {
// 		return $query;
// 	}

// 	// 使用出貨狀態欄位篩選出的post id
// 	$query->set( 'post__in', $order_ids );

// 	return $query;
// }

// 自訂「搜尋訂單」的搜尋 meta 欄位
add_filter( 'woocommerce_shop_order_search_fields', 'pm_admin_order_custom_search', 10, 1 );
function pm_admin_order_custom_search($ary_default_fields){
	$ary_default_fields[] = '_billing_name';
	$ary_default_fields[] = '_shipping_name';
	return $ary_default_fields;
}

// 後台訂單列表 - 加入項目明細
add_action( 'manage_shop_order_posts_custom_column', 'pm_shop_order_posts_cols', 10 );
function pm_shop_order_posts_cols($column) {

    global $post;

	switch ( $column ) {
		// case 'order_number' :
		// 	$the_order = new WC_Order($post->ID);
		// 	echo '<a href="' . admin_url( 'post.php?post=' . absint( $post->ID ) . '&action=edit' ) . '" class="row-title"><strong>#' . esc_attr( $the_order->get_order_number() ) . '</strong></a>';
		// break;

		case 'customer_info' :
			$the_order = new WC_Order($post->ID);
			if ( $the_order->get_customer_id() ) {
				$user     = get_user_by( 'id', $the_order->get_customer_id() );
				$username = '<a href="user-edit.php?user_id=' . absint( $the_order->get_customer_id() ) . '">';
				$username .= esc_html( ucwords( $user->display_name ) );
				$username .= '</a>';
			} elseif ( $the_order->get_billing_first_name() || $the_order->get_billing_last_name() ) {
				/* translators: 1: first name 2: last name */
				$username = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $the_order->get_billing_first_name(), $the_order->get_billing_last_name() ) );
			} elseif ( $the_order->get_billing_company() ) {
				$username = trim( $the_order->get_billing_company() );
			} else {
				$username = __( 'Guest', 'woocommerce' );
			}
			echo $username;
		break;

		case 'customer_email' :
			$the_order = new WC_Order($post->ID);
			if ( $the_order->get_billing_email() ) {
				echo '<small class="meta email">
					<a href="' . esc_url( 'mailto:' . $the_order->get_billing_email() ) . '">' . 
						esc_html( $the_order->get_billing_email() ) . 
					'</a></small>';
			}
			//echo '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details', 'woocommerce' ) . '</span></button>';
		break;

		//列出折價券
		case "shop_coupon" :
			$order = new WC_Order($post->ID);
			$coupons = $order->get_coupon_codes();
			if( !empty( $coupons ) ) {
				foreach ( $coupons as $coupon_code) {
					echo $coupon_code . '<br>';
				}
			} else {
				echo "無";
			}
			
		break;

		// //列出訂單來源
		// case "order_source" :
		// 	$order_source = get_post_meta( $post->ID, 'utm_source', true );
		// 	$order_medium = get_post_meta( $post->ID, 'utm_medium', true );
		// 	$order_campaign = get_post_meta( $post->ID, 'utm_campaign', true );
		// 	echo $order_source . "<br>" . $order_medium . "<br>" . $order_campaign . "<br>";
		// break;
	}

}

// 後台訂單列表 - 排序自訂欄位
add_filter( "manage_edit-shop_order_sortable_columns", 'pm_admin_order_sortable_col' );
function pm_admin_order_sortable_col( $columns ) {
    $custom = array(
        'order_number'    => '訂單編號',
    );
    return wp_parse_args( $custom, $columns );
}

// 後台 & 前台訂單內頁 - 自訂帳單欄位
add_filter( 'woocommerce_admin_billing_fields' , 'pm_admin_order_billing_custom_fields' );
function pm_admin_order_billing_custom_fields($fields)
{
	global $post;
	$order = wc_get_order($post->ID);
	$fields['name'] = array(
		'label' => '姓名',
		'value'=> get_post_meta( $order->get_id(), '_billing_name', true ),
		'show'  => true,
		//'class'   => '',
		'wrapper_class' => 'form-field-wide',
		'style' => '',
		//'id' => '',
		//'type' => '',
		//'name' => '',
		//'placeholder' => '',
		//'description' => '',
		//'desc_tip' => bool,
		//'custom_attributes' => '',
	);
	$fields['state1'] = array(
		'label' => '縣市',
		'value'=> get_post_meta( $order->get_id(), '_billing_state1', true ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => ''
	);
	$fields['city1'] = array(
		'label' => '市區',
		'value'=> get_post_meta( $order->get_id(), '_billing_city1', true ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => ''
	);

	unset($fields['company']);
	unset($fields['first_name']);
	unset($fields['last_name']);
	unset($fields['state']);
	unset($fields['city']);

	return $fields;
}

// 後台 & 前台訂單內頁 - 自訂運送欄位
add_filter( 'woocommerce_admin_shipping_fields' , 'pm_admin_order_shipping_custom_fields' );
function pm_admin_order_shipping_custom_fields($fields)
{
	global $post;
	$order = wc_get_order($post->ID);

	$fields['phone'] = array(
		'label' => '聯絡電話',
		'value' => get_post_meta( $order->get_id(), '_shipping_phone', true ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => ''
	);

	$fields['name'] = array(
		'label' => '姓名',
		'value'=> get_post_meta( $order->get_id(), '_shipping_name', true ),
		'show'  => true,
		//'class'   => '',
		'wrapper_class' => 'form-field-wide',
		'style' => '',
		//'id' => '',
		//'type' => '',
		//'name' => '',
		//'placeholder' => '',
		//'description' => '',
		//'desc_tip' => bool,
		//'custom_attributes' => '',
	);
	$fields['state1'] = array(
		'label' => '縣市',
		'value'=> get_post_meta( $order->get_id(), '_shipping_state1', true ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => ''
	);
	$fields['city1'] = array(
		'label' => '市區',
		'value'=> get_post_meta( $order->get_id(), '_shipping_city1', true ),
		'show'  => true,
		'wrapper_class' => 'form-field-wide',
		'style' => ''
	);

	unset($fields['company']);
	unset($fields['first_name']);
	unset($fields['last_name']);
	unset($fields['state']);
	unset($fields['city']);

	return $fields;
}

// // 當訂單生成時，自動為 order item 加入預設 meta
// add_action('woocommerce_checkout_order_processed', 'pm_frontend_order_init');
// // when frontend add new order.
// function pm_frontend_order_init($order_id) {
//     $order = new WC_Order( $order_id );
// 	pm_init_order_items($order_id, $order);
// 	pa_add_meta_when_order_created($order);
// }
// // when backend add new order.
// add_action('wp_insert_post', 'pm_backend_order_init');
// function pm_backend_order_init($order_id) {
// 	if (get_post_type($order_id) == 'shop_order') {
// 		$order = wc_get_order($order_id);
// 		if (!did_action('woocommerce_checkout_order_processed') && 
// 			pm_validate_backend_order_init($order)) 
// 		{
// 			pm_init_order_items($order_id, $order);
// 		}
// 	}
// }
// function pm_validate_backend_order_init($order) {
// 	// 後台新增訂單需指派購買會員，才會 return true
//     $user_meta = get_user_meta($order->get_user_id());
//     if ($user_meta) {
// 		return true;
// 	}
//     return false;
// }
// 加入 order item 的 meta 紀錄
function pm_init_order_items($order_id, $order) {
	$order_items = $order->get_items();
	foreach ( $order_items as $item_id => $order_item ) {
		// // 出貨狀態 pm_ship_status 預設為 tbs
		// if (empty(wc_get_order_item_meta($item_id, 'pm_ship_status', true))) {
		// 	wc_update_order_item_meta($item_id, 'pm_ship_status', 'tbs');
		// }
		// 成本取自商品當下設定的成本值
		if (empty(wc_get_order_item_meta($item_id, 'pm_product_cost', true))) {
			$_product = $order_item->get_product();
			$product_cost = get_post_meta($_product->get_id(), '_cost_price', true);
			wc_update_order_item_meta($item_id, 'pm_product_cost', $product_cost);
		}
	}
}

function pa_add_meta_when_order_created($order) {
	if (isset($_COOKIE['utm_source']) && $_COOKIE['utm_source']) {
		update_post_meta( $order->get_id(), 'utm_source', sanitize_text_field( $_COOKIE['utm_source'] ) );
		unset( $_COOKIE['utm_source'] );
		setcookie( 'utm_source', '', time() - ( 15 * 60 ) );
	}
	if (isset($_COOKIE['utm_medium']) && $_COOKIE['utm_medium']) {
		update_post_meta( $order->get_id(), 'utm_medium', sanitize_text_field( $_COOKIE['utm_medium'] ) );
		unset( $_COOKIE['utm_medium'] );
		setcookie( 'utm_medium', '', time() - ( 15 * 60 ) );
	}
	if (isset($_COOKIE['utm_campaign']) && $_COOKIE['utm_campaign']) {
		update_post_meta( $order->get_id(), 'utm_campaign', sanitize_text_field( $_COOKIE['utm_campaign'] ) );
		unset( $_COOKIE['utm_campaign'] );
		setcookie( 'utm_campaign', '', time() - ( 15 * 60 ) );
	}
}

// 後台訂單自訂script
add_action( 'admin_enqueue_scripts', 'pm_admin_order_enqueue' );
function pm_admin_order_enqueue($hook) {
	global $post_type;
    if ('shop_order' == $post_type && ('post.php' == $hook || 'post-new.php' == $hook )) {
		wp_enqueue_script(
			'pm_admin_product_script',
			get_bloginfo('stylesheet_directory').'/src/js/admin/pm-order.js',  //child theme dir
			array('jquery'),
			filemtime( get_template_directory().'/src/js/admin/pm-order.js' ), false
		);
	}else{
		return;
	}
}