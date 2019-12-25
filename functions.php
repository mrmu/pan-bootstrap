<?php
/*------------------------------------*\
	Theme support
\*------------------------------------*/
if (function_exists('add_theme_support'))
{    
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('pan-bootstrap', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// Register Custom Navigation Walker
require_once('inc/class-wp-bootstrap-navwalker.php');

function pan_bootstrap_nav()
{
    $search_btn = '<button type="button" class="d-none d-sm-inline-block nav-link buttonsearch" id="buttonsearch">
        <i class="fas fa-search openclosesearch"></i>
        <i class="fas fa-times openclosesearch" style="display:none"></i>
        </button>';
    $search_form = get_search_form( false ); // Return not echo

    $items_wrap = '<ul id="%1$s" class="%2$s">%3$s';
    $items_wrap .= sprintf( '<li id="searchItem" class="menu-item nav-item">%1$s</li></ul>', $search_btn.$search_form );
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'collapse navbar-collapse',
        'container_id'    => 'bs-example-navbar-collapse-1',
        'items_wrap'      => $items_wrap,
		'menu_class'      => 'navbar-nav ml-auto',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 2,
		'walker'          => new WP_Bootstrap_Navwalker()
		)
	);
}

function pan_bootstrap_header_scripts()
{
    if (!is_admin()) {
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
        wp_enqueue_script( 'jquery' );
    }

    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin() && !(is_home() || is_front_page())) {

        // // Custom scripts
        // $main_script_uri = get_template_directory_uri() . '/dist/main.bundle.js';
        // $main_script_dir = get_template_directory() . '/dist/main.bundle.js';
        // wp_enqueue_script( 
        //     'pan_bootstrap_scripts', 
        //     $main_script_uri,  //child theme dir
        //     array('jquery'), 
        //     filemtime( $main_script_dir ), 
        //     true
        // );

        // // Enqueue it!
        // wp_enqueue_script( array('pan_bootstrap_scripts') );

        // wp_localize_script(
        //     'pan_bootstrap_scripts', 
        //     'main_obj', 
        //     array(
        //         'ajax_url' => admin_url( 'admin-ajax.php' ),
        //         'i18n' => array(
        //             'name_is_required' => __('Name is required', 'pan-bootstrap'),
        //             'email_is_required' => __('Email is required', 'pan-bootstrap'),
        //             'comment_is_required' => __('Comment is required', 'pan-bootstrap')
        //         )
        //     )
        // );
    }
}

// Add attributes to the script tag
// async or defer
// *** for CDN integrity and crossorigin attributes ***
function add_script_tag_attributes($tag, $handle)
{
    switch ($handle) {

    	// adding async to main js bundle
    	// for defer, replace async="async" with defer="defer"
    	case ('pan_bootstrap_scripts'):
    		return str_replace( ' src', ' async="async" src', $tag );
    	break;

    	// example adding CDN integrity and crossorigin attributes
    	// Note: popper.js is loaded into the main.bundle.js from npm
    	// This is just an example
        case ('popper-js'):
            return str_replace( ' min.js', 'min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"', $tag );
        break;

    	// example adding CDN integrity and crossorigin attributes
    	// Note: bootstrap.js is loaded into the main.bundle.js from npm
    	// This is just an example
        case ('bootstrap-js'):
            return str_replace( ' min.js', 'min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"', $tag );
        break;

        default:
            return $tag;

    } // /switch
}

