<?php
/**
 * Payment
 *
 * Calc payment.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<!-- Start Payment -->
<div class="wpq-col-12">
	<?php view( 'calc', 'payment-inner'); ?>
</div>
<!-- End Payment -->