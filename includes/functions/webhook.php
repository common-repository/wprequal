<?php
/**
 * Webhook
 *
 * Post entry to a webhook.
 *
 * @since   7.10.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

add_action( 'wprequal_after_post_entry', function( $entry, $lead_id ) {

	if ( ! Core::status( 2 ) ) {
		return;
	}

	if ( ! Settings::is_checked( 'wprequal_webhook_active' ) ) {
		return;
	}

	if ( $url = get_option( 'wprequal_webhook_url' ) ) {

		if ( $labels  = get_post_meta( $lead_id, 'field_labels', TRUE ) ) {

			$_fields = [];
			$fields  = is_array( $entry['lead']['fields'] ) ? $entry['lead']['fields'] : [];

			foreach ( $fields as $key => $value ) {
				$label             = isset( $labels[ $key ] ) ? preg_replace('/[^A-Za-z0-9\-]+/', '', $labels[ $key ] ) : '';
				$_fields[ $label ] = $value;
			}

			$entry['lead']['fields'] = $_fields;

		}

		$args = [
			'timeout'   => 20,
			'sslverify' => TRUE,
			'body'      => json_encode( apply_filters( 'wprequal_webhook_entry', $entry, $lead_id ) ),
			'headers'   => [
				'Content-Type' => 'application/json'
			]
		];

		$response = wp_remote_post( $url, $args );

		Log::write( 'webhook', [
			'url'      => $url,
			'args'     => $args,
			'response' => $response
		] );

	}

}, 10, 2 );
