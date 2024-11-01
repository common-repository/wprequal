<?php
/**
 * Auto Update
 *
 * Auto update this plugin
 *
 * @since   6.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

/**
 * Auto Update Plugin
 *
 * @param $update
 * @param $item
 *
 * @return bool
 */

add_filter( 'auto_update_plugin', 'WPrequal\auto_update_plugin', 10, 2 );

function auto_update_plugin( $update, $item ) {

	if ( isset( $item->slug ) && 'wprequal' === $item->slug ) {

		if ( Settings::is_checked( 'wprequal_should_auto_update' ) ) {
			return TRUE;
		}

	}

	return $update;

}
