<?php
/** skip-links.php
*	Description: Adds skiplinks after the header to content, menu's and asides.
*	Author: Rian Rietveld
*	Plugin URI: http://genesis-accessible.org/
*	License: GPLv2 or later
*/

/** Add skiplinks for screen readers and keyboard navigation
*
* @since 1.0.0
*/
add_action ( 'genesis_header', 'genwpacc_skip_links', 5);
function genwpacc_skip_links() {

    $site_layout = genesis_site_layout();

	// set defaults
	$nav = false;
	$nav2 = false;
    $sidebar = false;
    $sidebar_alt = false;
	$footer = false;


   	//  navigation?
   	if ( genesis_get_option( 'menu-primary' ) == '1' || has_nav_menu( 'primary' ) )
   		$nav = true;
	if ( genesis_get_option( 'menu-secondary' ) == '1' || has_nav_menu( 'secondary' ) )
		$nav2 = true;

   	// sidebar?
	if ( $site_layout == 'sidebar-sidebar-content' || $site_layout == 'content-sidebar-sidebar' || $site_layout == 'sidebar-content-sidebar')  {
		$sidebar = true;
    	$sidebar_alt = true;
   	}
    if ( $site_layout == 'sidebar-content' || $site_layout == 'content-sidebar' )  $sidebar = true;


    // footer widgets?
    if ( current_theme_supports( 'genesis-footer-widgets' ) == '1' && is_active_sidebar( 'footer-1' ) ) {
    	$footer_widgets = get_theme_support( 'genesis-footer-widgets' );
    	if ( isset( $footer_widgets[0] ) && is_numeric( $footer_widgets[0] ) )
    		$footer = true;
    }



	// add id's to the elements to jump to
	// genesis_markup() http://docs.garyjones.co.uk/genesis/2.0.0/source-function-genesis_parse_attr.html#77-100
	// https://gist.github.com/salcode/7164690

	if ( function_exists( 'genesis_markup' ) ) {

		add_filter( 'genesis_attr_nav-primary', 'genwpacc_genesis_attr_nav_primary' );
		function genwpacc_genesis_attr_nav_primary( $attributes ) {
    		$attributes['id'] = 'genwpacc-genesis-nav';
    		return $attributes;
		}

		add_filter( 'genesis_attr_nav-secondary', 'genwpacc_genesis_attr_nav_secondary' );
		function genwpacc_genesis_attr_nav_secondary( $attributes ) {
    		$attributes['id'] = 'genwpacc-genesis-secondary';
    		return $attributes;
		}

		add_filter( 'genesis_attr_content', 'genwpacc_genesis_attr_content' );
		function genwpacc_genesis_attr_content( $attributes ) {
    		$attributes['id'] = 'genwpacc-genesis-content';
    		return $attributes;
		}

		add_filter( 'genesis_attr_sidebar-primary', 'genwpacc_genesis_attr_sidebar_primary' );
		function genwpacc_genesis_attr_sidebar_primary( $attributes ) {
    		$attributes['id'] = 'genwpacc-sidebar-primary';
    		return $attributes;
		}

		add_filter( 'genesis_attr_sidebar-secondary', 'genwpacc_genesis_attr_sidebar_secondary' );
		function genwpacc_genesis_attr_sidebar_secondary( $attributes ) {
    		$attributes['id'] = 'genwpacc-sidebar-secondary';
    		return $attributes;
		}

		if ( !function_exists( 'genesis_attr_footer-widgets' ) ) {

			add_filter( 'genesis_attr_footer-widgets', 'genwpacc_genesis_attr_footer_widgets' );
			function genwpacc_genesis_attr_footer_widgets( $attributes ) {

				if ( ! is_active_sidebar( 'footer-1' ) )
					return;

				$attributes['id'] = 'genwpacc-genesis-footer-widgets';
     			return $attributes;

			}

		} else {

			add_filter( 'genesis_attr_footer-widgets', 'genwpacc_genesis_attr_footer_widgets' );
			function genwpacc_genesis_attr_footer_widgets( $attributes ) {

					if ( ! is_active_sidebar( 'footer-1' ) )
						return;

    				$attributes['id'] .= 'genwpacc-genesis-footer-widgets';
    				return $attributes;
			}
		}

	}


    // write HTML, skiplinks in a list with a heading

   	?> <!-- skiplinks --><?php

    echo '<h2 class="screen-reader-text">'. __( 'Skip links', GENWPACC_DOMAIN ) .'</h2>' . "\n";

	echo '<ul class="genwpacc-genesis-skip-link">' . "\n";

    if ( $nav ) echo '  <li><a href="#genwpacc-genesis-nav" class="screen-reader-shortcut">'. __( 'Jump to main navigation', GENWPACC_DOMAIN ) .'</a></li>' . "\n";

	if ( $nav2 ) echo '  <li><a href="#genwpacc-genesis-secondary" class="screen-reader-shortcut">'. __( 'Jump to sub navigation', GENWPACC_DOMAIN ) .'</a></li>' . "\n";

	echo '  <li><a href="#genwpacc-genesis-content" class="screen-reader-shortcut">'. __( 'Jump to content', GENWPACC_DOMAIN ) .'</a></li>' . "\n";

	if ( $sidebar ) echo '  <li><a href="#genwpacc-sidebar-primary" class="screen-reader-shortcut">'. __( 'Jump to primary sidebar', GENWPACC_DOMAIN ) .'</a></li>' . "\n";

	if ( $sidebar_alt ) echo '  <li><a href="#genwpacc-sidebar-secondary" class="screen-reader-shortcut">'. __( 'Jump to secondary sidebar', GENWPACC_DOMAIN ) .'</a></li>' . "\n";

	if ( $footer ) echo '  <li><a href="#genwpacc-genesis-footer-widgets" class="screen-reader-shortcut">'. __( 'Jump to footer', GENWPACC_DOMAIN ) .'</a></li>' . "\n";

	echo '</ul>' . "\n";

}

add_action( 'wp_enqueue_scripts', 'genwpacc_skiplinks_scripts' );
function genwpacc_skiplinks_scripts() {

	wp_enqueue_script( 'genwpacc-skiplinks-js',  GENWPACC_PLUGIN_URL . 'js/genwpacc-skiplinks.js' );

	if ( ( 	genesis_get_option( 'genwpacc_skiplinks_css', 'genwpacc-settings' )  == 0 ) ) return;

	wp_register_style( 'genwpacc-skiplinks-css', GENWPACC_PLUGIN_URL. 'css/genwpacc-skiplinks.css' );
	wp_enqueue_style('genwpacc-skiplinks-css');
}
