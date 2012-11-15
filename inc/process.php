<?php
require_once '../../../../wp-load.php';

if ( isset($_POST['action']) && $_POST['action'] == 'pin_add_board_action') {
	
	pin_add_board();
}