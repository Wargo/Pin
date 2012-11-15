<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>

	<div class="navbar pin_fixed_width" id="pin-user-nav">
		<div class="navbar-inner">
		
			<div class="board-head">
				<h1><?php echo $board->name ?></h1>
				<?php if (! empty($board->description) )  echo '<p>' . $board->description . '</p>'?>	
			</div>
			
			
			<div id="BoardMeta">
				<div id="BoardUsers">
					<a  href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>"><?php echo  get_avatar( $pin_user->ID, 30); ?></a>
					<a class="user-name"  href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>"><?php echo  $pin_user->data->display_name ?></a>
				</div>
			
				<div id="BoardStats">
					<strong><?php echo $board->followers ?></strong> <span>seguidores</span>,
					<strong><?php echo $board->pin_count ?></strong> pines
				</div>
			
				<div id="BoardButton">

				<?php if ( pin_is_my_profile()):?>
					<a class="btn btn-large btn-block addLoading" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug) . PIN_EDIT_SLUG?>"><?php _e('Editar tablero', 'pin')?></a>
				<?php else :?>
					<?php if ( pin_im_following_board( $board->id)):?>
						<a class="btn btn-large btn-block follow-button active" data-text-unfollow="<?php _e('Dejar de seguir', 'pin'); ?>" data-board-id="<?php echo $board->id ?>" data-board-author-id="<?php echo $board->user_id ?>" data-text-follow="<?php _e('Seguir', 'pin'); ?>" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug) . PIN_UNFOLLOW_SLUG ?>"><?php _e("Dejar de seguir", 'pin')?></a>					
					<?php else :?>
						<a class="btn btn-large btn-block follow-button" data-text-unfollow="<?php _e('Dejar de seguir', 'pin'); ?>" data-board-id="<?php echo $board->id ?>" data-board-author-id="<?php echo $board->user_id ?>" data-text-follow="<?php _e('Seguir', 'pin'); ?>" href="<?php echo pin_url( 'user', pin_get_user_login($board->user_id), 1, $board->slug) . PIN_FOLLOW_SLUG ?>"><?php _e("Seguir", 'pin')?></a>					
					<?php endif;?>
					
				<?php endif;?>

				</div>
			</div>

		</div>
	</div>
	
	
	
	<div id="pins-wrapper">
		
		<?php 
			//dump($board);
			pin_locate_template('loop.php');	
		
		 ?>
	</div>

</div>

<?php get_footer('foro'); ?>
		