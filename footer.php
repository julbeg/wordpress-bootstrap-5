<?php if ( is_single() || is_archive() ) : ?>
				</div><!-- /.col -->

				<?php get_sidebar(); ?>

			</div><!-- /.row -->
			<?php endif; ?>
		</main><!-- /#main -->
		<footer id="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<?php /* translators: 1: Year date, 2: Blog name */ ?>
						<p><?php printf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'my-theme' ), wp_date( 'Y' ), get_bloginfo( 'name', 'display' ) ); ?></p>
					</div>

					<?php
					if ( has_nav_menu( 'footer-menu' ) ) :
						bootstrap_nav( array( 'theme_location' => 'footer-menu' ) );
					endif;

					if ( is_active_sidebar( 'third_widget_area' ) ) :
						?>
						<div class="col-md-12">
						<?php
							dynamic_sidebar( 'third_widget_area' );

						if ( current_user_can( 'manage_options' ) ) :
							?>
								<span class="edit-link"><a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" class="badge bg-secondary"><?php esc_html_e( 'Edit', 'my-theme' ); ?></a></span><!-- Show Edit Widget link -->
							<?php
							endif;
						?>
						</div>
						<?php
						endif;
					?>
				</div><!-- /.row -->
			</div><!-- /.container -->
		</footer><!-- /#footer -->
	</div><!-- /#wrapper -->
	<?php
		wp_footer();
	?>
</body>
</html>
