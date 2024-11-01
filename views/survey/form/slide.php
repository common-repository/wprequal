<?php
/**
 * Slide
 *
 * Survey form slide.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<div class="slide {type} {key}">

	<div class="wpq-row">
		<div class="heading">{heading}</div>
	</div>

	<div class="wpq-row">
		<div class="sub-heading">{sub_heading}</div>
	</div>

	<div class="wpq-row">
		<div class="note">{note}</div>
	</div>

	<?php do_action( 'wprequal_before_inputs' ); ?>

	<fieldset>

		<legend style="display:none;">{heading} Options</legend>

		<div class="wpq-row">{inputs}</div>

	</fieldset>

	<a class="previous-slide pointer"></a>

	<?php do_action( 'wprequal_after_inputs' ); ?>

</div>
