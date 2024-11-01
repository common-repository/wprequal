<?php
/**
 * Entry Class
 *
 * @since 1.0
 */

namespace WPrequal;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Entry {

	/**
	 * @var Entry
	 */

	public static $instance;

	/**
	 * Entry constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return Entry
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register Route
	 *
	 * @since 5.5.6
	 */

	public function register_route() {

		register_rest_route(
			'wprequal',
			'/' . WPREQUAL_VERSION . '/entry/',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'process' ),
				'permission_callback' => function() {
					return TRUE;
				}
			)
		);

	}

	/**
	 * Process
	 *
	 * @since 5.5.6
	 *
	 * @param $data
	 *
	 * @return array
	 */

	public function process( $data ) {

		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wprequal_secure_me' ) ) {

			$nonce = sanitize_text_field( $_REQUEST['nonce'] );

			Log::write( 'entry-nonce-failed', "Nonce Failed: $nonce" );

			return array(
				'success' => FALSE,
				'message' => 'We are sorry, an error occurred.',
				'type'    => FALSE
			);

		}

		$lead_id  = FALSE;
		$response = array(
			'success'  => FALSE,
			'message'  => 'We are sorry, we were not able to process your request.',
			'redirect' => FALSE
		);

		if ( $entry = $this->entry() ) {

			$lead_id = Lead::create_lead( $entry );

			$email = Email::instance( $lead_id  );
			$email->send_email();

			if ( Core::status( 2 ) ) {

				if ( Settings::is_checked( 'wprequal_connect_active' ) ) {
					$post_entry = PostEntry::instance();
					$post_entry->post( $lead_id );
				}

			}

			if ( Core::status( 1 ) ) {

				$text = Text::instance( $lead_id  );
				$text->send_text();

				do_action( 'wprequal_after_post_entry', $entry, $lead_id );

			}

			if ( in_array( $entry['type'], array( 'contact', 'register', 'get_quote' ) ) ) {

				$msg = ( 'get_quote' === $entry['type'] ) ? get_option( 'wprequal_get_quote_confirmation' ) : 'Success';

				$response = array(
					'success'    => TRUE,
					'registered' => TRUE,
					'message'    => $msg,
					'cookie'     => $this->get_cookie(),
					'type'       => $entry['type']
				);

			}

			else {

				$response = array(
					'success'    => TRUE,
					'registered' => FALSE,
					'message'    => FALSE,
					'cookie'     => $this->get_cookie(),
					'type'       => FALSE
				);

			}

		}

		return apply_filters( 'wprequal_entry_response', $response, $entry, $lead_id );

	}

	/**
	 * Sanitize the entry array.
	 *
	 * @return array|bool
	 */
	
	public function entry() {

		if ( isset( $_POST['entry'] ) ) {

			parse_str( $_POST['entry'], $data );

			Log::write( 'create-entry', $data );

			$entry = Core::sanitize_array( $data );

			return apply_filters( 'wprequal_entry', $entry );

		}

		return FALSE;

	}

	/**
	 * Get cookie.
	 *
	 * Set a 30 day cookie after a user has registered.
	 *
	 * @return string
	 */

	public function get_cookie() {

		$time 		= time() + ( 30 * 24 * 60 * 60 );
		$expires 	= date( 'D, d M Y G:i:s e', $time );

		return Core::cookie( $expires, 'registered' );

	}

}
