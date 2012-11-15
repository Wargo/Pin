jQuery(document).ready(function($) {

	/*	ELIMIAR CATEGORÍA
	--------------------------------------------------------------------------------------- */
	$("#pin-categories .delete-cat").live ( 'click', function () {

		var cat_wrapper = $(this).parents('tr');
		cat_wrapper.css('background-color', '#F28585');
		
		if (! $(this).attr('cat-id')) {
			alert('Ha habido un error eliminando su categoría');
			return false;
		}
		
		
		$.ajax({
		  type: "POST",
		  url: ajaxurl,
		  data: { action : "pin_delete_category_action", cat : $(this).attr('cat-id')},
		  success: function( res ) {
    			cat_wrapper.slideUp('fast', function () {
    				cat_wrapper.remove();
    			});
  		   }
		})
		
		return false; 
	});
	
	
/*	EDITAR CATEGORÍA
	--------------------------------------------------------------------------------------- */
	$("#pin-categories .edit-cat").live ( 'click', function () {

		var cat_wrapper = $(this).parents('tr');
		cat_wrapper.css('background-color', '#f5f5f5');
		
		if (! $(this).attr('cat-id')) {
			alert('Ha habido un error eliminando su categoría');
			return false;
		}
		
		name = cat_wrapper.find('.name');
		slug = cat_wrapper.find('.slug');
		count = cat_wrapper.find('.count');
		action = cat_wrapper.find('.actions');
		
		
		name.append('<input type="text" name="name" class="name" value="' + name.text() + '" />').find('span').hide();
		slug.append('<input type="text" name="slug" class="slug" value="' + slug.text() + '" />').find('span').hide();
		action.append('<div class="edit-actions"><a href="#" class="save-cat">Guardar</a> | <a href="#" class="cancel-edit-cat">Cancelar</a></div>').find('span').hide();
		/*
		$.ajax({
		  type: "POST",
		  url: ajaxurl,
		  data: { action : "pin_delete_category_action", cat : $(this).attr('cat-id')},
		  success: function( res ) {
    			cat_wrapper.slideUp('fast', function () {
    				cat_wrapper.remove();
    			});
  		   }
		})
		*/
		
		return false; 
	});
	
/*	CANCELAR EDITAR CATEGORÍA
	--------------------------------------------------------------------------------------- */
	$("#pin-categories .cancel-edit-cat").live ( 'click', function () {

		var cat_wrapper = $(this).parents('tr');
		cat_wrapper.css('background-color', 'transparent');

		
		cat_wrapper.find('input, .edit-actions').hide();
		cat_wrapper.find('span').show();
		
		return false; 
	});
	
/*	GUARDAR CATEGORÍA
	--------------------------------------------------------------------------------------- */
	$("#pin-categories .save-cat").live ( 'click', function () {

		var cat_wrapper = $(this).parents('tr');
		
		var name = cat_wrapper.find('.name');
		var slug = cat_wrapper.find('.slug');
		var count = cat_wrapper.find('.count');
		var action = cat_wrapper.find('.actions');
		
		var input_name = cat_wrapper.find('input.name').val();
		var input_slug = cat_wrapper.find('input.slug').val();
		
		$.ajax({
		  type: "POST",
		  url: ajaxurl,
		  data: { 
		  	action : "pin_edit_category_action", 
		  	cat_id : cat_wrapper.attr('cat-id'), 
		  	cat_slug: input_slug, 
		  	cat_name: input_name
		  },
		  success: function( res ) {
			cat_wrapper.css('background-color', 'transparent');
			cat_wrapper.find('input, .edit-actions').hide();
			name.find('span').text( input_name );
			slug.find('span').text( input_slug );
			cat_wrapper.find('span').show();
  		   }
		})
		
		return false; 
	});
	
	
	
});