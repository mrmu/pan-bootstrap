<?php
// pb_after_add_to_cart, woocommerce_after_add_to_cart_button
add_action('pb_after_add_to_cart', 'pb_add_raq_btn');
function pb_add_raq_btn() {
	global $product;
	echo do_shortcode('[wave_raq_btn page="raq" class="btn btn-outline-primary" product_id="'.$product->get_id().'"]');
}

add_filter( 'woocommerce_form_field' , 'pb_remove_checkout_optional_fields_label', 10, 4 );
function pb_remove_checkout_optional_fields_label( $field, $key, $args, $value ) {
    // Only on raq page
    if( is_page('raq') ) {
        $optional = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
        $field = str_replace( $optional, '', $field );
    }
    return $field;
}