<?php

declare(strict_types=1);

function my_theme_theme() {
	return \MyTheme\Theme\Theme::get_instance();
}

/**
 * Test if a page is a blog page.
 */
function is_blog() :bool {
	global $post;
	$posttype = get_post_type( $post );

	return ( ( is_archive() || is_author() || is_category() || is_home() || is_single() || ( is_tag() && ( 'post' === $posttype ) ) ) ? true : false );
}

function my_theme_image( $filename ) {
	return get_stylesheet_directory_uri() . '/assets/img/' . $filename;
}

/**
 * "Theme posted on" pattern.
 */
function my_theme_article_posted_on() : void {
	printf(
		/* translators: 1: Post link, 2: Date and time, 3: Date ISO 8601, 4: Date and time, Author url, 5: Author's posts url, 6: View all posts by author, 7: Author name */
		wp_kses_post( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author-meta vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'my-theme' ) ),
		esc_url( get_the_permalink() ),
		esc_attr( get_the_date() . ' - ' . get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() . ' - ' . get_the_time() ),
		esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
		/* translators: %s: Author name */
		sprintf( esc_attr__( 'View all posts by %s', 'my-theme' ), get_the_author() ),
		get_the_author()
	);
}