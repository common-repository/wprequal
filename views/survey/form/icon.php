<?php
/**
 * Survey Form Icon
 *
 * Survey form single icon template.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="wpq-col">

	<div class="icon-button next-slide wpq-pt-sm-5 wpq-pb-sm-5">

		<input type="radio" name="lead[fields][{key}]" value="{value}" id="{buttonID}" class="wpq-slide radio-button" aria-label="{label}">
		<span style="display:none;"><?php _e( 'Icon', 'wprequal' ); ?></span>
		<i class="{class}"></i>
		<label for="{buttonID}" class="label">{label}</label>

	</div>

</div>