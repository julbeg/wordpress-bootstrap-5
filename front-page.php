<?php
get_header();

the_post();
?>
<div <?php post_class( 'content' ); ?>>

	<?php the_content(); ?>

	<div class="clearfix"></div>
</div>
<?php
get_footer();
