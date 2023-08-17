<?php
/**
 * The template for displaying "not found" content in the Blog Archives.
 */
?>
<article id="post-0" class="post no-results not-found">
	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'my-theme' ); ?></h1>
	</header><!-- /.entry-header -->
	<div class="entry-content">
		<p><?php esc_html_e( 'Apologies, but no results were found for the requested archive.', 'my-theme' ); ?></p>
		<?php get_search_form(); ?>
	</div><!-- /.entry-content -->
</article><!-- /#post-0 -->
