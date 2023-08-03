<?php

declare(strict_types=1);

namespace MyTheme\Theme;

use WP_Admin_Bar;

class Theme {
	/**
	 * The current globally available container (if any).
	 *
	 * @var static
	 */
	protected static $instance;

	private bool $comments_support = true;

	/**
	 * The loaded modules
	 */
	private array $modules = array();

	/**
	 * Get the globally available instance of the container.
	 */
	public static function get_instance() :static {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	private function __construct() {
		$this->load_theme_functions();

		$this->load( Security::class, 'security' );
		$this->load( Admin::class, 'admin' );

		$this->add_theme_styles_and_scripts();

		$this->setup_theme();

		$this->add_menu(
			array(
				'main-menu' => 'Menu principal',
			)
		);

	}

	/**
	 * Retrieve a module
	 */
	public function __get( $name ) {
		return $this->modules[ $name ] ?? null;
	}

	/**
	 * Load theme helpers functions
	 */
	public function load_theme_functions() {
		require_once __DIR__ . '/functions.php';
	}

	/**
	 * Load a new module
	 */
	public function load( string $class, string|false $short_name = '' ) :mixed {
		$module = new $class;

		if ( false !== $short_name ) {
			if ( ! $short_name ) {
				$short_name = strtolower( ( new \ReflectionClass( $class ) )->getShortName() );
			}

			$this->modules[ $short_name ] = $module;
		}

		return $module;
	}

	/**
	 * Enqueue style with version based on filemtime
	 */
	public static function add_style( string $handle, string $src = '', array $deps = array(), bool $enqueue = true ) :void {
		$file     = get_template_directory() . $src;
		$file_uri = get_template_directory_uri() . $src;
		$version  = filemtime( $file );
		wp_register_style( $handle, $file_uri, $deps, $version );
		if ( $enqueue ) {
			wp_enqueue_style( $handle );
		}
	}

	/**
	 * Dequeue style
	 */
	public function remove_style(string $style) :self {
		add_action( 'wp_enqueue_scripts', function() use($style) {
			wp_dequeue_style( 'global-styles' );
		}, 100 );

		return $this;
	}
	
	/**
	 * Enqueue scripts with version based on filemtime
	 */
	public static function add_script( string $handle, string $src = '', array $deps = array(), bool $in_footer = true, bool $enqueue = true ) :void {
		$file     = get_template_directory() . $src;
		$file_uri = get_template_directory_uri() . $src;
		$version  = filemtime( $file );
		wp_register_script( $handle, $file_uri, $deps, $version, $in_footer );
		if ( $enqueue ) {
			wp_enqueue_script( $handle );
		}
	}


	/**
	 * Add theme support
	 */
	public function add_support( string $feature, $options = null ) :self {
		add_action(
			'after_setup_theme',
			function() use ( $feature, $options ) {
				if ( $options ) {
					add_theme_support( $feature, $options );
				} else {
					add_theme_support( $feature );
				}
			}
		);

		return $this;
	}

	/**
	 * Remove theme support
	 */
	public function remove_support( string $feature, $options = null ) :self {
		add_action(
			'after_setup_theme',
			function() use ( $feature, $options ) {
				if ( $options ) {
					remove_theme_support( $feature, $options );
				} else {
					remove_theme_support( $feature );
				}
			}
		);

		return $this;
	}

	/**
	 * Add custom image size
	 */
	public function add_image_size( string $name, int $width = 0, int $height = 0, bool $crop = false ) :self {
		add_action(
			'after_setup_theme',
			function() use ( $name, $width, $height, $crop ) {
				add_image_size( $name, $width, $height, $crop );
			}
		);

		return $this;
	}

	/**
	 * Register nav menus
	 */
	public function add_menu( array $locations = array() ) :self {
		add_action(
			'after_setup_theme',
			function() use ( $locations ) {
				register_nav_menus( $locations );
			}
		);

		return $this;
	}

	/**
	 * Register multiple wigets at once
	 */
	public function add_widget( string $name, string $id = '', int $number = 1 ) :void {
		if ( ! $id ) {
			$id = sanitize_title( $name );
		}

		if ( $number > 1 ) {
			for ( $i = 1; $i <= $number; $i++ ) {
				register_sidebar(
					array(
						'name'          => $name . ' ' . $i,
						'id'            => $id . '_' . $i,
						'before_widget' => '',
						'after_widget'  => '',
						'before_title'  => '<h3 class="widget-title">',
						'after_title'   => '</h3>',
					)
				);
			}
		} else {
			register_sidebar(
				array(
					'name'          => $name,
					'id'            => $id,
					'before_widget' => '',
					'after_widget'  => '',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				)
			);
		}
	}
	
	/**
	 * Register a block style
	 */
	public function add_block_style( string $block_name, array $style_properties ) :self {
		add_action(
			'after_setup_theme',
			function() use ( $block_name, $style_properties ) {
				register_block_style( $block_name, $style_properties );
			}
		);

		return $this;
	}

	/**
	 * Load google fonts on back and front
	 * css @import url() is breaking add_editor_style() so add google fonts as <link>
	 */
	public function add_google_fonts( string $family ) {
		add_action(
			'wp_head',
			function() use ( $family ) {
				$this->add_google_fonts_links( $family );
			}
		);
		add_action(
			'admin_head',
			function() use ( $family ) {
				$this->add_google_fonts_links( $family );
			}
		);
	}

	public function add_google_fonts_links( string $family ) {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
		echo '<link href="https://fonts.googleapis.com/css2?family=' . $family . '&display=swap" rel="stylesheet">';
	}

	/**
	 * Enqueue scripts and styles.
	 */
	private function add_theme_styles_and_scripts() :self {
		add_action(
			'wp_enqueue_scripts',
			function() {
				self::add_style( 'my-theme', '/assets/css/theme.css' );
				self::add_script( 'my-theme', '/assets/js/theme.js' );

				if ( $this->comments_support && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
					wp_enqueue_script( 'comment-reply' );
				}
			}
		);

		return $this;
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	private function setup_theme() {
		add_action(
			'after_setup_theme',
			function() {
				// Make theme available for translation.
				// load_theme_textdomain( 'my-theme', get_template_directory() . '/languages' );

				// Add default posts and comments RSS feed links to head.
				add_theme_support( 'automatic-feed-links' );

				// Let WordPress manage the document title.
				add_theme_support( 'title-tag' );

				// Enable support for Post Thumbnails
				add_theme_support( 'post-thumbnails' );

				/*
				* Switch default core markup for search form, comment form, and comments
				* to output valid HTML5.
				*/
				add_theme_support(
					'html5',
					array(
						'search-form',
						'comment-form',
						'comment-list',
						'gallery',
						'caption',
						'script',
						'style',
						'navigation-widgets',
					)
				);

				// Add theme support for selective refresh for widgets.
				add_theme_support( 'customize-selective-refresh-widgets' );

				// Add support for responsive embed
				add_theme_support( 'responsive-embeds' );
			}
		);
	}

	/**
	 * Hide default 'post' type
	 * Keep the post type registered for compatibility
	 */
	public function remove_posts() :self {
		add_action(
			'admin_menu',
			function() {
				remove_menu_page( 'edit.php' );
			}
		);

		add_action(
			'admin_bar_menu',
			function( WP_Admin_Bar $wp_admin_bar ) {
				$wp_admin_bar->remove_node( 'new-post' );
			},
			999
		);

		add_action(
			'load-post-new.php',
			function() {
				global $typenow;
				if ( 'post' === $typenow ) {
					wp_redirect( admin_url() );
					exit;
				}
			}
		);

		return $this;
	}

	/**
	 * Remove comment support for native post types
	 */
	public function remove_comments() :self {
		$this->comments_support = false;

		add_action(
			'after_setup_theme',
			function() {
				remove_post_type_support( 'post', 'comments' );
				remove_post_type_support( 'page', 'comments' );
			}
		);

		add_action(
			'admin_menu',
			function() {
				remove_menu_page( 'edit-comments.php' );
			}
		);

		add_action(
			'wp_before_admin_bar_render',
			function() {
				global $wp_admin_bar;

				$wp_admin_bar->remove_node( 'new-link' );
				$wp_admin_bar->remove_node( 'new-media' );
				$wp_admin_bar->remove_menu( 'wp-logo' );
				$wp_admin_bar->remove_menu( 'comments' );
			}
		);

		return $this;
	}

	/**
	 * Check if theme support comments
	 */
	public function support_comments() :bool {
		return $this->comments_support;
	}

	/**
	 * Remove gutenbord block directory support
	 */
	public function remove_block_editor() :self {
		// Disable Block Directory: https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/filters/editor-filters.md#block-directory
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
		remove_action( 'enqueue_block_editor_assets', 'gutenberg_enqueue_block_editor_assets_block_directory' );

		return $this;
	}

	/**
	 * remove duotone support for Gutenberg blocks
	 */
	public function remove_duotone_filters() :self {
		add_action(
			'after_setup_theme',
			function() {
				remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
				remove_action( 'wp_body_open', 'gutenberg_global_styles_render_svg_filters' );
			}
		);
		return $this;
	}

	/**
	 * Unregister post default taxonomies
	 */
	public function remove_post_taxonomies() :self {
		add_action(
			'after_setup_theme',
			function() {
				unregister_taxonomy_for_object_type( 'category', 'post' );
				unregister_taxonomy_for_object_type( 'post_tag', 'post' );
			}
		);

		return $this;
	}

	/**
	 * Remove emojis styles and scripts
	 */
	public function remove_emojis() :self {
		add_action(
			'after_setup_theme',
			function() {
				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
				remove_action( 'admin_print_styles', 'print_emoji_styles' );
				remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
				remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
				remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
				add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
				add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_remove_dns_prefetch' ), 10, 2 );
			}
		);

		return $this;
	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 */
	public function disable_emojis_tinymce( array $plugins ) :array {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		}

		return array();
	}

	/**
	 * Remove emoji CDN hostname from DNS prefetching hints.
	 */
	public function disable_emojis_remove_dns_prefetch( array $urls, string $relation_type ) :array {

		if ( 'dns-prefetch' === $relation_type ) {

			// Strip out any URLs referencing the WordPress.org emoji location
			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			foreach ( $urls as $key => $url ) {
				if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
					unset( $urls[ $key ] );
				}
			}
		}

		return $urls;
	}

	public function remove_attachment_pages() {
		// This will show 404 on the attachment page
		add_filter( 'template_redirect', array( $this, 'redirect_attachment' ) );

		// This will show 404 instead of redirecting to attachment page when dealing with a trailing slash
		add_filter( 'redirect_canonical', array( $this, 'redirect_attachment' ), 0 );

		// Redirect attachment page to file
		add_filter( 'attachment_link', array( $this, 'change_attachment_link' ), 10, 2 );

		//pPrevent attachment pages from reserving slugs
		add_filter( 'wp_unique_post_slug', array( $this, 'attachment_uuid_slug' ), 10, 6 );
	}

	public function redirect_attachment() {
		if ( is_attachment() ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
		}
	}

	public function change_attachment_link( string $url, int $id ) :string {
		$attachment_url = wp_get_attachment_url( $id );
		if ( $attachment_url ) {
			return $attachment_url;
		}
		return $url;
	}

	public function attachment_uuid_slug( string $slug, int $post_ID, string $post_status, string $post_type, int $post_parent, string $original_slug ) :string {
		if ( 'attachment' === $post_type ) {
			return str_replace( '-', '', wp_generate_uuid4() );
		}
		return $slug;
	}

	/**
	 * Set the excerpt length
	 */
	public function set_excerpt_length( int $length ) :void {
		add_filter(
			'excerpt_length',
			function() use ( $length ) {
				return $length;
			}
		);
	}

}
