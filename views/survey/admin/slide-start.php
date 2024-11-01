<?php
/**
 * Slide
 *
 * Slide element.
 *
 * @since   7.0
 *
 * @package WPrequal
 */

namespace WPrequal;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied!!' );
} ?>

<li class="slide {type} {key}">

	<div class="slide-head">
		<span><i class="fas fa-grip-vertical point"></i></span>
		<span><i class="fas fa-caret-down caret point"></i></span>
		<span class="label">{label}</span>
		<span><i class="far fa-trash-can delete-slide point red right" data-key="{key}"></i></span>

		<input type="hidden" name="slide[{key}][key]" value="{key}" class="slide-key">
		<input type="hidden" name="slide[{key}][type]" value="{type}" class="slide-type">
	</div>

	<table class="form-table">

		<tbody class="slide-headings">

			<tr>
				<th><label><?php _e( 'Lead Field Label', 'wprequal' ); ?></label></th>
				<td>
					<input type="text" name="slide[{key}][label]" value="{label}" class="slide-label regular-text wpq-required">
				</td>
			</tr>

			<tr>
				<th><label><?php _e( 'Heading', 'wprequal' ); ?></label></th>
				<td><input type="text" name="slide[{key}][heading]" class="regular-text"></td>

				<th><label><?php _e( 'Note', 'wprequal' ); ?></label></th>
				<td><input type="text" name="slide[{key}][note]" class="large-text"></td>
			</tr>

			<tr>
				<th><label><?php _e( 'Sub Heading', 'wprequal' ); ?></label></th>
				<td colspan="3"><input type="text" name="slide[{key}][sub_heading]" class="large-text"></td>
			</tr>

		</tbody>