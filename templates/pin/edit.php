<?php global $pin,$wp_pin;?>

<?php pin_locate_template( 'header.php' ) ?>

<div id="pin-page-wrapper" class="clearfix">
	
	<?php pin_locate_template('nav.php') ?>
	
	<div id="single-pin" class="edit white_and_shadow clearfix">
	
		<div class="img_holder">	
			<?php pin_image(200);?>
		</div>
		<div class="form_holder">
			<form class="form-horizontal" id="form-edit-pin" method="POST">
			    <fieldset>
			    	<div class="clearfix">
			    		<h3 class="left"><?php _e('Editar', 'pin')?></h3>
			    		<a href="#" onclick="delete_pin();" confirm="<? _e('¿Seguro que quieres eliminarlo?', 'pin') ?>" error="<? _e('Ha habido un error', 'pin') ?>" class="btn btn-danger right delete-pin" pin-id="<?php echo $pin->id ?>"><?php _e('Eliminar')?></a>
			    	</div>
			      
			      <div class="control-group">
			        <label for="pin_description" class="control-label"><?php _e('Describe tu pin', 'pin')?></label>
			        <div class="controls">
			          <textarea rows="3" id="pin_description" class="input-xlarge" name="pin_description"><?php echo $pin->name ?></textarea>
			        </div>
			      </div>
			      
			      <?php $wp_pin->board_selector()?>

			      <div class="form-actions">
			        <button class="btn btn-primary" type="submit"><?php _e('Guardar', 'pin')?></button>
			        <a class="btn" href="<?php echo pin_url( 'single', $pin->id )?>"><?php _e('Cancelar', 'pin')?></a>
			      </div>
			      
			      <?php  wp_nonce_field('edit-pin')?>
			      <input type="hidden" name="pin_id" value="<?php echo $pin->id ?>" />
			      
			    </fieldset>
			</form>
		</div>
		<p class="clear"></p>
		<div class="btn-toolbar"><a href="javascript: window.history.back();" class="btn"><i class="icon-chevron-left"></i> Volver</a></div>
	
	</div>
</div>

<?php pin_new_board_modal( 'new-board', $pin->id );?>
<?php get_footer('foro'); ?>