<?php

declare(strict_types=1);

/**
 * Display a navigation to next/previous pages when applicable.
 */
function my_theme_content_nav( string $nav_id ) : void {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) {
		?>
		<div id="<?php echo esc_attr( $nav_id ); ?>" class="d-flex mb-4 justify-content-between">
			<div><?php next_posts_link( '<span aria-hidden="true">&larr;</span> ' . esc_html__( 'Older posts', 'my-theme' ) ); ?></div>
			<div><?php previous_posts_link( esc_html__( 'Newer posts', 'my-theme' ) . ' <span aria-hidden="true">&rarr;</span>' ); ?></div>
		</div><!-- /.d-flex -->
		<?php
	} else {
		echo '<div class="clearfix"></div>';
	}
}

/**
 * Template for comments and pingbacks:
 * add function to comments.php ... wp_list_comments( array( 'callback' => 'my_theme_comment' ) );
 *
 * @since v1.0
 *
 * @param object $comment Comment object.
 * @param array  $args    Comment args.
 * @param int    $depth   Comment depth.
 */
function my_theme_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback':
		case 'trackback':
			?>
	<li class="post pingback">
		<p>
			<?php
				esc_html_e( 'Pingback:', 'my-theme' );
				comment_author_link();
				edit_comment_link( esc_html__( 'Edit', 'my-theme' ), '<span class="edit-link">', '</span>' );
			?>
		</p>
			<?php
			break;
		default:
			?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = ( '0' !== $comment->comment_parent ? 68 : 136 );
						echo get_avatar( $comment, $avatar_size );

						printf(
							/* translators: 1: Comment author, 2: Date and time */
							wp_kses_post( __( '%1$s, %2$s', 'my-theme' ) ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf(
								'<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								sprintf(
									/* translators: 1: Date, 2: Time */
									esc_html__( '%1$s ago', 'my-theme' ),
									/* phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested */
									human_time_diff( (int) get_comment_time( 'U' ), current_time( 'timestamp' ) )
								)
							)
						);

						edit_comment_link( esc_html__( 'Edit', 'my-theme' ), '<span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-author .vcard -->

				<?php if ( '0' === $comment->comment_approved ) { ?>
					<em class="comment-awaiting-moderation">
						<?php esc_html_e( 'Your comment is awaiting moderation.', 'my-theme' ); ?>
					</em>
					<br />
				<?php } ?>
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'reply_text' => esc_html__( 'Reply', 'my-theme' ) . ' <span>&darr;</span>',
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
							)
						)
					);
				?>
			</div><!-- /.reply -->
		</article><!-- /#comment-## -->
			<?php
			break;
	endswitch;
}


/**
 * Displays a bootstrap navigation menu.
 * @param array $args
 *
 * @return void|string|false Void if 'echo' argument is true, menu output if 'echo' is false.
 *                           False if there are no items or no menu was found.
 */
function bootstrap_navbar( $args ) {
	$args['type'] = 'navbar-nav';

	return bootstrap_nav( $args );
}

/**
 * Displays a bootstrap navigation menu.
 * @see wp_nav_menu()
 *
 * @param array $args {
 *     Optional. Array of nav menu arguments.
 *
 *     @type string             $type                 The type of nav : navbar-nav or nav.
 *                                                    Default 'nav'.
 *     @type string             $nav_style            The style of the nav : none, tabs, pills or underline.
 *                                                    Default is none.
 * }
 * @return void|string|false Void if 'echo' argument is true, menu output if 'echo' is false.
 *                           False if there are no items or no menu was found.
 */
function bootstrap_nav( $args ) {
	$defaults = array(
		'container'  => false,
		'type'       => 'nav',
		'nav_style'  => '',
		'menu_class' => 'menu',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( 'nav' === $args['type'] && $args['nav_style'] ) {
		$args['nav_style'] = 'nav-' . $args['nav_style'];
	}

	$args['menu_class']  = trim( $args['type'] . ' ' . $args['nav_style'] . ' ' . $args['menu_class'] );
	$args['walker']      = new \MyTheme\Theme\Bootstrap\Bootstrap_Walker_Nav_Menu();
	$args['fallback_cb'] = '\MyTheme\Theme\Bootstrap\Bootstrap_Walker_Nav_Menu::fallback';

	return wp_nav_menu( $args );
}