<?php
/**
 * Admin Select
 *
 * Admin settings select.
 *
 * @since   6.4
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<td>

	<select
		name="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
		id="<?php printf( esc_attr__( '%s' ), $setting['key'] ); ?>"
		class="<?php printf( esc_attr__( '%s' ), $setting['class'] ); ?>"
	>

		<?php foreach( $values as $key => $value ) {

			$selected = $choice === $key ? 'selected' : '';

			echo "<option value='{$key}' {$selected}>{$value}</option>";

		} ?>

	</select>

</td>