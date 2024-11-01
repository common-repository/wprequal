<?php
/**
 * Core Class
 *
 * @since 1.0
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Core {

	use Api;

	/**
	 * @var Core
	 */

	public static $instance;

	/**
	 * Core constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * @return Core
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Action hooks.
	 */

	public function actions() {

		add_action( 'init', array( $this, 'init' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 998 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'update_option_wprequal_allow_logging', array( $this, 'delete_error_log' ), 10, 2 );

		add_filter( 'body_class', array( $this, 'body_class' ), PHP_INT_MAX );

	}

	/**
	 * Init Core.
	 */

	public function init() {

		// Required classes
		Settings::instance()->actions();

		// Post types
		SurveyForm::instance()->actions();
		ContactForm::instance()->actions();
		RegisterForm::instance()->actions();
		Lead::instance()->actions();

		// Let's check the version and do any updates now.
		if ( $current_version = get_option( 'WPREQUAL_VERSION', '1.0' ) ) {

			if ( self::access_token() && WPREQUAL_VERSION !== $current_version ) {
				Update::instance( $current_version )->run();
			}

		}

		if ( is_admin() ) {

			// Admin classes
			SurveyFormAdmin::instance()->actions();
			ContactFormAdmin::instance()->actions();
			RegisterFormAdmin::instance()->actions();

		} else {

			// Calc
			Calc::instance()->actions();

			// Referrer
			Referrer::instance()->param_cookie();
			Referrer::instance()->social_cookie();

		}

	}

	/**
	 * Api status.
	 *
	 * @param int $min
	 *
	 * @return bool
	 */

	public static function status( $min = 0 ) {

		$status = Settings::instance()->get_status();

		return $status >= $min;

	}
	
	/**
	 * Enqueue Styles.
	 *
	 * Enqueue styles.
	 *
	 * @since 3.0.7
	 */
	 
	public function enqueue_styles() {
		wp_enqueue_style( 'wprequal_app_min', WPREQUAL_ASSETS . 'css/app.min.css', array(), WPREQUAL_VERSION );
	}

	/**
	 * Enqueue Scripts.
	 *
	 * Enqueue scriptsa. Localize scripts for WPrequal widget ajax.
	 *
	 * @since 1.0
	 * @since 3.0.7 Register scripts only.
	 */
	 
	public function enqueue_scripts() {

		wp_enqueue_script( 'wprequal_js', WPREQUAL_ASSETS . 'js/app.min.js', array( 'jquery' ), WPREQUAL_VERSION );

		wp_localize_script( 'wprequal_js', 'wprequal', array(
			'endpoint'       => $this->endpoint( 'entry' ),
			'nonce_endpoint' => $this->endpoint( 'nonce' ),
			'processing'     => 'Sending'
		));

	}

	/**
	 * Register admin scripts.
	 */

	public function admin_scripts() {

		wp_register_script( 'wprequal_admin_js', WPREQUAL_ASSETS . 'js/admin.min.js',
			array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ), WPREQUAL_VERSION );

	}

	/**
	 * Body Class
	 *
	 * @param array $classes
	 *
	 * @return array
     * @since 5.5
     *        8.2.6 Add EP version class
     */

	public function body_class( $classes ) {

		$classes[] = 'wprequal-pro';
		$classes[] = 'wprequal-' . WPREQUAL_VERSION;
        $classes[] = 'wprequal-ep-' . WPREQUAL_API_EP_VERSION;

		if ( self::status( 1 ) )  {
			$classes[] = 'wprequal-has-status';
		}

		return apply_filters( 'wprequal_body_class', $classes );

	}

	/**
	 * Endpoint.
	 *
	 * @param $param
	 *
	 * @return string
	 */

	public function endpoint( $param ) {

		return trailingslashit( join( '/', [
			home_url(),
			'wp-json',
			'wprequal',
			WPREQUAL_VERSION,
		    $param
		] ) );

	}

	/**
	 * Delete Error Logs
	 *
	 * @since 6.2.11
	 *
	 * @param $old_value
	 * @param $value
	 */

	public function delete_error_log( $old_value, $value ) {

		if ( 'checked' !== $value ) {

			foreach ( glob( WPREQUAL_ERROR_LOG_DIR . '*.log' ) as $file ) {
				unlink( $file );
			}

		}

	}

	/**
	 * Get access token.
	 *
	 * return string
	 */

	public static function get_access_token() {
		return ( new self )->fetch_access_token();
	}

	/**
	 * Fetch access token.
	 */

	public function fetch_access_token() {

		$query = $this->activate_query();

		$this->set_query( $query );
		$this->set_endpoint( 'activate/' );

		if ( $new_token = $this->get() ) {

			$access_token = sanitize_text_field( $new_token );

			if( ! empty( $access_token ) ) {
				update_option( 'wprequal_access_token', $access_token );
			}

		}

	}

	/**
	 * Access Token
	 *
	 * @since 6.0
	 *
	 * @return mixed|void|string
	 */

	public static function access_token() {
		return get_option( 'wprequal_access_token' );
	}

	/**
	 * Activate Query
	 *
	 * A query string for name and email supplied on the plugin get free access token form.
	 *
	 * @since 6.0.4
	 *
	 * @return string
	 */

	public function activate_query() {

		if ( isset( $_POST['name'] ) || isset( $_POST['email'] ) ) {

			$query = '?' . http_build_query( array_filter(
					array(
						'name'  => sanitize_text_field( $_POST['name'] ),
						'email' => sanitize_email( $_POST['email'] )
					)
				) );

		}

		return empty( $query ) ? '' : $query;

	}

	/**
	 * Sanitize array keys and values recursively.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */

	public static function sanitize_array( $data ) {

		if ( ! is_array( $data ) ) {
			Log::write( 'sanitize-not-array', $data );
		}

		foreach ( $data as $key => $value ) {

			// Insure the key a string or int.
			if ( ! is_string( $key ) && ! is_int( $key ) ) {
				Log::write( 'sanitize-key-failed', $data );
				// The key is not a string. Let's destroy this $data.
				unset( $data );
				return array();
			}

			// Do not allow any objects.
			if ( is_object( $key ) || is_object( $value ) ) {
				unset( $data[$key] );
			}

			$key = is_int( $key ) ? intval( $key ) : sanitize_text_field( $key );

			if ( is_array( $value ) ) {

				$data[$key] = self::sanitize_array( $value );

			} else {

				$data[$key] = self::sanitize( $value );
			}

		}

		Log::write( 'sanitized-data', $data );

		return $data;

	}

	/**
	 * Sanitize data.
	 *
	 * @param $value
	 *
	 * @return bool|float|int|string
	 */

	public static function sanitize( $value ) {

		switch ( $value ) {

			case empty( $value ):
				$clean_value = '';
				break;

			case is_bool( $value ):
				$clean_value = boolval( $value );
				break;

			case is_float( $value ):
				$clean_value = floatval( $value );
				break;

			case is_int( $value ):
				$clean_value = intval( $value );
				break;

			case is_numeric( $value ):
				$value = $value + 0;
				$clean_value = sanitize_text_field( $value );
				break;

			case is_email( $value ):
				$value       = trim( $value );
				$clean_value = sanitize_email( $value );
				break;

			case ( FALSE !== strpos( $value, '<' ) ):
				// If we have some html from an editor, let's use allowed post html.
				// All scripts, videos, etc... will be removed.
				$clean_value = wp_kses_post( $value );
				break;

			default:
				$value       = trim( $value );
				$clean_value = sanitize_text_field( $value );

		}

		return $clean_value;

	}

	/**
	 * Cookie Name.
	 *
	 * @return string
	 */

	public static function cookie_name() {
		return 'wprequal';
	}

	/**
	 * Cookie.
	 *
	 * @param $expires
	 * @param $value
	 *
	 * @return string
	 */

	public static function cookie( $expires, $value ) {

		return join( ';', array(
			self::cookie_name() . "={$value}",
			'expires=' . self::cookie_date( $expires ),
			'path=/'
		) );

	}

	/**
	 * Formatted cookie date.
	 *
	 * @param $expires
	 *
	 * @return false|string
	 */

	public static function cookie_date( $expires ) {
		$expires = is_int( $expires ) ? $expires : intval( $expires );
		return date( 'D, d M Y H:i:s e', $expires );
	}

	/**
	 * Currency symbols.
	 *
	 * @return array
	 */

	public static function currency_symbols() {

		return array(
			'dollar'       => '&dollar;',
			'percent'      => '&percnt;',
			'euro'         => '&euro;',
			'pound'        => '&pound;',
			'cent'         => '&cent;',
			'franc'        => '&#8355;',
			'lira'         => '&#8356;',
			'peseta'       => '&#8359;',
			'rubee'        => '&#x20B9;',
			'won'          => '&#8361;',
			'hryvnia'      => '&#8372;',
			'drachma'      => '&#8367;',
			'tugrik'       => '&#8366;',
			'german-penny' => '&#8368;',
			'guarani'      => '&#8370;',
			'peso'         => '&#8369;',
			'austral'      => '&#8371;',
			'cedi'         => '&#8373;',
			'kip'          => '&#8365;',
			'new-sheqel'   => '&#8362;',
			'dong'         => '&#8363;',
			'generic'      => '&curren;',
			'naira'        => '&#8358;'
		);

	}

	/**
	 * Extend the plugin.
	 *
	 * @param $extends
	 *
	 * @return bool
	 */

	public static function extend( $extends ) {

		if ( ! $extends ) {
			return TRUE;
		}

		if ( is_array( $extends ) ) {

			foreach ( $extends as $extend ) {

				if ( Settings::is_checked( "wprequal_{$extend}_extension" ) ) {
					return TRUE;
				}

			}

		}

		return FALSE;

	}

    /**
     * Default To Email
     *
     * @since 8.1.1
     *
     * @return string
     */

    public static function default_to_email() {

        if ( $email = get_option( 'wprequal_default_to_email' ) ) {

            if ( ! empty( $email ) && is_email( $email ) ) {
                return $email;
            }

        }

        return get_option( 'admin_email' );
    }

}
