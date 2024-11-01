jQuery( document ).ready( function( $ ) {

	wpqSetClass($);

	window.addEventListener('resize', function() {
		wpqSetClass($);
	}, false );
	
});
