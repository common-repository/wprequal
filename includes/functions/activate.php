<?php
/**
 * Activate
 *
 * Run the plugin activation functions.
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
 * Activate the plugin settings.
 */

function activate() {
	check_php_version();
	set_options();
}

/**
 * Check PHP version
 */

function check_php_version() {

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$version = '5.6';

	// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
	if ( version_compare( PHP_VERSION, $version, '<' ) ) {
		deactivate_plugins( WPREQUAL_PLUGIN );
		// Use array() for older versions of PHP.
		wp_die( __( "This plugin requires PHP Version $version or greater.  Sorry about that.", 'wprequal' ), 'Plugin Activation Error', array( 'back_link' => TRUE ) );
	}

}

/**
 * Set the plugin default settings during activation.
 */

function set_options() {

	add_option( 'wprequal_activation_redirect', 'redirect', '', 'no' );

	$options = options();

	foreach ( $options as $option => $value ) {
		if ( ! get_option( $option ) ) {
			update_option( $option, $value );
		}
	}

}

/**
 * Plugin setting defaults.
 *
 * @return array
 */

function options() {

	return [
		'wprequal_between'                   => 4,
		'wprequal_delay'                     => 5,
		'wprequal_currency'                  => 'dollar',
		'wprequal_from_email'                => 'support@wprequal.com',
        'wprequal_default_to_email'          => Core::default_to_email(),
		'wprequal_should_auto_update'        => 'checked',
		// Calc labels
		'wprequal_calc_title'                => 'Mortgage Calculator',
		'wprequal_price_label'               => 'Purchase Price:',
		'wprequal_down_payment_label'        => 'Down Payment:',
		'wprequal_term_label'                => 'Loan Term:	',
		'wprequal_term_suffix'               => 'years',
		'wprequal_rate_label'                => 'Interest Rate:',
		'wprequal_tax_label'                 => 'Property Tax:',
		'wprequal_insurance_label'           => 'Insurance:',
		// Calc values
		'wprequal_price_min'                 => 10000,
		'wprequal_price_max'                 => 2000000,
		'wprequal_price_step'                => 5000,
		'wprequal_price_default'             => 250000,
		'wprequal_down_payment_min'          => 0,
		'wprequal_down_payment_max'          => 200000,
		'wprequal_down_payment_step'         => 500,
		'wprequal_down_payment_default'      => 1000,
		'wprequal_term_type'                 => 'years',
		'wprequal_term_min'                  => 5,
		'wprequal_term_max'                  => 40,
		'wprequal_term_step'                 => 5,
		'wprequal_term_default'              => 30,
		'wprequal_rate_min'                  => .1,
		'wprequal_rate_max'                  => 10,
		'wprequal_rate_step'                 => .05,
		'wprequal_rate_default'              => 4.5,
		'wprequal_tax_min'                   => 0,
		'wprequal_tax_max'                   => 30000,
		'wprequal_tax_step'                  => 500,
		'wprequal_tax_default'               => 2500,
		'wprequal_insurance_min'             => 0,
		'wprequal_insurance_max'             => 30000,
		'wprequal_insurance_step'            => 100,
		'wprequal_insurance_default'         => 1200,

		// Amortize
		'wprequal_amortize_title'            => 'Loan Amortization',
		'wprequal_amortize_label'            => 'Amortize',
		'wprequal_amortize_principal_color'   => '#3cba9f',
		'wprequal_amortize_principal_border'  => '#379a7f',
		'wprequal_amortize_principal_label'   => 'Principal',
		'wprequal_amortize_interest_color'   => '#3e95cd',
		'wprequal_amortize_interest_border'  => '#366e9e',
		'wprequal_amortize_interest_label'   => 'Interest',

		// Get Quote
		'wprequal_get_quote_button_text'     => 'Get Quote',
		'wprequal_get_quote_cta'             => 'Request a quote based on the values entered above.',
		'wprequal_get_quote_confirmation'    => 'We will contact you about your quote ASAP.',

		// Referrers
		'wprequal_url_referrer_cookie'    => 30,
		'wprequal_social_referrer_cookie' => 30,

		// Extensions
		'wprequal_mortgage_extension'     => 'checked',
		'wprequal_real_estate_extension'  => 'checked'
	];

}
