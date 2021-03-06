<?php
require_once '../../../wp-load.php';

global $user_ID;
$pin = new Pin();

if (is_user_logged_in()) {
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Nuevo Pin</title>
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
				<img class="left img" src="<?php echo $_REQUEST['image']; ?>" />
			</div>
			<div class="form_holder">
				<form method="POST" action="save.php" class="clearfix" id="new-pin-form">
				
					
					<?php $pin->board_selector(); ?>
					
					
					<div class="control-group">
						<label for="name"><?php echo __('Nombre', true); ?></label>
						<div class="controls">
							<input style="width: 285px" name="name" id="name" type="text" value="<?php if (! empty($_REQUEST['title'])) echo $_REQUEST['title']?>"/>
						</div>
						<p class="hide">Describe tu pin</p>
					</div>
					
					
					
					<input name="via" type="hidden" value="<?php echo $_REQUEST['via']; ?>" />
					<input name="referer" type="hidden" value="<?php echo $_REQUEST['referer']; ?>" />
					<input name="url" type="hidden" value="<?php echo $_REQUEST['image']; ?>" />
					<input type="submit" value="Guardar" class="right btn btn-success btn-large" onclick="javascript: document.getElementById('name')" />
					
				</form>
			</div>
		</div>
		
		
	</body>
	</html>
	<?php
} else {
	wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
	
	
	
}