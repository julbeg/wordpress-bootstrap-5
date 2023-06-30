<?php
require __DIR__ . '/vendor/autoload.php';

use MyTheme\Theme\Bootstrap\Bootstrap;
use MyTheme\Theme\Theme;

$theme = Theme::get_instance();

$theme->add_google_fonts( 'Open+Sans:ital,wght@0,400;0,700;1,400;1,700' );

$theme->remove_duotone_filters()
		->remove_block_editor()
		->remove_comments()
		->remove_post_taxonomies()
		->remove_emojis();

$theme->add_menu( array( 'footer-menu' => 'Menu du footer' ) );

$theme->add_widget( 'Primary Widget Area (Sidebar)', 'primary_widget_area' );
$theme->add_widget( 'Secondary Widget Area (Header Navigation)', 'secondary_widget_area' );
$theme->add_widget( 'Third Widget Area (Footer)', 'third_widget_area' );

Bootstrap::init();
