jQuery(document).ready(function($){

	if(typeof wprequal_popup !== 'undefined') {

		if ( typeof wprequal_popup.popupForm !== 'undefined' && wprequal_popup.popupForm ) {
			setTimeout(function() {
				if ( $('.wprequal-form-popup').length ) {
					if ( wprequal_popup.cookie ) {
						document.cookie = wprequal_popup.cookie;
					}
					$.magnificPopup.open(wprequal_popup.popupForm);
				}
			}, wprequal_popup.delay);
		}

	}

	if(typeof wprequal_calc_popup !== 'undefined' ) {

		if(typeof wprequal_calc_popup.popupCalc !== 'undefined' && $('.calc-hide .wprequal-calc').length ) {
			$('.button.calc-pop').magnificPopup(wprequal_calc_popup.popupCalc);
		}

	}

	$('.wpq-popup-button').on('click', function() {
		var src = $(this).closest('.wpq-popup-button-wrapper').find('.wpq-popup-button-form');

		$.magnificPopup.open({
			items         : {
				src : src,
				type: 'inline'
			},
			closeOnBgClick: true,
			showCloseBtn  : true,
			callbacks: {
				elementParse: function(item) {
					$(window).trigger('resize');
				}
			}
		} );

	});

});