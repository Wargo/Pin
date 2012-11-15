<?php
require_once '../../../wp-load.php';

if (is_user_logged_in()) {

	if ( ! empty( $_POST['repin']) ) {
		
		global $wpdb;
	
		$pin = new Pin();	
		$repin = $pin->get_pin( $_POST['repin'] );	

		$pin_id =$pin->add_pin ( array( 'url' => $repin->url, 'name' => $_POST['name'], 'board' => $_POST['board'], 'via' => $repin->via, 'referer' => $repin->referer, 'parent'=> $repin->id));
		
		wp_redirect( PIN_PLUGIN_URL . 'saved.php?pin_id=' . $pin_id);
	
	} elseif (!empty($_POST['name'])) {
	
		extract($_POST);
		$pin = new Pin();
		$pin_id =$pin->add_pin ( array( 'url' => $url, 'name' => $name, 'board' => $board, 'via' => $via, 'referer' => $referer));
		
		wp_redirect( PIN_PLUGIN_URL . 'saved.php?pin_id=' . $pin_id);
	} else {
		die( __('Hubo un error enviando los datos', 'pin'));
	}

} else {
	wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
}
