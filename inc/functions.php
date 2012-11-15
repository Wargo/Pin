<?php
/**
 *  Required functions
 */


/**
 * Redirects template
 *
 * @param string $url
 */
function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

/**
 * Are we on Pin plugin?
 *
 * @return boolean
 */
function is_pin () {
	return is_page( get_option('pin_post_id') );
}

/**
 * Returns plugin URL sections
 *
 * @param string $section
 * @return string 
 */
function pin_url ( $section = 'home', $item_id = null, $page = 1, $subsection = null) {
	
	global $pin_slugs;

	if (is_multisite()) switch_to_blog(1);
	$pin_base_url = trailingslashit(get_permalink( get_option('pin_post_id') ));
	if (is_multisite()) restore_current_blog();
	
	
	
	if ( empty($section)) {
		$return_url = $pin_base_url;
	} else {
		$return_url = trailingslashit($pin_base_url . $pin_slugs[$section]);
	}

	
	if ( ! empty( $item_id )) {
		$return_url	.= $item_id;
	}
	
	if ( ! empty( $subsection )) {
		$return_url	.= "/$subsection";
	}
	
	if ( $page != 1 && $page > 0 ) {
		$return_url = trailingslashit( $return_url );
		$return_url .= 'page/' . $page;
	}

	return trailingslashit($return_url);

}
/**
 * Returns plugin page name
 *
 */
function pin_page_name () {
	return get_the_title( get_option('pin_post_id') );
}

/**
 * Returns categories array
 *
 * @return array
 */
function pin_categories () {

	global $wpdb, $wp_pin;
	$table_name = $wpdb->base_prefix . $wp_pin->table_name;
	return $wpdb->get_results("SELECT * FROM " . $table_name . "_categories");
}

function pin_get_categories () {

	global $pin_categories, $pin;
	$pin_categories = $pin->get_categories_array();
}

function pin_get_pins ( $args = array() , $limit = 20) {

	global $wp_pin, $pins;
	$pins = $pin->get_pins( $args , $limit );
}

function pin_nav_categories ( $current = null ) { 

?>
	<ul id="pin-categories-nav" class="clearfix">
		
		<li><a <?php if ( empty($current)) echo 'class="active"' ?> href="<?php echo pin_url()?>">Home</a></li>
		<?php 
			global $pin_categories;
			$main_cats = array_slice( $pin_categories, 0, 5);
			//dump($pin_categories);
			
			foreach ( $main_cats as $pin_cat ) {
				//dump($pin_cat);
				$active = '';
				if ( ! empty( $current ) && $pin_cat->slug == $current ) {
					$active = 'active';
				}
				echo '<li><a class="' . $active . '" href="' . pin_url('category', $pin_cat->slug) . '">' . $pin_cat->name . ' <span class="count">(' . $pin_cat->pin_count . ')</span></a></li>';
			}
		
		?>
		<li class="right" id="more-categories"><a href="#" title="<?php _e('Más categorías', 'pin')?>"><i class=" icon-plus-sign icon-white"></i></a>
			<ul class="submenu">
			<?php 
				$sub_cats = array_slice( $pin_categories, 5);
				//dump($pin_categories);
				
				foreach ( $sub_cats as $pin_cat ) {
					//dump($pin_cat);
					$active = '';
					if ( ! empty( $current ) && $pin_cat->slug == $current ) {
						$active = 'active';
					}
					echo '<li><a class="' . $active . '" href="' . pin_url('category', $pin_cat->slug) . '">' . $pin_cat->name . ' <span class="count">(' . $pin_cat->pin_count . ')</span></a></li>';
				}
			
			?>
			</ul>
		</li>
	</ul>
<?php

}



function pin_get_user_login ( $user_id ) {	

	global $wpdb;
	
	$result = wp_cache_get( 'user_login_' . $user_id );
	
	if ( false === $result ) {
		$result = $wpdb->get_var ( "SELECT user_login FROM wp_users WHERE ID = $user_id" );
		wp_cache_set( 'user_login_' . $user_id, $result );
	}
	 
	return $result;	
}

