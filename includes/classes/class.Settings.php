<?php
/**
 * Settings Class
 *
 * @since 5.5
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

class Settings extends Options {

	use Api;

	/**
	 * API status key.
	 *
	 * @since 7.7.9
	 */

	const status_key = 'wprequal_status';

	/**
	 * @var int|mixed
	 *
	 * @access private
	 * @since 7.7.9
	 */

	private $status = null;

	/**
	 * Main Tab
	 *
	 * The array key for the active tab.
	 *
	 * @access private
	 * @since 6.0
	 */

	private $main_tab = 'dashboard';

    /**
     * @var string
     */

	private $active_tab = 'dashboard';

	/**
	 * @var Settings
	 */

	public static $instance;

	/**
	 * Settings constructor.
	 */

	public function __construct() {

		if ( is_admin() ) {
            $this->set_active_tab();
		}

		$this->set_status();

		self::$instance = $this;

	}

	/**
	 * Instance.
	 *
	 * @return Settings
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Actions
	 *
	 * @since 1.0
	 */

	public function actions() {

		if ( ! is_admin() ) {
			add_action( 'init', array( $this, 'option_filter' ) );
		}

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'startup' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ), 2 );
			add_action( 'update_option_wprequal_access_token', array( $this, 'update_access_token' ), 10, 3 );
			add_action( 'admin_menu', array( $this, 'add_pages' ), 5 );
			add_action( 'admin_init', array( $this, 'redirect' ) );
			add_action( 'wprequal_help_buttons', array( $this, 'help_buttons' ) );
		}

	}

	/**
	 * Set status.
	 *
	 * @since 7.12.0
	 * @return void
	 */

	public function set_status() {
		$this->status = $this->status();
	}

    /**
     * Set Active Tab
     *
     * @return void
     */

    public function set_active_tab() {
        $this->active_tab = $this->get_current_tab();
    }

	/**
	 * Status.
	 *
	 * @since 5.0.0
	 * @since 7.12.0 - Add free version support
	 *
	 * @return int
	 */

	private function status() {

		if ( $this->is_free_version() ) {
			return 0;
		}

		if ( $status = get_transient( self::status_key ) ) {
			return intval( $status );
		}

		$this->set_endpoint( 'status/' );

		if ( $status = $this->get() ) {
			set_transient( self::status_key, $status, 24 * HOUR_IN_SECONDS );

			return intval( $status );
		}

		return 0;

	}

	/**
	 * Get status.
	 *
	 * @since 7.12.0
	 * @return int|mixed|null
	 */

	public function get_status() {
		return $this->status;
	}

	/**
	 * Is free version.
	 *
	 * @since 7.12.0
	 * @return bool
	 */

	public function is_free_version() {

		$access_token = Core::access_token();

		return 'FREE-' === substr( $access_token, 0, 5 );

	}

	/**
	 * Startup
	 *
	 * Listen for startup.
	 *
	 * @since 2.0.0
	 * @since 5.0.12 Redirect to reload settings page.
	 */

	public function startup() {

		if( isset( $_POST[ 'wprequal_start_up' ] ) && check_admin_referer( 'wprequal_start' ) ) {

			Core::get_access_token();
			$this->admin_redirect();

		}

	}

	/**
	 * Add settings page to Settings submenu.
	 *
	 * @since 1.0
	 * @since 2.0.0 Replace submenu page with menu page.
	 * @since 2.1.0 Add sub-menu page for settings.
	 * @since 3.0.0 Do not display leads page if no API key exists.
	 */

	public function add_pages() {

		add_menu_page(
			__( 'WPrequal Settings', 'wprequal' ),
			__( 'WPrequal Settings', 'wprequal' ),
			WPREQUAL_CAP,
			WPREQUAL_OPTIONS,
			array( $this, 'settings_page' ),
			WPREQUAL_ASSETS . 'img/wprequal-icon.svg',
			6
		);

	}

	/**
	 * Register Settings
	 *
	 * Register the settings for WPrequal popups and widgets.
	 *
	 * @since 3.0.0
	 */

	public function register_settings() {

        $options = call_user_func( [ $this, $this->active_tab ] );

		add_filter( "option_page_capability_{$this->active_tab}", function() {
			return WPREQUAL_CAP;
		} );

		foreach ( $options as $key => $values ) {

			if ( apply_filters( 'wprequal_allow_option', $key ) ) {

				extract( $values );

				if ( method_exists( $this, $callback ) ) {

					// Add the key into the args object.
					if ( ! empty( $args ) ) {
						$args['key'] = $key;
					}

					register_setting( $this->active_tab, $key, $args );
					add_settings_field( $key, $label, array(
							$this,
							$callback
						), WPREQUAL_CAP, 'default', $args );

				}

			}

		}

	}

	/**
	 * Settings Page
	 *
	 * The page that displays the WPrequal settings.
	 *
	 * @since 3.0.0
	 */

	public function settings_page() {

		if ( ! current_user_can( WPREQUAL_CAP ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.') );
		}

		wp_enqueue_style( 'wprequal_css' );

		if ( Core::status( 1 ) ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wprequal_admin_js' );
		}

		if ( Core::access_token() ) {

			view( 'settings', 'page', [
				'active_tab' => $this->active_tab,
				'tabs'       => $this->tabs()
			] );

		}
		else {

			view( 'settings', 'startup', [
				'logo' => WPREQUAL_ASSETS . 'img/wprequal-logo.png'
			] );

		}

	}

	/**
	 * Tabs
	 *
	 * Admin tabs for settings page
	 *
	 * @since 6.0
	 */

	public function tabs() {

		ob_start();

		view( 'settings', 'tabs', [
			'tabs'       => $this->admin_tabs(),
			'active_tab' => $this->active_tab,
			'href'       => admin_url( "admin.php?page=" . WPREQUAL_OPTIONS . "&active_tab=" )
		]);

		return ob_get_clean();

	}

	/**
	 * Admin Tabs.
	 *
	 * @return array
	 */

	public function admin_tabs() {

		return array(
			'dashboard' => array(
				'label'   => __( 'Dashboard', 'wprequal' ),
				'status'  => TRUE,
				'extends' => FALSE
			),
            'popup' => array(
				'label'   => __( 'Pop Up', 'wprequal' ),
				'status'  => FALSE,
				'extends' => FALSE
			),
            'calculator' => array(
				'label'   => __( 'Calculator', 'wprequal' ),
				'status'  => FALSE,
				'extends' => array( 'real_estate', 'mortgage' )
			),
            'amortize' => array(
				'label'   => __( 'Amortize', 'wprequal' ),
				'status'  => FALSE,
				'extends' => array( 'real_estate', 'mortgage' )
			),
            'referrer' => array(
				'label'   => __( 'Referral Tracking', 'wprequal' ),
				'status'  => FALSE,
				'extends' => FALSE,
			),
            'fontawesome' => array(
				'label'   => __( 'FontAwesome', 'wprequal' ),
				'status'  => FALSE,
				'extends' => FALSE
			),
            'webhook' => array(
				'label'   => __( 'Webhook', 'wprequal' ),
				'status'  => FALSE,
				'extends' => FALSE
			),
            'extensions' => array(
				'label'   => __( 'Extensions', 'wprequal' ),
				'status'  => FALSE,
				'extends' => FALSE
			)
		);

	}

	/**
	 * Value
	 *
	 * @since 1.0
	 *
	 * @param $setting
	 *
	 * @return mixed|string|void
	 */

	public function value( $setting ) {

		if ( isset( $setting['value'] ) ) {
			return $setting['value'];
		}

		if ( isset( $setting['key'] ) ) {
			return get_option( $setting['key'], '' );
		}

		return '';

	}

	/**
	 * Get select options values.
	 *
	 * @param $values
	 *
	 * @return array
	 */

	public function get_values( $callback ) {

		switch ( $callback ) {

			case 'currency_symbols':
				return Core::currency_symbols();

			default:
				return [];
		}

	}

	/**
	 * Hidden
	 *
	 * @since 1.0
	 *
	 * @param $setting
	 */
	public function hidden( $setting ) {

		view( 'settings', 'hidden', [
			'value'   => $this->value( $setting ),
			'setting' => $setting
		]);

		$this->note( $setting );

	}

	/**
	 * Input
	 *
	 * @since 1.0
	 *
	 * @param $setting
	 */
	public function input( $setting ) {

		view( 'settings', 'input', [
			'value'   => $this->value( $setting ),
			'setting' => $setting
		]);

		$this->note( $setting );

	}

	/**
	 * Number input.
	 *
	 * @since 7.7.9
	 *
	 * @param $setting
	 */

	public function number( $setting ) {

		view( 'settings', 'number', [
			'value'   => $this->value( $setting ),
			'setting' => $setting
		]);

		$this->note( $setting );

	}

	/**
	 * Select
	 *
	 * @since 6.2.6
	 *
	 * @param $setting
	 */
	public function select( $setting ) {

		view( 'settings', 'select', [
			'choice'  => $this->value( $setting ),
			'values'  => is_object( $setting['values'] ) ? $setting['values'] : $this->get_values( $setting['values'] ),
			'setting' => $setting
		]);

		$this->note( $setting );

	}

	/**
	 * Checkbox
	 *
	 * @since 1.0
	 *
	 * @param $setting
	 */

	public function checkbox( $setting ) {

		view( 'settings', 'checkbox', [
			'value'   => $this->value( $setting ),
			'setting' => $setting
		]);

		$this->note( $setting );

	}

	/**
	 * Editor
	 *
	 * @since 5.3.4
	 * @since 5.3.7 Add wpautop to wp_editor.
	 *
	 * @param $setting
	 */

	public function editor( $setting ) {

		$value = $this->value( $setting );

		$settings = array(
			'wpautop'       => FALSE,
			'textarea_name' => "{$setting['key']}",
			'textarea_rows' => 5
		);

		echo '<td colspan="2">';
		wp_editor( $value, $setting['key'], $settings );
		echo '</td>';

		$this->note( $setting );

	}

	/**
	 * Select to choose which form post to use.
	 *
	 * @param $setting
	 */

	public function form_select( $setting ) {

		$value = $this->value( $setting );

		$args = array(
			'post_type' => $setting['post_type'],
			'name'      => $setting['key'],
			'class'     => $setting['class'],
			'id'        => 'post_id',
			'selected'  => $value
		);

		echo '<td>';
		wp_dropdown_pages( $args );
		echo '</td>';

	}

	/**
	 * Note
	 *
	 * @since 1.0
	 *
	 * @param $setting
	 */

	public function note( $setting ) {

		switch ( $setting['note'] ) {

			case 'premium':
				view( 'buttons', 'go-premium' );
				break;
			case 'pro':
				view( 'buttons', 'go-pro' );
				break;
			case 'connect-api':
				view( 'buttons', 'get-connect-api' );
				break;
			case 'pro-fa-icons':
				view( 'buttons', 'pro-fa-icons' );
				break;
			case 'free-fa-icons':
				view( 'buttons', 'free-fa-icons' );
				break;
			default:
				view( 'settings', 'note', [
					'note' => $this->notes( $setting['note'] )
				] );
		}

	}

	/**
	 * Heading is an empty function since the label portion built by WP.
	 *
	 * @param $setting
	 */

	public function heading( $setting ) {}

	/**
	 * Help Buttons
	 *
	 * Display help buttons on admin pages.
	 *
	 * @since 3.0.0
	 * @since 5.0.12 Add action hook for Premium Button
	 */

	public function help_buttons() {
		view( 'buttons', 'help-buttons' );
	}

	/**
	 * Update Access Token
	 *
	 * Delete the admin settings and api status if the token code is updated.
	 *
	 * @since 5.5
	 */

	public function update_access_token( $old_value, $value, $option ) {

		if ( $old_value !== $value && ! empty( $value ) ) {

			$this->set_endpoint( 'register/' );

			// Register paid access token
			$this->get();

			delete_transient( self::status_key );
		}

	}

	/**
	 * Redirect
	 *
	 * Redirect to settings page.
	 *
	 * @since 5.0.9
	 */

	public function redirect() {

		if ( 'redirect' === get_option( 'wprequal_activation_redirect' ) ) {
			update_option( 'wprequal_activation_redirect', 'complete' );

			if ( ! isset( $_GET['activate-multi'] ) ) {
				$this->admin_redirect();
			}
		}
	}

	/**
	 * Admin Redirect
	 *
	 * Redirect to the WPrequal Settings page
	 */
	public function admin_redirect() {

		$url = admin_url( "admin.php?active_tab={$this->main_tab}&page=" . WPREQUAL_OPTIONS );
		wp_redirect( $url );

		exit();

	}

	/**
	 * Get the current tab.
	 *
	 * @return string
	 */

	public function get_current_tab() {
		return isset( $_GET['active_tab'] ) ? $_GET['active_tab'] : $this->main_tab;
	}

	/**
	 * Option Filter
	 *
	 * We filter the options to keep things from breaking
	 * if the site does not have the correct status.
	 */

	public function option_filter() {

		$options = $this->options();

        foreach ( $options as $key => $value ) {

            if ( $value['filter'] ) {
                add_filter( "option_{$key}", array( $this, 'option_value' ), 10, 2 );
            }

		}

	}

	/**
	 * Filter the option value based on status.
	 *
	 * @param $value
	 * @param $option
	 *
	 * @return mixed
	 */

	public function option_value( $value, $option ) {

		$options = $this->options();

        if ( array_key_exists( $option, $options ) ) {

            if ( ! Core::status( $options[ $option ]['status'] ) ) {
                return FALSE;
            }

		}

		return $value;

	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */

	public function notes( $id ) {

		$notes = array(
			1000 => __( 'Push leads to Top CRMs via the <a href="https://wprequal.com/my-account/connect_api/" target="_blank">Connect API</a>' ),
			2000 => __( 'What is a <a href="https://wprequal.com/how-to-set-the-url-referrer-param/" target="_blank">URL Referrer Param</a>?' ),
			3000 => __( 'Days until cookie expires' ),
			4000 => __( 'The background color for the full page calculator.' ),
			5000 => __( 'Show the Get Quote button.' ),
			6000 => __( 'Numbers only - Example: 500000' ),
			7000 => __( 'Numbers or Decimals only - Example: 15 or 15.45' ),
			8000 => __( 'We use this information to help debug issues with this plugin. We recommend keeping this deactivated when not required.' ),
			9000 => __( 'This forces the user to complete the form.' ),
			10000 => __( 'The number of minutes to wait before the form pops up again.' ),
			11000 => __( 'The number of seconds to wait after the page loads for the form to pop-up.' ),
			12000 => __( 'Automatically update this plugin.' ),
            14000 => __( 'Email address to send notifications if no other email address is available.', 'wprequal' )
		);

		return isset( $notes[$id] ) ? $notes[$id] : FALSE;

	}

	/**
	 * Is Checked.
	 *
	 * @param $key
	 *
	 * @return bool
	 */

	public static function is_checked( $key ) {
		return 'checked' === get_option( $key );
	}

}
