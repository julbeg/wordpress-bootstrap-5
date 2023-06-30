<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<a href="#main" class="visually-hidden-focusable"><?php esc_html_e( 'Skip to main content', 'my-theme' ); ?></a>

<div id="wrapper">
	<header>
		<nav id="header" class="navbar navbar-expand-md navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php echo my_theme_image( 'logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
				</a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'my-theme' ); ?>">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div id="navbar" class="collapse navbar-collapse">
					<?php
					bootstrap_navbar(
						array(
							'theme_location' => 'main-menu',
							'menu_class'     => 'me-auto mb-2 mb-lg-0'
						)
					);
					?>

					<form class="search-form my-2 my-lg-0" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<div class="input-group">
							<input type="text" name="s" class="form-control" placeholder="<?php esc_attr_e( 'Search', 'my-theme' ); ?>" title="<?php esc_attr_e( 'Search', 'my-theme' ); ?>" />
							<button type="submit" name="submit" class="btn btn-outline-secondary"><?php esc_html_e( 'Search', 'my-theme' ); ?></button>
						</div>
					</form>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container -->
		</nav><!-- /#header -->
	</header>

	<main id="main" class="container" >
		<?php if ( is_single() || is_archive() ) : ?>
			<div class="row">
				<div class="col-md-8 col-sm-12">
			<?php endif; ?>