function pin_current_user_can_edit ( $pin_id ) {

	if ( current_user_can( 'manage_options')) 
		return true;
		
	if (! is_user_logged_in())
		return false;
		
	global $user_ID, $wp_pin, $wpdb;
	$table_name = $wpdb->base_prefix . $wp_pin->table_name;
	
	$result = wp_cache_get( 'pin_author_' . $pin_id );
	
	if ( false === $result ) {
		$result = $wpdb->get_var ( "SELECT user_id FROM $table_name WHERE id = $pin_id" );
		wp_cache_set( 'pin_author_' . $pin_id, $result );
	}
	 
	if ( $user_ID == $result) 
		return true;
		
	return false;
}
/**
 * Select de las categorías
 *
 * @param unknown_type $current
 */
function pin_dropdown_categories ( $current = '', $name = 'pin_category') {

	global $wp_pin;
	$categories = $wp_pin->getCategories();
	?>
	<select id="<?php echo $name ?>" name="<?php echo $name ?>">
		<option value=""><?php _e('Selecciona una categoría', 'pin')?></option>
		<?php foreach ( $categories as $cat ): ?>
			<option value="<?php echo $cat->id ?>" <?php selected( $cat->id, $current );?>><?php echo $cat->name ?></option>
		<?php endforeach;?>
	</select>
	
	<?php
	
}
/**
 * Select de los tableros de un pin	
 *
 * @param int $user_id
 * @param int $current Current board-id
 * @param string $name Name and id select stributes
 */
function pin_dropdown_boards ( $user_id, $current = null, $name = 'pin_board') {

	global $wp_pin;
	$categories = $wp_pin->get_boards($user_id);
	?>
	<select id="<?php echo $name ?>" name="<?php echo $name ?>">
		<!--<option value=""><?php _e('Selecciona un tablero', 'pin')?></option>-->
		<?php foreach ( $categories as $cat ): ?>
			<option value="<?php echo $cat->id ?>" <?php selected( $cat->id, $current );?>><?php echo $cat->name ?></option>
		<?php endforeach;?>
	</select>
	
	<?php
	
}


/**
 * Imprime los metas de un pin (comentarios, repines)
 *
 * @param object $pin
 */
function pin_metas ( $pin ) {

	if (! empty( $pin->repins ) || ! empty( $pin->comments_count )){
	
		echo '<p class="metas">';
			$metas = array();
			if ( ! empty( $pin->repins ))
				$metas[] =  $pin->repins . ' ' ._n('repin', 'repines', $pin->repins , 'pin');
			if ( ! empty( $pin->comments_count ))
				$metas[] =  $pin->comments_count . ' ' . _n('comentario', 'comentarios', $pin->comments_count , 'pin');
			echo implode(' • ', $metas);
		echo '</p>';
	}
}

function pin_update_repins ( $pin_id ) {

	global $wpdb, $wp_pin;
	$table_name = $wpdb->base_prefix . $wp_pin->table_name;
	// Actualizamos el contador de REPINES
	$count_repims = $wpdb->get_var ("SELECT count(*) FROM $table_name WHERE parent = '{$pin_id}'");
	$wpdb->update( $table_name, array('repins' => $count_repims), array('id' => $pin_id));
	
}

function pin_update_cat_pins ( $category ) {

	global $wpdb, $wp_pin;
	
	//wp_mail('j.arques@artvisual.net', 'actualiza', $category );
	$table_name = $wpdb->base_prefix . $wp_pin->table_name;
	// Actualizamos el contador de categorías
	$count_cat_pins = $wpdb->get_var ("SELECT count(*) FROM $table_name WHERE category = '{$category}'");
	$wpdb->update( "{$table_name}_categories", array('pin_count' => $count_cat_pins), array('slug' => $category));
	
}
/**
 * Update pins board counter
 *
 * @param int $board board id
 */
