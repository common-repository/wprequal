<?php
/**
 * Hide Metaboxes
 *
 * Hide other plugin meta boxes on WPrequal post types.
 *
 * @since   1.0.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'Access denied!!', 'wprequal' ) );
}

add_filter( 'hidden_meta_boxes', function( $hidden, $screen, $use_defaults ) {

	global $wp_meta_boxes;

	$cpts    = array( 'wpq_survey_form', 'wprequal_lead', 'wpq_contact_form', 'wpq_register_form' );
	$allowed = array(
		'submitdiv',
		'lead_sourcediv',
		'social_referrerdiv',
		'param_referrerdiv',
		'note_meta',
		'contact',
		'fields',
		'shortcode',
		'slide-options',
		'slides',
		'settings',
		'details',
		'inputs',
		'lead-fields',
		'contact-fields',
		'register-shortcode',
		'register-input'
	);

	foreach ( $cpts as $cpt ) {

		if ( $cpt === $screen->id && isset( $wp_meta_boxes[ $cpt ] ) ) {

			$tmp = array();

			foreach ( (array) $wp_meta_boxes[ $cpt ] as $context_key => $context_item ) {

				foreach ( $context_item as $priority_key => $priority_item ) {

					foreach ( $priority_item as $metabox_key => $metabox_item ) {

						if ( ! in_array( $metabox_key, $allowed ) ) {
							$tmp[] = $metabox_key;
						}

					}

				}
			}

			$hidden = $tmp;

		}

	}

	return $hidden;

}, 10, 3 );
