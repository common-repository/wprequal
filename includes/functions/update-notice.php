<?php
/**
 * Update Notice
 *
 * Display update notices
 *
 * @since   6.3
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

add_action( 'in_plugin_update_message-wprequal/wprequal.php', 'WPrequal\update_message', 10, 2 );

/**
 * @param $data
 * @param $response
 */

function update_message( $data, $response ) {

	if( isset( $data['upgrade_notice'] ) ) {

		printf(
			'<div class="update-message">%s</div>',
			wpautop( $data['upgrade_notice'] )
		);

	}

}
