<?php
/**
 * Functions for Theme.
 *
 * @package     Pan-Bootstrap
 * @since       0.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * 輸出符合 bootstrap 格式的主選單
 */
if ( ! function_exists( 'pan_bootstrap_nav' ) ) {
	function pan_bootstrap_nav() 
	{
		$search_btn = '<button type="button" class="d-none d-sm-inline-block nav-link buttonsearch" id="buttonsearch">
			<i class="fas fa-search openclosesearch"></i>
			<i class="fas fa-times openclosesearch" style="display:none"></i>
			</button>';
		$search_form = get_search_form( false ); // Return not echo

		wp_nav_menu(
			array(
				'theme_location'  => 'primary-menu',
				'menu'            => '',
				'container'       => 'div',
				'container_class' => 'collapse navbar-collapse',
				'container_id'    => 'bs-example-navbar-collapse-1',
				'items_wrap'      => '%3$s',
				'menu_class'      => 'navbar-nav ml-auto',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'depth'           => 4,
				'walker'          => new WP_Bootstrap_Navwalker()
			)
		);

	}
}

/**
 * 輸出分頁連結
 */
if ( ! function_exists( 'pan_bootstrap_pagination' ) ) {
	function pan_bootstrap_pagination($query = null)
	{
		$links = get_the_posts_pagination(
			array( 
				'prev_text' => '<span class="paginate-next pt-2 pb-2 pr-2 pl-2">上一頁</span>',
				'next_text' => '<span class="paginate-next pt-2 pb-2 pr-2 pl-2">下一頁</span>',
				'before_page_number' => '<span class="paginate-number pt-2 pb-2 pr-3 pl-3">',
				'after_page_number' => '</span>',
			)
		);
	
		if ( $links ) {
			echo $links;
		}
	}
}

/**
 * Custom callback for outputting comments 
 */
if ( ! function_exists( 'bootstrap_comment' ) ) {
	function bootstrap_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment; 
		?>
		<?php if ( $comment->comment_approved == '1' ): ?>
		<li class="media">
			<div class="media-left">
				<?php echo get_avatar( $comment ); ?>
			</div>
			<div class="media-body">
				<h4 class="media-heading"><?php comment_author_link() ?></h4>
				<time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
				<?php comment_text() ?>
			</div>
		<?php endif;
	}
}

/**
 * Custom callback for outputting comments (Pan Bootstrap)
 */
if ( ! function_exists( 'pan_bootstrap_comments' ) ) {
	function pan_bootstrap_comments($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<!-- heads up: starting < for the html tag (li or div) in the next line: -->
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
			<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>
		<div class="comment-author vcard">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
			<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'pan-bootstrap') ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-content-wrap">
			<div class="comment-meta mb-3 commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
				<?php
					printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
				?>
			</div>
			<?php comment_text() ?>
			<div class="reply">
				<?php 
				// comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) 
				$myclass = 'btn btn-primary';
				echo preg_replace( 
					'/comment-reply-link/', 
					'comment-reply-link ' . $myclass, 
					get_comment_reply_link(
						array_merge( 
							$args, 
							array(
								'add_below' => $add_below, 
								'depth' => $depth, 
								'max_depth' => $args['max_depth']
							)
						)
					), 
					1 
				); 
				?>
			</div>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
	<?php 
	}
}

/**
 * 取得文章內的第一張圖片
 */
if ( ! function_exists( 'pb_get_post_1st_img' ) ) {
	function pb_get_post_1st_img($post_id) {
		$post = get_post($post_id);
		$first_img = '';
		if ($post->post_type == 'post') {
			// ob_start();
			// ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			if (!empty($matches[1][0])) {
				$first_img = $matches[1][0];
			}
		}
		return $first_img;
	}
}

/**
 * 取得文章的縮圖
 */
