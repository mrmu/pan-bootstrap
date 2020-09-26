<?php
/**
 * frontend/myaccount-return-single.php : 查詢單一退貨紀錄
 *
 * @package WordPress
 * @subpackage PanAcademy
 * @since PanAcademy 1.0
 */

add_action( 'template_redirect', 'pm_return_single_redirect');
function pm_return_single_redirect()
{
	if (pb_is_wc_endpoint_url('return-single'))
	{
		$return_pid = (isset($_GET['return_pid']))?absint($_GET['return_pid']):'';		//退貨單號
		// 沒有退貨單號就返回列表
		if (empty($return_pid)) {
			wc_add_notice( '沒有退貨單號。', 'notice' );
			wp_safe_redirect( wc_get_endpoint_url('returns') );
			exit;
		}elseif (isset($_GET['mode']) && $_GET['mode']=='print') {
			// add_action( 'wp_head', 'pm_print_return_mode', 20);
			// add_action( 'wp_footer', 'pm_print_return_doc', 20 );
			add_filter( 'pre_get_document_title', 'pm_return_wp_title', 10, 1 );
		}
		// 若查看者與退單擁有者不同，就返回列表
		$ret_query_args = array(
			'key' => 'ID',
			'value' => $return_pid,
			'relation' => '='
		);
		$rst = pm_get_return_log($ret_query_args);
		if (isset($rst['data'][0]))
		{
			if ( $rst['data'][0]->user_id != get_current_user_id()){
				wc_add_notice( '無權查看此退單。', 'notice' );
				wp_safe_redirect( wc_get_endpoint_url('returns') );
				exit;
			}
		}else{
			wc_add_notice( '無此退單。', 'notice' );
			wp_safe_redirect( wc_get_endpoint_url('returns') );
			exit;
		}
	}
}

function pm_return_wp_title( $title ) 
{
	$return_pid = (isset($_GET['return_pid']))?absint($_GET['return_pid']):'';		//退貨單號
	return '退貨紀錄 (退貨單號：'.$return_pid.') - '.$title;
}

