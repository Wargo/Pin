<?php

/*	AÑADIR COMENTARIO
--------------------------------------------------------------------------------------- */
add_action('wp_ajax_pin_add_comment_action', 'pin_add_comment');
add_action('wp_ajax_nopriv_pin_add_comment_action', 'pin_add_comment');

function pin_add_comment() {

	header( "Content-Type: application/json" );
	
	if ( empty($_POST) || !wp_verify_nonce($_POST['pin_add_comment'], 'add_comment') ){
	   echo json_encode( array( 'success' => 0 ));
	} else {
		global $wpdb, $wp_pin, $user_ID; // this is how you get access to the database
		$table_name = $wpdb->base_prefix . $wp_pin->table_name;
		
		$comment_id = $wpdb->insert ( $table_name . "_comments", array('user_id' => $user_ID, 'pin_id' => $_POST['pin_id'], 'content' => $_POST['content'], 'time' => date('Y-m-d H:i:s')));
		
		// Actualizamos el contador de comentarios
		$comments_count = $wpdb->get_var ("SELECT count(*) FROM " . $table_name . "_comments WHERE pin_id = '{$_POST['pin_id']}'");
		$wpdb->update( $table_name, array('comments_count' => $comments_count), array('id' => $_POST['pin_id']));
		
		if ( $comment_id ) {
			echo json_encode( array( 'comment_id' => $wpdb->insert_id, 'success' => 1, 'nonce' => wp_create_nonce( 'pin_delete_comment' )));
		} else {
			echo json_encode( array( 'success' => 0 ));
		}
	}

	die(); // this is required to return a proper result
}

