<?php
/**
 * The template for displaying comments.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Pan-Bootstrap
 * @since 0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( post_password_required() ) :?>
	<div id="comments">
		<p>
			<?php _e('This post is password protected. Enter the password to view any comments', 'pan-bootstrap'); ?>
		</p>
	</div>
	<?php
	return;
endif;
?>

<div id="comments">
	<?php if ( have_comments() ) : ?>
		<h2>
			<?php comments_number(); ?>
		</h2>
		<ul class="media-list">
			<?php wp_list_comments( array( 'callback' => 'pan_bootstrap_comments' ) ); ?>
		</ul>
	<?php elseif ( !comments_open() && !is_page() &&  post_type_supports( get_post_type(), 'comments' ) ): ?>
		<p>
			<?php echo __('Comments are closed', 'pan-bootstrap')?>
		</p>
	<?php endif; ?>

	<?php
	/*
	 * Adding bootstrap support to comment form,
	 * and some form validation using javascript.
	 */
	
	ob_start();
	$commenter = wp_get_current_commenter();
	$req = true;
	$aria_req = ( $req ? " aria-required='true'" : '' );
	
	$comments_arg = array(
		'form'	=> array(
			'class' => 'form-horizontal'
		),
		'fields' => apply_filters( 
			'comment_form_default_fields', array(
				'autor' => 
					'<div class="form-group">' . 
						'<label for="author">' . __( 'Name', 'pan-bootstrap' ) . '</label> ' . ( $req ? '<span>*</span>' : '' ) .
						'<input id="author" name="author" class="form-control" type="text" value="" size="30"' . $aria_req . ' />'.
						'<p id="d1" class="text-danger"></p>' . 
					'</div>',
				'email' => 
					'<div class="form-group">' .
						'<label for="email">' . __( 'Email', 'pan-bootstrap' ) . '</label> ' . ( $req ? '<span>*</span>' : '' ) .
						'<input id="email" name="email" class="form-control" type="text" value="" size="30"' . $aria_req . ' />'.
						'<p id="d2" class="text-danger"></p>' . 
					'</div>',
				'url' => ''
			)
		),
		'comment_field' => 
			'<div class="form-group">' . 
				'<label for="comment">' . __( 'Comment', 'pan-bootstrap' ) . '</label><span>*</span>' .
				'<textarea id="comment" class="form-control" name="comment" rows="3" aria-required="true"></textarea>'.
				'<p id="d3" class="text-danger"></p>' . 
			'</div>',
		'comment_notes_after' => '',
		'class_submit' => 'btn btn-primary'
	); 
	comment_form($comments_arg);
	echo str_replace('class="comment-form"','class="comment-form" name="commentForm"',ob_get_clean());
	?>	
</div>
<!--#comments-->