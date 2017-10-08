jQuery( document ).ready( function( $ ){

	/* Only if form exist */
	if ( $( '.application_details form' ).length ) {

		/* On form submit */
		$( 'body' ).on( 'submit', '.application_details form', function(){
			var that = $( this );
			var wrap = $( this ).closest( '.application_details' );
			if( ! wrap.hasClass( 'wpjms_submitted' ) ){
				$.ajax({
					type: "POST",
					url: wpjms_stat_afs.ajax_url,
					data:{
						action     : 'wpjms_stat_apply_form_submit',
						nonce      : wpjms_stat_afs.ajax_nonce,
						post_id    : wpjms_stat_afs.post_id,
					},
					dataType: 'json',
					success: function( data ){
						console.log( data );
						if( data ){
							wrap.addClass( 'wpjms_submitted' );
						}
						return;
					},
				});
			}
		});
	}

});