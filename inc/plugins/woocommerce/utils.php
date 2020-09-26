<?php
/**
 * Custom WC util functions
 *
 * @package PanBootstrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_log' ) ) :
	function wc_log($msg, $source_name) {
		if (function_exists('wc_get_logger')) {
			$log = wc_get_logger();
			$log_context = array( 'source' => $source_name );
			if (is_array($msg) || is_object($msg)) {
				$log->alert( wc_print_r( $msg, true ), $log_context);
			}else{
				$log->log('info', $msg, $log_context);
			}
		}else{
			return false;
		}
	}
endif;

// 查看目前所在頁面是否為 wc endpoint (myaccount 相關頁面)
function pb_is_wc_endpoint_url($endpoint)
{
	$all_endpoints = array(
		'orders', 'view-order', 'my-edit-account', 'returns', 'return-single', 'serial-numbers'
	);
	global $wp_query;
	// var_dump($wp_query->query_vars);
	if ( isset( $wp_query->query_vars[ $endpoint ] ) ) {
		return true;
	}elseif ($endpoint == 'orders'){
		if ( isset( $wp_query->query_vars[ 'view-order' ] ) ) {
			return true;
		}
	}elseif ($endpoint == 'returns'){
		if ( isset( $wp_query->query_vars[ 'return-single' ] ) ) {
			return true;
		}
	}elseif ($wp_query->query_vars['pagename'] == 'my-account' && $endpoint=='dashboard') {
		foreach ($all_endpoints as $ep) {
			if ( isset($wp_query->query_vars[$ep]) ){
				return false;
			}
		}
		return true;
	}
	return false;
}

function pb_wc_mail($args) {
	if (empty($args) || !is_array($args) || empty($args['to']) ) {
		return new WP_Error( 'invalid_args', __( 'WC Mail: Invalid arguments.', [plugin_slug] ) );
	}
	
	$to = $args['to'];
	$subject = $args['subject'];
	$message_heading = $args['message_heading'];
	$message_body = $args['message_body'];
	$attachment = '';
	if( !empty($args['attachment']) ){
		$attachment = $args['attachment'];	
	}
	$mailer = WC()->mailer();		
	$message = $mailer->wrap_message($message_heading, $message_body );
	return $mailer->send( $to, $subject, $message, '', $attachment);
}

/* 訂單 */

// 顯示 order item 內所有 item meta
function pm_display_item_meta($item, $flat, $return)
{
	$before = '<ul class="wc-item-meta"><li>';
	$after = '</li></ul>';
	$separator = '</li><li>';
	$echo = true;

	if ($flat) {
		$before = '';
		$after = '';
		$separator = '';
	}
	if ($return) {
		$echo = false;
	}

	$args = array(
		'before'    => $before,
		'after'     => $after,
		'separator' => $separator,
		'echo'      => $echo,
		'autop'     => false
	);

	if ( $args['echo'] ) {
		echo wc_display_item_meta($item, $args);
	} else {
		return wc_display_item_meta($item, $args);
	}
}

function pb_is_html($string){
	return $string != strip_tags($string) ? true:false;
}

function pb_nl2br_when_text($string) {
	// if no html tags inside
	if ($string == strip_tags($string)) {
		return nl2br($string);
	}
	return wpautop($string);
}