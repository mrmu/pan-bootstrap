<?php
// fedex 於後台更新 tracking number, ship status, ship date 時，一併更新到 order items
add_action('wave_fedex_ajax_save_tracking_form', 'pb_save_ship_status_from_fedex_form', 10, 2);
function pb_save_ship_status_from_fedex_form($order_id, $ship_info) {
	$order = wc_get_order( $order_id );
	// Wave ship status
	foreach( $order->get_items() as $item_id => $item ){
		Wave_Ship_Status_Utils::set_ship_status($item_id, $ship_info['ship_status']);
		Wave_Ship_Status_Utils::set_ship_date($item_id, $ship_info['ship_date']);
		Wave_Ship_Status_Utils::set_ship_code($item_id, $ship_info['ship_code']);
	}
}