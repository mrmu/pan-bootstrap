<?php
/**
 * admin/product_category.php : 商品分類客製
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */


function pm_product_cat_banner_fields_js() {
	?>
	<script type="text/javascript">

		var banner_set = function (banner_name, file_frame) {

			// Only show the "remove image" button when needed
			if ( 0 == jQuery( '#product_cat_'+banner_name+'_id' ).val() ) {
				jQuery( '.remove_'+banner_name+'_button' ).hide();
			}

			jQuery( document ).on( 'click', '.upload_'+banner_name+'_button', function( event ) {

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.downloadable_file = wp.media({
					title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
					button: {
						text: '<?php _e( "Use image", "woocommerce" ); ?>'
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
					var attachment_banner = attachment.sizes.banner || attachment.sizes.full;

					jQuery( '#product_cat_'+banner_name+'_id' ).val( attachment.id );
					jQuery( '#product_cat_'+banner_name ).find( 'img' ).attr( 'src', attachment_banner.url );
					jQuery( '.remove_'+banner_name+'_button' ).show();
				});

				// Finally, open the modal.
				file_frame.open();
			});

			jQuery( document ).on( 'click', '.remove_'+banner_name+'_button', function() {
				jQuery( '#product_cat_'+banner_name ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
				jQuery( '#product_cat_'+banner_name+'_id' ).val( '' );
				jQuery( '.remove_'+banner_name+'_button' ).hide();
				return false;
			});

			jQuery( document ).ajaxComplete( function( event, request, options ) {
				if ( request && 4 === request.readyState && 200 === request.status
					&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

					var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
					if ( ! res || res.errors ) {
						return;
					}
					// Clear Thumbnail fields on submit
					jQuery( '#product_cat_'+banner_name ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
					jQuery( '#product_cat_'+banner_name+'_id' ).val( '' );
					jQuery( '.remove_'+banner_name+'_button' ).hide();
					// Clear Display type field on submit
					jQuery( '#display_type' ).val( '' );
					return;
				}
			} );
		}

		var file_frame_b1, file_frame_b2, file_frame_b3;
		banner_set('banner1', file_frame_b1);
		banner_set('banner2', file_frame_b2);
		banner_set('banner3', file_frame_b3);

	</script>
	<?php
}
add_action( 'product_cat_add_form_fields', 'pm_product_cat_add_fields', 15 );
function pm_product_cat_add_fields() {
	?>
	<div class="form-field term-banner1-wrap">
		<label>首圖1</label>
		<div id="product_cat_banner1" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="100px" height="60px" /></div>
		<div style="line-height: 60px;">
			<input type="hidden" id="product_cat_banner1_id" name="product_cat_banner1_id" />
			<button type="button" class="upload_banner1_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
			<button type="button" class="remove_banner1_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
		</div>
		<div class="clear"></div>
	</div>
	<div class="form-field term-banner1-url-wrap">
		<label>首圖1 連結</label>
		<div style="line-height: 60px;">
			<input type="url" id="product_cat_banner1_url" name="product_cat_banner1_url" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="form-field term-banner2-wrap">
		<label>首圖2</label>
		<div id="product_cat_banner2" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="100px" height="60px" /></div>
		<div style="line-height: 60px;">
			<input type="hidden" id="product_cat_banner2_id" name="product_cat_banner2_id" />
			<button type="button" class="upload_banner2_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
			<button type="button" class="remove_banner2_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
		</div>
		<div class="clear"></div>
	</div>
	<div class="form-field term-banner2-url-wrap">
		<label>首圖2 連結</label>
		<div style="line-height: 60px;">
			<input type="url" id="product_cat_banner2_url" name="product_cat_banner2_url" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="form-field term-banner3-wrap">
		<label>首圖3</label>
		<div id="product_cat_banner3" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="100px" height="60px" /></div>
		<div style="line-height: 60px;">
			<input type="hidden" id="product_cat_banner3_id" name="product_cat_banner3_id" />
			<button type="button" class="upload_banner3_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
			<button type="button" class="remove_banner3_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
		</div>
		<div class="clear"></div>
	</div>
	<div class="form-field term-banner3-url-wrap">
		<label>首圖3 連結</label>
		<div style="line-height: 60px;">
			<input type="url" id="product_cat_banner3_url" name="product_cat_banner3_url" />
		</div>
		<div class="clear"></div>
	</div>

	<?php 
	pm_product_cat_banner_fields_js();
}
add_action( 'product_cat_edit_form_fields', 'pm_product_cat_edit_fields', 15 );
function pm_product_cat_edit_fields($term) {
	
	$banner1_id = absint( get_term_meta( $term->term_id, 'banner1_id', true ) );
	if ( $banner1_id ) {
		$image1 = wp_get_attachment_thumb_url( $banner1_id );
	} else {
		$image1 = wc_placeholder_img_src();
	}
	$banner1_url = esc_url_raw ( get_term_meta( $term->term_id, 'banner1_url', true ) );

	$banner2_id = absint( get_term_meta( $term->term_id, 'banner2_id', true ) );
	if ( $banner2_id ) {
		$image2 = wp_get_attachment_thumb_url( $banner2_id );
	} else {
		$image2 = wc_placeholder_img_src();
	}
	$banner2_url = esc_url_raw ( get_term_meta( $term->term_id, 'banner2_url', true ) );

	$banner3_id = absint( get_term_meta( $term->term_id, 'banner3_id', true ) );
	if ( $banner3_id ) {
		$image3 = wp_get_attachment_thumb_url( $banner3_id );
	} else {
		$image3 = wc_placeholder_img_src();
	}
	$banner3_url = esc_url_raw ( get_term_meta( $term->term_id, 'banner3_url', true ) );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label>首圖1</label></th>
		<td>
			<div id="product_cat_banner1" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image1 ); ?>" width="100px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="product_cat_banner1_id" name="product_cat_banner1_id" value="<?php echo $banner1_id; ?>" />
				<button type="button" class="upload_banner1_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
				<button type="button" class="remove_banner1_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
				<p class="description">建議尺寸：1020x480</p>
			</div>
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label>首圖1 連結</label></th>
		<td>
			<div style="line-height: 60px;">
				<input type="url" id="product_cat_banner1_url" name="product_cat_banner1_url" value="<?php echo $banner1_url; ?>" placeholder="請輸入完整網址，連同 http://" />
			</div>
			<div class="clear"></div>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label>首圖2</label></th>
		<td>
			<div id="product_cat_banner2" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image2 ); ?>" width="100px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="product_cat_banner2_id" name="product_cat_banner2_id" value="<?php echo $banner2_id; ?>" />
				<button type="button" class="upload_banner2_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
				<button type="button" class="remove_banner2_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
				<p class="description">建議尺寸：1020x480</p>
			</div>
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label>首圖2 連結</label></th>
		<td>
			<div style="line-height: 60px;">
				<input type="url" id="product_cat_banner2_url" name="product_cat_banner2_url" value="<?php echo $banner2_url; ?>" placeholder="請輸入完整網址，連同 http://" />
			</div>
			<div class="clear"></div>
		</td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label>首圖3</label></th>
		<td>
			<div id="product_cat_banner3" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image3 ); ?>" width="100px" height="60px" /></div>
			<div style="line-height: 60px;">
				<input type="hidden" id="product_cat_banner3_id" name="product_cat_banner3_id" value="<?php echo $banner3_id; ?>" />
				<button type="button" class="upload_banner3_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
				<button type="button" class="remove_banner3_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
				<p class="description">建議尺寸：1020x480</p>
			</div>
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label>首圖3 連結</label></th>
		<td>
			<div style="line-height: 60px;">
				<input type="url" id="product_cat_banner3_url" name="product_cat_banner3_url" value="<?php echo $banner3_url; ?>" placeholder="請輸入完整網址，連同 http://" />
			</div>
			<div class="clear"></div>
		</td>
	</tr>

	<?php
	pm_product_cat_banner_fields_js();
}

