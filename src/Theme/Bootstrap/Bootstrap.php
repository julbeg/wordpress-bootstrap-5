<?php

declare(strict_types=1);

namespace MyTheme\Theme\Bootstrap;

use MyTheme\Theme\Theme;

class Bootstrap {

	public static function init() :void {
		self::load_theme_functions();

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_script' ) );

		add_filter( 'use_default_gallery_style', array( __CLASS__, '__return_false' ) );

		// Apply nav styles to menu widgets
		// add_filter( 'wp_nav_menu_args', array( __CLASS__, 'custom_widget_nav_menu') );

		add_filter( 'edit_post_link', array( __CLASS__, 'custom_edit_post_link' ) );

		add_filter( 'edit_comment_link', array( __CLASS__, 'custom_edit_comment_link' ) );

		add_filter( 'embed_oembed_html', array( __CLASS__, 'oembed_filter' ) );

		add_filter( 'next_posts_link_attributes', array( __CLASS__, 'posts_link_attributes' ) );
		add_filter( 'previous_posts_link_attributes', array( __CLASS__, 'posts_link_attributes' ) );

		add_filter( 'the_password_form', array( __CLASS__, 'password_form' ) );

		add_filter( 'comment_reply_link', array( __CLASS__, 'replace_reply_link_class' ) );

		add_filter( 'comment_form_defaults', array( __CLASS__, 'custom_commentform' ) );
	}

	public static function load_theme_functions() {
		require_once __DIR__ . '/functions.php';
	}


	/**
	 * Enqueue scripts and styles.
	 */
	public static function add_script() :void {
		Theme::add_script( 'bootstrap', '/assets/js/bootstrap.js' );
	}

	/**
	 * Customize nav menu widget
	 */
	public static function custom_widget_nav_menu( array $args ) :array {
		// If no theme_location probably a widget
		if ( empty( $args['walker'] ) && empty( $args['theme_location'] ) ) {
			$args['walker']     = new Bootstrap_Walker_Nav_Menu();
			$args['menu_class'] = 'nav flex-column menu';
		}

		return $args;
	}


	/**
	 * Style Edit buttons as badges: https://getbootstrap.com/docs/5.0/components/badge
	 */
	public static function custom_edit_post_link( string $link ) :string {
		return str_replace( 'class="post-edit-link"', 'class="post-edit-link badge bg-secondary"', $link );
	}

	/**
	 * Style Edit buttons as badges: https://getbootstrap.com/docs/5.0/components/badge
	 */
	public static function custom_edit_comment_link( string $link ) :string {
		return str_replace( 'class="comment-edit-link"', 'class="comment-edit-link badge bg-secondary"', $link );
	}

	/**
	 * Responsive oEmbed filter: https://getbootstrap.com/docs/5.0/helpers/ratio
	 */
	public static function oembed_filter( string $html ) :string {
		return '<div class="ratio ratio-16x9">' . $html . '</div>';
	}


	/**
	 * Add Class.
	 */
	public static function posts_link_attributes() :string {
		return 'class="btn btn-secondary btn-lg"';
	}


	/**
	 * Template for Password protected post form.
	 */
	public static function password_form() :string {
		global $post;
		$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );

		$output                  = '<div class="row">';
			$output             .= '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
			$output             .= '<h4 class="col-md-12 alert alert-warning">' . esc_html__( 'This content is password protected. To view it please enter your password below.', 'my-theme' ) . '</h4>';
				$output         .= '<div class="col-md-6">';
					$output     .= '<div class="input-group">';
						$output .= '<input type="password" name="post_password" id="' . esc_attr( $label ) . '" placeholder="' . esc_attr__( 'Password', 'my-theme' ) . '" class="form-control" />';
						$output .= '<div class="input-group-append"><input type="submit" name="submit" class="btn btn-primary" value="' . esc_attr__( 'Submit', 'my-theme' ) . '" /></div>';
					$output     .= '</div><!-- /.input-group -->';
				$output         .= '</div><!-- /.col -->';
			$output             .= '</form>';
		$output                 .= '</div><!-- /.row -->';

		return $output;
	}

	/**
	 * Style Reply link.
	 */
	public static function replace_reply_link_class( string $class ) :string {
		return str_replace( "class='comment-reply-link", "class='comment-reply-link btn btn-outline-secondary", $class );
	}


	/**
	 * Custom Comment form.
	 */
	public static function custom_commentform( array $args = array(), int $post_id = null ) :array {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		$commenter = wp_get_current_commenter();
		$user      = wp_get_current_user();

		$args = wp_parse_args( $args );

		$req      = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true' required" : '' );
		$consent  = ( empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"' );
		$fields   = array(
			'author'  => '<div class="form-floating mb-3">
							<input type="text" id="author" name="author" class="form-control" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_html__( 'Name', 'my-theme' ) . ( $req ? '*' : '' ) . '"' . $aria_req . ' />
							<label for="author">' . esc_html__( 'Name', 'my-theme' ) . ( $req ? '*' : '' ) . '</label>
						</div>',
			'email'   => '<div class="form-floating mb-3">
							<input type="email" id="email" name="email" class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '" placeholder="' . esc_html__( 'Email', 'my-theme' ) . ( $req ? '*' : '' ) . '"' . $aria_req . ' />
							<label for="email">' . esc_html__( 'Email', 'my-theme' ) . ( $req ? '*' : '' ) . '</label>
						</div>',
			'url'     => '',
			'cookies' => '<p class="form-check mb-3 comment-form-cookies-consent">
							<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" class="form-check-input" type="checkbox" value="yes"' . $consent . ' />
							<label class="form-check-label" for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'my-theme' ) . '</label>
						</p>',
		);

		$defaults = array(
			'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'        => '<div class="form-floating mb-3">
											<textarea id="comment" name="comment" class="form-control" aria-required="true" required placeholder="' . esc_attr__( 'Comment', 'my-theme' ) . ( $req ? '*' : '' ) . '"></textarea>
											<label for="comment">' . esc_html__( 'Comment', 'my-theme' ) . '</label>
										</div>',
			/* This filter is documented in wp-includes/link-template.php */
			/* translators: %s: Login url */
			'must_log_in'          => '<p class="must-log-in">' . sprintf( wp_kses_post( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'my-theme' ) ), wp_login_url( esc_url( get_the_permalink( get_the_ID() ) ) ) ) . '</p>',
			/** This filter is documented in wp-includes/link-template.php */
			/* translators: %1$s: Logged user edit link, %2$s: User name, %3$s: Logout url */
			'logged_in_as'         => '<p class="logged-in-as">' . sprintf( wp_kses_post( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'my-theme' ) ), get_edit_user_link(), $user->display_name, wp_logout_url( apply_filters( 'the_permalink', esc_url( get_the_permalink( get_the_ID() ) ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="small comment-notes">' . esc_html__( 'Your Email address will not be published.', 'my-theme' ) . '</p>',
			'comment_notes_after'  => '',
			'id_form'              => 'commentform',
			'id_submit'            => 'submit',
			'class_submit'         => 'btn btn-primary',
			'name_submit'          => 'submit',
			'title_reply'          => '',
			/* translators: %s: Comment's user name */
			'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'my-theme' ),
			'cancel_reply_link'    => esc_html__( 'Cancel reply', 'my-theme' ),
			'label_submit'         => esc_html__( 'Post Comment', 'my-theme' ),
			'submit_button'        => '<input type="submit" id="%2$s" name="%1$s" class="%3$s" value="%4$s" />',
			'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
			'format'               => 'html5',
		);

		return $defaults;
	}

}
