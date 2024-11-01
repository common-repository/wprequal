<?php
/**
 * Upgrade Meta Box
 *
 * Upgrade metatbox for post types
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="upgrade-meta-box" id="upgrade-meta-box">

	<h2 class="message" style="color:#9e0000;">
		<?php _e( 'Upgrade Required to edit survey forms.', 'wprequal' ); ?>
	</h2>

	<div style="padding: 20px 10px;">
		<?php view( 'buttons', 'go-premium' ); ?>
	</div>

</div>