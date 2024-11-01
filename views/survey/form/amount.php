<?php
/**
 * Amount Slider
 *
 * Amount slider for survey form.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="amount wpq-col-12">

	<div class="total">
		<span class="number-before">{before}</span>
		<span class="number-commas">{number}</span>
		<span class="number-after">{after}</span>
	</div>

	<input type="range" name="lead[fields][{key}]" id="{key}" min="{min}" max="{max}" step="{step}" value="{value}" class="range-slider amount-input">

	<div class="button next-slide"><?php _e( 'Next', 'wprequal' ); ?></div>

</div>
