if (document.images.length < 1) {
	alert('No hay imágenes que captar');
} else {
	var via = top.location.host; // Web from user gets this bookmark
	via = via.replace("www.", "");	
	var referer = window.location;
	var max_size = 200; // Max size of images that will be displayed
	
	
	var title = document.getElementsByTagName('title');
	title = title[0];
	
	var title_text = title.innerHTML;
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
	//console.log(title);
	
	/*
	f = document.createElement("div");
	//f.type = "text/css";
	//f.media = "screen";
	f.innerHTML = "body{padding:0px;margin:0px;}._div_title a{color:#CCC;}._div_title{z-index:999999;padding:10px;position:fixed;top:0;background:#333;text-align: center;width:100%;}._all_images{text-align:left;z-index:999999;max-height:92%;overflow-y:scroll;margin:10px;position:fixed;left:0px;top:30px;border:1px solid #666;background:#FFF;}._all_images div._img{position:relative;border:solid 1px #CCC;text-align:center;width:240px;height:230px;float:left;cursor:pointer;}._all_images div span{display:block;}._all_images div._img span.button{color:#444;display:none;background:#F3F3F3;border:solid 1px #D9D9D9;padding:5px 8px;border-radius: 2px 2px 2px 2px;position:absolute;top:100px;left:62px;}._all_images img{border:solid 1px #CCC;margin:5px;max-height:187px;max-width:230px;}._overlay{background-color:#FFF;bottom:0;left:0;opacity:0.9;position:fixed;right:0;top:0;z-index:99999;}";
	//var newStyle = document.createTextNode('body { background-color: yellow; }');
	//f.appendChild(newStyle);
	document.body.appendChild(f);
	*/
	
	
	
	/* Nueva manera de insertar el CSS */
	
	var css =  "body{padding:0px;margin:0px;}._div_title a{color:#CCC;}._div_title{z-index:999999;padding:10px;position:fixed;top:0;background:#333;text-align: center;width:100%;}._all_images{text-align:left;z-index:999999;max-height:92%;overflow-y:scroll;margin:10px;position:fixed;left:0px;top:30px;border:1px solid #666;background:#FFF;}._all_images div._img{position:relative;border:solid 1px #CCC;text-align:center;width:240px;height:230px;float:left;cursor:pointer;}._all_images div span{display:block;}._all_images div._img span.button{color:#444;display:none;background:#F3F3F3;border:solid 1px #D9D9D9;padding:5px 8px;border-radius: 2px 2px 2px 2px;position:absolute;top:100px;left:62px;}._all_images img{border:solid 1px #CCC;margin:5px;max-height:187px;max-width:230px;}._overlay{background-color:#FFF;bottom:0;left:0;opacity:0.9;position:fixed;right:0;top:0;z-index:99999;}";
	
	var head = document.getElementsByTagName('head')[0];
	var style = document.createElement('style');
	
	style.type = 'text/css';
	if(style.styleSheet){
	    style.styleSheet.cssText = css;
	}else{
	    style.appendChild(document.createTextNode(css));
	}
	head.appendChild(style);

	var html = '';

	var d = document.createElement("div");
	d.setAttribute("class", "_all_images")
	document.body.appendChild(d);

	var d2 = document.createElement("div");
	d2.setAttribute("class", "_overlay")
	document.body.appendChild(d2);

	var d3 = document.createElement("div");
	d3.setAttribute("class", "_div_title");
	document.body.appendChild(d3);

	d3.innerHTML = '<a href="#cerrar" onclick="close_all(); return false;">Cerrar</a>';

	
	//var ilist = document.images;
	var ilist = new Array();
	
	for (x = 0; x < document.images.length; x ++) { 
		ilist.push( document.images[x] );
	}

	var a = 0;
	var b = 0;
	for (y = 0; y < ilist.length; y ++) { 
	
		if ( (typeof ilist[y].parentNode.href != 'undefined') && checkImageURL( ilist[y].parentNode.href )) {
			a ++;
			checkImage ( ilist[y].parentNode.href, y );
		}
		
	}
	if ( a == 0 ){
		pin_add_images();
	}
}
	function select(item, x) {
		item.style.borderColor = '#000';
		x = '_id_' + x;
		document.getElementById(x).style.display = 'block';
	}
	
	function close_all () {
		head.removeChild(style);
		//document.body.removeChild(f);
		document.body.removeChild(d);
		document.body.removeChild(d2);
		document.body.removeChild(d3);
	}
	
	function unselect(item, x) {
		item.style.borderColor = '#CCC';
		x = '_id_' + x;
		document.getElementById(x).style.display = 'none';
	}

	function go(image) {
	
		//window.open('http://arques/publi/wp-content/plugins/pin/read.php?via=' + via + '&title=' + title + '&image=' + image + '&referer=' + referer, 'pick_image', 'toolbar=no,width=600,height=400,left=200,top=200,scrollbars=yes,resizable=no');
	
		window.open('http://elembarazo.net/wp-content/plugins/pin/read.php?via=' + via + '&title=' + title + '&image=' + image + '&referer=' + referer, 'pick_image', 'toolbar=no,width=600,height=320,left=200,top=200,scrollbars=yes,resizable=no');
		//window.open('http://cuidadoinfantil.net/wp-content/plugins/pin/read.php?via=' + via + '&title=' + title + '&image=' + image + '&referer=' + referer, 'pick_image', 'toolbar=no,width=600,height=320,left=200,top=200,scrollbars=yes,resizable=no');
		
		close_all();
	}
	
	function checkImageURL(url) {
    	return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
	}
	
	function checkImage(src, x) {
		b ++;
		var img = new Image();
		
		img.onload = function () {
		
			ilist[x] =  img ;
			
			if (a == b) {
				//console.log( 'ulti' );
				//console.log( a + " " + b);
				pin_add_images();
			}
			//console.log( img );
			
		};
		
		img.src = src; // fires off loading of image
	}
	function pin_add_images () {
		for (z = 0; z < ilist.length; z ++) {
	 		
			if ( ilist[z].height >= max_size && ilist[z].width >= max_size) {
				addImage ( ilist[z], z);
	
			}
			
		}
		d.innerHTML = html;
	}
	function addImage ( img, x) {
		html += '<div class="_img" onmouseover="select(this, ' + x + ');" onmouseout="unselect(this, ' + x + ');" onclick="go(\'' + img.src + '\')">';
			html += '<img src="' + img.src + '" />';
			html += '<span>' + img.width + 'x' + img.height + '</span>';
			html += '<span id="_id_' + x + '" class="button">Seleccionar</span>';
		html += '</div>';
	}