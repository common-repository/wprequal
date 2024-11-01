<?php
/**
 * Confirmation
 *
 * Confirmation slide for survey form.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

$content = apply_filters( 'wprequal_confirmation_content', '{editor}' ); ?>

<div class="wpq-col-12">
	<div class="editor"><?php printf( '%s', wp_kses_post( $content ) ); ?></div>
</div>
