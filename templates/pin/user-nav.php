<?
	global $pin_user;
	//dump($pin_user);
	
	//dump($pin_user->ID);
?>

<div class="navbar pin_fixed_width" id="pin-user-nav">
	<div class="navbar-inner">
	
		<a class="user-avatar" href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>"><?php echo  get_avatar( $pin_user->ID, 30); ?></a>
		<a class="brand" href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>"><?php echo  $pin_user->data->display_name ?></a>
		
		<ul class="nav">
			<?php if ( pin_is_my_profile()): ?>
			<li class="<?php if ( ! get_query_var('pinsubsection')) echo 'active'?>">
				<a href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>" class="addLoading" title="<?php echo __('Tus pins y los pins que sigues')?>"><?php _e('Mi home', 'pin')?></a></li>
			<?php endif; ?>
    		<li class="<?php if ( get_query_var('pinsubsection') === PIN_BOARDS_SLUG ) echo 'active'?>">
    			<a href="<?php echo pin_url( 'user', $pin_user->data->user_login ) . PIN_BOARDS_SLUG ?>" class="addLoading"><?php _e('Tableros', 'pin')?> (<?php $pin_count_boards =  pin_count_boards( $pin_user->ID ); echo $pin_count_boards?>)</a></li>
    		<li class="<?php if ( get_query_var('pinsubsection') === 'pins') echo 'active'?>">
    			<a href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>pins" class="addLoading"><?php _e('Pins', 'pin')?> (<?php echo pin_count_pins( $pin_user->ID )?>)</a></li>
    		<li class="<?php if ( get_query_var('pinsubsection') === PIN_FOLLOWING_SLUG ) echo 'active'?>">
    			<a href="<?php echo pin_url( 'user', $pin_user->data->user_login ) . PIN_FOLLOWING_SLUG?>" class="addLoading"><?php _e('Siguiendo', 'pin')?> (<?php $pin_count_following =  pin_count_following( $pin_user->ID ); echo $pin_count_following ?> <?php echo _n( 'tablero', 'tableros', $pin_count_following, 'pin')?>)</a></li>
    		<li class="<?php if ( get_query_var('pinsubsection') === PIN_FOLLOWERS_SLUG ) echo 'active'?>">
    			<a href="<?php echo pin_url( 'user', $pin_user->data->user_login ) . PIN_FOLLOWERS_SLUG?>" class="addLoading"><?php _e('Seguidores', 'pin')?> (<?php echo pin_count_followers( $pin_user->ID )?>)</a></li>
    		<?php if ( pin_is_my_profile()): ?>
    		<li><a href="#new-board" role="button" data-toggle="modal" class="new-board"><i class="icon icon-plus-sign"></i>&nbsp;<?php _e('Nuevo tablero', 'pin')?></a></li>
    		<?php endif; ?>
		</ul>
		<?php if ( is_user_logged_in() ): ?>
			<?php if (! pin_is_my_profile()): ?>
					<?php if ( pin_im_following_user( $pin_user->id )):?>
						<a class="btn follow-user-button active" data-text-unfollow="<?php _e("Dejar de seguir sus tableros", 'pin')?>"  data-user-id="<?php echo $pin_user->id ?>" data-text-follow="<?php _e("Seguir sus tableros", 'pin')?>" href="<?php echo pin_url( 'user', $pin_user->user_login) . PIN_UNFOLLOW_SLUG ?>"><?php _e("Dejar de seguir sus tableros", 'pin')?></a>
					<?php else :?>
						<a class="btn follow-user-button" data-text-unfollow="<?php _e("Dejar de seguir sus tableros", 'pin')?>" data-user-id="<?php echo $pin_user->id ?>" data-text-follow="<?php _e("Seguir sus tableros", 'pin')?>" href="<?php echo pin_url( 'user', $pin_user->user_login) . PIN_FOLLOW_SLUG ?>"><?php _e("Seguir sus tableros", 'pin')?></a>					
					<?php endif;?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>

   
