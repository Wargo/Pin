<?php
require_once '../../../wp-load.php';
$wp_pin = new Pin();
global $user_ID;

$pin = $wp_pin->get_pin( $_REQUEST['pin_id'] );



if ( is_user_logged_in()) {
	
	if ( empty($_REQUEST['pin_id'])) {
		die(__('Hubo un error enviando los datos', 'pin'));
	}
?>	
<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
	<head>
		<?php wp_head();?>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Wordpress &rsaquo; Pin</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script type="text/javascript">
			
			function close_and_go ( url ) {
			
				//window.opener.location = url;
				window.parent.open( url, '_blank');
    			window.close();
				return false;
				
			}
		</script>
	</head>
	<body>
		<div class="saved-pin">
			<div class="logo">
				<?php echo $wp_pin->logo(); ?>
			</div>
			<div class="alert alert-success">
				<strong><?php _e('¡Bien hecho!')?></strong>&nbsp;<?php _e('Tu pin ha sido publicado correctamente', 'pin')?>
			</div>
			<p><?php _e('¿Qué quieres hacer ahora?', 'pin')?></p>
			<p>
				<a href="#" onclick="close_and_go('<?php echo pin_url( 'single' , $pin->id )?>')" class="btn btn-success btn-large" style="padding: 9px 24px;"><?php _e('Ver mi pin', 'pin')?></a>
				<!--<li><a href="#" class="btn" onclick="close_and_go('<?php echo pin_url( 'user' , pin_get_user_login($user_ID) )?>')"><b><?php _e('Ver todos mis pins', 'pin')?></b></a></li>-->
				<a class="btn btn-large" href="http://www.facebook.com/sharer.php?u=<?php echo pin_url('single', $pin->id)?>" href="#facebook"><i class="icon-facebook"></i> Compártelo en Facebook</a>
				<a class="btn btn-large" href="https://twitter.com/share?url=<?php echo pin_url('single', $pin->id)?>&amp;text=<?php echo urlencode($pin->name) ?>&amp;via=<?php echo get_option('pin_twitter');?>" href="#twitter"><i class="icon-twitter"></i> Twitea tu pin</a>
			</p>
		</div>	
	</body>
</html>
<?php
} else {
	wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
}