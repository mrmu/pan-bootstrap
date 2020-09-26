<?php
/**
 * single-product : 客製商品內頁的呈現
 *
 * 商品內頁的 template： 
 *	/themes/xxx/woocommerce/single-product.php 外層架構
 *	/themes/xxx/woocommerce/content-single-product.php 版面內容
 *	/themes/xxx/woocommerce/single-product/* 局部版面
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

add_action( 'pm_single_product_title', 'woocommerce_template_single_title', 10);
add_action( 'pm_single_product_excerpt', 'woocommerce_template_single_excerpt', 10);
add_action( 'pm_single_product_price', 'woocommerce_template_single_price', 20);
add_action( 'pm_single_product_add_to_cart', 'woocommerce_template_single_add_to_cart', 30);
add_action( 'pm_single_product_meta', 'woocommerce_template_single_meta', 40);
add_action( 'pm_single_product_images', 'woocommerce_show_product_images', 20 );

// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'pb_custom_single_add_to_cart_text', 10); 
function pb_custom_single_add_to_cart_text($default) {
    return 'Buy Now <i>C</i>'; 
}

// remove_action ( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
// add_action ( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20);

// Remove the additional information tab
add_filter( 'woocommerce_product_tabs', 'pb_remove_product_tabs', 99 );
function pb_remove_product_tabs( $tabs ) {
    unset( $tabs['additional_information'] );
    return $tabs;
}

// 相關商品 - loop 圖片
add_action('pm_single_product_related_img', 'pm_loop_product_related_img', 20);
function pm_loop_product_related_img()
{
	global $post;
	echo pm_get_product_list_pic($post->ID);
}

// 若商品有自訂屬性，且該屬性未套用於「變化類型」，則直接顯示於摘要說明下方
add_action('pm_single_product_excerpt', 'pm_woocommerce_product_custom_attr', 25);
function pm_woocommerce_product_custom_attr()
{ 
    global $product;
    $attributes = $product->get_attributes();

    if ( ! $attributes ) {
        return;
    }
 
    $out = '<ul class="custom-attributes">';

    foreach ( $attributes as $attribute ) 
	{
        // skip variations
        if ( $attribute['is_variation'] ) {
 	       continue;
        }
  
        if ( $attribute['is_taxonomy'] ) 
		{
            $terms = wp_get_post_terms( $product->get_id(), $attribute['name'], 'all' );
 
            // get the taxonomy
            $tax = $terms[0]->taxonomy;
 
            // get the tax object
            $tax_object = get_taxonomy($tax);
 
            // get tax label
            if ( isset ($tax_object->labels->name) ) {
                $tax_label = $tax_object->labels->name;
            } elseif ( isset( $tax_object->label ) ) {
                $tax_label = $tax_object->label;
            }
 
            foreach ( $terms as $term ) 
			{
                $out .= '<li class="' . esc_attr( $attribute['name'] ) . ' ' . esc_attr( $term->slug ) . '">';
                $out .= '<span class="attribute-label">' . $tax_label . '：</span> ';
                $out .= '<span class="attribute-value">' . $term->name . '</span></li>';
            }
        } 
		else 
		{
            $out .= '<li class="' . sanitize_title($attribute['name']) . ' ' . sanitize_title($attribute['value']) . '">';
            $out .= '<span class="attribute-label">' . $attribute['name'] . '：</span> ';
            $out .= '<span class="attribute-value">' . $attribute['value'] . '</span></li>';
        }
    }
 
    $out .= '</ul>';
    echo $out;
}

// 移除自訂頁籤內容的 title
add_filter('yikes_woocommerce_custom_repeatable_product_tabs_heading', 'pa_remove_tab_title', 20, 2);
function pa_remove_tab_title( $tab_title_html, $tab ){
    return '';
}

add_action('woocommerce_after_add_to_cart_form', 'pb_attachs_download_links');
function pb_attachs_download_links() {
    global $post;
    $ary_attach = array('attach1', 'attach2', 'attach3', 'attach4', 'attach5', 'attach6');
    ?>
    <ul class="download_links d-flex pl-0">
        <?php
        $user_id = get_current_user_id();
        // 已登入
        if (!empty($user_id)) {
            $billing_email = get_user_meta($user_id, 'billing_email', true);
            // // 曾購買本商品
            // if (wc_customer_bought_product( $billing_email, $user_id, $post->ID )) {
                // 顯示文件載點
                foreach ($ary_attach as $attach) {
                    $attach_title = get_post_meta($post->ID, $attach.'_title', true);
                    $attach_id = get_post_meta($post->ID, $attach.'_path', true);
                    $attach_url = wp_get_attachment_url($attach_id);
                    if (!empty($attach_title) && !empty($attach_id)) {
                        ?>
                        <li class="text-center">
                            <a href="<?php echo $attach_url;?>" target="_blank">
                                <i class="text-center icon icon-file"></i>
                                <div class="mt-2 text-center" style="font-size: 12px;">
                                    <?php echo $attach_title;?>
                                </div>
                            </a>
                        </li>
                        <?php
                    }
                }
            // }
        }
        ?>
    </ul>
    <?php
}