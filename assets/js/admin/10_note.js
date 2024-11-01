jQuery(document).ready(function($) {
	
	$( '.add-note' ).on( 'click', function() {
		
		var input = new Object();
		input.text = $( '#new-note-text' ).val();
		input.lead_id = $( '#lead_id' ).val();

		$.ajax ( {
			url : note.endpoint,
			type : 'post', 
			data : {
				action : 'new_note_ajax',
				input : input,
				note_nonce : note.nonce,
			},
			beforeSend: function() {
				$( '.new-note-spinner' ).removeClass( 'fa-plus-square fa-exclamation-triangle' ).addClass( 'fa-spinner fa-spin' );
				$('.new-note').removeClass('new-note');
			},
			success: function( response ) {
				$( '.new-note-spinner' ).removeClass( 'fa-spinner fa-spin' ).addClass( response.class );
				if( response.success ) {
					$( '#wprequal-note-wrapper' ).prepend( response.note );
					$( '#new-note-text' ).val('').focus();
				}
			},
			dataType:'json'
		});
	})
	
});