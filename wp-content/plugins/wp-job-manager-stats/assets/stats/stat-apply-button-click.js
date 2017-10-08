jQuery( document ).ready( function( $ ){

	/* Click apply button */
	$( 'body' ).on( 'click', '.application_button.button', function(e){
		var that = $( this );
		if( ! that.hasClass( 'wpjms_clicked' ) ){
			$.ajax({
				type: "POST",
				url: wpjms_stat_abc.ajax_url,
				data:{
					action     : 'wpjms_stat_apply_button_click',
					nonce      : wpjms_stat_abc.ajax_nonce,
					post_id    : wpjms_stat_abc.post_id,
				},
				dataType: 'json',
				success: function( data ){
					console.log( data );
					if( data ){
						that.addClass( 'wpjms_clicked' );
					}
					return;
				},
			});
		}
	});
});