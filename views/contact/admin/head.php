<?php
/**
 * Start Input
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="slide-head">
	<span><i class="fas fa-grip-vertical point"></i></span>
	<span><i class="fas fa-caret-down caret point"></i></span>
	<span class="label"><?php _e( $input['label'], 'wprequal' ); ?></span>

	<span class="right">
		<span class="type-label light-gray"><?php _e( $input['type_label'], 'wprequal' ); ?></span>
		<span><i class="far fa-trash-can delete-slide point red" data-key="<?php esc_attr_e( $input['key'] ); ?>"></i></span>
	</span>

	<input type="hidden" name="input[<?php esc_attr_e( $input['key'] ); ?>][key]" value="<?php esc_attr_e( $input['key'] ); ?>">
	<input type="hidden" name="input[<?php esc_attr_e( $input['key'] ); ?>][type]" value="<?php esc_attr_e( $input['type'] ); ?>">
	<input type="hidden" name="input[<?php esc_attr_e( $input['key'] ); ?>][label]" value="<?php esc_attr_e( $input['label'] ); ?>" class="hidden-label">
	<input type="hidden" name="input[<?php esc_attr_e( $input['key'] ); ?>][type_label]" value="<?php esc_attr_e( $input['type_label'] ); ?>" class="hidden-type-label">
</div>

