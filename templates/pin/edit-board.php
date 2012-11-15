<?php global $board; ?>

<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>
	
	<div id="single-pin" class="edit white_and_shadow clearfix">
	
		<div class="form_holder">
			<form class="form-horizontal" id="form-edit-pin" method="POST">
			    <fieldset>
			    	<div class="clearfix">
			    		<h3 class="left"><?php _e('Editar tablero', 'pin')?></h3>
			    		<a href="#" onclick="delete_board(); return false;" confirm="<? _e('¿Seguro que quieres eliminar el tablero y todos los pines de dentro?', 'pin') ?>" error="<? _e('Ha habido un error', 'pin') ?>" class="btn btn-danger right delete-board" board-id="<?php echo $board->id ?>"><?php _e('Eliminar tablero')?></a>
			    	</div>
			      
			      <div class="control-group">
			        <label for="board-name" class="control-label"><?php _e('Nombre', 'pin')?></label>
			        <div class="controls">
			        	<input type="text" name="board-name" id="board-name" value="<?php echo $board->name ?>" />
			        </div>
			      </div>
			      
			      <div class="control-group">
			        <label for="board-category" class="control-label"><?php _e('Categoría', 'pin')?></label>
			        <div class="controls">
			        	<?php pin_dropdown_categories( $board->category, 'board-category');?>
			        </div>
			      </div>
			      
			      <div class="control-group">
			        <label for="board-description" class="control-label"><?php _e('Descripción', 'pin')?></label>
			        <div class="controls">
			          <textarea rows="3" id="board-description" class="input-xlarge" name="board-description"><?php echo $board->description ?></textarea>
			        </div>
			      </div>
			      
			      <div class="form-actions">
			        <button class="btn btn-primary addLoading" type="submit"><?php _e('Guardar', 'pin')?></button>
			        <a class="btn" href="<?php echo pin_url( 'user', $pin_user->user_login, 1, $board->slug )?>"><?php _e('Cancelar', 'pin')?></a>
			      </div>
			      
			      <?php  wp_nonce_field('edit-board')?>
			      <input type="hidden" name="board-id" value="<?php echo $board->id ?>" />
			      <input type="hidden" name="action" value="edit-board" />
			      
			    </fieldset>
			</form>
		</div>
		<p class="clear"></p>
		<div class="btn-toolbar"><a href="javascript: window.history.back();" class="btn"><i class="icon-chevron-left"></i> Volver</a></div>
	
	</div>

</div>

<?php get_footer('foro'); ?>