if ( ! function_exists( 'pb_get_post_thumbnail' ) ) {
	function pb_get_post_thumbnail($post_id, $size = 'medium') {
		if (!empty($post_id)) {
			// 精選圖片
			$obj_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
			if (!empty($obj_img)) {
				return $obj_img[0];
			}
			// 文中第一張圖
			$first_img = apply_filters('pb_loop_post_thumbnail', pb_get_post_1st_img($post_id), $post_id);
			if (!empty($first_img)) {
				return $first_img;
			}
		}

		// 自訂: 頁首圖片
		$header_image_id = get_theme_mod('header_image');
		if (!empty($header_image_id)) {
			$obj_header_img = wp_get_attachment_image_src( $header_image_id, 'full' );
			return $obj_header_img[0];
		}

		// 設定預設佔位圖，如：get_template_directory_uri().'/images/def_thumbnail.jpg'
		$def_thumbnail = apply_filters( 'pb_default_thumbnail', '');
		if (empty($def_thumbnail)) {
			$def_thumbnail = 'https://placehold.it/1200x628';
		}

		// 佈景預設 banner 圖
		return $def_thumbnail;
	}
}

/**
 * 取得所有頁面的 meta 設定
 */
if ( ! function_exists( 'pb_get_metas' ) ) {
	function pb_get_metas() {
		global $post;
		$site_name = get_bloginfo('sitename');
		$obj_data = get_queried_object();
		// print_r($obj_data);
		$default_img_urls = array(
			get_template_directory_uri().'/images/banner1.jpg',
			get_template_directory_uri().'/images/banner2.jpg'
		);

		$metas = array(
			'title' => get_bloginfo('name'),
			'desc' => get_bloginfo('description'),
			'type' => 'article',
			'url' => home_url(),
			'images' => $default_img_urls
		);

		if (is_author()) {
			$metas['title'] = $obj_data->data->display_name.' - '.$site_name; 
			$metas['type'] = 'object';
			$metas['desc'] = $metas['title'].'，'.$metas['desc'];
			$metas['url'] = get_author_posts_url($obj_data->data->ID);
		}
		elseif (is_search()) { 
			$s = get_query_var('s');
			$metas['title'] =  'Search for &quot;'.esc_html($s).'&quot;'.' - '.$site_name; ; 
		} 
		elseif (is_404()) {
			$metas['title'] = 'Sorry, no data for you.'; 
		}
		elseif (is_home() || is_front_page()) {
			if (!empty($meta['desc'])) {
				$desc = ' - '.$metas['desc'];
			}		
			$metas['title'] = $metas['title'].$desc; 
		}
		elseif ( is_archive() && !empty($obj_data)) { 
			$metas['title'] = ''.$obj_data->name.' - '.$site_name; 
			$metas['type'] = 'object';
			$metas['desc'] = $metas['title'].'，'.$metas['desc'];
			if (!empty($obj_data->term_id)) {
				$metas['url'] = get_term_link($obj_data->term_id);
			}
		}
		elseif ((is_single()) || (is_page())) { 
			$meta_imgs = array();
			$post_id = $post->ID;

			// 取得預設的標題和摘要
			$post_title = get_the_title($post_id); 
			$post_excerpt = mb_substr ( wp_strip_all_tags(get_post_field('post_content', $post_id)), 0, 120 );
			if (empty($post_excerpt)) {
				$post_excerpt = get_the_title($post_id);
			}
		
			// title / desc / url
			$metas['title'] = $post_title.' - '.$site_name; ; 
			$metas['desc'] = $post_excerpt;
			$metas['url'] = get_permalink($post_id);

			if (has_post_thumbnail( $post_id )) { 
				$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
				// 把圖放到預設圖的前面
				array_unshift($metas['images'], esc_attr( $thumbnail_src[0] ));
			}

		}
		return $metas;
	}
}

/**
 * 顯示頁面上方置頂 Banner (title bar)
 */
