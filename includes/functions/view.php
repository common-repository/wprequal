<?php
/**
 * View
 *
 * Load view file.
 *
 * @since   1.0.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

/**
 * Include the view.
 *
 * @param $path
 * @param $view
 * @param $args
 */
function view( $path, $view, $args = [] ) {

	$view_path = view_path( $path, $view );

	if ( file_exists( $view_path ) ) {

		extract( $args );

        ob_start();
		include $view_path;
        echo ob_get_clean();

	}

}

/**
 * Get the path to the view.
 *
 * @param $path
 * @param $view
 *
 * @return mixed|void
 */
function view_path( $path, $view ) {

	$view_path = WPREQUAL_VIEWS . "$path/$view.php";

	return apply_filters( 'wprequal_view', $view_path, $view );

}
