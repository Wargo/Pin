/*
* 
*/

var via = top.location.host; // Web from user gets this bookmark
via = via.replace("www.", "");	
var referer = window.location;
var max_size = 200; // Max size of images that will be displayed

var title = jQuery('title');
var title_text = title.text();
var subtitle = "";

if ( title_text.indexOf(" - ") != -1) {
	subtitle = title_text.split(" - ");
	title = subtitle[0];
} else if ( title_text.indexOf(" | ") != -1){
	subtitle = title_text.split(" | ");
	title = subtitle[0];
} else {
	title = title_text;
}


function pin_this( image_url ) {
	window.open( pin_plugin_url + '/read.php?via=' + via + '&image=' + image_url + '&referer=' + referer + '&title=' + title, 'pick_image', 'toolbar=no,width=600,height=320,left=200,top=200,scrollbars=yes,resizable=no');
}

	function pin_check_image_url (url) {
    	return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
	}
	
	function pin_check_bigger_image( image, src ) {
		var img = new Image();
		
		img.onload = function () {	
			if ( img.height > max_size || img.width > max_size ) {
				pin_insert_button ( image, src );
			}
			
		};
		
		img.src = src; // fires off loading of image
	}
	
	function pin_insert_button ( img, src) {
		
		img_class = jQuery(img).attr("class");
		
		if ( ! jQuery(img).hasClass('no-pin') ) {
			jQuery( img ).wrap('<span class="pin_image_wrapper '+img_class+'" style="position: relative;"></span>').parent().append('<a style="position: absolute; right: 20px; bottom: 15px; text-decoration: none" class="btn pin-btn" href="javascript: pin_this (\'' + src + '\');"><i class="icon" style="background: url(' + pin_plugin_url + '/inc/img/sprite.png) 0px -33px no-repeat;float: left;width: 24px;height: 20px;"></i> Qué bonito!</a>');		
		}

		
	
	}

jQuery(document).ready(function($) {

		var images = $(".post img");
		
		if ( images.length > 0 ) {
			$.each( images, function () {
				//console.log ( this );
				
			if ( (typeof this.parentNode.href != 'undefined') && pin_check_image_url( this.parentNode.href )) {
				pin_check_bigger_image ( this, this.parentNode.href );
			} else {
				if ( (this.height > max_size || this.width > max_size) && ( $(this).css("display") != "none" ) ) {
					pin_insert_button ( this, this.src );
				}
			}
				
			});
			
		}
});