<?php
/**
 * Survey Form Button
 *
 * Survey form button template.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>


<div class="button next-slide wpq-col-12">

	<input type="radio" name="lead[fields][{key}]" value="{value}" id="{buttonID}" class="wpq-slide radio-button" aria-label="{label}">
	<label for="{buttonID}" class="label">{label}</label>

</div>
