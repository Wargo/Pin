<?php
/**
 * Pin - User boards
 */
	global $boards, $pin_user;
	
	if ( empty( $boards)) {
	?>
	
	<div class="alert alert-block alert-error fade in">
	<h4 class="alert-heading">Nada por aquí</h4>
	<p>Lo siento pero no hemos encontrado ningún tablero</p>
	</div>
	<?php
	
	} else {
	foreach ($boards as $board) { 

	?>
	
		<div class="pin pin-category pin-board">

		
        	<h3 class="segoe">

        		<a class="tipsyHover" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug)?>" title="<?php echo $board->description ?>"><?php echo $board->name ?><br />
        		<span class="count"><?php echo $board->pin_count . ' ' . _n('pin', 'pines', $board->pin_count, 'pin'); ?>&nbsp;•&nbsp;</span>
        		<span class="followers"><?php echo $board->followers ?> <?php echo _n('seguidor', 'seguidores', $board->followers, 'pin'); ?></span>
        		

        		</a>
        		
        	</h3>
        	
        	<?php if ( ! pin_is_my_board( $board )):?>	
    			<?php $board_user = get_userdata( $board->user_id );?>
				<a href="<?php echo pin_url( 'user', $board_user->data->user_login ) ?>" class="btn btn-block board-author">
					<?php echo  get_avatar($board->user_id, 18); ?>
					<?php echo $board_user->data->display_name ?>
				</a>
        	<?php endif; ?>
        	
        	<div class="board">
            
    	        <a class="link" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug)?>">&nbsp;</a>
    	        
            	<div class="holder">

                    <span class="cover">
                    	<?php if (! empty($board->pins[0])): ?>
                        <img src="<?php echo thumbGen($board->pins[0], 222, 150, "background=#FFFFFF") ?>" width="222" height="150" alt="<?php echo $board->name ?> cover">
                        <?php endif;?>
                    </span>
                    
                    <span class="thumbs">
                    	<?php if (! empty($board->pins[0])): array_shift($board->pins ); endif; if (! empty( $board->pins )): foreach ( $board->pins as $img_src) : ?>
                    		<img src="<?php echo thumbGen($img_src, 55, 55, "background=transparent") ?>" width="75" height="75" alt="<?php echo $board->name ?> thumb">
                        <?php endforeach; endif;?>
                    </span>
            	</div>
            	<?php if (is_user_logged_in()): ?>
				<div class="board-actions">
					<?php if ( pin_is_my_board( $board )):?>
						<a class="btn btn-large btn-block addLoading" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug) . PIN_EDIT_SLUG?>"><?php _e('Editar tablero', 'pin')?></a>
					<?php else :?>
					
						<?php if ( pin_im_following_board( $board->id)):?>
							<a class="btn btn-large btn-block follow-button active" data-text-unfollow="<?php _e('Dejar de seguir', 'pin'); ?>" data-board-id="<?php echo $board->id ?>" data-board-author-id="<?php echo $board->user_id ?>" data-text-follow="<?php _e('Seguir', 'pin'); ?>" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug) . PIN_UNFOLLOW_SLUG ?>"><?php _e("Dejar de seguir", 'pin')?></a>					
						<?php else :?>
							<a class="btn btn-large btn-block follow-button" data-text-unfollow="<?php _e('Dejar de seguir', 'pin'); ?>" data-board-id="<?php echo $board->id ?>" data-board-author-id="<?php echo $board->user_id ?>" data-text-follow="<?php _e('Seguir', 'pin'); ?>" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug) . PIN_FOLLOW_SLUG ?>"><?php _e("Seguir", 'pin')?></a>					
						<?php endif;?>
						
					<?php endif;?>
		        </div>
		        <?php endif; ?>
	            
        </div>
    </div>
		
	<?php } }?>