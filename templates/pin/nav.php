<?php 

	$pin_section = get_query_var('pinsection');
	$pin_id = get_query_var('pinid');
	$pageid = get_option('pin_post_id');

?>
<div id="pin-main-menu" class="white_and_shadow">
	<a href="<?php echo pin_url() ?>" id="pin-logo" title="<?php echo get_the_title( $pageid ) ?>"><?php echo get_the_title( $pageid ) ?></a>
	
		
	<a title="¿Qué es Cosas Bonitas?" id="info-popover" data-content="¡Enhorabuena! Has aterrizado en una de las páginas más útiles de nuestra web.<br /><br />Guarda tus fotos favoritas en tablones, como si fueran álbumes de fotos. ¡Es muy fácil! Solo tienes que instalarte el botón “Qué bonito!” (encontrarás instrucciones a tu derecha) y, conforme vayas navegando por Internet, guardar las fotos que más te gusten. <br /><br />Después, podrás recuperarlas, editarlas, organizarlas y ver las fotos de tus amigos, ¡sencillo!" rel="popover" class="" href="#" data-original-title="¿Qué es Cosas Bonitas?"><i class="icon-question-sign" title="¿Qué es Cosas Bonitas?"></i></a>


	
	<ul id="pin-main-nav">
		<li class="nav-item">
			<a class="tipsyHoverBottom <?php if ( empty($pin_section) ) echo 'active'?>" href="<?php echo pin_url() ?>" title="<?php _e('Explora todos los pins de los usuarios', 'pin')?>"><i class="icon-home"></i> <?php _e('HOME', 'pin')?></a>
		</li>
		<li class="nav-item">
			<a class="tipsyHoverBottom <?php if ( $pin_section == PIN_CATEGORIES_SLUG ) echo 'active'?>" href="<?php echo pin_url('categories') ?>" title="<?php _e('Descubre cosas bonitas a través de las categorías principales', 'pin')?>"><i class="icon-folder-open"></i> <?php _e('CATEGORÍAS', 'pin')?></a>
		</li>
		<? /*
		<li class="nav-item">
			<a class="tipsyHoverBottom <?php if ( $pin_section === PIN_USERS_SLUG ) echo 'active'?>" href="<?php echo pin_url() . PIN_USERS_SLUG ?>" title="<?php _e('Directorio de usuarios', 'pin')?>"><i class="icon-home"></i> <?php _e('USUARIOS', 'pin')?></a>
		</li>
		*/ ?>
		<?php if ( is_user_logged_in() ) : $current_user = wp_get_current_user(); ?>
		<li class="nav-item">
			<a class="tipsyHoverBottom <?php if ( $pin_section == PIN_USER_SLUG && $current_user->user_login == $pin_id ) echo 'active'?>" href="<?php echo pin_url('user', $current_user->user_login ) ?>" title="<?php _e('Tus pins y los pins de los tableros que sigues', 'pin')?>"><i class="icon-user"></i> <?php _e('MI MURO', 'pin')?></a>
		</li>
		<?php endif; ?>
		
		
	</ul>
	<?php pin_locate_template( 'bookmarklet.php' );?>
</div>

<div id="pin-template-notices"><?php do_action( 'pin_template_notices' ) ?></div>