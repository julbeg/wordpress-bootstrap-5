<?php

declare(strict_types=1);

namespace LTJ\Theme;

use WP_Sitemaps_Provider;

class Security {
	public function __construct() {
		// Disable author pages
		add_action( 'template_redirect', array( __CLASS__, 'disable_author_page' ) );

		//Remove users from sitemap
		add_filter( 'wp_sitemaps_add_provider', array( __CLASS__, 'remove_sitemap_users' ), 10, 2 );

		// Remove users from rest API
		add_action( 'rest_authentication_errors', array( __CLASS__, 'remove_api_users' ) );

		// Disable attachments comments
		add_filter( 'comments_open', array( __CLASS__, 'filter_media_comment_status' ), 10, 2 );
	}

	/**
	 * Disable author pages for security
	 */
	public static function disable_author_page() :void {
		global $wp_query;

		if ( is_author() || isset( $_GET['author'] ) ) {
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
		}
	}

	/**
	 * Remove users from sitemap
	 */
	public static function remove_sitemap_users( WP_Sitemaps_Provider $provider, string $name ) :WP_Sitemaps_Provider|bool {
		if ( 'users' === $name ) {
			return false;
		}

		return $provider;
	}

	public static function remove_api_users( $access ) {
		if ( is_user_logged_in() ) {
			return $access;
		}

		if ( ( preg_match( '/users/i', $_SERVER['REQUEST_URI'] ) !== 0 )
			|| ( isset( $_REQUEST['rest_route'] ) && ( preg_match( '/users/i', $_REQUEST['rest_route'] ) !== 0 ) )
		) {
			return new \WP_Error(
				'rest_cannot_access',
				'Only authenticated users can access the User endpoint REST API.',
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return $access;
	}

	/**
	 * Disable comments for Media (Image-Post, Jetpack-Carousel, etc.)
	 */
	public static function filter_media_comment_status( bool $open, int $post_id = null ) :bool {
		$media_post = get_post( $post_id );

		if ( 'attachment' === $media_post->post_type ) {
			return false;
		}

		return $open;
	}

}
