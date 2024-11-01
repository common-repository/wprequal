<?php
/**
 * Referrer
 *
 * Capture the HTTP referrer
 *
 * @since   1.0.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Referrer {

	/**
	 * @var bool|string
	 */

	private $social;

	/**
	 * @var bool|string
	 */

	private $param;

	/**
	 * @var Referrer
	 */

	public static $instance;

	/**
	 * Referrer constructor.
	 */

	public function __construct() {

		$this->social = $this->social();
		$this->param  = $this->param();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return Referrer
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Get and log the referrer
	 */

	public function social() {

		$referrer = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( $_SERVER['HTTP_REFERER'] ) : FALSE;

		Log::write( 'http-referrer', $referrer );

		return $referrer;

	}

	/**
	 * Get the value of the referrer param.
	 *
	 * @return bool|string
	 */

	public function param() {

		if ( $url_param = get_option( 'wprequal_url_referrer_param' ) ) {

			if ( ! empty( $url_param ) && isset( $_GET[$url_param] ) ) {

				$param = sanitize_text_field( $_GET[$url_param] );

				return $param;

			}

		}

		return FALSE;

	}

	/**
	 * Get the social media referrer.
	 *
	 * @return bool|string
	 */

	public function social_referrer() {

		$networks = $this->social_networks();

		foreach ( $networks as $key => $value ) {

			if ( FALSE !== strpos( $this->social, $key ) ) {
				return $value;
			}

		}

		return FALSE;

	}

	/**
	 * Social networks.
	 *
	 * @return array
	 */

	public function social_networks() {

		return array(
			'blogger'     => 'Blogger',
			'facebook'    => 'Facebook',
			'google'      => 'Google',
			'instagram'   => 'Instagram',
			'linkedin'    => 'Linkedin',
			'pinterest'   => 'Pinterest',
			'realtor.com' => 'Realtor.com',
			'tumblr'      => 'Tumblr',
			'twitter'     => 'Twitter',
			'vimeo'       => 'Vimeo',
			'wordpress'   => 'WordPress',
			'yelp.com'    => 'Yelp',
			'youtube'     => 'YouTube'
		);

	}

	/**
	 * Set param cookie.
	 */

	public function param_cookie() {

		$cookie_name = 'wpq_param_referrer';

		if ( ! isset( $_COOKIE[$cookie_name] ) && $this->param ) {

			$expires = $this->expires( 'url' );

			setcookie( $cookie_name, $this->param, $expires, '/' );

		}

	}

	/**
	 * Set social cookie.
	 */

	public function social_cookie() {

		$cookie_name = 'wpq_social_referrer';

		if ( ! isset( $_COOKIE[$cookie_name] ) && $this->social ) {

			if ( $cookie_value = $this->social_referrer() ) {

				$expires = $this->expires( 'social' );

				setcookie( $cookie_name, $cookie_value, $expires, '/' );

			}

		}

	}

	/**
	 * Cookie expires.
	 *
	 * @param $type
	 *
	 * @return float|int
	 */

	public function expires( $type ) {

		$expires = get_option( "wprequal_{$type}_referrer_cookie", 30 );
		$expires = intval( $expires );

		return empty( $expires ) ? time() + ( 86400 * 30 ) : time() + ( 86400 * $expires );

	}

}