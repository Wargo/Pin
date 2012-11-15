	
function isEmpty( inputStr ) { if ( null == inputStr || "" == inputStr ) { return true; } return false; }

function validateForm( form ) {
	 	//console.log(form.elements);
		if ( form.elements['name'].value == '' || form.elements['category'].value == '' || form.elements['board'].value == '') {
			alert('Rellena todos los campos por favor');
			return false;
		}
	}
	

jQuery(document).ready(function($) {

	// Validación del formulario
	
	$("#new-pin-form").submit ( function () {
	
		error = false;
		
		
		if ( $("#name").val() == '' ) {
			$("#name").parents('.control-group').addClass('error');
			error = true;
		} else {
			$("#name").parents('.control-group').removeClass('error');
		}
		if ( $("#board").val() == '' ) {
			$("#board").parents('.control-group').addClass('error');
			error = true;
		} else {
			$("#board").parents('.control-group').removeClass('error');
		}
		
		if ( error ) {
			return false;
		} else {
			return true;
		}
	
	});
	
	var current_board_label = $(".BoardPicker .CurrentBoard");
	var current_board_input = $("input#board");
	
	
	$(document).click(function(event) { 
	    if($(event.target).parents().index($('.BoardPicker')) == -1) {
	        if($('.BoardList').is(":visible")) {
	            $('.BoardList').hide()
	        }
	    }        
	})
	
	$(".BoardPicker .current").click( function () { $('.BoardList').show();});

	$(".BoardPicker ul li").click( function () {
		current_board_label.text( $(this).text() );
		current_board_input.val( $(this).attr('data') );
		$('.BoardList').hide();
	});
	
	$(".CreateBoard button").click( function () {
		
		if ( $("#new-board-name").val() == '') {
			alert('Agrega un nombre por favor');
			return false;
		}
		var button = $(this);
		
		button.attr('disabled', 'disabled');
		button.addClass('loading');
		board_list = $("#my_boards ul");
		
		
		$.ajax({
		  type: "POST",
		  url: pinajaxurl,
		  data: { action : "pin_add_board_action", user_id :user_id, board_name: $("#new-board-name").val()},
		  dataType: "json",
		  success: function( res ) {
		  	//console.log(res);
		  	if ( res.success ) {
		  		
		  		current_board_label.text( $("#new-board-name").val() );
		  		current_board_input.val( res.response );
		  		button.removeAttr( 'disabled' );
		  		button.removeClass('loading');
		  		board_list.append('<li data="' + res.response + '"><span>' + $("#new-board-name").val() + '</span></li>');
		  		$('.BoardList').hide();
		  		button.val('Crear nuevo tablero');
		  	}
    		
    			
  		   }
		})
		
	});

	


});