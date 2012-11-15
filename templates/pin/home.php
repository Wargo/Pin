<?php
/**
 * Pin - Home (all pins)
 *
 */
?>

<?php pin_locate_template( 'header.php' ) ?>


<div id="pin-page-wrapper" class="clearfix">

	<?php pin_locate_template('nav.php') ?>
	
	<div id="pins-wrapper"><div class="content">
		<?php pin_locate_template('loop.php');?>
	</div></div>
	
</div>

<?php get_footer('foro'); ?>


<script type="text/javascript">

	jQuery(document).ready(function($) {
	
		var html = '<div id="concurso-loader"><img src="http://i.imgur.com/6RMhx.gif" alt="Loading..."><div><em>Tenemos una sorpresa para tí!</em></div></div><div class="white_and_shadow hide" id="concurso" style="display: none;"><div class="content"><img src="<?php echo PIN_PLUGIN_URL ?>/inc/img/<?php if ( site_url() == 'http://elembarazo.net') echo 'concurso-elembarazo.jpg'; else echo 'concurso-cuidadoinfantil.jpg'?>" id="concurso-pin" width="550" height="773"  alt="¿Quieres conseguir un ipad?"/><div class="info"><h1>Participa en nuestro sorteo</h1><a href="<?php echo PIN_PLUGIN_URL ?>/read.php?title=<?php echo urlencode('¿Quieres conseguir un ipad?')?>&image=<?php echo PIN_PLUGIN_URL ?>/inc/img/cartel_sinfondo.jpg&referer=<?php echo pin_url('home')?>" class="btn btn-large repin"><i class="icon-pin"></i>&nbsp;&nbsp;Qué bonito!</a><div class="bases"><p><a href="<?php echo site_url();?>/como-participar-en-el-sorteo-de-cosas-bonitas" target="_blank">¿Cómo participar en el sorteo?</a></p><p><a href="<?php echo site_url()?>/bases-legales-sorteo-cosas-bonitas" target="_blank">Bases legales</a></p></div></div></div><button data-dismiss="alert" class="close" onclick="close_concurso();" type="button">×</button></div><!--END CONCURSO-->';	    
		$("#pin-template-notices").append(html);
		
		setTimeout(function(){$("#concurso-loader").fadeOut(); $("#concurso").slideDown('1000');},1500);
		
		
	});
	
	function close_concurso () {
		
		jQuery("#concurso").slideUp(1000);
		
	}

</script>

