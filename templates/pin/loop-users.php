<?php

global $users, $wp_pin;


//dump($users);

if ( empty( $users)) {
?>
	<div class="alert alert-block alert-error fade in">
	<h4 class="alert-heading">Nada por aquí</h4>
	<p>Lo siento pero no hemos encontrado ningún seguidor</p>
	</div>
<?php
} else {

	foreach ( $users as $user ) {
	
		
	?>
	
		<div class="pin pin-category pin-user">

   	
        	<div class="board">
            
  	        
            	<div class="holder">

                    <span class="cover">
                    	
        				<h3><a href="<?php echo pin_url( 'user', $user->user_login )?>" title="<?php echo $user->display_name ?>"><?php echo $user->display_name ?></a></h3>
        				<a href="<?php echo pin_url( 'user', $user->user_login )?>" title="<?php echo $user->display_name ?>"><?php echo get_avatar( $user->ID, 82)?></a>
        				
        				<p>
        					<span class="followers"><a href="<?php echo pin_url( 'user', $user->user_login, 1, PIN_FOLLOWERS_SLUG)?>" title="Seguidores de <?php echo $user->display_name ?>"><?php echo pin_count_followers($user->id)?>&nbsp;<?php echo _n('seguidor', 'seguidores', pin_count_followers($user->id), 'pin')?></a></span>·
        					<span class="pins"><a href="<?php echo pin_url( 'user', $user->user_login, 1, 'pins')?>" title="Pines de <?php echo $user->display_name ?>"><?php echo pin_count_pins($user->id) ?>&nbsp;<?php echo _n('pin', 'pines', pin_count_pins($user->id), 'pin')?></a></span>·
        					<span class="boards"><a href="<?php echo pin_url( 'user', $user->user_login, 1, PIN_BOARDS_SLUG)?>" title="Tableros de <?php echo $user->display_name ?>"><?php echo pin_count_boards($user->id) ?>&nbsp;<?php echo _n('tablero', 'tableros', pin_count_boards($user->id), 'pin')?></a></span>
        				</p>
        				
                    </span>
                    
                    <span class="thumbs">
                    	<?php if (! empty( $user->pins )): foreach ( $user->pins as $img_src) : ?>
                    		<img src="<?php echo thumbGen($img_src, 55, 55, "background=transparent") ?>" width="75" height="75" alt="<?php echo $user->display_name ?> thumb">
                        <?php endforeach; endif;?>
                    </span>
            	</div>
            	<?php if ( is_user_logged_in() ):?>
				<div class="board-actions">
					<?php if ( pin_im_following_user( $user->id )):?>
						<a class="btn btn-large btn-block follow-user-button active" data-text-unfollow="<?php _e('Dejar de seguir', 'pin'); ?>"  data-user-id="<?php echo $user->id ?>" data-text-follow="<?php _e('Seguir', 'pin'); ?>" href="<?php echo pin_url( 'user', $user->user_login) . PIN_UNFOLLOW_SLUG ?>"><?php _e("Dejar de seguir sus tableros", 'pin')?></a>
					<?php else :?>
						<a class="btn btn-large btn-block follow-user-button" data-text-unfollow="<?php _e('Dejar de seguir', 'pin'); ?>" data-user-id="<?php echo $user->id ?>" data-text-follow="<?php _e('Seguir', 'pin'); ?>" href="<?php echo pin_url( 'user', $user->user_login) . PIN_FOLLOW_SLUG ?>"><?php _e("Seguir sus tableros", 'pin')?></a>					
					<?php endif;?>
		        </div>
		        <?php endif; ?>
            	
	            
        </div>
    </div>
	
	<?php
	
	
	}
}