if ( ! function_exists( 'pb_show_title_bar' ) ) {
	function pb_show_title_bar($img, $text = '') {
		if (empty($img)) {
			return false;
		}
		if (is_array($img)) {
			$img_url = $img[0];
		}

		$hide_title_bar_text = 'yes'; 

		$title_bg_url = apply_filters('pb_banner_bg_url', $img_url, 'large');

		$pb_title_bar_styles = apply_filters('pb_title_bar_styles', '');

		$title_bg_style = '';
		if (!empty($title_bg_url)) {
			$title_bg_style = 'background-image: url('.$title_bg_url.');';
		}
		$pb_title_bar_styles .= $title_bg_style;
		?>
		<div class="title_bar" style="<?php echo $pb_title_bar_styles;?>">
			<div class="container">
				<?php
				if (empty($hide_title_bar_text)) {
					?>
					<h1 class="header-title"><?php echo $text; ?></h1>
					<div class="triangled_colored_separator"></div>
					<?php
				}else{
					?>
					<style>.title_bar:after{background: rgba(0,0,0,0);}</style>
					<h1 class="header-title"></h1>
					<div style="height:30px;">&nbsp;</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}

/**
 * Retrieve category parents.
 *
 * @param int $id Category ID.
 * @param array $visited Optional. Already linked to categories to prevent duplicates.
 * @return string|WP_Error A list of category parents on success, WP_Error on failure.
 */
if ( ! function_exists( 'pb_custom_get_category_parents' ) ) {
	function pb_custom_get_category_parents( $id, $position_length, $visited = array() ) {
		$chain = '';
		$parent = get_term( $id, 'category' );
		if ( is_wp_error( $parent ) ) 
			return $parent;

		$name = $parent->name;

		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			$obj_chain = pb_custom_get_category_parents( $parent->parent, $position_length, $visited );
			$chain .= $obj_chain['chain'];
			$position_length = $obj_chain['length'];
		}
		$chain .= 
			'<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'.
				'<a href="' . esc_url( get_category_link( $parent->term_id ) ) . '" itemprop="item" itemscope itemtype="https://schema.org/WebPage" itemid="'.esc_url( get_category_link( $parent->term_id ) ).'">' . 
					'<span itemprop="name">'.$name.'</span>'. 
				'</a>' . 
				'<meta itemprop="position" content="'.$position_length++.'" />'.
			'</li>';    
		return array(
			'chain' => $chain, 
			'length' => $position_length
		);
	}
}

/**
 * Pan-Bootstrap Bradcumb: with schema.org attributes
 */
if ( ! function_exists( 'pb_breadcrumb' ) ) {
	function pb_breadcrumb() {
		global $post;
		$home_txt = __('Home', 'pan-bootstrap');
		$li_atts = 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"';
		$li_a_atts = 'itemprop="item" itemscope itemtype="https://schema.org/WebPage"';
		$item_wrap_s = '<span itemprop="name">';
		$item_wrap_e = '</span>';
		$position_length = 1;

		$html = '<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
		if ( (is_front_page()) || (is_home()) ) {
			$html .= '<li class="breadcrumb-item active" '.$li_atts.'>'.$home_txt.'</li>';
		}else {
			$html .= 
				'<li class="breadcrumb-item" '.$li_atts.'>'.
					'<a href="'.esc_url(home_url('/')).'" '.$li_a_atts.' '.'itemid="'.esc_url(home_url('/')).'">'.
						$item_wrap_s.$home_txt.$item_wrap_e.
					'</a>'.
					'<meta itemprop="position" content="'.$position_length++.'" />'.
				'</li>'
			;
			
			if ( is_attachment() ) {
				$parent = get_post($post->post_parent);
				$categories = get_the_category($parent->ID);
				if ( $categories[0] ) {
					$obj_chain = pb_custom_get_category_parents( $categories[0], $position_length );
					$position_length = $obj_chain['length'];
					$html .= $obj_chain['chain'];
				}
				$html .= 
					'<li class="breadcrumb-item" '.$li_atts.'>'.
						'<a href="' . esc_url( get_permalink( $parent ) ) . '" '.$li_a_atts.'>' . 
							$parent->post_title . 
						'</a>'.
						'<meta itemprop="position" content="'.$position_length++.'" />'.
					'</li>';
				$html .= 
					'<li class="breadcrumb-item active" '.$li_atts.'>' . 
						get_the_title() . 
						'<meta itemprop="position" content="'.$position_length++.'" />'.
					'</li>';
			}elseif ( is_category() ) {
				$category = get_category( get_query_var( 'cat' ) );
				if ( $category->parent != 0 ) {
					$obj_chain = pb_custom_get_category_parents( $category->parent, $position_length );
					$position_length = $obj_chain['length'];
					$html .= $obj_chain['chain'];
				}
				$html .= 
					'<li class="breadcrumb-item active" '.$li_atts.'>' . 
						$item_wrap_s. single_cat_title( '', false ) . $item_wrap_e .
						'<meta itemprop="position" content="'.$position_length++.'" />'.
					'</li>';
			}elseif ( is_page() && !is_front_page() ) {
				$parent_id = $post->post_parent;
				$parent_pages = array();
				while ( $parent_id ) {
					$page = get_page($parent_id);
					$parent_pages[] = $page;
					$parent_id = $page->post_parent;
				}
				$parent_pages = array_reverse( $parent_pages );
				if ( !empty( $parent_pages ) ) {
					foreach ( $parent_pages as $parent ) {
						$html .= 
							'<li class="breadcrumb-item" '.$li_atts.'>'.
								'<a href="' . esc_url( get_permalink( $parent->ID ) ) . '" '.$li_a_atts.'>' . 
									get_the_title( $parent->ID ) . 
								'</a>'.
								'<meta itemprop="position" content="'.$position_length++.'" />'.
							'</li>';
					}
				}
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'>' . get_the_title() . '</li>';
			}elseif ( is_singular( 'post' ) ) {
				$categories = get_the_category();
				if ( $categories[0] ) {
					$obj_chain = pb_custom_get_category_parents( $categories[0], $position_length );
					$position_length = $obj_chain['length'];
					$html .= $obj_chain['chain'];
				}
				$html .= 
					'<li class="breadcrumb-item active" '.$li_atts.'>' . 
						$item_wrap_s . get_the_title() . $item_wrap_e .
						'<meta itemprop="position" content="'.$position_length++.'" />'.
					'</li>'
				;
			
			}elseif ( is_tag() ) {
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'>' . $item_wrap_s . single_tag_title( '', false ) . $item_wrap_e . '</li>';
			}elseif ( is_day() ) {
				$html .= '<li class="breadcrumb-item" '.$li_atts.'><a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" '.$li_a_atts.'>' . get_the_time( 'Y' ) . '</a></li>';
				$html .= '<li class="breadcrumb-item" '.$li_atts.'><a href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '" '.$li_a_atts.'>' . get_the_time( 'm' ) . '</a></li>';
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'>' . get_the_time('d') . '</li>';
			}elseif ( is_month() ) {
				$html .= '<li class="breadcrumb-item" '.$li_atts.'><a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" '.$li_a_atts.'>' . get_the_time( 'Y' ) . '</a></li>';
				$html .= '<li class="breadcrumb-item active '.$li_atts.'">' . get_the_time( 'F' ) . '</li>';
			}elseif ( is_year() ) {
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'>' . get_the_time( 'Y' ) . '</li>';
			}elseif ( is_author() ) {
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'>' . get_the_author() . '</li>';
			}elseif ( is_search() ) {
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'> Search </li>';
			}elseif ( is_404() ) {
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'> 404 </li>';
			}elseif ( function_exists('is_woocommerce') && is_woocommerce() ){
				$breadcrumbs = new WC_Breadcrumb();
				$breadcrumb = $breadcrumbs->generate();

				for ( $i = 0; $i < sizeof($breadcrumb); $i++ ) {
					if (!is_array($breadcrumbs)) {
						$breadcrumbs = array($breadcrumbs);
					}
					$act_class = '';
					if ($i == (sizeof($breadcrumbs) - 1)) {
						$act_class = 'active';
					}
					$crumb = $breadcrumb[$i];
					$item_name = $crumb[0];
					$item_link = $crumb[1];
					$html .= '
						<li class="breadcrumb-item '.$act_class.'" '.$li_atts.'>'.
							'<a href="'.$item_link.'">'.
							$item_wrap_s.$item_name.$item_wrap_e.
							'</a>'.
						'</li>
					';
				}
			}
			// custom tax
			elseif ( is_tax('case_cate') || is_tax('tpost_cate')) {
				$html .= '<li class="breadcrumb-item active" '.$li_atts.'>' . $item_wrap_s . single_tag_title( '', false ) . $item_wrap_e . '</li>';
			}
			// custom post type
			elseif ( is_singular('tpost') ){
				$post_type = $post->post_type;
				$terms = get_the_terms( $post->ID , 'tpost_cate' );
				$term_link = get_term_link($terms[0]);
				$html .= '
					<li class="breadcrumb-item">
						<a href="'.$term_link.'">'.$terms[0]->name.'</a>
					</li>
				';
				$html .= 
					'<li class="breadcrumb-item active" '.$li_atts.'>' . 
						$item_wrap_s . get_the_title() . $item_wrap_e .
					'</li>'
				;
			}elseif ( is_singular('case') ){
				$terms = get_the_terms( $post->ID , 'case_cate' );
				$term_link = get_term_link($terms[0]);
				$html .= '
					<li class="breadcrumb-item">
						<a href="'.$term_link.'">'.$terms[0]->name.'</a>
					</li>
				';
				$html .= 
					'<li class="breadcrumb-item active" '.$li_atts.'>' . 
						$item_wrap_s . get_the_title() . $item_wrap_e .
					'</li>'
				;
			}
		}
		$html .= '</ol>';
		echo $html;
	}
}


/* WooCommerce */

/**
 * 取得商品的代表分類
 */
if ( ! function_exists( 'pb_get_product_one_cate_name' ) ) {
	function pb_get_product_one_cate_name($post_id) {
		$cate_name = '未分類';
		// 先取 yoast 主要分類
		$yoast_main_cate_id = get_post_meta( $post_id, '_yoast_wpseo_primary_product_cat', true );
		if (!empty($yoast_main_cate_id)) {
			$obj_cate = get_term_by( 'id', $yoast_main_cate_id, 'product_cat' );
			$cate_name = $obj_cate->name;
		}else{
			$product_cats = wp_get_post_terms( $post_id, 'product_cat' );
			// 若有其他分類可顯示，就排除 recommended
			if (sizeof($product_cats) > 1) {
				$product_cats = wp_list_filter($product_cats, array('slug'=>'recommended'), 'NOT');
			}
			if ( $product_cats && ! is_wp_error ( $product_cats ) ){
				$single_cat = array_shift( $product_cats );
				// $term_link = get_term_link($single_cat);
				$cate_name = $single_cat->name;
			}
		}
		return $cate_name;
	}
}

/**
 * 取得商品列表正方圖片
 */
if ( ! function_exists( 'pm_get_product_list_pic' ) ) {
	function pm_get_product_list_pic($pid, $size='medium')
	{
		// ACF is required.
		if (function_exists('get_field')) {
			$image = get_field('product_list_pic', $pid);
		}else{
			$image = '';
		}

		// 有設定商品圖片，就取看看值 (attachment id)
		if (!empty($image)) {
			$attach_html = wp_get_attachment_image($image, $size);
		}
		// 如果 attachment post 存在就 return
		if( !empty($attach_html) ) {
			return $attach_html;
		}elseif( has_post_thumbnail($pid) ) {
			// attachement 不存在，但特色圖片存在，那就 return 特色圖片
			return get_the_post_thumbnail( $pid, 'shop_thumbnail' );
		}else{
			// 沒有商品圖片也沒有特色圖片，看它是不是變化商品，是的話就拿裡頭的圖片設定
			$_product = wc_get_product($pid);
			if ('variation' === $_product->get_type()) {
				$data = $_product->get_parent_data();
				if (0 !== absint($data['image_id'])) {
					return wp_get_attachment_image( absint($data['image_id']), $size );
				}
			}
		}
		// 什麼圖都沒有設定就用 placeholder 圖
		return wc_placeholder_img( $size );
	}
}
