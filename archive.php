<?php
/**
 * The Template for displaying Archive pages.
 */

get_header();

if ( have_posts() ) :
	?>
<header class="page-header">
	<h1 class="page-title">
		<?php
		if ( is_day() ) :
			/* translators: %s: Date */
			printf( esc_html__( 'Daily Archives: %s', 'my-theme' ), get_the_date() );
			elseif ( is_month() ) :
				/* translators: %s: Month and Year */
				printf( esc_html__( 'Monthly Archives: %s', 'my-theme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'my-theme' ) ) );
			elseif ( is_year() ) :
				/* translators: %s: Year */
				printf( esc_html__( 'Yearly Archives: %s', 'my-theme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'my-theme' ) ) );
			else :
				esc_html_e( 'Blog Archives', 'my-theme' );
			endif;
			?>
	</h1>
</header>
	<?php
	get_template_part( 'template-parts/archive-loop' );
else :
	// 404.
	get_template_part( 'template-parts/content-none' );
endif;

wp_reset_postdata(); // End of the loop.

get_footer();
