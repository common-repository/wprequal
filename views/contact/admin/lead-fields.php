<?php
/**
 * Lead Fields
 *
 * Lead field buttons
 *
 * @since   7.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="lead-field slide-options">

	<?php foreach( $inputs as $id => $label ) { ?>

		<?php if( ! in_array( $id, $contact_fields ) ) { ?>
			<button class="option" id="<?php esc_attr_e( $id ); ?>"><?php esc_html_e( $label ); ?></button>
		<?php } ?>

	<?php } ?>

</div>