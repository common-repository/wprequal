<?php
/**
 * Form Email
 *
 * Form email input
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$class = isset( $class ) ? $class : ''; ?>

<div class="wpq-col <?php esc_attr_e( $class ); ?>">

	<?php view( 'contact/form', 'label', $args ); ?>
	<?php view( 'contact/form', 'email-input', $args ); ?>

</div>
