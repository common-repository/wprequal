<?php
/**
 * Contact Form
 *
 * Contact form template
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<form class="wprequal-form contact-form">

	<?php view( 'contact/form', 'contact-inputs', [
		'inputs'          => $inputs,
		'details'         => $details,
		'type'            => $type,
		'contact_form_id' => $contact_form_id
	] ); ?>

</form>