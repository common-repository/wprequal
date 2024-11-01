<?php
/**
 * Inputs
 *
 * Contact form inputs
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

wp_nonce_field( 'save_contact_inputs', 'save_contact_inputs_nonce' ); ?>

<ul class="contact-inputs">

	<?php foreach ( $inputs as $input ) { ?>
		<?php ContactFormAdmin::view( $input['type'], $input ); ?>
	<?php } ?>

</ul>