function pan_bootstrap_conditional_scripts()
{
    // Conditional script(s)
    if (is_home() || is_front_page()) {
        wp_register_script(
            'home_js', 
            get_template_directory_uri() . '/dist/home.min.js', 
            array('jquery'), 
			filemtime( (dirname( __FILE__ )) . '/dist/home.min.js' ),
			true
        );
		wp_enqueue_script('home_js');
        wp_localize_script(
            'home_js', 
            'home_obj', 
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );	
    }elseif (is_singular()) {
		global $post;
        wp_register_script(
            'single_js', 
            get_template_directory_uri() . '/dist/single.min.js', 
            array('jquery'), 
			filemtime( (dirname( __FILE__ )) . '/dist/single.min.js' ),
			true
        );
		wp_enqueue_script('single_js');
        wp_localize_script(
            'single_js', 
            'single_obj', 
            array( 
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'i18n' => array(
                    'name_is_required' => __('Name is required', 'pan-bootstrap'),
                    'email_is_required' => __('Email is required', 'pan-bootstrap'),
                    'comment_is_required' => __('Comment is required', 'pan-bootstrap')
                )
            )
        );		
	}elseif (is_search() || is_archive()) {
        wp_register_script(
            'archive_js', 
            get_template_directory_uri() . '/dist/archive.min.js', 
            array('jquery'), 
			filemtime( (dirname( __FILE__ )) . '/dist/archive.min.js' ),
			true
        ); 
		wp_enqueue_script('archive_js');
        wp_localize_script(
            'archive_js', 
            'arch_obj', 
            array( 
				's' => get_search_query(),
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );	
	}else{
        wp_register_script(
            'archive_js', 
            get_template_directory_uri() . '/dist/archive.min.js', 
            array('jquery'), 
			filemtime( (dirname( __FILE__ )) . '/dist/archive.min.js' ),
			true
        ); 
		wp_enqueue_script('archive_js');
        wp_localize_script(
            'archive_js', 
            'arch_obj', 
            array( 
				's' => get_search_query(),
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );	
    }

}

function pan_bootstrap_styles() {

    if (!is_admin()) {
        wp_dequeue_style( 'wp-block-library' );
    }

    if (is_home() || is_front_page()) {
		wp_enqueue_style( 
			'home_styles', 
			get_template_directory_uri() . '/dist/home.min.css', 
			array(), 
			filemtime( (dirname( __FILE__ )) . '/dist/home.min.css' ),
			'all'
		);
    }elseif (is_singular()) {
		wp_enqueue_style( 
			'single_styles', 
			get_template_directory_uri() . '/dist/single.min.css', 
			array(), 
			filemtime( (dirname( __FILE__ )) . '/dist/single.min.css' ),
			'all'
		);
	}elseif (is_search() || is_archive()) {
		wp_enqueue_style( 
			'archive_styles', 
			get_template_directory_uri() . '/dist/archive.min.css', 
			array(), 
			filemtime( (dirname( __FILE__ )) . '/dist/archive.min.css' ),
			'all'
		);
	}else{
		wp_enqueue_style( 
			'archive_styles', 
			get_template_directory_uri() . '/dist/archive.min.css', 
			array(), 
			filemtime( (dirname( __FILE__ )) . '/dist/archive.min.css' ),
			'all'
		);        
    }

}

function register_pan_bootstrap_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'pan-bootstrap'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'pan-bootstrap'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'pan-bootstrap') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Remove Emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'pan-bootstrap'),
        'description' => __('Description for this widget-area...', 'pan-bootstrap'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s card mb-2"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3 class="card-title">',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'pan-bootstrap'),
        'description' => __('Description for this widget-area...', 'pan-bootstrap'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s card mb-2"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3 class="card-title">',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function pan_bootstrap_pagination()
{
    global $wp_query;
    $big = 999999999;
    $links = paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<span class="border p-1">&lt;</span>',
        'next_text' => '<span class="border p-1">&gt;</span>',
        'before_page_number' => '<span class="border p-1">',
        'after_page_number' => '</span>',
    ));

    if ( $links ) :

        echo $links;

    endif;

}

// Custom Excerpts
function pan_bootstrap_wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using pan_bootstrap_excerpt('pan_bootstrap_index');
{
    return 580;
}

// Create 40 Word Callback for Custom Post Excerpts, call using pan_bootstrap_excerpt('pan_bootstrap_custom_post');
function pan_bootstrap_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function pan_bootstrap_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function pan_bootstrap_view_article($more)
{
    global $post;
    return '... <p><a class="view-article btn btn-secondary" href="' . get_permalink($post->ID) . '" role="button">' . __('Read more', 'pan-bootstrap') . ' </a></p>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function pan_bootstrap_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function pan_bootstrap_gravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
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
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
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
<?php }

// add Bootstrap 4 .img-fluid class to images inside post content
function add_class_to_image_in_content($content) 
{

	$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
	$document = new DOMDocument();
    libxml_use_internal_errors(true);
    if (!empty($content)) {
        $document->loadHTML(utf8_decode($content));

        $imgs = $document->getElementsByTagName('img');
        foreach ($imgs as $img) {           
            $img->setAttribute('class','img-fluid');
        }
    }
    $html = $document->saveHTML();
	return $html;  	

}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('wp', 'pan_bootstrap_header_scripts', 99); // Add Custom Scripts to wp_head
add_action('wp', 'pan_bootstrap_conditional_scripts', 99); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'pan_bootstrap_styles'); // Add Theme Stylesheet
add_action('init', 'register_pan_bootstrap_menu'); // Add Menu
add_action('init', 'create_post_type_custom_post_type_demo'); // Add Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'pan_bootstrap_pagination'); // Add our pan_bootstrap Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('script_loader_tag', 'add_script_tag_attributes', 10, 2); // Add attributes to CDN script tag
add_filter('avatar_defaults', 'pan_bootstrap_gravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'pan_bootstrap_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'pan_bootstrap_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
// add .img-fluid class to images in the content
add_filter('the_content', 'add_class_to_image_in_content');

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('pan_bootstrap_shortcode_demo', 'pan_bootstrap_shortcode_demo'); // You can place [pan_bootstrap_shortcode_demo] in Pages, Posts now.
add_shortcode('pan_bootstrap_shortcode_demo_2', 'pan_bootstrap_shortcode_demo_2'); // Place [pan_bootstrap_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [pan_bootstrap_shortcode_demo] [pan_bootstrap_shortcode_demo_2] Here's the page title! [/pan_bootstrap_shortcode_demo_2] [/pan_bootstrap_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called custom-post-type
function create_post_type_custom_post_type_demo()
{

}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function pan_bootstrap_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function pan_bootstrap_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

/**
 * Custom callback for outputting comments 
 */
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

function pb_get_post_1st_img($post) {
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if (!empty($matches[1][0])) {
        $first_img = $matches[1][0];
    }
    return $first_img;
}

add_shortcode('code', 'code_highlight_function');
function code_highlight_function( $atts = array(), $content = null ) {
    // set up default parameters
    extract(
        shortcode_atts(array(
            'lang' => 'php'
            ), $atts
        )
    );
    return '<pre><code class="'.$lang.'">'.$content.'</code></pre>';
}