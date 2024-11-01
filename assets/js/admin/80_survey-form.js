jQuery(document).ready(function($) {

	if (typeof surveyAdmin !== 'undefined' ) {
		getAssets();
	}

	let assets;
	let slides;
	let inputs;
	let fa;
	let logicOptions;

	const loading = $('.wpq-loading');

	const showLoading = () => {
		loading.fadeIn(500);
	}

	const hideLoading = () => {
		loading.fadeOut(1500);
	}

	function getAssets() {

		assets = surveyAdmin.assets;
		slides = assets.slides;
		inputs = assets.inputs;

		getFa();

	}

	function getFa() {

		$.ajax({
			url: surveyAdmin.jsonUrl,
			cache: true,
			dataType: 'json',
			success: function(data) {
				fa = data;
				hideLoading();
			},
			complete: function( data ) {
				runLoader();
			}
		});

	}

	function runLoader () {

		if (Object.keys(slides).length) {

			$('.no-slides').remove();

			if ( addSlides() ) {
				hideLoading()
			}

		}

	}

	/**
	 * Add slides into admin
	 */
	function addSlides () {

		$.each(slides, function() {
			var input = getInput(this.type, this.key, this.label, false);
			$('.survey-slides').append(input);
			populateInput(this);
			populateValue(this);
			populateLogic(this);
		});

		logic();

		$.each(slides, function() {
			populateLogicValues(this);
		});

		return true;

	}

	function populateInput ($this) {

		switch($this.type) {
			case 'icons':
				populateIcons($this);
				break;
			case 'buttons':
				populateButtons($this);
				break;
			case 'confirmation':
				populateEditor($this);
				break;
			case 'redirect':
				hideHeadings($this.type, $this.key);
				break;
		}

	}

	function getInput(type, key, label, rand) {

		switch(type) {
			case 'icons':
				var input = inputs.icons;
				break;
			case 'buttons':
				var input = inputs.buttons;
				break;
			case 'text':
				var input = inputs.text;
				break;
			case 'amount':
				var input = inputs.amount;
				break;
			case 'contact':
				var input = inputs.contact;
				break;
			case 'processing':
				var input = inputs.processing;
				break;
			case 'confirmation':
				var input = inputs.confirmation;
				break;
			case 'redirect':
				var input = inputs.redirect;
				input = input.replace(/{label}/g, 'Redirect');
				break;
			case 'fa-select':
				var input = inputs.faSelect;
				break;
			case 'button':
				var input = inputs.button;
				break;
			case 'logic':
				var input = inputs.logicInput;
				break;
		}

		var randID = rand ? rand : Math.random().toString(36).substr(2, 9);;

		input = input.replace(/{label}/g, label);
		input = input.replace(/{key}/g, key);
		input = input.replace(/{randID}/g, randID);
		input = input.replace(/{type}/g, type);

		return input;

	}

	function populateValue($this) {

		input($this.key, 'heading', value($this.heading) );
		input($this.key, 'sub_heading', value($this.sub_heading) );
		input($this.key, 'note', value($this.note) );

		if ( 'amount' === $this.type ) {
			select($this.key, 'currency_symbol', value($this.currency_symbol) );
			select($this.key, 'currency_placement', value($this.currency_placement) );

			input($this.key, 'min_amount', amount($this.min_amount) );
			input($this.key, 'max_amount', amount($this.max_amount) );
			input($this.key, 'default_amount', amount($this.default_amount) );
			input($this.key, 'step_amount', amount($this.step_amount) );
		}

		else if ( 'contact' === $this.type ) {
			select($this.key, 'contact_form_id', value($this.contact_form_id) );
			select($this.key, 'button_action', value($this.button_action) );
		}

		else if ( 'text' === $this.type ) {
			select($this.key, 'input_mask', value($this.input_mask) );
		}

		else if ( 'redirect' === $this.type ) {
			input($this.key, 'endpoint', value($this.endpoint) );
			input($this.key, 'fname', value($this.fname) );
			input($this.key, 'lname', value($this.lname) );
			input($this.key, 'email', value($this.email) );
			input($this.key, 'phone', value($this.phone) );
			input($this.key, 'additional', value($this.additional) );
		}
	}

	function input(key, type, value) {
		$('input[name="slide[' + key + '][' + type + ']"]').val( value );
	}

	function select(key, type, value) {
		$('select[name="slide[' + key + '][' + type + ']"]').val( value );
	}

	function amount(amount) {
		if (typeof amount === 'undefined' || ! amount || '' === amount) {
			return '0';
		}
		return amount;
	}

	function value(string) {
		if (typeof string === 'undefined' || ! string || '' === string) {
			return '';
		}
		return string;
	}

	function populateIcons($this) {

		$.each($this.icons, function(i) {

			// Set the input
			var input = getInput('fa-select', $this.key, $this, i);
			$('.slide.icons.' + $this.key).find('.fa-select-tbody').append(input);

			//  Populate the input
			var name = 'slide[' + $this.key + '][icons][' + i + ']';
			faSelect('select[name="' + name + '[class]"]', this.class);

			var text = (typeof this.text !== 'undefined' && '' !== this.text ) ? this.text : '';
			$('input[name="' + name + '[text]"]').val(text);
		});
	}

	function populateButtons($this) {

		$.each($this.buttons, function(i) {

			if (typeof this.text !== 'undefined' && this.text && '' !== this.text ) {
				// Set the input
				var input = getInput('button', $this.key, $this, i);
				$('.slide.buttons.' + $this.key).find('.button-tbody').append(input);

				var name = 'slide[' + $this.key + '][buttons][' + i + '][text]';
				$('input[name="' + name + '"]').val(this.text);
			}
		});
	}

	function populateEditor($this) {
		if (typeof $this.editor !== 'undefined') {
			var value = (! $this.editor || '' === $this.editor) ? '' : $this.editor;
			$('.editor-' + $this.key).val(value);
		}
	}

	function populateLogic($this) {
		if (typeof $this.check !== 'undefined') {
			if (typeof $this.check.conditional_logic !== 'undefined' && 'checked' === $this.check.conditional_logic) {
				var name = 'slide[' + $this.key + '][check][conditional_logic]';
				$('input[name="' + name + '"]').prop('checked', true);
				populateLogicInputs($this);
			}
		}
	}

	function populateLogicInputs($this) {

		var name = 'slide[' + $this.key + ']';

		$('.slide.' + $this.key).find('.conditional-logic').slideDown(500);

		$('select[name="' + name + '[logic][show_hide]"]').val($this.logic.show_hide);
		$('select[name="' + name + '[logic][any_all]"]').val($this.logic.any_all);

		$.each($this.logic.conditions, function(i) {

			// Set the input
			var input = getInput('logic', $this.key, $this.label, i);
			$('.slide.' + $this.key).find('.conditional-logic').append(input);

		});

	}

	function populateLogicValues($this) {

		$.each($this.logic.conditions, function(i) {

			//  Populate the values.
			var thissName = 'slide[' + $this.key + '][logic][conditions][' + i + ']';
			$('select[name="' + thissName + '[logic_option]"]').val(this.logic_option);
			$('select[name="' + thissName + '[logic_equal]"]').val(this.logic_equal);
			$('input[name="' + thissName + '[logic_value]"]').val(this.logic_value);

		});

	}

	function hideHeadings(type, key) {
		if ('redirect' === type) {
			$('.slide.' + key + ' .slide-headings').hide();
		}
	}

	$('.survey.slide-options .option').on('click', function(e) {
		e.preventDefault()

		if ( $('.no-slides').length ) {
			$('.no-slides').remove();
		}

		let label = '';
		let key   = createKey();
		let ID = $(this).data('id');

		let input = getInput(ID, key, label, false);
		$('.survey-slides').append(input);

		logic();
		hideHeadings(ID, key);

		let slideNew   = $(document).find('.slide.' + ID + '.' + key);

		slideNew.find('.caret').trigger('click');
		slideNew.closest('.slide').find('.form-table input').filter(':visible:first').focus();

		$('html, body').animate({
			scrollTop: slideNew.offset().top - 60
		}, 1000 );

	});

	function getLabel(e) {
		return $(e).closest('.slide').find('.slide-label').val();
	}

	function getKey(e) {
		return $(e).closest('.slide').find('.slide-key').val();
	}

	function createKey() {
		return Math.round((Math.pow(36, 30 + 1) - Math.random() * Math.pow(36, 30))).toString(36).slice(1);
	}

	$(document).on('click', '.slide .caret', function() {
		$(this).toggleClass( 'fa-caret-up fa-caret-down');
		$(this).closest('.slide').find('.form-table').slideToggle(200);
	});

	$(document).on('click', '.add-fa-row', function() {
		var label = getLabel(this);
		var key = getKey(this);
		var input = getInput('fa-select', key, label, false);
		var e = $(this).closest('.slide.icons').find('.fa-select-tbody');
		e.append(input);
		faSelect(e.children('.fa-select-row').last().find('.fa-select'), '');
	});

	$(document).on('click', '.add-button', function() {
		var label = getLabel(this);
		var key = getKey(this);
		var input = getInput('button', key, label, false);
		var e = $(this).closest('.slide.buttons').find('.button-tbody');
		e.append(input);
		e.find('tr td input').last().focus();
	});

	$(document).on('click', '.minus-fa-row', function() {
		$(this).closest('.fa-select-row').remove();
	});

	$(document).on('click', '.slide-head .delete-slide', function() {
		$(this).closest('.slide').remove();
	});

	$(document).on('keyup', '.slide .slide-label', function() {
		$(this).removeClass('wpq-empty');
		var val = $(this).val();
		$(this).closest('.slide').find('.slide-head .label').html(val);
		logic();
	});

	function faSelect(e, value) {

		$(e).select2({
			width            : "100%",
			templateSelection: iformat,
			templateResult   : iformat,
			minimumInputLength: 1,
			placeholder      : "Select One",
			allowClear       : true,
			data             : fa.results
		});

		$(e).val(value).trigger('change');

		return true;
	}

	function iformat(icon) {
		return $('<span><i class="' + icon.id + '"></i> ' + icon.text + '</span>');
	}

	$( '.survey-slides' ).sortable();
	$( '.survey-slides' ).disableSelection();

	/**
	 * Conditional logic
	 */

	$(document).on('click', '.conditional-checkbox', function() {
		$(this).closest('.slide').find('.conditional-logic').slideToggle();
	});

	$(document).on('click', '.logic-add', function() {
		var label = getLabel(this);
		var key = getKey(this);
		var input = getInput('logic', key, label, false);
		$(this).closest('.slide').find('.conditional-logic').append(input);
		logic();
	});

	$(document).on('click', '.minus', function() {
		$(this).closest('.result').remove();
	});

	function setLogicOptions(){
		logicOptions = new Array();

		$('.slide').each(function() {
			var key  = $(this).find('.slide-key').val();
			var label = $(this).find('.slide-label').val();
			var val   = {'key': key, 'label': label};
			logicOptions.push(val);
		});

	};

	function logic() {

		setLogicOptions();

		var value;
		var select;
		var options = selectOptions();

		$('.logic-option').each( function() {
			select = $(this);
			value = select.val();
			select.empty().append(options).val(value);
		});
	}

	function selectOptions() {

		var options = '<option>Select One</option>'

		$.each(logicOptions, function() {
			options += '<option value="' + this.key + '">' + this.label + '</option>';
		});

		return options;

	}

	/**
	 * Validate the from
	 */
	$('.post-type-wpq_survey_form #post').submit(function(e) {
		e.preventDefault();

		let validate = true;

		// Let's alert on form reset here.
		// We do not need to validate if the form is being reset.
		if ( $("input[name='create_slides_from_template']").is(':checked') ) {

			if ( '' === $("select[name='template']").val() ) {
				alert( assets.template_msg );
				return false;
			}

			if ( ! confirm( assets.reset_msg ) ) {
				return false;
			}

		}
		else {

			$('.wpq-required').each(function() {

				var slide = $(this).closest('.slide');

				if ( '' === $(this).val() ) {

					$(this).addClass('wpq-empty').attr('placeholder', 'Required field').focus();

					slide.find('.fa-caret-down').toggleClass('fa-caret-up fa-caret-down');
					slide.find('.form-table').slideDown(200);

					validate = false;
				}
			});

		}

		if (validate) {
			showLoading();
			$(this).unbind('submit').submit();
		}
		else {
			alert('Please correct the errors and try again!');
		}

	});

});
