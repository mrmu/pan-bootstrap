<?php
/**
 * frontend/myaccount-my-edit-account.php : 會員個人資訊頁-自訂個人資訊
 *
 * @package PanBootstrap
 */

// 個人資訊：儲存欄位資訊
add_action( 'wp', 'pb_update_my_edit_account', 10);

// 個人資訊：頁面內容
add_action( 'woocommerce_account_my-edit-account_endpoint', 'pm_my_edit_account_endpoint_content' );

function pm_my_edit_account_endpoint_content() 
{	
	$user_id = get_current_user_id();

	// 內建欄位 - 取值
	$account_email = get_the_author_meta( 'user_email', $user_id ); 
	$account_nickname = get_the_author_meta( 'nickname', $user_id ); 

	// 自訂欄位 - 取值
	$account_name = get_user_meta( $user_id, 'account_name', true ); //full name
	$birth_y = get_user_meta( $user_id, 'birth_y', true ); 
	$birth_m = get_user_meta( $user_id, 'birth_m', true ); 
	$birth_d = get_user_meta( $user_id, 'birth_d', true ); 
	$account_gender = get_user_meta( $user_id, 'account_gender', true );
	$account_mobile = get_user_meta( $user_id, 'account_mobile', true );
	$account_tel = get_user_meta( $user_id, 'account_tel', true );
	$account_edu = get_user_meta( $user_id, 'account_edu', true );
	$account_job = get_user_meta( $user_id, 'account_job', true );
	$account_interest = get_user_meta( $user_id, 'account_interest', true );  

	$pan_member_url = ''; 
	?>
	<form class="woocommerce-EditMyAccountForm edit-my-account" action="" method="post">

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_name">姓名 <span class="required">*</span></label>
			<input type="text" class="form-control col-sm-10" name="account_name" id="account_name" value="<?php echo $account_name; ?>" />
		</p>

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_name">暱稱 </label>
			<input type="text" class="form-control col-sm-10" name="account_nickname" id="account_nickname" value="<?php echo $account_nickname; ?>" />
		</p>

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">電子信箱 <span class="required">*</span></label>
			<input type="text" class="form-control col-sm-10" name="account_email" id="account_email" value="<?php echo $account_email; ?>" />
		</p>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">性別 </label>
			<div class="col-sm-10">
				<div class="form-check form-check-inline" style="top: -3px; margin-right: 20px;">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="account_gender" id="inlineRadio1" value="m" <?php echo ($account_gender=='m')?'checked':'';?>> 男
					</label>
				</div>
				<div class="form-check form-check-inline" style="top: -3px; margin-right: 20px;">
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="account_gender" id="inlineRadio2" value="f" <?php echo ($account_gender=='f')?'checked':'';?>> 女
					</label>
				</div>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">生日 </label>
			<div id="account_birth" class="birth col-sm-10" style="padding-left:0"></div>
		</div>

		<div class="clear"></div>

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">行動電話 <span class="required">*</span></label>
			<input type="text" class="form-control col-sm-10" name="account_mobile" id="account_mobile" value="<?php echo $account_mobile; ?>" />
		</p>

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">室內電話 </label>
			<input type="text" class="form-control col-sm-10" name="account_tel" id="account_tel" value="<?php echo $account_tel; ?>" />
		</p>

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">教育程度 </label>
			<select name="account_edu" id="account_edu" class="col-sm-10 form-control">
				<option value="" selected="">請點選教育程度</option>
				<option value="1" <?php echo ($account_edu==1)?'selected':'';?>>研究所或以上</option>
				<option value="2" <?php echo ($account_edu==2)?'selected':'';?>>大學</option>
				<option value="3" <?php echo ($account_edu==3)?'selected':'';?>>專科</option>
				<option value="4" <?php echo ($account_edu==4)?'selected':'';?>>高中/高職</option>
				<option value="5" <?php echo ($account_edu==5)?'selected':'';?>>國中或以下</option>
			</select>
		</p>
		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">職業 </label>
			<input type="text" class="form-control col-sm-10" name="account_job" id="account_job" value="<?php echo $account_job; ?>" />
		</p>

		<p class="form-group row">
			<label class="col-sm-2 col-form-label" for="account_email">興趣 </label>
			<input type="text" class="form-control col-sm-10" name="account_interest" id="account_interest" value="<?php echo $account_interest; ?>" />
		</p>


		<p class="row">
			<div class="col-12" style="text-align:center">
				<?php wp_nonce_field( 'edit_my_account_form', 'save_my_account_details_nonce' ); ?>
				<input type="submit" class="woocommerce-Button btn btn-primary fill_blue_btn" name="save_my_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>" />
				<input type="hidden" name="action" value="save_my_account_details" />
			</div>
		</p>
	</form>

	<script>
	var $ = jQuery.noConflict();
	$(function(){
		$(".birth").birthdaypicker({'dateFormat':'bigEndian'});

		<?php 
		if ($birth_y){ ?>
			$('#account_birth .birth-year').val('<?php echo $birth_y;?>');
		<?php 
		}
		if ($birth_m){ ?>
			$('#account_birth .birth-month').val('<?php echo $birth_m;?>');
		<?php
		}
		if ($birth_d){ ?>
			$('#account_birth .birth-day').val('<?php echo $birth_d;?>');
		<?php
		}
		?>
	})
	</script>
	<?php
}

