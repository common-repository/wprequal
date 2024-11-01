<?php
/**
 * Survey Form
 *
 * Survey form template
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<script type="text/javascript">
	var form = <?php echo json_encode( $form ); ?>;
	surveyForm.forms.push(form);
</script>

<style>
	<?php esc_attr_e( $styles ); ?>
</style>

<form id="<?php esc_attr_e( $form['form_id'] ); ?>" class="<?php esc_attr_e( $classes ); ?>" index="0" data-back-text="<?php esc_attr_e( $back_text ); ?>">

	<input type="hidden" name="survey_id" value="<?php esc_attr_e( $post_id ); ?>">
	<?php do_action( 'wprequal_prequal_hidden_inputs' ); ?>

</form>
