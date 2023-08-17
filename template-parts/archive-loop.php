<?php
/**
 * The template for displaying the archive loop.
 */

my_theme_content_nav( 'nav-above' );

?>
<div class="loop">
<?php
while ( have_posts() ) :
	the_post();

	/**
	 * Include the Post-Type-specific template for the content.
	 * If you want to overload this in a child theme then include a file
	 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
	 */
	get_template_part( 'template-parts/content', get_post_type() ); // Post type: content-post.php
	endwhile;
?>
</div>
<?php

wp_reset_postdata();

my_theme_content_nav( 'nav-below' );
