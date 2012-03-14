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
		<form method="POST" action="save.php" class="clearfix">
			<div class="logo">
				<?php echo $pin->logo(); ?>
			</div>
			<img class="left img" src="<?php echo $_REQUEST['image']; ?>" />
			<div class="left">
				<div class="box clearfix">
					<label for="name"><?php echo __('Nombre', true); ?></label>
					<input name="name" id="name" />
				</div>
				<div class="box clearfix">
					<label for="category"><?php echo __('Categoría', true); ?></label>
					<select id="category" name="category">
						<?php
						$categories = $pin->getCategories();
						foreach ($categories as $key => $value) {
							echo '<option value="' . $key . '">' . $value['name'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<input name="from" type="hidden" value="<?php echo $_REQUEST['from']; ?>" />
			<input type="submit" value="Guardar" class="right" />
		</form>
	</body>
	</html>
	<?php
} else {
	wp_redirect(wp_login_url($_SERVER['REQUEST_URI']));
}
