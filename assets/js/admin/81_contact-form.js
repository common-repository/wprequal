jQuery(document).ready(function($) {

	if (typeof contactAdmin !== 'undefined') {
		var inputs = contactAdmin.inputs;
		var msg    = contactAdmin.msg;
	}

	$( '.contact-inputs' ).sortable();
	$( '.contact-inputs' ).disableSelection();

	$(document).on('keyup', '.input-label', function() {
		var slide = $(this).closest('.slide');
		if (! slide.hasClass('full-name') ) {
			var value = $(this).val();
			slide.find('.slide-head .label').html(value);
			slide.find('.slide-head .hidden-label').val(value);
		}
	});

	$(document).on('click', '.input-option .add-input', function() {

		var clone = $(this).closest('.input-option').clone();

		var key  = clone.find('.key').val();
		var rand = createKey();
		var name = 'input[' + key + '][options][' + rand + ']';

		clone.find('input:text').val('');
		clone.find('input:checkbox, input:radio').prop('checked', false);
		clone.find('.checkbox').attr('name', name + '[checked]');
		clone.find('.option').attr('name', name + '[option]');
		clone.find('.value').attr('name', name + '[value]');

		$(this).closest('.input-options').append(clone);
	});

	$(document).on('click', '.input-options .radio', function() {
		var options = $(this).closest('.input-options');
		options.find('.radio').attr('checked', false);
		$(this).attr('checked', true);
	});

	$(document).on('click', '.input-option .trash-input', function() {
		var count = $(this).closest('.slide').find('.input-option').length;
		if ( count > 1 ) {
			$(this).closest('.input-option').remove();
		}
	});

	/**
	 * Validate the from
	 */
	$('.post-type-wpq_contact_form #post').submit(function(e) {
		e.preventDefault();

		var validate = true;

		$('.wpq-required').each(function() {

			var slide = $(this).closest('.slide');

			if ( '' === $(this).val() ) {

				$(this).addClass('wpq-empty').attr('placeholder', 'Required field').focus();

				slide.find('.fa-caret-down').toggleClass('fa-caret-up fa-caret-down');
				slide.find('.form-table').slideDown(200);

				validate = false;
			}
		});

		if (validate) {
			$(this).unbind('submit').submit();
		}
		else {
			alert(msg.correctErrors);
		}

	});

	$('.contact.slide-options .option, .lead-field.slide-options .option').on('click', function(e) {
		e.preventDefault()

		var label = $(this).text();
		var key   = createKey();

		if ( $(this).hasClass('contact-option') ) {
			if ( $('.slide.' + this.id).length ) {
				alert( msg.onlyOneAllowed.replace(/{label}/g, label) );
				return false;
			}
		}

		var input = getInput(this.id, key, label);
		$('.contact-inputs').append(input);

		hideHeadings(key);

		$(document).find('.slide.' + this.id + '.' + key).find('.caret').trigger('click');

	});

	function createKey() {
		return Math.round((Math.pow(36, 30 + 1) - Math.random() * Math.pow(36, 30))).toString(36).slice(1);
	}

	function getInput(type, key, typeLabel) {

		var input = inputs[type];
			input = input.replace(/{key}/g, key);
			input = input.replace(/{type}/g, type);
			input = input.replace(/{type_label}/g, typeLabel);

		return input;

	}

	function hideHeadings(key) {
		$('.slide.' + key + ' .slide-headings').hide();
	}

});