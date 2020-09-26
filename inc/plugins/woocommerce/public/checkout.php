<?php
/**
 * frontend/checkout.php : 前台結帳頁客製
 *
 * 未登入結帳時，輸入密碼加入會員的表單 /woocommerce/checkout/form-billing.php
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

 // 加上確認 checkbox
add_action( 'woocommerce_review_order_before_submit', 'pb_add_checkout_checkbox', 10 );
function pb_add_checkout_checkbox() {
   
	echo '<div class="container-fluid p-0 mb-3">';
	echo '<div class="row no-gutters">';
	echo '<div class="col-12">';
    woocommerce_form_field( 
		'agree-checkbox', array( // CSS ID
       'type'          => 'checkbox',
       'class'         => array(), // CSS Class
       'label_class'   => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
       'input_class'   => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
       'required'      => true, // Mandatory or Optional
       'label'         => 'I\'ve read and understand the payment description.', 
	)); 
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
add_action('woocommerce_checkout_process', 'pb_custom_checkout_field_process');
function pb_custom_checkout_field_process() {
    global $woocommerce;
 
    // Check if set, if its not set add an error.
    if (!$_POST['agree-checkbox']) {
		wc_add_notice( __('Please check the agree checkbox.'), 'error' );
	}
}

// 加上幣別 USD
add_action('woocommerce_before_cart', 'pb_add_price_suffix_action');
add_action('woocommerce_review_order_before_order_total', 'pb_add_price_suffix_action');
function pb_add_price_suffix($format, $currency_pos) {
	switch ( $currency_pos ) {
		case 'left' :
			$currency = get_woocommerce_currency();
			$format = $currency.'%1$s%2$s&nbsp;';
		break;
	}
 
	return $format;
}
function pb_add_price_suffix_action() {
	add_action('woocommerce_price_format', 'pb_add_price_suffix', 1, 2);
}


// 自訂 woocommerce_form_field password 元件的輸出HTML
add_filter( 'woocommerce_form_field_password', 'pm_customize_form_field_password', 10, 4 );
function pm_customize_form_field_password($field, $key, $args, $value)
{
	if ($key === 'account_password') {
		$required = '';
		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			// $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
		}
		$field = '
			<span class="pwd_wrap">
				<input type="' . esc_attr( $args['type'] ) . 
				'" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . 
				'" name="' . esc_attr( $key ) . 
				'" id="' . esc_attr( $args['id'] ) . 
				'" placeholder="' . esc_attr( $args['placeholder'] ) . 
				'"  value="' . esc_attr( $value ) . 
				'" '. 
			' /></span>';

		$field_html = '';
		$sort            = $args['priority'] ? $args['priority'] : '';
		$label_id        = $args['id'];
		$field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';

		if ( $args['label'] && 'checkbox' != $args['type'] ) {
			$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . $required . '</label>';
		}

		$field_html .= $field;

		if ( $args['description'] ) {
			$field_html .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
		}

		$container_class = esc_attr( implode( ' ', $args['class'] ) );
		$container_id    = esc_attr( $args['id'] ) . '_field';
		$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
	}
	return $field;
}
// 自訂 woocommerce_form_field checkbox 元件的輸出HTML
add_filter( 'woocommerce_form_field_checkbox', 'pm_customize_form_field_checkbox', 10, 4 );
function pm_customize_form_field_checkbox($field, $key, $args, $value)
{
	$required = '';
	if ( $args['required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
	}
	$field = '<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" '.checked( $value, 1, false ) .' /> '.
			'<label for="' . esc_attr( $key ) . '" class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . '>'.
			$args['label'] . $required . '</label>';
	return $field;
}

// 自訂 woocommerce_form_field radio 元件的輸出HTML
add_filter( 'woocommerce_form_field_radio', 'pm_customize_form_field_radio', 10, 4 );
function pm_customize_form_field_radio($field, $key, $args, $value)
{
	if ( $args['required'] ) {
		$args['class'][] = 'validate-required';
		$required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
	} else {
		$required = '';
	}

	$field = '';
	$label_id = $args['id'];
	$field_container = '<p class="form-row %1$s" id="%2$s">%3$s</p>';

	$label_id = current( array_keys( $args['options'] ) );

	if ( ! empty( $args['options'] ) ) {
		foreach ( $args['options'] as $option_key => $option_text ) {
			$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) .'" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
			$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) .'">' . $option_text . '</label>';
			$field .= '<br>';
		}
	}

	if ( ! empty( $field ) ) {
		$field_html = '';

		if ( $args['label'] && 'checkbox' != $args['type'] ) {
			$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) .'">' . $args['label'] . $required . '</label>';
		}

		$field_html .= $field;

		if ( $args['description'] ) {
			$field_html .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
		}

		$container_class = 'form-row ' . esc_attr( implode( ' ', $args['class'] ) );
		$container_id = esc_attr( $args['id'] ) . '_field';

		$after = ! empty( $args['clear'] ) ? '<div class="clear"></div>' : '';

		$field = sprintf( $field_container, $container_class, $container_id, $field_html ) . $after;
	}
	return $field;
}

// add_action( 'woocommerce_register_post', 'pm_pan_reg_before_checkout', 10, 3 );
// function pm_pan_reg_before_checkout($username, $email, $validation_errors)
// {
// 	$user_pwd = $_POST['account_password'];
// 	if (empty($user_pwd)) {
// 		$status = array( 'error' => '密碼不得為空。' );
// 	}elseif (strlen($user_pwd) < PASSWORD_DIGITS) {
// 		$status = array( 'error' => '密碼建議設定以英數字混合組成 8 位數以上。' );
// 	}elseif (!is_email($email)) {
// 		$status = array( 'error' => 'E-mail 格式不合法，請重新輸入。' );
// 	}elseif (class_exists('Wp_Pan_Login_Public')) {
// 		$opts = get_option('wpl_settings');
// 		$email = sanitize_email( $email );
// 		$password = $_POST['account_password'];

// 		$ary_args = array(
// 			'client_id' => $opts['client_id'],  //會員系統的client id
// 			'email' => $email,
// 			'password' => $password
// 		);

// 		$ret = Wp_Pan_Login_Public::curl_get_contents($opts['api_url_reg_normal'], $ary_args);
// 		if ($ret[0] === true)
// 		{
// 			$ret = json_decode($ret[1]);

// 			$ret_code = intval($ret->code);
// 			if (22===$ret_code || 21===$ret_code) { //21: Already Login
// 				// $psk_token = $ret->message->access_token;
// 				$status = array( 'success' => '已完成會員系統註冊。');
// 			}elseif (23===$ret_code) { //23: EmailNotConfirmed
// 				$status = array( 'success' => '系統已寄發會員驗證信至您的信箱，請點選驗證信內的連結以完成註冊。' );
// 			}else{
// 				switch ($ret_code) {
// 					case 41:
// 						$rst = "E-mail 未通過驗證。";
// 						break;
// 					case 42:
// 						$rst = "E-mail 已有會員使用，請更換一組。";
// 						break;
// 					case 43:
// 						$rst = "E-mail 或密碼錯誤。";
// 						break;
// 					case 44:
// 						$rst = "請輸入密碼。";
// 						break;
// 					case 45:
// 						$rst = "E-mail 應屬社群帳號，請嘗試 Facebook 或 Google 登入。";
// 						break;
// 					case 46:
// 						$rst = "(46) Client ID 有誤。";
// 						break;
// 					case 51:
// 						$rst = "伺服器錯誤，請稍候再試。";
// 						break;
// 					default:
// 						$rst = '未預期的錯誤：('.$ret_code.')'.$ret_text;
// 				}
// 				$status = array( 'error' => $rst );
// 			}
// 		}else{
// 			$status = array( 'error' => $ret[1] );
// 		}
// 	}else{
// 		$status = array( 'error' => '請先安裝 WP Pan Login 外掛。' );
// 	}
// 	if (!empty($status['error'])) {
// 		$validation_errors->add( 'error',  $status['error']);
// 	}
// }

//// TBD: 未測試
// add_action( 'user_register', 'pm_link_orders_at_registration' );
// function sv_link_orders_at_registration( $user_id ) {
//     $count = wc_update_new_customer_past_orders( $user_id );
//     update_user_meta( $user_id, '_wc_linked_order_count', $count );
// }

// add_action( 'woocommerce_before_account_orders', 'maybe_show_linked_order_count', 1 );
// function maybe_show_linked_order_count($has_orders) {

//     $user_id = get_current_user_id();

//     if ( ! $user_id ) {
//         return;
//     }

// 	// TBD: 可以在建立會員時 (user_register) 做 (上面未測試的code)，但未完成測試
//     $count = wc_update_new_customer_past_orders( $user_id );
//     update_user_meta( $user_id, '_wc_linked_order_count', $count );
	
//     // check if we've linked orders for this user at registration
//     $count = get_user_meta( $user_id, '_wc_linked_order_count', true );

//     if ( $count && $count > 0 ) {
    
//         $name = get_user_by( 'id', $user_id )->display_name;

//         $message  = $name ? sprintf( __( '%s，您好! ', 'pm' ), $name ) : __( '您好! ', 'pm' );
//         $message .= ' ' . sprintf( _n( '歡迎加入會員，您先前的訂單紀錄已經連結到您目前的帳號。', '歡迎加入會員，您先前的 %s 筆訂單紀錄已經連結到您目前的帳號。', $count, 'pm' ), $count );
//         $message .= '<br><br><a class="btn btn-primary" href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">' . esc_html__( '重新整理', 'pm' ) . '</a>';

//         // add a notice with our message and delete our linked order flag
//         wc_print_notice( $message, 'notice' );
//         delete_user_meta( $user_id, '_wc_linked_order_count' );
//     }
// }


// 結帳頁 & myaccount地址編輯頁 - 客製地址欄位
// add_filter( 'woocommerce_default_address_fields' , 'pm_override_default_address_fields' );
// function pm_override_default_address_fields( $address_fields )
// {
// 	$temp_fields = array();

// 	$address_fields['name'] = array(
// 		'label'     => __('姓名', 'woocommerce'),
// 		'required'  => true,
// 		'class'     => array('form-row-wide'),
// 		'type'  => 'text'
// 	);

// 	$address_fields['postcode'] = array(
// 		'label'     => __('郵遞區號', 'woocommerce'),
// 		'required'  => false,
// 		'class'     => array('form-row-wide'),
// 		'type'  => 'text'
// 	);

// 	$address_fields['state1'] = array(
// 		'label'     => __('縣/市', 'woocommerce'),
// 		'required'  => false,
// 		'class'     => array('form-row-first'),
// 		'type'  => 'select',
// 		'options'   => array(''=>'請選擇')
// 	);
// 	$address_fields['city1'] = array(
// 		'label'     => __('鄉/鎮/市/區', 'woocommerce'),
// 		'required'  => false,
// 		'class'     => array('form-row-last'),
// 		'type'  => 'select',
// 		'options'   => array(''=>'請選擇')
// 	);

// 	$address_fields['address_1'] = array(
// 		'placeholder' 	=> '不接受郵政信箱',
// 		'label' 		=> '地址',
// 		'required'  	=> false,
// 		'class' 		=> array('form-row-wide')
// 	);

// 	$address_fields['phone'] = array(
// 		'label'     => '聯絡電話',
// 		'required'  => false,
// 		'class'     => array('form-row-wide'),
// 		'type' => 'text'
// 	);

// 	global $pm_address_fields;
// 	$pm_address_fields = array(
// 		//'country',
// 		//'first_name',
// 		//'last_name',
// 		//'company',
// 		//'address_2',
// 		'name',
// 		//'state',
// 		//'city',
// 		'state1',
// 		'city1',
// 		'postcode',
// 		'address_1',
// 		'phone',
// 		//'email',
// 		//'mobile', //new field
// 		//'ship_time'
// 	);

//     foreach($pm_address_fields as $fky) {
// 	    $temp_fields[$fky] = $address_fields[$fky];
//     }
    
//     $address_fields = $temp_fields;

//     return $address_fields;
// }

// 結帳頁：自訂欄位 - 電子發票類型及統編
add_action('woocommerce_before_order_notes', 'pm_custom_checkout_fields_before_oo');
function pm_custom_checkout_fields_before_oo( $checkout ) 
{
/*
	echo '<div id="pm_custom_checkout_field"><h3>電子發票資訊 </h3>';

	woocommerce_form_field ( 
		'invoice_type', 
		array(
			'type'  => 'radio',
			'class' => array( 'pm-invoice_type form-row-wide pm-radio-inline' ),
			//'label' => '',
			'required'  => true,
			'default'  => 'personal',
			'options' => array(
							'personal' => '個人發票',
							'corp' => '公司戶發票 (寄送至帳單地址)'
			)
		), 
		$checkout->get_value( 'invoice_type' )
	);

	woocommerce_form_field ( 
		'invoice_number', 
		array(
			'type'  => 'text',
			'class' => array( 'pm-invoice_number form-row-wide' ),
			'label' => '公司統編',
			'required'  => false
		),
		$checkout->get_value( 'checkout_agreement' )
	);

	echo '</div><div class="clear"></div>';
*/
}

