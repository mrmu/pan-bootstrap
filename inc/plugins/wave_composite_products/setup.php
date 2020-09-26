<?php

add_action( 'pb_not_cable_product_summary_left', 'pb_add_series_products_dropdown', 70);
function pb_add_series_products_dropdown() {
    global $product;
    $series = get_post_meta($product->get_id(), 'series', true);
    if (!empty($series)) {
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'orderby'   => 'date',
            'order' => 'DESC',
            'meta_key'  => 'series',
            'meta_value' => $series,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ),
                array(
                    'taxonomy' => 'product_type',
                    'field' => 'name',
                    'terms' => 'wave_composite',
                    'operator' => 'NOT IN',
                )
            )
        );
    
        $s_query = new WP_Query( $args );
        $series_products = array();
        // series
        if ( $s_query->have_posts() ) {
            while ( $s_query->have_posts() ) {
                $s_query->the_post();
                $series_products[] = array( 
                    'ID' => $s_query->post->ID,
                    'post_title' => $s_query->post->post_title,
                    'permalink' => get_permalink( $s_query->post->ID )
                );
            }
        }
        wp_reset_postdata();

        echo '<select id="series_product" class="form-control">';
        echo '<option value=""> - Series products - </option>';
        foreach ($series_products as $sp) {
            echo '<option value="'.$sp['permalink'].'">'.$sp['post_title'].'</option>';
        }
        echo '</select>';
    }
}

// 出現在哪個HOOK: pb_not_cable_product_summary_right, woocommerce_before_add_to_cart_form
// 客製品與標準品的切換連結
add_action( 'pb_not_cable_product_summary_right', 'pb_add_standard_custom_swich_link', 70);
function pb_add_standard_custom_swich_link() {
    global $product;
    // 標準品 ID
    $standard_pid = get_post_meta($product->get_id(), 'standard_product_id', true);
    if ('publish' !== get_post_status($standard_pid)) {
        $standard_pid = '';
    }
    // 客製品 ID
    $custom_pid = get_post_meta($product->get_id(), 'custom_product_id', true);
    if ('publish' !== get_post_status($custom_pid)) {
        $custom_pid = '';
    }

    $link = '';
    $text = '';
    // 若目前商品是客製品 (composite)，且對應的標準品為已發佈狀態
    if ($product->is_type('wave_composite') && !empty($standard_pid)) {
        $link = get_permalink($standard_pid);
        $text = 'Standard';
    }
    // 若目前商品是標準品，且對應的客製品為已發佈狀態
    elseif (!empty($custom_pid)) {
        $link = get_permalink($custom_pid);
        $text = 'Customization';
    }

    if (!empty($link) && !empty($text)) {
        echo '<a href="'.$link.'" class="mb-2 float-right btn btn-outline-dark">'.$text.' <i class="fa fa-arrow-right"></i></a>';
    }
}

// 只有 cable 的 antenna connectors 有列出來的 term，才能被 antenna 客製品選擇
add_filter('wave_composite_variable_component_children', 'pb_filter_enabled_antenna_connectors', 10, 2);
function pb_filter_enabled_antenna_connectors($variation_ids, $cable_id) {
    // 取出此 cable 允許使用的 connectors
    if( have_rows('antenna_connector_ids', $cable_id) ){
        $valid_conn_slugs = array();        
        while ( have_rows('antenna_connector_ids', $cable_id) ) : 
            the_row();
            // display a sub field value
            $connector_id = get_sub_field('connector_id');
            $conn_term = get_term_by('id', $connector_id, 'pa_connector-a');
            $valid_conn_slugs[] = $conn_term->slug;
        endwhile;
    }else{
        // no data
    }

    $valid_variation_ids = array();
    for ($i = 0; $i < sizeof($variation_ids); $i++) {
        $vid = $variation_ids[$i];
        $conn_a_slug = get_post_meta($vid, 'attribute_pa_connector-a', true);
        $conn_b_slug = get_post_meta($vid, 'attribute_pa_connector-b', true);
        // 天線用的 cable 沒有 connector b
        if ((in_array($conn_a_slug, $valid_conn_slugs)) && empty($conn_b_slug) ){
            $valid_variation_ids[] = $vid;
        }
    }

    return $valid_variation_ids;
}