<?php
/**
 * Popup Button Form
 *
 * Form for prequal widget.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<p><label for="button-text"><?php _e( 'Button Text', 'wprequal' ); ?></label>
<p><input type="text" class="widefat" value="<?php esc_attr_e( $text['value'] ); ?>" id="button-text" name="<?php esc_attr_e( $text['name'] ); ?>"></p>

<p><label for="size"><?php _e( 'Survey Form', 'wprequal' ); ?></label>
<p><?php wp_dropdown_pages( $args ); ?></p>

<p><label for="button-align"><?php _e( 'Align Button', 'wprequal' ); ?></label>
<p>
	<select class="widefat" value="<?php esc_attr_e( $align['value'] ); ?>" id="button-align" name="<?php esc_attr_e( $align['name'] ); ?>">

		<?php foreach ( $align['options'] as $key => $label ) { ?>
			<?php $selected = $key === $align['value'] ? 'selected' : ''; ?>
			<option value="<?php esc_attr_e( $key ); ?>" <?php esc_attr_e( $selected ); ?>><?php esc_html_e( $label ); ?></option>
		<?php } ?>
	</select>
</p>