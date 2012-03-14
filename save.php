<?php
require_once '../../../wp-load.php';

$pin = new Pin();

if (is_user_logged_in()) {
	?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Wordpress &rsaquo; Pin</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<div class="success">
			<?php echo __('Guardado correctamente', true); ?>
		</div>
	</body>
	</html>
	<?php
}
