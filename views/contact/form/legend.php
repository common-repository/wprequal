<?php
/**
 * Legend
 *
 * Legend
 *
 * @since   7.6
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<legend>
	<?php printf( '%s%s', $label, required_span( $required ) ); ?>
</legend>

