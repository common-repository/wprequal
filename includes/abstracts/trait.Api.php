<?php
/**
 * Api Class
 *
 * @since 6.2.14
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

trait Api {

	/**
	 * @var string
	 */

	private $base_url = 'https://api.wprequal.' . WPREQUAL_API_EVN;

	/**
	 * @var string
	 */

	private $query = '';

	/**
	 * @var string
	 */

	private $endpoint;

	/**
	 * @var $response
	 *
	 * @access private
	 * @since 2.0
	 */

	private $response;

	/**
	 * Set endpoint
	 *
	 * @since 8.0.0
	 * @param $endpoint
	 *
	 * @return void
	 */

	public function set_endpoint( $endpoint ) {
		$this->endpoint = $endpoint;
	}

	/**
	 * Set query.
	 *
	 * @since 8.0.0
	 * @param array $query
	 *
	 * @return void
	 */
	public function set_query( $query ) {
		$this->query = $query;
	}

	/**
	 * Get
	 *
	 * Get request.
	 *
	 * @since 1.0
	 *
	 * @return mixed The response from the API.
	 */
	
	public function get() {

		$this->response = wp_remote_get(
			$this->get_url(),
			$this->get_args()
		);

		Log::write( 'get-response', $this->response );

		return $this->get_body();

	}

	/**
	 * Get URL.
	 *
	 * @since 8.0.0
	 * @return string
	 */

	public function get_url() {

		return join( '/', array(
			$this->base_url,
			'wp-json',
			'wprequal-api',
			WPREQUAL_API_EP_VERSION,
			$this->endpoint
		) ) . $this->query;

	}

	/**
	 * Body.
	 *
	 * Get the response body.
	 *
	 * @since 5.2
	 *
	 * @return string|bool $body The response body on success. Bool false on failure.
	 */

	public function get_body() {

		if ( $this->is_success() ) {
			// Decode body as array.
			$body  = json_decode( wp_remote_retrieve_body( $this->response ), TRUE );

			if  ( is_array( $body ) ) {

				// Clean the array data.
				$clean = Core::sanitize_array( $body );

				// Convert the array back to an object.
				return json_decode( json_encode( $clean ) );

			}

			if  ( is_string( $body ) ) {

				// Clean the data.
				$clean = sanitize_text_field( $body );

				return $clean;

			}

			// Int and float should already be clean
			if  ( is_int( $body ) || is_float( $body ) ) {
				return $body;
			}

		}

		return FALSE;

	}

	/**
	 * Get args
	 *
	 * An array of request args and HTTP headers.
	 *
	 * @since 2.0.0
	 * @since 3.0.0 Send admin_email if wprequal_email does not exist.
	 *
	 * @return array $headers An array of HTTP headers
	 */

	public function get_args() {

		return array(
			'timeout'	=> WPREQUAL_REQUEST_TIMEOUT,
			'sslverify' => WPREQUAL_SSL_VERIFY,
			'headers'	=> array(
            	'Accept' 			=> 'application/json',
				'Referer' 			=> home_url(),
				'Authentication'	=> Core::access_token(),
				// Get the stored plugin version in case
	            // we are making a request during a plugin update.
				'Plugin-Version'	=> get_option( 'WPREQUAL_VERSION' ),
	            'Php-Version'	    => PHP_VERSION
        	)
		);

	}

	/**
	 * Success.
	 *
	 * Check response code for success.
	 *
	 * @since 5.2
	 *
	 * @return bool $success Bool true on success, false on failure.
	 */

	public function is_success() {

		$response_code = wp_remote_retrieve_response_code( $this->response );

		if ( 200 === $response_code ) {
			return true;
		}

		return false;

	}

}