// 個人資訊：退貨紀錄
add_action( 'woocommerce_account_return-single_endpoint', 'pm_return_single_endpoint_content' );
function pm_return_single_endpoint_content()
{
	$return_pid = (isset($_GET['return_pid']))?absint($_GET['return_pid']):''; // 退貨單號
	$ret_query_args = array(
		'key' => 'ID',
		'value' => $return_pid,
		'relation' => '='
	);
	$rst = pm_get_return_log($ret_query_args);
	if (isset($rst['data'][0]))
	{
		$order_id = $rst['data'][0]->order_id;
		$return_date = $rst['data'][0]->return_date;
		$return_status = $rst['data'][0]->return_status;
		$reason = $rst['data'][0]->reason;
		$reason_text = $rst['data'][0]->reason_text;
		$return_bank_name = $rst['data'][0]->return_bank_name;
		$return_bank_user = $rst['data'][0]->return_bank_user;
		$return_bank_code = $rst['data'][0]->return_bank_code;
		$return_bank_account = $rst['data'][0]->return_bank_account;

		$ret_itm_query_args = array(
			'key' => 'log_id',
			'value' => $return_pid,
			'relation' => '='
		);
		$items = pm_get_return_log_item($ret_itm_query_args);

		// 退貨商品項目
		$list_items = array();
		$items_tot = 0;
		foreach ($items['data'] as $itm)
		{
			$item_id = $itm->item_id;
			$_product = wc_get_product($itm->product_id);
			$p_price = $_product->get_price();
			$p_name = $_product->get_title();
			$p_img = $_product->get_image();
			$qty = $itm->qty;

			if( $_product->is_type( 'variable' ) )
			{
				$item = pm_get_order_item($item_id, $order_id);
				$p_name .= ' '.pm_display_item_meta($item, true, true);
			}
			$list_items[] = array(
				'p_name' => $p_name,
				'p_img' => $p_img,
				'qty' => $qty,
				'p_price' => $p_price,
			);
			$pay_method = pm_get_order_payment_method(wc_get_order($order_id));
		}

		if (absint($order_id) > 0) {
			$order = wc_get_order($order_id);
			if (!empty($order)){
				$items_tot = 0; //總退貨金額
				foreach($order->get_refunds() as $refund_itm){
					$items_tot += $refund_itm->get_amount();
				}
			}
		}

		// 列印取貨單 url
		// $print_return_doc_url = add_query_arg(array('mode'=>'print', 'return_pid'=>$return_pid), 'return-single');
		?>

		<div class="row">
			<div class="col-md-7 text-left" style="font-weight:400">
				<div class="ordernb hidden-md-down">
					<span class="nb">訂單編號</span>
					<span class=""><?php echo $order_id;?></span>
				</div>
				<div class="hidden-md-down ">
					<span class="date">退貨編號</span>
					<span class="date"><?php echo $return_pid;?></span>
					<span class="date return_date">退貨日期</span>
					<span class="date"><?php echo date('Y.m.d', strtotime($return_date));?></span>
				</div>
			</div>
			<div class="col-12 col-md-5 text-right margin_t_20 button_top"> 
				<!--<a class="btn btn-secondary" href="<?php //echo $print_return_doc_url;?>" target="_blank">列印取貨單</a>-->
				<!--<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_print">列印取貨單</button>-->
				<!--<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#order_leave_message" >我要留言</button>-->
			</div>
			<!--
			<div class="modal fade" id="order_print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog center">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<p>您已申請退貨！我們將儘快協助處理！</p>  
						</div>
						<div class="modal-footer">
							<button type="button" class=" center btn blue_btn" data-toggle="modal" data-target="">
							列印取貨單
						</button>    
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="order_leave_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog center">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<p>55555555</p>  
						</div>
						<div class="modal-footer">
							<button type="button" class=" center btn blue_btn" data-toggle="modal" data-target="">
								列印取貨單
							</button>    
						</div>
					</div>
				</div>
			</div>
			-->
		</div>

		<table class="table table-hover return_status">
			<tbody class="table_top">
				<tr class="border_b hidden-lg-up ">  
					<td class="td_title">退貨編號 </td> 
					<td class="">23456789-12345</td></tr><tr class="hidden-lg-up">
					<td class="td_title ">退貨日期</td> 
					<td class="">2017-12-12</td> 
				</tr>
				<?php /* ?>
				<tr> 
					<td class=" td_title ">退貨狀態</td> 
					<td class=""><?php echo $return_status;?></td> 
				</tr>
				<?php */ ?>
				<tr>
					<td class=" td_title ">發票號碼</td> 
					<td class="">123456789</td> 
				</tr>
				<tr>
					<td class=" td_title ">付款方式</td> 
					<td class="">
						<?php echo pm_get_payment_method_name($pay_method);?>
					</td> 
				</tr>
				<tr>
					<td class=" td_title">總價</td> 
					<td class=""><?php echo wc_price($items_tot);?></td> 
				</tr>
				<?php
				if ($pay_method === 'atm') {
				?>
				<tr>
					<td class=" td_title">銀行名稱</td> 
					<td class=""><?php echo $return_bank_name;?></td> 
				</tr>
				<tr>
					<td class=" td_title">戶名</td> 
					<td class=""><?php echo $return_bank_user;?></td> 
				</tr>
				<tr>
					<td class=" td_title">銀行代號</td> 
					<td class=""><?php echo $return_bank_code;?></td> 
				</tr>
				<tr>
					<td class=" td_title">銀行帳號</td> 
					<td class=""><?php echo $return_bank_account;?></td> 
				</tr>
				<?php
				}
				?>
				<tr>
					<td class=" td_title">退貨原因</td> 
					<td class="result">
						<?php echo $reason; ?><br>
						<?php echo $reason_text;?>
					</td> 
				</tr>
			</tbody>
        </table>

		<div class="col-12 good_title ">
			退貨商品:
		</div>

		<table class="table myaccount_list ">
			<tbody>
				<tr class="hidden-md-down ">
					<th>商品</th>
					<th>描述</th>
					<th>數量</th>
					<th>金額</th>
				</tr>

				<?php
				$ary_v_info = array();
				foreach ($items['data'] as $itm)
				{
					$item_id = $itm->item_id;
					$_product = wc_get_product($itm->product_id);
					$p_link = $_product->get_permalink();
					$p_name = $_product->get_title();
					$p_img = pm_get_product_list_pic($itm->product_id);
					$qty = $itm->qty;

					// 取得可變商品的規格圖及規格名稱
					if( $_product->is_type( 'variable' ) ) {
						$order = wc_get_order($order_id);
						$item = pm_get_order_item($item_id, $order_id);
						$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
						$is_visible        = $_product && $_product->is_visible();
						$p_link = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $_product->get_permalink( $item ) : '', $item, $order );
						$p_name = apply_filters( 'woocommerce_order_item_name', $p_link ? sprintf( '<a href="%s" style="color:#292b2c;">%s</a>', $p_link, $item['name'] ) : $item['name'], $item, $is_visible );
						$p_img = pm_get_product_list_pic($_product->get_id());
					}
					$rg_name = get_user_meta( $itm->vendor_id, 'RG_name', true );
					$rg_adds = get_user_meta( $itm->vendor_id, 'RG_adds', true );

					$ary_v_info[$itm->vendor_id]['prod'][] = array(
						'name' => $p_name, 
						'qty' => $qty, 
					);
					$ary_v_info[$itm->vendor_id]['rg'] = array(
						'adds' => $rg_adds, 
						'name' => $rg_name
					);

					?>
					<tr>
						<td class="tdpic into" data-th="商品">
							<div class="row">
								<div class="col-6 col-sm-3 col-md-3 col-lg-12">
									<a href="<?php echo $p_link;?>" class="goods">
										<?php echo $p_img?>
									</a>
								</div>
								<div class=" text-left col-6 col-md-8 hidden-lg-up">
									<p class="title"><?php echo $p_name;?></p>
									<p class="combin margin_t"></p>
									<div class="margin hidden-lg-up">
										<span>
											<p class="mobile_css right hidden-lg-up ">
												<font> <?php echo $qty;?> 件</font>
												<font> x</font>
												<font> <?php echo $items_tot;?></font> 
											</p>
										</span>
									</div>
								</div>
							</div>
						</td>
						<td class="tdpic hidden-md-down" data-th="描述">
							<div class=" text-left hidden-sm-down">
								<p class="title"><?php echo $p_name;?></p>
								<p class="combin margin_t"></p>
								<div class="margin hidden-lg-up">
									<span>
										<p class="mobile_css right md_amount">
											<font class=" "> <?php echo $qty;?> 件</font>
											<font class="hidden-lg-up"> x</font>
											<font class="hidden-lg-up"> <?php echo $items_tot;?></font> 
										</p>
									</span>
									<p class="mobile_css right margin_t">
										<font class="hidden-md-down"> <?php echo wc_price($items_tot); ?></font> 
									</p>
								</div>
							</div>
						</td>
						<td class="tdpic hidden-md-down" data-th="數量"> <?php echo $qty;?>  </td>
						<td class="tdpic hidden-md-down" data-th="金額"> <?php echo wc_price($items_tot); ?> </td>
					</tr>
					<?php
				}
				?>

			</tbody>
		</table>

		<?php
	}else{
	?>
		尚無紀錄。
	<?php
	}

}
