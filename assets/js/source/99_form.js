/**
 * @since     5.0
 * @requires  /wp-includes/js/jquery/jquery.js
 */

jQuery( document ).ready( function( $ ) {

	var form;
	var nonce;
	var delay = 0;

	if ( $('.survey-form').length ) {
		populateForms();
	}

	if ( $('.cookie').length ) {
		setCookies();
	}

	maskInputs();

	function populateForms() {

		$.each(surveyForm.forms, function() {

			var form = $('#' + this.form_id);

			populateHTML(form);

		});

	}

	function hideLast(form, input) {
		var height = form.height();
		form.css('min-height', height);
		input.closest('.slide').slideUp(800);
	}

	function showNext(html, ID) {
		$(html).hide().appendTo('#' + ID).slideDown(800);
	}

	function populateHTML(form) {

		var html  = surveyForm.slide;
		var ID    = form.attr('id');
		var i     = parseInt( form.attr('index') );
		var obj   = $.grep(surveyForm.forms, function(obj) {
			return obj.form_id === ID;
		});
		var count = obj[0].slides.length;

		if (count <= i) {
			return false;
		}

		var slide = obj[0].slides[i];

		// If the slide is not allowed.
		if ( ! logic(form, slide) ) {
			// Index id
			i++;
			// Update the from index
			form.attr('index', i);
			// Try the next slide
			populateHTML(form);
			return true;
		}

		if ('redirect' === slide.type) {
			// Allow time to read previous slide.
			setTimeout(function() {
				form.fadeOut(500);
				redirect(form, slide);
			}, 1000);
			return false;
		}

		html = populateSlide(html, slide);
		html = populateContent(html, slide);
		html = $(html);

		i++;

		html.attr('index', i);
		form.attr('index', i);

		if ( 1 === i ) {
			html.find('.previous-slide').remove();
		} else {
			var back_text = form.data('back-text');
			html.find('.previous-slide').html(back_text);
		}

		showNext(html, ID);

		if ('text' === slide.type || 'contact' === slide.type) {
			initInputMask(form, slide);
		}

		return slide;

	}

	function logic(form, slide) {

		if (typeof slide.check !== 'undefined') {
			var checkboxes = slide.check;
		}

		if (typeof checkboxes === 'undefined') {
			return true;
		}

		if (typeof checkboxes.conditional_logic === 'undefined') {
			return true;
		}

		if ('checked' !== checkboxes.conditional_logic) {
			return true;
		}

		var logic = slide.logic

		if (typeof logic === 'undefined') {
			return true;
		}

		if (typeof logic.conditions === 'undefined') {
			return true;
		}

		if (1 > logic.conditions.length) {
			return true;
		}

		var matches = new Array();

		$.each(logic.conditions, function() {

			var cond = false;

			// Check to see if this condition matches.
			if ( compareValues(form, logic, this) ) {
				cond = true;
			}

			matches.push(cond);

		});

		var show;

		if ( 'hide' === logic.show_hide && 'any' === logic.any_all ) {
			// If anything is true, return false
			show = true;
			$.each(matches, function(i) {
				// If anything is true, hide this.
				if (true === matches[i]) {
					show = false;
					return;
				}
			});
			return show;
		}

		if ( 'show' === logic.show_hide && 'any' === logic.any_all ) {
			// If anything is true, return true
			show = false;
			$.each(matches, function(i) {
				// If anything is true, show this.
				if (true === matches[i]) {
					show = true;
					return;
				}
			});
			return show;
		}

		if ( 'hide' === logic.show_hide && 'all' === logic.any_all ) {
			// If everything is true, true false
			// If anything is false, return true
			show = false;
			$.each(matches, function(i) {
				// If anything is false, show this.
				if (false === matches[i]) {
					show = true;
					return;
				}
			});
			return show;
		}

		if ( 'show' === logic.show_hide && 'all' === logic.any_all ) {
			// If everything is true, return true
			show = true;
			$.each(matches, function(i) {
				// If anything is false, hide this.
				if (false === matches[i]) {
					show = false;
					return;
				}
			});
			return show;
		}

		// We have no reason not to load, so let's load.
		return true;
	}

	function compareValues(form, logic, c) {

		var input = form.find('input[name="lead[fields][' + c.logic_option + ']"]');

		if ( input.hasClass('radio-button') ) {
			var option = form.find('input[name="lead[fields][' + c.logic_option + ']"]:checked').val();
		}
		else {
			var option = input.val();
		}
		var value = c.logic_value;

		switch(c.logic_equal) {
			case '=':
				return option === value;
				break;
			case '!=':
				return option !== value;
				break;
			case '>':
				return option > value;
				break;
			case '<':
				return option < value;
				break;
			case '<=':
				return option <= value;
				break;
			case '>=':
				return option >= value;
				break;
		}

		return true;

	}

	function populateSlide(html, slide) {

		var heading     = (! slide.heading)     ? '' : slide.heading;
		var sub_heading = (! slide.sub_heading) ? '' : slide.sub_heading;
		var note        = (! slide.note)        ? '' : slide.note;

		html = html.replace(/{label}/g, slide.label);
		html = html.replace(/{key}/g, slide.key);
		html = html.replace(/{type}/g, slide.type);
		html = html.replace(/{heading}/g, heading);
		html = html.replace(/{sub_heading}/g, sub_heading);
		html = html.replace(/{note}/g, note);
		
		return html;
	}

	function populateContent(html, slide) {

		switch(slide.type) {
			case 'icons':
				var inputs = populateIcons(surveyForm.icon, slide);
				break;
			case 'buttons':
				var inputs = populateButtons(surveyForm.button, slide);
				break;
			case 'text':
				var inputs = populateText(surveyForm.text, slide);
				break;
			case 'amount':
				var inputs = populateAmount(surveyForm.amount, slide);
				break;
			case 'contact':
				var inputs = populateContactForm(surveyForm.contact, slide);
				break;
			case 'processing':
				var inputs = surveyForm.processing;
				break;
			case 'confirmation':
				var inputs = populateEditor(surveyForm.confirmation, slide);
				break;
		}
		
		return html.replace(/{inputs}/g, inputs);
	}

	function populateIcons(icon, slide) {

		var inputs = ''

		$.each(slide.icons, function() {
			inputs += html(icon, this, slide.key);;
		});

		return inputs;
	}

	function populateButtons(button, slide) {

		var inputs = ''

		$.each(slide.buttons, function() {
			inputs += html(button, this, slide.key);
		});

		return inputs;
	}

	function html(html, $this, key) {

		if ( ! $this.text ) {
			return '';
		}

		var buttonID = $this.text.replace(/ /g, '-').toLowerCase();

		html = html.replace(/{label}/g, $this.text);
		html = html.replace(/{value}/g, $this.text);
		html = html.replace(/{buttonID}/g, buttonID);
		html = html.replace(/{class}/g, $this.class);
		html = html.replace(/{key}/g, key);

		return html;
	}
	
	function populateAmount(amount, slide) {

		var price  = numberWithCommas(slide.default_amount);
		var symbol =  surveyForm.currency_symbols[slide.currency_symbol];
		var before = ('before' === slide.currency_placement) ? symbol : '';
		var after  = ('after' === slide.currency_placement)  ? symbol : '';

		amount = amount.replace(/{before}/g, before);
		amount = amount.replace(/{after}/g, after);
		amount = amount.replace(/{number}/g, price);

		amount = amount.replace(/{min}/g, slide.min_amount);
		amount = amount.replace(/{max}/g, slide.max_amount);
		amount = amount.replace(/{step}/g, slide.step_amount);
		amount = amount.replace(/{value}/g, slide.default_amount);
		amount = amount.replace(/{key}/g, slide.key);
		
		return amount;
	}

	function populateText(text, slide) {

		text = text.replace(/{key}/g, slide.key);

		return text;
	}

	function populateEditor(text, slide) {

		text = text.replace(/{editor}/g, slide.editor);

		return text;
	}

	function populateContactForm(input, slide) {

		input = input.replace(/{contact_form}/g, slide.contact_form);

		return input;
	}

	function numberWithCommas(x) {
		if(typeof x !== 'undefined'){
			return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		return x;
	}

	function validateText(e) {
		var input = e.closest('.slide').find('.text-input .required');
		var value = input.val();

		input.removeClass( 'error' );

		if ( '' === value ) {
			input.addClass('error').val('').attr('placeholder', 'Invalid - Try Again').focus();
			return false;
		}
		return true;
	}

	function initInputMask(form, slide) {

		if (typeof slide.input_mask !== 'undefined') {

			if ( 'None' !== slide.input_mask && 'text' === slide.type ) {
				var mask = slide.input_mask.toString();
				form.find('input[name="lead[fields][' + slide.key + ']"]').inputmask(mask).focus();
			}

		}

		if (typeof slide.phone_mask !== 'undefined') {

			if ( 'None' !== slide.phone_mask && 'contact' === slide.type ) {
				form.find('.wprequal-phone').inputmask(slide.phone_mask);
			}

		}

		if (typeof slide.email_mask !== 'undefined') {

			if ( 'None' !== slide.email_mask && 'contact' === slide.type ) {
				form.find('.wprequal-email').inputmask(slide.email_mask);
			}

		}

	}

	function validateContactForm($this) {

		$this.removeClass('error');

		if ( $this.hasClass('radio-button') || $this.hasClass('checkbox') ) {
			var name = $this.attr('name');
			var input = $('input[name="' + name + '"]');
			if ( ! input.is(':checked') ) {
				input.addClass('error');
				return false;
			}

		}

		else if ( '' === $this.val() ) {
			$this.addClass( 'error' ).val( '' ).focus();
			$this.attr( 'placeholder', 'Required Field' );
			return false;
		}

		return true;
	}

	function redirect(form, slide) {
		var entry = form.serialize();
		var args  = customKeys(entry, slide);
		
		window.location = slide.endpoint + '?' + args;
	}
	
	function customKeys(entry, slide) {

		var fname = (typeof slide.fname === 'undefined' || '' === slide.fname) ? 'fname' : slide.fname;
		var lname = (typeof slide.lname === 'undefined' || '' === slide.lname) ? 'lname' : slide.lname;
		var email = (typeof slide.email === 'undefined' || '' === slide.email) ? 'email' : slide.email;
		var phone = (typeof slide.phone === 'undefined' || '' === slide.phone) ? 'phone' : slide.phone;
		var additional = (typeof slide.additional === 'undefined' || '' === slide.additional) ? 'fname' : slide.additional;

		var args;
		args = entry.replace(/fname/g, fname );
		args = args.replace(/lname/g, lname );
		args = args.replace(/email/g, email );
		args = args.replace(/phone/g,phone );
	
		if ('' !== additional) {
			args = args + '&' + additional;
		}
		
		return args;
	}

	function setCookies() {

		if ( $.cookie('wpq_param_referrer') ) {
			$('.param_referrer').val($.cookie('wpq_param_referrer'));
		}

		if ( $.cookie('wpq_social_referrer') ) {
			$('.social_referrer').val($.cookie('wpq_social_referrer'));
		}

	}

	$(document).on('click', '.slide .next-slide', function(e) {
		e.preventDefault();

		form = $(this).closest('.wprequal-form.survey-form');

		var validate = true;

		if ( $(this).hasClass('icon-button') || $(this).hasClass('button') ) {
			$(this).find('.radio-button').prop('checked', true);
		}

		if ( $(this).hasClass('wpq-validate-text') ) {
			validate = validateText($(this));
		}

		$(this).closest('.slide').find('.wpq-required').each(function() {
			if( ! validateContactForm($(this)) ) {
				validate = false;
			};
		});

		if( ! validate ) {
			return;
		}

		if ( populateHTML(form) ) {
			hideLast(form, $(this));
			maskInputs();
		}

	});

	$(document).on('input change', '.range-slider', function(){
		var amount = $(this).val();
		$(this).closest('.amount').find('.number-commas').html(numberWithCommas(amount));
	});

	// Focus the cursor to the first open position
	$(document).on('mouseover', '.wpq-input', function(){
		$(this).focus();
	});

	$(document).on('click', '.wprequal-submit', function(e) {

		e.preventDefault();

		var validate = true;
		var $this = $(this);
		var slide;

		form = $this.closest('.wprequal-form');

		form.find('.wpq-required').each(function() {
			if( ! validateContactForm($(this)) ) {
				validate = false;
			};
		});

		if( ! validate ) {
			return;
		}

		var entry = form.serialize();

		$.ajax ( {
			url : wprequal.endpoint,
			type : 'post', 
			data : {
				entry : entry,
				nonce : nonce
			},
			beforeSend: function() {
				if ( form.hasClass('survey-form') ) {
					slide = populateHTML(form);
					if ( slide ) {
						hideLast(form, $this);
					}
				} else {
					form.find('.wprequal-submit').html(wprequal.processing);
				}
			},
			success: function(response) {

				if (response.success) {

					if ( response.cookie ) {
						document.cookie = response.cookie;
					}

					if ( response.registered ) {

						if ( 'get_quote' === response.type ) {
							form.find('.get-quote-contact').slideUp(700).html(response.message).slideDown(700);
						}
						else {
							form.find('.wprequal-submit').html(response.message).removeClass('wprequal-submit');
							form.find('.text-input, .textarea, .checkbox, .radio, .select').prop('disabled', true);
						}

					}

					if ( response.redirect ) {
						window.location = response.redirect;
					}

					else {
						setTimeout(function() {
							while ( populateHTML(form) ) {
								var input = $('.slide.' + slide.key);
								hideLast(form, input);
							}
						}, 300);
					}

				}
				else {
					form.html(response.message);
				}
			},
			complete: function(response) {
				setTimeout(function() {
					$.magnificPopup.close();
				}, 3000);
			},
			dataType:'json'
		} );
		
	});

	$.get(wprequal.nonce_endpoint, function(data) {
		nonce = data;
	});

	function maskInputs() {

		if ( $('.wprequal-form').length ) {
			if ( wpqContactForm.emailMask ) {
				$('.mask-email').inputmask(wpqContactForm.emailMask);
			}

			$('.mask-phone').each(function() {
				var mask = $(this).attr('data-phone-mask');
				if ( typeof mask !== typeof undefined && mask !== false ) {
					$(this).inputmask(mask);
				}
			});
		}

	}

	$(document).on('click', '.previous-slide', function() {
		var form = $(this).closest('.survey-form');
		var slide = $(this).closest('.slide');

		var i = slide.prev().attr('index');
		form.attr('index', i);

		if ( slide.slideUp(600).prev().slideDown(600) ) {
			slide.remove();
		}
	});

	// Print the output value for the contact form range slider.
	$(document).on('input change', '.range-input', function() {
		var val = $(this).val();
		$(this).closest('.range-wrap').find('.output').html(val);
	})
	
});