/*	ELIMINAR COMENTARIO
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_delete_comment_action', 'pin_delete_comment');
add_action('wp_ajax_nopriv_pin_delete_comment_action', 'pin_delete_comment');

function pin_delete_comment() {

	header( "Content-Type: application/json" );
	
	if ( empty($_POST) || !wp_verify_nonce($_POST['nonce'], 'pin_delete_comment' ) || (! is_user_logged_in()) || ( empty($_POST['comment_id'])) ){
	   echo json_encode( array( 'success' => 0 ));
	} else {
	
		global $wpdb, $wp_pin, $user_ID; // this is how you get access to the database
		$table_name = $wpdb->base_prefix . $wp_pin->table_name . "_comments";
		
		$comment_author = $wpdb->get_var( "SELECT user_id FROM $table_name WHERE id = {$_POST['comment_id']}");
		
		
		if ( $comment_author == $user_ID || current_user_can('manage_options')) {
			$success = $wpdb->query("DELETE FROM $table_name WHERE id = {$_POST['comment_id']}");
			
			// Actualizamos el contador de comentarios
			$pin_id = $wpdb->get_var( "SELECT pin_id FROM $table_name WHERE id = {$_POST['comment_id']}");
			$comments_count = $wpdb->get_var ("SELECT count(*) FROM " . $wpdb->base_prefix . $wp_pin->table_name . " WHERE pin_id = $pin_id");
			$wpdb->update( $wpdb->base_prefix . $wp_pin->table_name, array('comments_count' => $comments_count), array('id' => $pin_id ));
		}
		
		if ( $success ) {
			echo json_encode( array( 'success' => 1));
		} else {
			echo json_encode( array( 'success' => 0 ));
		}
	}

	die(); // this is required to return a proper result
}

/*	ELIMINAR PIN
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_delete_pin_action', 'pin_delete_pin');
add_action('wp_ajax_nopriv_pin_delete_pin_action', 'pin_delete_pin');

function pin_delete_pin(){

	header( "Content-Type: application/json" );
	
	if ( ! empty($_POST['pin_id']) && is_user_logged_in() ) {
		global $wpdb, $use_ID, $wp_pin;
		$table_name = $wpdb->base_prefix . $wp_pin->table_name;
		
		$category = $wpdb->get_var ("SELECT category FROM $table_name WHERE id = {$_POST['pin_id']}");
		
		if ($wpdb->query("DELETE FROM $table_name WHERE id = {$_POST['pin_id']}") != false) {
		
		
	 		$current_user = wp_get_current_user();
			$return_url = pin_url( 'user',  $current_user->user_login);
			 
			// Actualizamos el contador de categoría
			
			pin_update_cat_pins ($category);
			
			echo json_encode( array('success' => 1, 'response' => $return_url));
		} else {
			echo json_encode( array ('success' => 0) );
		}
	}
	die();
}


/*	ELIMINAR CATEGORIA
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_delete_category_action', 'pin_delete_category');
add_action('wp_ajax_nopriv_pin_delete_category_action', 'pin_delete_category');

function pin_delete_category( ){

	if ( ! empty($_POST['cat']) && current_user_can('manage_options') ) {
	
		global $wpdb, $use_ID, $wp_pin;
		$table_name = $wpdb->base_prefix . $wp_pin->table_name . '_categories';
		
		$wpdb->query("DELETE FROM $table_name WHERE id = {$_POST['cat']}");
	}
	
	die();
}

/*	GUARDAR CATEGORIA
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_edit_category_action', 'pin_edit_category');
add_action('wp_ajax_nopriv_pin_edit_category_action', 'pin_edit_category');

function pin_edit_category( ){

	if ( ! empty($_POST['cat_name']) || ! empty($_POST['cat_slug']) && current_user_can('manage_options') && ! empty($_POST['cat_id']) ) {
	
		global $wpdb, $use_ID, $wp_pin;
		
		$table_name = $wpdb->base_prefix . $wp_pin->table_name . '_categories';
		
		
		$prev_slug = $wpdb->get_var("SELECT slug FROM $table_name WHERE id = {$_POST['cat_id']}");
		
		$wpdb->update( $table_name, array('name' =>$_POST['cat_name'], 'slug'=> $_POST['cat_slug']), array('id' => $_POST['cat_id']));
		
		if ( $prev_slug != $_POST['cat_slug']) {
			$wpdb->query("update wp_pin set category = replace( category,'$prev_slug','{$_POST['cat_slug']}')");
		}

	}
	
	die();
}


/*	ELIMINAR BOARD / TABLERO
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_delete_board_action', 'pin_delete_board');
add_action('wp_ajax_nopriv_pin_delete_board_action', 'pin_delete_board');

function pin_delete_board(){

	header( "Content-Type: application/json" );
	
	if ( ! empty($_POST['board_id']) && is_user_logged_in() ) {
		global $wpdb, $use_ID, $wp_pin;
		$pins_table = $wpdb->base_prefix . $wp_pin->table_name;
		$boards_table = $pins_table . '_boards';
		
		//$category = $wpdb->get_var ("SELECT category FROM $table_name WHERE id = {$_POST['pin_id']}");
		
		if ($wpdb->query("DELETE FROM $boards_table WHERE id = {$_POST['board_id']}") != false) {
		
		
			$wpdb->query("DELETE FROM $pins_table WHERE board = {$_POST['board_id']}");
	 		$current_user = wp_get_current_user();
			$return_url = pin_url( 'user',  $current_user->user_login);
			 
			// Actualizamos el contador de categoría
			
			//pin_update_cat_pins ($category);
			
			echo json_encode( array('success' => 1, 'response' => $return_url));
		} else {
			echo json_encode( array ('success' => 0) );
		}
	}
	die();
}


/*	AÑADIR BOARD / TABLERO
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_add_board_action', 'pin_add_board');
add_action('wp_ajax_nopriv_pin_add_board_action', 'pin_add_board');

function pin_add_board(){

	header( "Content-Type: application/json" );

	if ( ! empty($_POST['board_name']) && is_user_logged_in() ) {
	
		global $user_ID, $wp_pin;
		
		if ( $return = $wp_pin->add_board ( $_POST['board_name'] ) ) {

			echo json_encode( array('success' => 1, 'response' => $return['id']));
		} else {
			echo json_encode( array ('success' => 0, 'response' => '') );
		}
	}
	die();
}


/*	SEGUIR TABLERO
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_follow_board_action', 'pin_follow_board');
add_action('wp_ajax_nopriv_pin_follow_board_action', 'pin_follow_board');
add_action('wp_ajax_pin_unfollow_board_action', 'pin_follow_board');
add_action('wp_ajax_nopriv_pin_unfollow_board_action', 'pin_follow_board');

function pin_follow_board(){

	header( "Content-Type: application/json" );

	if ( ! empty($_POST['board_id']) && is_user_logged_in() ) {
	
		global $user_ID, $wp_pin;
		
		if ( $_POST['action'] == 'pin_unfollow_board_action') {
			if ($wp_pin->unfollow_board( $user_ID, $_POST['board_author_id'], $_POST['board_id'])) {
				echo json_encode( array('success' => 1, 'action' => 'unfollow'));
			} else {
				echo json_encode( array('success' => 0, 'action' => 'unfollow'));
			}
		} else {
			if ($wp_pin->follow_board( $user_ID, $_POST['board_author_id'], $_POST['board_id'])) {
				echo json_encode( array('success' => 1, 'action' => 'follow'));
			} else {
				echo json_encode( array('success' => 0, 'action' => 'follow'));
			}
		}
	}
	die();
}


/*	SEGUIR USUARIO (TODOS SUS TABLEROS)
--------------------------------------------------------------------------------------- */

add_action('wp_ajax_pin_follow_user_action', 	'pin_follow_user');
add_action('wp_ajax_nopriv_pin_follow_user_action', 'pin_follow_user');
add_action('wp_ajax_pin_unfollow_user_action', 'pin_follow_user');
add_action('wp_ajax_nopriv_pin_unfollow_user_action', 'pin_follow_user');

function pin_follow_user(){

	header( "Content-Type: application/json" );

	if ( ! empty($_POST['user_id']) && is_user_logged_in() ) {
	
		global $user_ID, $wp_pin;
		
		if ( $_POST['action'] == 'pin_unfollow_user_action') {
			if ($wp_pin->unfollow_user( $user_ID, $_POST['user_id'])) {
				echo json_encode( array('success' => 1, 'action' => 'unfollow'));
			} else {
				echo json_encode( array('success' => 0, 'action' => 'unfollow'));
			}
		} else {
			if ($wp_pin->follow_user( $user_ID, $_POST['user_id'])) {
				echo json_encode( array('success' => 1, 'action' => 'follow'));
			} else {
				echo json_encode( array('success' => 0, 'action' => 'follow'));
			}
		}
	}
	die();
}