add_action( 'created_term', 'pm_save_category_fields', 15, 3 );
add_action( 'edit_term', 'pm_save_category_fields', 15, 3 );
function pm_save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {

	if ( isset( $_POST['product_cat_banner1_id'] ) && 'product_cat' === $taxonomy ) {
		update_woocommerce_term_meta( $term_id, 'banner1_id', absint( $_POST['product_cat_banner1_id'] ) );
	}
	if ( isset( $_POST['product_cat_banner1_url'] ) && 'product_cat' === $taxonomy ) {
		update_woocommerce_term_meta( $term_id, 'banner1_url', esc_url_raw ( $_POST['product_cat_banner1_url'] ) );
	}

	if ( isset( $_POST['product_cat_banner2_id'] ) && 'product_cat' === $taxonomy ) {
		update_woocommerce_term_meta( $term_id, 'banner2_id', absint( $_POST['product_cat_banner2_id'] ) );
	}
	if ( isset( $_POST['product_cat_banner2_url'] ) && 'product_cat' === $taxonomy ) {
		update_woocommerce_term_meta( $term_id, 'banner2_url', esc_url_raw ( $_POST['product_cat_banner2_url'] ) );
	}

	if ( isset( $_POST['product_cat_banner3_id'] ) && 'product_cat' === $taxonomy ) {
		update_woocommerce_term_meta( $term_id, 'banner3_id', absint( $_POST['product_cat_banner3_id'] ) );
	}
	if ( isset( $_POST['product_cat_banner3_url'] ) && 'product_cat' === $taxonomy ) {
		update_woocommerce_term_meta( $term_id, 'banner3_url', esc_url_raw ( $_POST['product_cat_banner3_url'] ) );
	}
}
