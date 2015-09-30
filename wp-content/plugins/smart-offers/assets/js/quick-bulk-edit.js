jQuery(function() {
	jQuery( '#bulk_edit' ).live( 'click', function() {
		// define the bulk edit row
		var bulk_row = jQuery( '#bulk-edit' );
 
		// get the selected post ids that are being edited
		var post_ids = new Array();

		bulk_row.find( '#bulk-titles' ).children().each( function() {
			post_ids.push( jQuery( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
		});
 
		// get the data
		var so_reset_quick_stats = bulk_row.find( 'input[name="so_reset_quick_stats"]' ).is(':checked');
		var is_reset_quick_stats = ( so_reset_quick_stats ) ? 'yes' : 'no';

		// save the data
		jQuery.ajax({
			url: ajaxurl, // this is a variable that WordPress has already defined for us
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'process_bulk_edit_smart_offers', // this is the name of our WP AJAX function that we'll set up next
				post_ids: post_ids, // and these are the 2 parameters we're passing to our function
				so_reset_quick_stats: is_reset_quick_stats
			}
		});
	});
});