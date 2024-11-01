<?php
/**
 * Styles
 *
 * Styles meta Box.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

// We add this here since this is a free feature and slides are premium.
wp_nonce_field( 'save_slides', 'save_slides_nonce' );

$post_id   = get_the_ID();
$styles    = get_post_meta( $post_id, 'styles', TRUE ) ?: array();
$back_text = get_post_meta( $post_id, 'back_text', TRUE ) ?: ''; ?>

<table class="form-table">

	<tbody class="shortcode">

		<tr class="theme">

			<th scope="row">
				<label for="wpq-shortcode"><?php _e( 'Shortcode', 'wprequal' ); ?></label>
			</th>

			<td>
				<code><?php _e( "[wprequal post_id={$post_id}]", 'wprequal' ); ?></code>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="back_text"><b><?php _e( 'Back Link Text', 'wprequal' ); ?></b></label>
			</th>

			<td>
				<input type="text" name="back_text" id="back_text" value="<?php esc_attr_e( $back_text ); ?>">
			</td>

			<td class="message">
                <?php _e( 'Leave blank to hide back link.', 'wprequal' ); ?>
			</td>
		</tr>

	</tbody>

</table>

<table class="form-table">
	<thead>

		<tr>
			<th colspan="3"><?php esc_html_e( 'Appearance', 'wprequal' ); ?></th>
		</tr>

	</thead>

	<tbody>
		<tr class="theme">

			<th scope="row">
				<label for="wpq-theme"><?php _e( 'Theme', 'wprequal' ); ?></label>
			</th>

			<td>

				<select name="styles[theme]" id="wpq-theme" class="theme">

					<?php $options = array(
						'default'       => __( 'Default', 'wprequal' ),
						'default-dark'  => __( 'Default Dark', 'wprequal' ),
						'authentic'     => __( 'Authentic', 'wprequal' ),
						'circumference' => __( 'Circumference', 'wprequal' ),
						'disk'          => __( 'Disk', 'wprequal' ),
						'impress'       => __( 'Impress', 'wprequal' ),
						'passage'       => __( 'Passage', 'wprequal' ),
						'perimeter'     => __( 'Perimeter', 'wprequal' ),
						'precise'       => __( 'Precise', 'wprequal' ),
						'realistic'     => __( 'Realistic', 'wprequal' ),
						'sphere'        => __( 'Sphere', 'wprequal' )
					);

					foreach ( $options as $key => $value ) {

						$selected = isset( $styles['theme'] ) && ( $key === $styles['theme'] ) ? 'selected' : ''; ?>

						<option value="<?php esc_attr_e( $key ); ?>" <?php esc_attr_e( $selected ); ?>>
							<?php esc_html_e( $value ); ?>
						</option>

					<?php } ?>

				</select>

			</td>

		</tr>

		<tr class="wpq-icon-color">

			<th scope="row">
				<label for="wpq-icon-color-picker"><?php _e( 'Icon Color', 'wprequal' ); ?></label>
			</th>

			<td>
				<input
					type="text"
					name="styles[icon_color]"
					id="wpq-icon-color-picker"
					class="wpq-icon-color-picker"
					<?php $icon_color = isset( $styles['icon_color'] ) ? $styles['icon_color'] : ''; ?>
					value="<?php esc_attr_e( $icon_color ); ?>"
				>
			</td>

		</tr>

		<tr class="wpq-icon-hover">

			<th scope="row">
				<label for="wpq-icon-hover-picker"><?php _e( 'Icon Hover Color', 'wprequal' ); ?></label>
			</th>

			<td>
				<input
					type="text"
					name="styles[icon_hover]"
					id="wpq-icon-hover-picker"
					class="wpq-icon-hover-picker"
					<?php $icon_hover = isset( $styles['icon_hover'] ) ? $styles['icon_hover'] : ''; ?>
					value="<?php esc_attr_e( $icon_hover ); ?>"
				>
			</td>

		</tr>

		<tr class="wpq-button-color">

			<th scope="row">
				<label for="wpq-button-color-picker"><?php _e( 'Button Color', 'wprequal' ); ?></label>
			</th>

			<td>
				<input
					type="text"
					name="styles[button_color]"
					id="wpq-button-color-picker"
					class="wpq-button-color-picker"
					<?php $button_color = isset( $styles['button_color'] ) ? $styles['button_color'] : ''; ?>
					value="<?php esc_attr_e( $button_color ); ?>"
				>
			</td>

		</tr>

		<tr class="wpq-button-hover">

			<th scope="row">
				<label for="wpq-button-hover-picker"><?php _e( 'Button Hover Color', 'wprequal' ); ?></label>
			</th>

			<td>
				<input
					type="text"
					name="styles[button_hover]"
					id="wpq-button-hover-picker"
					class="wpq-button-hover-picker"
					<?php $button_hover = isset( $styles['button_hover'] ) ? $styles['button_hover'] : ''; ?>
					value="<?php esc_attr_e( $button_hover ); ?>"
				>
			</td>

		</tr>

		<tr class="wpq-button-text-color">

			<th scope="row">
				<label for="wpq-button-text-color-picker"><?php _e( 'Button Text Color', 'wprequal' ); ?></label>
			</th>

			<td>
				<input
					type="text"
					name="styles[button_text_color]"
					id="wpq-button-text-color-picker"
					class="wpq-button-text-color-picker"
					<?php $button_text_color = isset( $styles['button_text_color'] ) ? $styles['button_text_color'] : ''; ?>					value="<?php esc_attr_e( $button_text_color ); ?>"
				>
			</td>

		</tr>

	</tbody>
</table>

<table class="form-table" >
	<thead>

		<tr>
			<th colspan="3"><?php esc_html_e( 'Template Loader', 'wprequal' ); ?></th>
		</tr>

	</thead>

	<tbody>
		<tr>
			<th><?php esc_html_e( 'Template', 'wprequal' ); ?></th>

			<td>
                <?php wp_nonce_field( 'create_slides', 'create_slides_nonce' ); ?>
				<select name="template" class="widefat">
					<option value=""><?php _e( 'Select One', 'wprequal' ); ?></option>
					<option value="mortgage"><?php _e( 'Mortgage', 'wprequal' ); ?></option>
					<option value="real_estate"><?php _e( 'Real Estate', 'wprequal' ); ?></option>
				</select>
			</td>
		</tr>

		<tr clas="template">
			<th><label><?php _e( ' Create Slides from Template', 'wprequal' ); ?></label></th>
			<td>
				<input type="hidden" value="<?php esc_attr_e( $post_id ); ?>" name="post_id">
				<input type="checkbox" name="create_slides_from_template" value="checked">
			</td>
		</tr>

		<tr>
			<th class="red">
                <?php _e( 'WARNING:', 'wprequal' ); ?>
			</th>

			<td class="red">
                <?php _e( 'This will overwrite all current slides.', 'wprequal' ); ?>
			</td>
		</tr>
	</tbody>

</table>
