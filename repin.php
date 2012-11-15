<?php
require_once '../../../wp-load.php';

if (is_user_logged_in()) {

	$pin = new Pin();
	global $user_ID;
	if ( $_REQUEST ['id']) {
		$repin = $pin->get_pin( $_REQUEST ['id'] );
	}
	$boards = $pin->get_boards ( $user_ID, 0);
	
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Wordpress &rsaquo; Pin</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
		<script type="text/javascript">
			var pinajaxurl = '<?php echo PIN_PLUGIN_AJAX ?>';
			var user_id = '<?php echo $user_ID?>'
		</script>
		<script type="text/javascript" src="<?php echo trailingslashit(site_url()). WPINC ?>/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="<?php echo PIN_PLUGIN_URL?>popup.js"></script>
	</head>
	<body>
		<div class="pin-form">
			<div class="logo">
				<?php echo $pin->logo(); ?>
			</div>
			<div class="image_holder">
				<img class="img" alt="<?php echo $repin->name ?>" src="<?php thumbGen( $repin->url, 200, 0, "background=transparent")?>">
			</div>
			<div class="form_holder">
				<form method="POST" action="save.php" class="clearfix" id="new-pin-form">
				
					<?php $pin->board_selector(); ?>
				
					<div class="control-group">
						<label for="name"><?php echo __('Nombre', true); ?></label>
						<div class="controls">
							<input style="width: 285px" name="name" id="name" type="text" value="<?php echo $repin->name ?>"/>
						</div>
						<p class="hide">Describe tu pin</p>
					</div>
					

					
					<input type="hidden" name="repin" value="<?php echo $repin->id ?>" />
					<input type="submit" value="Guardar" class="btn btn-success btn-large" onclick="javascript: document.getElementById('name')" />
				</form>
			</div>
		</div>
	</body>
	</html>
	<?php
} else {
	wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
	
	
	
}