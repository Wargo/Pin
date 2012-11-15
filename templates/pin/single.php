<?php
	global $pin, $pin_categories, $pin_user, $board;
	//dump( $pin_categories );
?>

<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>
	
	<div class="navbar pin_fixed_width single-pin" id="pin-user-nav">
		<div class="navbar-inner">
		
			<div id="BoardMeta">
				<a class="user-avatar" href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>"><?php echo  get_avatar( $pin_user->ID, 30); ?></a>
				<a class="brand" href="<?php echo pin_url( 'user', $pin_user->data->user_login ) ?>"><?php echo  $pin_user->data->display_name ?></a>
			</div>
			
			<?php if ( $board ) : ?>
				<div class="board-head">
					<p><a href="<?php echo pin_url('user', $pin->user_name, 1, $board->slug)?>"><?php echo $board->name ?></a> 
					<span class="pins-count">(<?php echo $board->pin_count?> <?php echo _n('pin', 'pines', $board->pin_count, 'pin')?>)</span></p>
				</div>
			<?php endif;?>

		</div>
	</div>
	
	<div id="single-pin" class="clearfix">
		
		<div class="main white_and_shadow">
			<div  id="pin-image-holder">
				<a href="<?php echo $pin->referer ?>" target="_blank" rel="nofollow" class="referer">
				
					<?php pin_image();?>
				
					<i class="icon-share-alt icon-white"></i>
				</a>
			</div>
				
		</div>
		<?php dump($pin->url);?>
		<div id="singe-pin-side" class="white_and_shadow">
		
			<div class="author-box">
				<a href="<?php echo pin_url( 'user', $pin->user_name) ?>" class="author-avatar"><?php echo  get_avatar($pin->user_id, 50); ?></a>
				<div class="author-text">
					<a href="<?php echo pin_url( 'user', $pin->user_name) ?>" class="author-name"><?php bp_profile_field_data(array('field' => 1, 'user_id' =>  $pin->user_id))?></a> <?php _e('vía', 'pin')?>
					<a href="<?php echo pin_url( 'via', $pin->via)?>" class="pin-via"><?php echo $pin->via?></a> 
					<?php if (! empty( $board )) : ?>
						<?php _e('en', 'pin')?> <a href="<?php echo pin_url('user', $pin->user_name, 1, $board->slug)?>"><?php echo $board->name ?></a>
					<?php endif;?>
					
				</div>
			</div>
			
			<p class="pin-decription"><?php echo $pin->name ?></p>	
			
			<div class="pin-actions clearfix">
				<a class="btn btn-small repin" pin-id="<?php echo $pin->id ?>" href="<?php echo PIN_PLUGIN_URL ?>repin.php?id=<?php echo $pin->id ?>"><i class="icon-pin"></i> <?php _e('Qué bonito!', 'pin')?></a>
				<a class="btn btn-small share-button" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php echo pin_url('single', $pin->id)?>','window','width=550,height=400'); return false;" href="#facebook"><i class="facebook"></i> Facebook</a>
				<a class="btn btn-small share-button" onclick="window.open('https://twitter.com/share?url=<?php echo pin_url('single', $pin->id)?>&amp;text=<?php echo urlencode($pin->name) ?>&amp;via=<?php echo get_option('pin_twitter');?>','window','width=550,height=400'); return false;" href="#twitter"><i class="twitter"></i> Twitter</a>
				<!--<a class="btn btn-small" onclick="window.open('https://plusone.google.com/_/+1/confirm?hl=es&amp;url=<?php echo pin_url('pin', $pin->id)?>','window','width=450,height=430')" href="javascript: return false;">Google +1</a>-->
				
			<?php if ( pin_current_user_can_edit($pin->id ) ) : ?>
				<a class="btn btn-small" href="<?php echo pin_url( 'single', $pin->id )?>editar" title="<?php _e('Editar', 'pin')?>">&nbsp;<i class="icon-edit"></i>&nbsp;<?php _e('Editar', 'pin')?></a>
			<?php endif;?>

			</div>
			
			<div id="pin-comments">
				<?php if ( ! empty( $pin->comments )) pin_locate_template('loop-comments.php') ?>
			</div>
			
			<?php pin_locate_template('comment-form.php')?>
			
		</div>
	
	</div>
	
	


</div>

<?php get_footer('foro'); ?>
	