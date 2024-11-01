<?php
/**
 * Form
 *
 * Form for contact widget.
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<p>
	<label for="<?php echo esc_attr( $title_id ); ?>">
		<?php esc_attr_e( 'Ttile:', 'wprequal' ); ?>
	</label>

	<input
		class="widefat"
		id="<?php echo esc_attr( $title_id ); ?>"
		name="<?php echo esc_attr( $title_name ); ?>"
		type="text"
		value="<?php echo esc_attr( $title ); ?>"
	>
</p>

<p>
	<label for="<?php echo esc_attr( $content_id ); ?>">
		<?php esc_attr_e( 'Content:', 'wprequal' ); ?>
	</label>

	<textarea
		class="widefat"
		rows="5"
		id="<?php echo esc_attr( $content_id ); ?>"
		name="<?php echo esc_attr( $content_name ); ?>"
	><?php echo esc_attr( $content ); ?></textarea>
</p>

<p><label for="size"><?php _e( 'Contact Form', 'wprequal' ); ?></label>
<p><?php wp_dropdown_pages( $args ); ?></p>