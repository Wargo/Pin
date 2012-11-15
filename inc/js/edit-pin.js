/* BORRAR PIN
--------------------------------------------------------------------------------------- */


function delete_pin () {
	var button = jQuery('.delete-pin');
	var r = confirm( button.attr('confirm') );
	if (r){		
		button.addClass('loading');
	 	jQuery.ajax({
		  type: "POST",
		  url: ajaxurl,
		  dataType: "json",
		  data: { action : "pin_delete_pin_action", pin_id : button.attr('pin-id')},
		  success: function( res ) {
		  	
		  	if ( res.success == 1 ) {
		  		window.location = res.response;
		  	} else {
		  		button.removeClass('loading');
		  		alert( button.attr('error') );
		  	}
		  	
  		   }
		})
	}
}