function pb_update_my_edit_account() {
	// 會員後台：個人資訊頁
	if (is_account_page())
	{		
		$act = (isset($_POST['action']))?esc_attr($_POST['action']):'';

		// 更新個人資訊
		if ($act == 'save_my_account_details')
		{
			// 安全性處理 nonce 防 csrf
			if (!isset( $_POST['save_my_account_details_nonce'] ) || 
				!wp_verify_nonce( $_POST['save_my_account_details_nonce'], 'edit_my_account_form' ) ) 
			{
			   wc_add_notice( '表單提交未通過 Nonce 驗證，請再試一次。', 'error');
			}
			else
			{
				$cur_user = wp_get_current_user();
				$user_id = $cur_user->ID;

				// --- WP內建欄位 --- //

			    // 密碼
			    if ( !empty($_POST['current_pass']) && !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) 
			    {
			    	$err_string = pm_update_form_pwd ($cur_user, $_POST['current_pass'], $_POST['pass1'], $_POST['pass2']);
			    	if ($err_string !== false) {
			    		wc_add_notice( $err_string, 'error');
			    	}
			    }

			    // 暱稱 & E-amil
			    if (isset($_POST['account_email']) && !is_email( $_POST['account_email'] ) ) {
					wc_add_notice( '不合法的電子郵件地址。', 'error');
				}else{
					$account_nickname = (isset($_POST['account_nickname']))?esc_attr($_POST['account_nickname']):'';
					$account_email = esc_attr($_POST['account_email']);
					$updated_user_id = wp_update_user( 
											array( 
												'ID' => $user_id, 
												'nickname' => $account_nickname,
												'user_email' => $account_email 
											) 
										);
					if ( is_wp_error( $updated_user_id ) ) {
						$error_string = $updated_user_id->get_error_message();
						wc_add_notice('更新時發生錯誤，請稍後再試。('.$error_string.')', 'error');
					} else {
						// Success!
						do_action('edit_user_profile_update', $cur_user->ID);
					}
				}

			    // 驗證姓名
			    if (!isset($_POST['account_name']) || strlen(esc_attr($_POST['account_name'])) < 2) 
			    {
					wc_add_notice( '「姓名」是必填欄位，請輸入正確的姓名。', 'error' );
				}

			    // 驗證行動電話
			    if (!isset($_POST['account_mobile']) || strlen(esc_attr($_POST['account_mobile'])) != 10) 
			    {
					wc_add_notice( '「行動電話」是必填欄位，請填寫合法的手機號碼。', 'error' );
				}

			    // 通過驗證，開始儲存變更
			    if (wc_notice_count('error')==0)
			    {
					// -- 自訂欄位 --
					// 姓名
					$account_name = esc_attr($_POST['account_name']);
					update_user_meta( $user_id, 'account_name', $account_name );

					$account_mobile = esc_attr($_POST['account_mobile']);
					update_user_meta( $user_id, 'account_mobile', $account_mobile );

					// 性別
					if (isset($_POST['account_gender']))
					{
						$account_gender = esc_attr($_POST['account_gender']);
						update_user_meta( $user_id, 'account_gender', $account_gender );
					}

					// 生日
					if (isset($_POST['birth'])) 
					{
						$birth_y = intval($_POST['birth']['year'][0]);
						$birth_m = intval($_POST['birth']['month'][0]);
						$birth_d = intval($_POST['birth']['day'][0]);
						if ($birth_y > 0 && $birth_m > 0 && $birth_d > 0 )
						{
							update_user_meta( $user_id, 'birth_y', $birth_y );
							update_user_meta( $user_id, 'birth_m', $birth_m );
							update_user_meta( $user_id, 'birth_d', $birth_d );
						}
					}

					// 市話
					if (isset($_POST['account_tel']) && $_POST['account_tel'])
					{
						$account_tel = esc_attr($_POST['account_tel']);
						update_user_meta( $user_id, 'account_tel', $account_tel );
					}

					// 教育程度
					if (isset($_POST['account_edu']) && $_POST['account_edu'])
					{
						$account_edu = esc_attr($_POST['account_edu']);
						update_user_meta( $user_id, 'account_edu', $account_edu );
					}

					// 工作
					if (isset($_POST['account_job']) && $_POST['account_job'])
					{
						$account_job = esc_attr($_POST['account_job']);
						update_user_meta( $user_id, 'account_job', $account_job );
					}

					// 興趣
					if (isset($_POST['account_interest']) && $_POST['account_interest'])
					{
						$account_interest = esc_attr($_POST['account_interest']);
						update_user_meta( $user_id, 'account_interest', $account_interest );
					}
					
			    	wc_add_notice( '已成功儲存變更的資料。', 'success');
			    }

			} // nonce detect
		}// action
	}
}