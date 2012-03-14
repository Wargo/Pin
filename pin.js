if (document.images.length < 1) {
	alert('No hay imágenes que captar');
} else {
	var max_size = 1;
	f = document.createElement("style");
	f.type = "text/css";
	f.media = "screen";
	f.innerHTML = "body{padding:0px;margin:0px;}._div_title a{color:#CCC;}._div_title{z-index:999999;padding:10px;position:fixed;top:0;background:#333;text-align: center;width:100%;}._all_images{text-align:left;z-index:999999;max-height:92%;overflow-y:scroll;margin:10px;position:fixed;left:0px;top:30px;border:1px solid #666;background:#FFF;}._all_images div.img{position:relative;border:solid 1px #CCC;text-align:center;width:240px;height:230px;float:left;cursor:pointer;}._all_images div span{display:block;}._all_images div.img span.button{color:#444;display:none;background:#F3F3F3;border:solid 1px #D9D9D9;padding:5px 8px;border-radius: 2px 2px 2px 2px;position:absolute;top:100px;left:62px;}._all_images img{border:solid 1px #CCC;margin:5px;max-height:187px;max-width:230px;}._overlay{background-color:#FFF;bottom:0;left:0;opacity:0.9;position:fixed;right:0;top:0;z-index:99999;}";
	document.body.appendChild(f);

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

	d3.innerHTML = '<a href="javascript:void(0);" onclick="document.body.removeChild(f);document.body.removeChild(d);document.body.removeChild(d2);document.body.removeChild(d3);">Cerrar</a>';

	for (x = 0; x < document.images.length; x ++) {
		if (document.images[x].height >= max_size && document.images[x].width >= max_size) {
			html += '<div class="img" onmouseover="select(this, ' + x + ');" onmouseout="unselect(this, ' + x + ');" onclick="go(\'' + document.images[x].src + '\')">';
				html += '<img src="' + document.images[x].src + '" />';
				html += '<span>' + document.images[x].width + 'x' + document.images[x].height + '</span>';
				html += '<span id="_id_' + x + '" class="button">Seleccionar</span>';
			html += '</div>';
		}
	}
	d.innerHTML = html;

	function select(item, x) {
		item.style.borderColor = '#000';
		x = '_id_' + x;
		document.getElementById(x).style.display = 'block';
	}
	
	function unselect(item, x) {
		item.style.borderColor = '#CCC';
		x = '_id_' + x;
		document.getElementById(x).style.display = 'none';
	}

	function go(image) {
		//alert(image);
		window.open('http://wordpress.dev/wp-content/plugins/pin/read.php?from=xxx&image=' + image, 'pick_image', 'toolbar,width=600,height=300,left=200,top=200,scrollbars=yes');
	}

}