function pin_update_board_pins ( $board ) {

	global $wpdb, $wp_pin;
	
	$table_name = $wpdb->base_prefix . $wp_pin->table_name;
	// Actualizamos el contador de pins de un tablero
	$count_board_pins = $wpdb->get_var ("SELECT count(*) FROM $table_name WHERE board = '{$board}'");
	$wpdb->update( "{$table_name}_boards", array('pin_count' => $count_board_pins), array('id' => $board));
	
}

function pin_locate_template ( $templatefilename ) {

    if (file_exists( TEMPLATEPATH . '/pin/' . $templatefilename )) {
        $return_template = TEMPLATEPATH . '/pin/' . $templatefilename;
    } else {
        $return_template = PIN_PLUGIN_DIR . '/templates/pin/' . $templatefilename;
    }
    
    include($return_template);
}
function pin_is_my_profile () {

	if (! is_user_logged_in())
		return false;

	if ( get_query_var('pinsection') == PIN_USER_SLUG ) {
		$pinid = get_query_var('pinid');
		$current_user = wp_get_current_user();	
		if ( $current_user->data->user_login === $pinid )
			return true;
	}
	return false;
	
}

function pin_is_my_board ( $board ) {
	if ( ! is_user_logged_in() )	
		return false;
	global $user_ID;
	if ( $user_ID == $board->user_id )
		return true;
	return false;
}
function pin_im_following_board ( $board ) {

	if (! is_user_logged_in())
		return false;
		
	global $wpdb, $user_ID,$wp_pin;
	
	$table_name = $wpdb->base_prefix . $wp_pin->table_name . "_follow";
	
	return $wpdb->get_var("select count(*) from $table_name where follower = $user_ID and board = $board");
}

function pin_im_following_user ( $user_id ) {

	if (! is_user_logged_in())
		return false;
	
	global $wpdb, $user_ID,$wp_pin;
	
	$table_name = $wpdb->base_prefix . $wp_pin->table_name . "_follow";
	
	return $wpdb->get_var("select count(*) from $table_name where follower = $user_ID and followed = $user_id");
}

function pin_new_board_modal ( $id = "new-board", $pin_id = null) {
?>
<div class="modal hidden" id="<?php echo $id ?>" tabindex="-1" role="dialog" aria-labelledby="crear-tablero" aria-hidden="true">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    	<h3 id="crear-tablero"><?php _e('Crear tablero nuevo', 'pin')?></h3>
    </div>
	
	<form class="bs-docs-example form-horizontal" id="pin-board-form" method="POST">
		<div class="modal-body">
		
	        <div class="control-group">
	          <label for="board-name" class="control-label">
	          	<?php _e('Nombre del tablero', 'pin')?>
	          </label>
	          <div class="controls">
	            <input type="text" id="board-name" name="board-name">
	            <span class="help-inline"><?php _e('Por favor, inserta un nombre', 'pin')?></span>
	          </div>
	        </div>
	        
	        <div class="control-group">
	          <label for="board-description" class="control-label">
	          	<?php _e('Descripción', 'pin')?>
	          </label>
	          <div class="controls">
	            <textarea id="board-description" name="board-description"></textarea>
	          </div>
	        </div>
	        
	        <div class="control-group">
	          <label for="board-category" class="control-label"><?php _e('Categoría del tablero', 'pin')?></label>
	          <div class="controls">
	            <?php pin_dropdown_categories('', 'board-category', 'id');?>
	          </div>
	        </div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary"><?php _e('Crear tablero', 'pin')?></button>
		</div>
		<input type="hidden" name="action" value="new-board" />
		
		<?php
			if (! empty( $pin_id )) {
				echo '<input type="hidden" value="' . $pin_id . '" name="pin_id" id="pin_id" />';
			}
		?>
		 <?php wp_nonce_field('new-board','new-board'); ?>
    </form>

</div>
<?php
}

