<?php 
/**
 * Loop Comments - Listado de comentarios
 */
?>
<?php global $pin,$user_ID; foreach ( $pin->comments as $comment ) : ?>
		<div class="comment">
			<?php if (is_user_logged_in() && $user_ID == $comment->user_id || current_user_can('manage_options')):?>
			<a comment-id="<?php echo $comment->id ?>" nonce="<?php echo wp_create_nonce( 'pin_delete_comment' )?>" href="#" title="<?php _e('Eliminar comentario', 'pin')?>" class="delete-comment floatRight tipsyHover">X</a>
			<?php endif;?>
			<a class="avatar" href="<?php echo pin_url( 'user', pin_get_user_login( $comment->user_id ))?>">
				<?php echo get_avatar( $comment->user_id , 50 )?>
			</a>
			<p>
				<a href="<?php echo pin_url( 'user', pin_get_user_login( $comment->user_id ))?>"><?php bp_profile_field_data(array('field' => 1, 'user_id' =>  $comment->user_id ))?></a>
				<br><?php echo $comment->content ?></p>
		</div>
<?php endforeach;?>
