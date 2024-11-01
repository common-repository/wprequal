jQuery(document).ready(function($) {

	if ( $('.wpq-icon-color').length ) {
		$('.wpq-icon-color input').wpColorPicker({
			defaultColor: 'FFFFFF'
		});
	}

	if ( $('.wpq-icon-hover').length ) {
		$('.wpq-icon-hover input').wpColorPicker({
			defaultColor: '0066cc'
		});
	}

	if ( $('.wpq-button-color').length ) {
		$('.wpq-button-color input').wpColorPicker({
			defaultColor: '0066cc'
		});
	}

	if ( $('.wpq-button-hover').length ) {
		$('.wpq-button-hover input').wpColorPicker({
			defaultColor: '5d91bf'
		});
	}

	if ( $('.wpq-button-text-color').length ) {
		$('.wpq-button-text-color input').wpColorPicker({
			defaultColor: 'ffffff'
		});
	}

	if ( $('.wpq-color-picker').length ) {
		$('td .wpq-color-picker').wpColorPicker({
			defaultColor: '999999'
		});
	}
});