function pin_count_followers ( $user_id ) {
	global $wp_pin;
	$count = get_user_meta( $user_id, 'pin_count_followers', true);
	if ( empty( $count ))
		$count = $wp_pin->update_count_followers ( $user_id );
		
	return $count;

}
function pin_count_following( $user_id ) {
	global $wp_pin;
	$count = get_user_meta( $user_id, 'pin_count_following', true);
	if ( empty( $count ))
		$count = $wp_pin->update_count_following ( $user_id );
		
	return $count;

}

function pin_count_pins( $user_id ) {
	global $wp_pin;
	$count = get_user_meta( $user_id, 'pin_count_pins', true);
	if ( empty( $count ))
		$count = $wp_pin->update_count_pins ( $user_id );
		
	return $count;

}
function pin_count_boards( $user_id ) {
	global $wp_pin;
	$count = get_user_meta( $user_id, 'pin_count_boards', true);
	if ( empty( $count ))
		$count = $wp_pin->update_count_boards ( $user_id );
		
	return $count;
}
function pin_image ( $width = 550, $height = 0, $img = true){

	global $pin;
	
	
	
	$url = pin_get_image( $pin->url );
//	
//	dump( $pin->url  );
//	dump( $url );
//	
	$max_size = 550;
	$size = $width;
	if ( $width > 550 ) {
		$imagesize = getimagesize( $url );
		if ( $imagesize[0] < $max_size ){
			$size = $imagesize[0];
		}
			
	}
	
	
	$src = thumbGen( $url, $size, $height, "background=transparent&return=1");

	if ( $img ) {
		echo '<img src="'.$src.'" alt="'.$pin->name.'" title="'.$pin->name.'" width="'.$size.'" />';
	} else {
		echo $src;
	}
}
/**
 * Retorna la URL de la imágen dentro de nuestra caché de imágenes.
 * Para ello hace el md5 de la URL 
 * Si no existe crea otro imagen
 *
 * @param String $url de la imagen
 * @return String url de la imagen en nuestra caché
 */
function pin_get_image ( $url ) {

	// Problemas con la codificación
	//$url = str_replace(' ', '+', $url);
//	$basename = basename( $url );
//	$url = str_replace($basename, urlencode($basename), $url);	

	/*
	$parsed_url = parse_url( $url );
	
	if ( ! empty( $parsed_url['query'])) {
		$url = str_replace( '?' . $parsed_url['query'], '', $url);
	}
	*/
	
	$url = trim($url);
	
	// Se pasa a MD5
	$md5 = md5( $url );
	$base_folder = substr( $md5, 0, 5);
	$filename = substr( $md5, 5);
	$folderpath = PIN_IMAGES_SERVER_FOLDER . $base_folder . '/';
	$filepath = $folderpath . $filename;

	if ( ! file_exists( $filepath )) {
		pin_save_image( $url );
	}
	
	return PIN_IMAGES_SERVER_FOLDER . $base_folder .'/'. $filename;
}
/**
 * Esta función salva la imagen pasada en la URL en una cache externa con una llamada por CURL
 *
 * @param String $url de la imagen
 */
function pin_save_image ( $url ) {


	//$basename = basename( $url );$img_url = str_replace($basename, rawurlencode($basename), $url);	

	//$query_url = PIN_IMAGES_SERVER . 'save.php?url=' . $img_url . '&hash=' . PIN_IMAGES_CACHE_HASH;
	
	$params['body'] = array( 'hash' => PIN_IMAGES_CACHE_HASH, 'url' => $url );

	$response = wp_remote_post( PIN_IMAGES_SERVER . 'save.php', $params );
	
	if( is_wp_error( $response ) ) {
		return false;
	} else {
		return true;
	}
}