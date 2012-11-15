<!-- Bookmarklet ElEmbarazo -->
<div id="boton-quebonito">
         Arrastra este botón a tus favoritos 
         <a class="btn bookmarklet" href="javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','<?php echo PIN_PLUGIN_URL ?>pin.js');document.body.appendChild(e)})());" onclick="alert('Arrastra este botón a tu barra de favoritos'); return false;">Qué bonito! (<?php echo str_replace('http://', '', site_url()) ?>)</a>
        <div id="desplegable-pin-container">
            <div id="desplegable-pin">
                <img src="<?php echo get_bloginfo('template_url');?>/images/desplegable.jpg">
                <p>Ten siempre a mano tu marcador de cosas bonitas.<br/>Arrastra este botón a tu barra de favoritos.</p>
                <div class="info-cosasbonitas"><a href="<?php echo site_url();?>/como-anadir-tu-marcador-de-cosas-bonitas" class="btn btn-info"><i class="icon-info-sign"></i>&nbsp;Más información</a></div>
            </div>
        </div>
</div>