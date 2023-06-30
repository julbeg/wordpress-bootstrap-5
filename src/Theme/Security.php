<?php

declare(strict_types=1);

namespace MyTheme\Theme;

use WP_Sitemaps_Provider;

class Security {
	public function __construct() {
		// Disable author pages
		add_action( 'template_redirect', array( __CLASS__, 'disable_author_page' ) );

		//Remove users from sitemap
		add_filter( 'wp_sitemaps_add_provider', array( __CLASS__, 'remove_sitemap_users' ), 10, 2 );

		// Disable attachments comments
		add_filter( 'comments_open', array( __CLASS__, 'filter_media_comment_status' ), 10, 2 );
	}

	/**
	 * Disable author pages for security
	 */
	public static function disable_author_page() :void {
		if ( is_author() ) {
			wp_redirect( get_option( 'home' ), 301 );
			exit;
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
