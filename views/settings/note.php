<?php
/**
 * Admin Note
 *
 * Admin settings note.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
}

if ( ! empty( $note ) ) {
	printf( '<td>%s</td>', $note );
}