remove_action ('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action ('woocommerce_after_order_notes', 'woocommerce_checkout_payment', 20); // checkout/payment.php

// 結帳頁：自訂欄位 - 服務條款選項
add_action('woocommerce_after_order_notes', 'pm_custom_checkout_fields_after_oo', 10);
function pm_custom_checkout_fields_after_oo () {
	wc_get_template( 'checkout/terms.php' );
}

// add_filter ('woocommerce_order_button_text', 'pm_change_order_button_text', 10);
// function pm_change_order_button_text($ori_name) {
// 	return '前往付款';
// }

// 結帳頁：修改Label及加入新欄位
//add_filter ('woocommerce_checkout_fields', 'pm_change_checkout_account_pwd_text', 80);
// function pm_change_checkout_account_pwd_text($fields) {
// 	// 修改 Label
// 	if (isset($fields['account']['account_password'])) {
// 		$fields['account']['account_password']['label'] = '請設定新帳號的密碼';
// 	}

// 	if (isset($fields['billing']['billing_customer_identifier'])) {
// 		$fields['billing']['billing_customer_identifier']['label'] = '統一編號 <abbr class="required" title="必要欄位">*</abbr>';
// 	}

// 	$fields['billing']['billing_invoice_title'] = array(
// 		'type' 		=> 'text',
// 		'label'         => '發票抬頭 <abbr class="required" title="必要欄位">*</abbr>',
// 		'required'      => false
// 	);

// 	// 聯絡電話改為必填
// 	if (isset($fields['billing']['billing_phone'])) {
// 		$fields['billing']['billing_phone']['label'] = '行動電話';
// 		$fields['billing']['billing_phone']['required'] = true;
// 	}

// 	$fields['order']['order_comments']['placeholder'] = '您的訂單備註';

// 	return $fields;
// }

// // 電子發票：修改發票類型的title
// add_filter( 'allpay_invoice_type_select_values', 'pm_set_invoice_type_values', 10);
// function pm_set_invoice_type_values($invoice_values) {
// 	$invoice_values['c'] = '三聯式(公司行號)';
// 	return $invoice_values;
// }

// // 電子發票：修改購買人地址
// add_filter('allpay_invoice_order_address', 'pm_modify_invoice_order_address', 10, 2);
// function pm_modify_invoice_order_address($address, $order_id) {
// 	// 地址
// 	$postcode = get_post_meta( $order_id, '_billing_postcode', true );
// 	$state = get_post_meta( $order_id, '_billing_state1', true );
// 	$city = get_post_meta( $order_id, '_billing_city1', true );
// 	$address = get_post_meta( $order_id, '_billing_address_1', true );
// 	return '('.$postcode.') '.$state.$city.$address;
// }

// // 電子發票：修改購買人姓名；當選擇公司戶時，修改購買人的抬頭
// add_filter('allpay_invoice_order_user_name', 'pm_apply_title_depend_on_invoice_type', 10, 3);
// function pm_apply_title_depend_on_invoice_type($human_name, $nOrder_Id, $sInvoice_Type) {
// 	// 公司戶
// 	if( $sInvoice_Type == 'c' ) {
// 		return get_post_meta($nOrder_Id, '_billing_invoice_title', true) ; // 發票抬頭	
// 	}
// 	// $human_name 預設抓 first_name + last_name，要改
// 	$order = wc_get_order($nOrder_Id);
// 	$order_user_id = $order->get_customer_id();
// 	$billing_name = '訪客';
// 	if (absint($order_user_id) != 0) {
// 		$billing_name = get_user_meta($order_user_id, 'billing_name', true);
// 	}
// 	return $billing_name ; 	// 購買人
// }

// // 電子發票：當選擇公司戶時，於後台 note 增加發票抬頭欄位的顯示
// add_filter('allpay_invoice_info_of_note', 'pm_add_title_into_note', 10, 3);
// function pm_add_title_into_note($sInvoice_Info, $nOrder_Id, $sInvoice_Type) {
// 	if ($sInvoice_Type == 'c') {
// 		$sInvoiceTitle = get_post_meta($nOrder_Id, '_billing_invoice_title', true) ; // 發票抬頭
// 		$sInvoice_Info .= '<br>發票抬頭 : ' . $sInvoiceTitle . '<br />';
// 	}
// 	return $sInvoice_Info;
// }

// // 結帳頁：客製欄位 - 後端驗證
// add_action('woocommerce_checkout_process', 'pm_custom_checkout_field_process'); 
// function pm_custom_checkout_field_process() {

// 	// 若選擇公司戶，發票抬頭為必填
// 	if ( isset($_POST['billing_invoice_type']) && $_POST['billing_invoice_type'] == 'c' && $_POST['billing_invoice_title'] == '' )
// 	{
// 		wc_add_notice( __( '請輸入發票抬頭' ), 'error' );
// 	}

// 	if ( isset($_POST['billing_invoice_type']) && $_POST['billing_invoice_type'] == 'c' && $_POST['billing_invoice_title'] != '' )
// 	{
// 		if( mb_strlen($_POST['billing_invoice_title'], 'UTF-8') > 30){
// 			wc_add_notice( __( '發票抬頭字數超過30字，無法自動開立發票，請聯繫客服。' ), 'error' );
// 		}
		
// 	}

// 	//手機格式檢查
// 	if( isset($_POST['billing_phone']) ) {
// 		$special = '/[^0-9]/';
// 		$_POST['billing_phone'] = preg_replace( $special, '', $_POST['billing_phone']);
// 	}

// 	// 電子發票: 公司戶統編
// 	if (isset($_POST['invoice_type']) && $_POST['invoice_type'])
// 	{
// 		$inv_type = esc_attr($_POST['invoice_type']);
// 		if ($inv_type==='corp')
// 		{
// 			$inv_number = esc_attr($_POST['invoice_number']);
// 			if (strlen($inv_number)!==8)
// 			{
// 				wc_add_notice('不合法的統一編號，請確認共8碼。', 'error');
// 			}
// 		}
// 	}

// 	// // 服務條款
// 	// if (!isset($_POST['checkout_agreement']) && $_POST['checkout_agreement']!=='1') 
// 	// {
// 	// 	wc_add_notice('你必須同意我們的服務條款，才能進行下一步。', 'error');
// 	// }
//}

// 結帳頁：客製欄位 - 儲存更新
// add_action('woocommerce_checkout_update_order_meta', 'pm_custom_checkout_field_update_order_meta');
// function pm_custom_checkout_field_update_order_meta( $order_id ) 
// {
// 	// 同意服務條款
//     if (isset($_POST['checkout_agreement']) && $_POST['checkout_agreement']==='1') 
//     {
// 		update_post_meta( $order_id, 'checkout_agreement', 'by '.get_current_user_id());
//     }
//     // 發票類型
//     if (isset($_POST['invoice_type']) && $_POST['invoice_type'])
//     {
// 		$inv_type = esc_attr($_POST['invoice_type']);
// 		if ($inv_type==='corp')
// 		{
// 			// 公司統編
// 			update_post_meta( $order_id, 'invoice_number', esc_attr($_POST['invoice_number']));
// 		}
// 	}
// }
