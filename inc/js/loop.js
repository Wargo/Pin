/**
 loops de pins
 
*/

jQuery(document).ready(function($) {

	
	/* INFINITESCROLL && MASONRY http://masonry.desandro.com/
	--------------------------------------------------------------------------------------- */
	var $container = $('#pins-wrapper');
	
	$container.imagesLoaded( function(){
	  $container.masonry({
	    itemSelector : '.pin'
	  });
	});
	
	$container.infinitescroll({
	      navSelector  : '#page-nav',    // selector for the paged navigation 
	      nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
	      itemSelector : '.pin',     // selector for all items you'll retrieve,
	      animate: false,
	      bufferPx: 150,
	      loading: {
	          finishedMsg: 'No hay más cosas bonitas',
	          img: 'http://i.imgur.com/6RMhx.gif',
	          msgText: "<em>Cargando más cosas bonitas...</em>",
	        }
	      },
	      // trigger Masonry as a callback
	      function( newElements ) {
	        // hide new items while they are loading
	        var $newElems = $( newElements ).css({ opacity: 0 });
	        // ensure that images load before adding to masonry layout
	        $newElems.imagesLoaded(function(){
	          // show elems now they're ready
	          $newElems.animate({ opacity: 1 });
	          $container.masonry( 'appended', $newElems, true ); 
	        });
	      }
	    );
	    
	    

	
});




