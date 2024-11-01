<?php
/**
 * Nonce
 *
 * Handle nonce requests.
 *
 * @since   1.0.0
 *
 * @package WPrequal
 */

namespace WPrequal;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Nonce {

	/**
	 * @var Nonce
	 */

	public static $instance;

	/**
	 * Nonce constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return Nonce
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register route.
	 */

	public function register_route() {

		register_rest_route(
			'wprequal',
			'/' . WPREQUAL_VERSION . '/nonce/',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'nonce' ],
				'permission_callback' => function() {
					return TRUE;
				}
			]
		);

	}

	/**
	 * Get nonce.
	 *
	 * @return bool|string
	 */

	public function nonce() {
		return wp_create_nonce( 'wprequal_secure_me' );
	}

}
