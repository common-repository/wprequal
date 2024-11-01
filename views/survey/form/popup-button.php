<?php
/**
 * Popup button.
 *
 * Popup button for for survey form.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wpq-popup-button-wrapper" style="text-align: <?php esc_attr_e( $args['align'] ); ?>">

	<div class="button wpq-popup-button"><?php esc_html_e( $args['text'] ); ?></div>

	<div style="display: none;">
		<div class="wpq-popup-button-form">
			<?php do_action( 'wprequal_survey_form', $post_id ); ?>
		</div>
	</div>

</div>
