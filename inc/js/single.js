/**
 Plugins JS
 
*/

jQuery(document).ready(function($) {

	
	/* ESTILOS GENERALES
	--------------------------------------------------------------------------------------- */

	// Template para los comentarios
	var comment_row = _.template("<div class='comment' style='background: #F2F0F0;border-bottom: 1px solid #FFFFFF;'>\
									<a href='#' title='Eliminar comentario' class='delete-comment floatRight tipsyHover'>X</a>\
									<a href='<%= user_url %>' class='avatar'><img src='<%= avatar %>' alt='Avatar de <%= user_name %>'></a>\
									<p><a href='<%= user_url %>'><%= user_name %></a><br><%= msg %></p>\
								</div>");

	

	
	/* ACTIVAR FORMULARIO DE ENVIO DEL COMENTARIO
	--------------------------------------------------------------------------------------- */
	$('#comment-content').keyup(function(event) {
		var $num_char = $('#comment-content').val().length;
		//console.log($num_char);
		if ( $num_char > 0 ) {
			$('#comment-pin-form #comment-submit').removeAttr('disabled'); // Activar
		} else {
			$('#comment-pin-form #comment-submit').attr('disabled', 'disabled'); // Desactivar
		}
	})
	
	/* ENVIAR COMENTARIO
	--------------------------------------------------------------------------------------- */
	$('#comment-pin-form').submit(function() {
			
		msg = $('#comment-content').val();
		user_id = $('#comment_user_id').val();
		pin_id = $('#comment_pin_id').val();
		avatar = $("#comment-pin-form .avatar").attr('src');
		user_name = $("#comment_user_name").val();
		user_url = $("#comment_user_url").val();
		pin_id = $("#comment_pin_id").val();
		comments = $("#pin-comments");
		

		comments.append (comment_row({ msg : msg, avatar: avatar, user_url: user_url}));

		$.ajax({
		  type: "POST",
		  url: ajaxurl,
		  data: { action : "pin_add_comment_action", pin_id : pin_id, user_id : user_id, content : msg, pin_add_comment : $("#pin_add_comment").val()},
		  dataType: "json",
		  success: function( res ) {
		  	$new_comment = $('#pin-comments .comment:last-child a.delete-comment');
		  	$new_comment.attr( 'comment-id', res.comment_id );
		  	$new_comment.attr( 'nonce', res.nonce );
    		//console.log( res );
    		
  		   }
		})
				
	  	this.reset(); // Reseteamos el formulario
	  	$('#comment-pin-form #comment-submit').attr('disabled', 'disabled'); // Desactivar button
	  	
	  	return false;
	  	
	});
	
	/*	ELIMIAR COMENTARIOS
	--------------------------------------------------------------------------------------- */
	$("#pin-comments .delete-comment").live ( 'click', function () {

		var comment_wrapper = $(this).parent();
		comment_wrapper.css('background-color', '#F28585');
		
		if (! $(this).attr('comment-id')) {
			alert('Ha habido un error eliminando su comentario');
			return false;
		}
		
		
		$.ajax({
		  type: "POST",
		  url: ajaxurl,
		  data: { action : "pin_delete_comment_action", comment_id : $(this).attr('comment-id'), nonce: $(this).attr('nonce')},
		  dataType: "json",
		  success: function( res ) {
    		//console.log( res )
    		if ( res.success ) {
    			comment_wrapper.slideUp('fast', function () {
    				comment_wrapper.remove();
    			});
    			
    		} else {
    			alert('Ha habido un error eliminando su comentario');
    		}
  		   }
		})
		
		return false; 
	});
	
	
	
	

	
	
	
});

