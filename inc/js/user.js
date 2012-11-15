/**
 Plugins JS
 
*/

jQuery(document).ready(function($) {
	
	/* AÑADIR TABLERO
	--------------------------------------------------------------------------------------- */
	$("#pin-board-form").submit( function () {
		if ( $("#board-name").val() == '' ) {
			 $("#board-name").parents('.control-group').addClass('error');
			 $("#board-name").focus();
			 $("#pin-board-form .help-inline").css( 'display', 'block');
			return false;
		} else {
			return true;
		}
		return  false;
	})
	
	/* SEGUIR / DEJAR DE SEGUIR TABLERO
	--------------------------------------------------------------------------------------- */
	
	$(".follow-button").click ( function () {
	
		follow_button = $(this);
		follow_button.addClass('loading');
		
		if ( follow_button.hasClass('active')) {
			// Dejar de seguir
			action = 'pin_unfollow_board_action';
			follow_board ( action, follow_button);		

		} else {
		
			// Seguir
			action = 'pin_follow_board_action';
			follow_board ( action, follow_button);
		}

		return false;
		
	})
	
	/* SEGUIR / DEJAR DE SEGUIR USUARIO
	--------------------------------------------------------------------------------------- */
	
	$(".follow-user-button").click ( function () {
	
		follow_button = $(this);
		follow_button.addClass('loading');
		
		if ( follow_button.hasClass('active')) {
			// Dejar de seguir
			action = 'pin_unfollow_user_action';
			follow_user ( action, follow_button);		

		} else {
		
			// Seguir
			action = 'pin_follow_user_action';
			follow_user ( action, follow_button);
		}

		return false;
		
	})
	
	
		
});

/* ELIMINAR TABLERO
--------------------------------------------------------------------------------------- */

function delete_board () {
	var button = jQuery('.delete-board');
	var r = confirm( button.attr('confirm') );
	if (r){		
		button.addClass('loading');
	 	jQuery.ajax({
		  type: "POST",
		  url: ajaxurl,
		  dataType: "json",
		  data: { action : "pin_delete_board_action", board_id : button.attr('board-id')},
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
	return false;
}


/* SEGUIR TABLERO
--------------------------------------------------------------------------------------- */

function follow_board ( action, follow_button ) {

	jQuery.ajax({
	  type: "POST",
	  url: ajaxurl,
	  dataType: "json",
	  data: { 
	  	action : action, 
	  	board_id : follow_button.attr('data-board-id'), 
	  	board_author_id: follow_button.attr('data-board-author-id')
	  },
	  success: function( res ) {
	  	
	  	if ( res.success == 1 ) {
	  		if ( res.action == 'unfollow') {
	  			follow_button.text( follow_button.attr ( 'data-text-follow')).removeClass('loading active');
	  		} else {
	  			follow_button.text( follow_button.attr ( 'data-text-unfollow')).removeClass('loading').addClass('active');
	  		}
	  		
	  	} else {
	  		
	  		alert( 'No ha sido posible' );
	  	}
	  }
	})
}

/* SEGUIR USUARIO
--------------------------------------------------------------------------------------- */

function follow_user ( action, follow_button ) {

	jQuery.ajax({
	  type: "POST",
	  url: ajaxurl,
	  dataType: "json",
	  data: { 
	  	action : action, 
	  	user_id : follow_button.attr('data-user-id')
	  },
	  success: function( res ) {
	  	
	  	if ( res.success == 1 ) {
	  		if ( res.action == 'unfollow') {
	  			follow_button.text( follow_button.attr ( 'data-text-follow')).removeClass('loading active');
	  		} else {
	  			follow_button.text( follow_button.attr ( 'data-text-unfollow')).removeClass('loading').addClass('active');
	  		}
	  		
	  	} else {
	  		
	  		alert( 'No ha sido posible' );
	  	}
	  }
	})
}