<?php
/**
 * 	Single Pin
 * 
 */
	global $pin;
	
?>
<div class="pin">
	<div class="holder">	
		<a class="PinImage ImgLink" href="<?php echo pin_url( 'single', $pin->id) ?>">
			<?php pin_image( 200 );?>
			
		</a>
	</div>

	<p class="name"><?php echo $pin->name ?></p>
	
	<?php pin_metas( $pin ) ?>
	
	<div class="author-box">
		<a href="<?php echo pin_url( 'user', $pin->user_name) ?>" class="author-avatar"><?php echo  get_avatar($pin->user_id, 30); ?></a>
		<div class="author-text">
			<a href="<?php echo pin_url( 'user', $pin->user_name) ?>"><?php bp_profile_field_data(array('field' => 1, 'user_id' =>  $pin->user_id))?></a> 
			<?php _e('vía', 'pin')?>
			<a href="<?php echo pin_url( 'via', $pin->via)?>" title="<?php _e('vía', 'pin')?> <?php echo $pin->via?>"><?php echo $pin->via?></a>
			
			<?php if ( ! empty( $pin->board )):?>
				<?php _e('en', 'pin')?>
				<a href="<?php echo pin_url( 'user', $pin->user_name, 1, $pin->board_slug)?>"><?php echo $pin->board_name ?></a>
			<?php endif;?>
	
		</div>
	</div>
	
	<div class="pin-actions">
		<a class="btn btn-small repin" pin-id="<?php echo $pin->id ?>" href="<?php echo PIN_PLUGIN_URL ?>repin.php?id=<?php echo $pin->id ?>"><i class="icon-pin"></i> <?php _e('Qué bonito!', 'pin')?></a>
	<?php if ( pin_current_user_can_edit($pin->id ) ) : ?>
		<a class="btn btn-small" href="<?php echo pin_url( 'single', $pin->id )?>editar" title="<?php _e('Editar', 'pin')?>">&nbsp;<i class="icon-edit"></i>&nbsp;<?php _e('Editar', 'pin')?></a>
	<?php endif;?>
	</div>

</div>
