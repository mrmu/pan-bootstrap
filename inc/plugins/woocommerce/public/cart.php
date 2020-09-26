<?php
/**
 * frontend/cart.php : 購物車客製
 *
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

// 修正購物車頁 package name: "運送方式 1"
add_filter( 'woocommerce_shipping_package_name', 'pm_shipping_package_name', 10, 3 ); 
function pm_shipping_package_name($origin_name, $i, $package) {
	$packages = WC()->shipping->get_packages();
	if (count($packages) <= 1) {
		return __( 'Shipping', 'woocommerce' ); 
	}
	return $origin_name;
}