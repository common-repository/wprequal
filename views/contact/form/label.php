<?php
/**
 * Label
 *
 * Label
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="label">
	<?php printf( '%s%s', $label, required_span( $required ) ); ?>
</div>

