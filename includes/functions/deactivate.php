<?php
/**
 * Deactivate
 *
 * Run the plugin deactivation functions.
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
 * Deactivate the plugin.
 */

function deactivate() {
	clear_options();
	clear_transients();
}

/**
 * Clear plugin options.
 */

function clear_options() {

	$options = array_merge( deactivate_options(), deprecated_options() );

	foreach ( $options as $option ) {
		delete_option( $option );
	}

}

/**
 * Options to delete on plugin deactivation.
 *
 * @return array
 */

function deactivate_options() {

	return [];

}

/**
 * Options that are deprecated and should be removed from database.
 *
 * @return array
 */

function deprecated_options() {

	return [
		'wprequal_dark',
		'wprequal_admin_settings',
		'wprequal_lead_fields',
		'wprequal_form_endpoint',
		'wprequal_token_code',
		'wprequal_api_status',
		'wprequal_pro_dark',
		'wprequal_postal_codes_canada',
		'wprequal_form',
		'wprequal_font_color',
		'wprequal_icon_hover_color',
		'wprequal_icon_color',
		'wprequal_zipcode_mask',
		'wprequal_theme',
		'wprequal_form_loan_amount_min',
		'wprequal_confirmation',
		'wprequal_redirect_query_arg_fname',
		'wprequal_redirect_query_arg_lname',
		'wprequal_redirect_query_arg_email',
		'wprequal_redirect_query_arg_phone',
		'wprequal_form_loan_amount_min',
		'wprequal_form_loan_amount_max',
		'wprequal_form_loan_amount_step',
		'wprequal_form_loan_amount_default',
		'wprequal_form_down_payment_min',
		'wprequal_form_down_payment_max',
		'wprequal_form_down_payment_step',
		'wprequal_form_down_payment_default',
		'wprequal_fontawesome_loaded',
		// Deprecated v7.4
		'wprequal_email_mask',
		'wprequal_phone_mask',
		// Deprecated v7.5
		'wprequal_settings',
		// Deprecated v7.7.9
		'wprequal_options'
	];

}

/**
 * Clear plugin transients.
 */

function clear_transients() {

	$transients = [ Settings::status_key ];

	foreach ( $transients as $transient ) {
		delete_transient( $transient );
	}

}
