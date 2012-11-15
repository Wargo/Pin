jQuery(document).ready(function($) {

	/* GENERAL
	--------------------------------------------------------------------------------------- */

	// Loading ...
	$('input[type="submit"], #pin-categories-nav li a, #pin-main-nav li.nav-item a, .pin-category h3 a, .addLoading').click( function () { $(this).addClass('loading')});
	
	// Requiere el tooltip de Bootstrap
	//if(typeof tooltip == 'function') {
		// Tipsy tooltip
		$('.tipsyHover').tooltip();
		$('.tipsyHoverBottom').tooltip( { placement: 'bottom' } );
	//}
	
	$("#info-popover").popover( { placement: 'bottom'});
	
		
	/* REPIN
	--------------------------------------------------------------------------------------- */
	
	$(".repin").live ( 'click', function () {
		window.open( this.getAttribute("href"), 'pick_image', 'toolbar=no,width=600,height=400,left=200,top=200,scrollbars=yes,resizable=no'); 
		return false;
	});
	

});