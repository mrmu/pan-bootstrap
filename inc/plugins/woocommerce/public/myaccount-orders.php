<?php
/**
 * inc/frontend/myaccount-orders.php : 個人資訊頁 > 訂單記錄頁 客製 hooks
 *
 * 訂單列表：woocommerce/myaccount/orders.php 
 * 訂單內頁：woocommerce/myaccount/view-order.php 
 * 		訂單明細：woocommerce/order/order-details.php
 *		訂單明細項目：woocommerce/order/order-details-item.php
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

// 排除 meta: 出貨狀態(新舊名)、出貨日、物流碼、退貨日，只留自訂規格
define ("EXCLUDE_ORDER_ITEM_META_EXCEPT_SPEC",
	serialize (
		array(
			// 'pm_ship_state',
			// 'pm_ship_status',
			// 'pm_ship_date',
			// 'pm_back_verified_date',
			'pm_product_cost'
		)
	)
);

// 訂單列表增加退單查詢欄位
add_filter( 'woocommerce_account_orders_columns', 'pm_account_order_list_col', 10, 1);
function pm_account_order_list_col($cols)
{
	$col = array(
		'order-number'  => __( 'Order', 'woocommerce' ),
		'order-date'    => __( 'Date', 'woocommerce' ),
		'order-products'    => '名稱',
		'order-status'  => __( 'Status', 'woocommerce' ),
		'order-total'   => __( 'Total', 'woocommerce' ),
		// 'order-type' 	=> '類型',
		'order-actions' => '&nbsp;',
		// 'order-returns' => '&nbsp;'
	);
	return $col;
}

add_filter( 'woocommerce_display_item_meta', 'pm_trim_item_meta', 10, 3);
function pm_trim_item_meta($html, $item, $args) {
	// 在任何出現order_item meta的地方，都不自動顯示 出貨狀態、出貨日期、貨運碼
	$ary_exclude_meta = unserialize (EXCLUDE_ORDER_ITEM_META_EXCEPT_SPEC);

	$strings = array();
	$html    = '';
	$args    = wp_parse_args( $args, array(
		'before'    => '<ul class="wc-item-meta"><li>',
		'after'     => '</li></ul>',
		'separator' => '</li><li>',
		'echo'      => true,
		'autop'     => false,
	) );

	foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
		if (!in_array($meta->key, $ary_exclude_meta)) {
			$value = $args['autop'] ? wp_kses_post( wpautop( make_clickable( $meta->display_value ) ) ) : wp_kses_post( make_clickable( $meta->display_value ) );
			$strings[] = '<strong class="wc-item-meta-label">' . wp_kses_post( $meta->display_key ) . ':</strong> ' . $value;
		}
	}

	if ( $strings ) {
		$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
	}

	return $html;
}

add_filter( 'woocommerce_order_items_meta_display', 'pm_trim_order_item_meta', 10, 2);
function pm_trim_order_item_meta ($output, $obj) 
{
	// 在任何出現order_item meta的地方，都不自動顯示 出貨狀態、出貨日期、貨運碼
	$ary_exclude_meta = unserialize (EXCLUDE_ORDER_ITEM_META_EXCEPT_SPEC);
	$item_metas = explode(',', $output);

	$new_output = '';
	$meta_list = array();
	foreach ($item_metas as $m)
	{
		$meta = explode(':', $m);
		if (!empty($meta) && !empty($meta[0]) && !empty($meta[1]))
		{
			$meta_key = trim($meta[0]);
			$meta_val = trim($meta[1]);
			//echo $meta_key.':'.$meta_val.'<br>';
			if (!in_array($meta_key, $ary_exclude_meta))
			{
				$meta_list[] = wp_kses_post( $meta_key . '：' . $meta_val );
				$new_output .= implode( ',', $meta_list );
			}
		}
	}

	return $new_output; 
}

// // 物流單號 & 出貨狀態顯示: Hook 到 訂單明細項目 (order-details-item.php)
// add_action( 'woocommerce_order_item_meta_start', 'load_ship_code_fields', 10, 3);
// function load_ship_code_fields( $item_id, $item, $order) 
// {
// 	// 出貨狀態
// 	$ship_status = wc_get_order_item_meta($item_id, 'pm_ship_status', true);
// 	$ship_status_txt = pm_get_ship_status_wording($ship_status);
// 	if (isset($item['item_meta']['ship_code'])) {
// 		$ship_code = $item['item_meta']['ship_code'][0];
// 	}else{
// 		$ship_code = '無';
// 	}
/* 	?>
 	物流單號: <?php echo $ship_code;?><br>
 	出貨狀態: <?php echo $ship_status_txt; ?><br>
	 <?php
*/
// }

// // 退貨勾選: Hook 到 訂單明細項目 (order-details-item.php)
// add_action( 'pm_order_item_return_check', 'pm_display_return_checkbox', 10, 1);
// function pm_display_return_checkbox( $order_item_id ) 
// {
// 	$order_item_return = pm_check_order_item_returnable($order_item_id);

// 	if ($order_item_return['is_returnable']) {
// 		echo '<input type="hidden" name="return_item_id[]" value="' . $order_item_id . '"> 退貨';
// 	} else {
// 		echo '<div style="color:#c11">'.$order_item_return['reason'].'</div>';
// 		// echo '<a href="javascript:void(0)" onClick="alert(\'' . $order_item_return['reason'] . '\')" title="' . $order_item_return['reason'] . '">'
// 		// 	. '<i class="fa fa-ban" aria-hidden="true"></i>'
// 		// 	. '</a>';
// 	}
// }

add_filter( 'woocommerce_my_account_my_orders_query', 'pm_my_account_orders_fix', 10, 1 ); 
function pm_my_account_orders_fix( $args ) { 

	// [work around] pagination issue: https://github.com/woocommerce/woocommerce/issues/15930 
	if (pb_is_wc_endpoint_url('orders')) {
		if (isset($args['page'])) {
			$args['paged'] = $args['page'];
		}
		if (is_numeric($args['customer'])){
			$args['customer_id'] = absint($args['customer']);
		}
		//　只顯示 6　個月內的訂單紀錄( 取消六個月限制 )
		// $args['date_created'] = '>' . ( time() - 6 * MONTH_IN_SECONDS );

	}
    return $args; 
}; 