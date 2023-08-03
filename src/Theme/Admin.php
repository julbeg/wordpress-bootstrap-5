<?php

declare(strict_types=1);

namespace MyTheme\Theme;

use WP_Admin_Bar;

class Admin {

	public function __construct() {
		add_action( 'login_enqueue_scripts', array( __CLASS__, 'login_enqueue_scripts' ) );
		add_filter( 'login_headerurl', array( __CLASS__, 'login_headerurl' ) );
		add_action( 'admin_bar_menu', array( __CLASS__, 'remove_admin_bar_wp_logo' ) );

		add_action( 'admin_init', array( __CLASS__, 'editor_styles' ) );
	}

	/**
	 * Customize admin login page
	 */
	public static function login_enqueue_scripts() {
		?>
		<style type="text/css">
			body.login div#login h1 a {
				background-image: url(<?php echo my_theme_image( 'logo.png' ); ?>);
				background-size: contain;
				width: 100px;
				height: 100px;
			}
		</style>
		<?php
	}

	/**
	 * Change admin login page logo link
	 */
	public static function login_headerurl() {
		return home_url();
	}

	/**
	 * Remove the WP logo from admin bar
	 */
	public static function remove_admin_bar_wp_logo( WP_Admin_Bar $wp_admin_bar ) :void {
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}

	/**
	 * Add theme supports and editor style
	 */
	public static function editor_styles() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );
	}

	/**
	 * Disable posts auto saving
	 */
	public function disable_autosave() :void {
		add_action(
			'admin_init',
			function () {
				wp_deregister_script( 'autosave' );
			}
		);
	}

}
