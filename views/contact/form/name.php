<?php
/**
 * Form Name
 *
 * Form name input
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$first = isset( $labels['fname'] ) ? $labels['fname'] : '';
$last  = isset( $labels['lname'] ) ? $labels['lname'] : '';
$class = isset( $class ) ? $class : ''; ?>

<div class="wpq-col <?php esc_attr_e( $class ); ?>">

	<?php view( 'contact/form', 'label', [ 'label' => $first, 'required' => $required ] ); ?>

	<input type="text" name="lead[contact][fname]" class="<?php required_class( $required ); ?> text-input wpq-input"
	       aria-label="First Name"/>
</div>

<div class="wpq-col <?php esc_attr_e( $class ); ?>">

	<?php view( 'contact/form', 'label', [ 'label' => $last, 'required' => $required ] ); ?>

	<input type="text" name="lead[contact][lname]" class="<?php required_class( $required ); ?> text-input wpq-input"
	       aria-label="Last Name"/>
</div>
