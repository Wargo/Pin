<?php if ( is_user_logged_in() ) : global $user_ID, $pin;  ?>

<form id="comment-pin-form">

		<?php echo get_avatar( $user_ID, 50 )?>
		<div class="input">
			<textarea placeholder="Añade un comentario..." maxlength="1000" name="caption" id="comment-content"></textarea>
		</div>
		
		<input type="hidden" name="user_id" value="<?php echo $user_ID ?>" id="comment_user_id" />
		<input type="hidden" name="pin_id" value="<?php echo $pin->id ?>" id="comment_pin_id" />
		<input type="hidden" name="user_name" value="<?php bp_profile_field_data(array('field' => 1, 'user_id' =>  $user_ID))?>" id="comment_user_name" />
		<input type="hidden" name="user_url" value="<?php echo pin_url( 'user', $pin->user_name)?>" id="comment_user_url" />
		<input type="hidden" name="user_url" value="<?php echo $pin->id ?>" id="comment_pin_id" />
		 <?php wp_nonce_field( 'add_comment', 'pin_add_comment') ?> 
		<div class="controls">
			<button class="btn" id="comment-submit" disabled >Enviar comentario</button>
		</div>

</form>

<?php else :?>


<?php endif; ?>