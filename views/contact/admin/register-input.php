<?php
/**
 * Register Inputs
 *
 * Register input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

wp_nonce_field( 'save_register_inputs', 'save_register_inputs_nonce' );

view( RegisterFormAdmin::view_path, 'register-email', [ 'input' => $input ] ); ?>

<input type="hidden" name="input[<?php esc_attr_e( $input['key'] ); ?>][key]" value="<?php esc_attr_e( $input['key'] ); ?>">
<input type="hidden" name="input[<?php esc_attr_e( $input['key'] ); ?>][type]" value="<?php esc_attr_e( $input['type'] ); ?>">
