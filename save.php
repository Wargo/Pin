<?php
require_once '../../../wp-load.php';

$pin = new Pin();

if (is_user_logged_in()) {
	if (!empty($_POST['name'])) {
		$name = $_POST['name'];
		$category = $_POST['category'];
		$from = $_POST['from'];
		$url = $_POST['url'];

		global $wpdb, $user_ID;
		$table_name = $wpdb->prefix . $pin->table_name;
		$query = "INSERT INTO $table_name (time, user_id, url, name, category) VALUES (NOW(), '$user_ID', '$url', '$name', '$category')";
		$wpdb->query($query);
	}
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
