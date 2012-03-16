<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Wordpress &rsaquo; Pin</title>
	<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
	<div class="show_pins clearfix">
		<?php
		require_once '../../../wp-load.php';

		$pin = new Pin();

		if (is_user_logged_in()) {
			global $wpdb, $user_ID;
			$table_name = $wpdb->prefix . $pin->table_name;
			$query = "SELECT * FROM $table_name WHERE user_id = '$user_ID'";
			$pins = $wpdb->get_results($query);
			foreach ($pins as $p) {
				extract((array)$p);
				echo '<div class="img">';
					echo '<img src="' . $url . '" />';
					echo '<br />';
					echo '<a href="' . $url . '" target="_blank">' . $name . '</a>';
					echo '<br />';
					echo $pin->getCategory($category);
				echo '</div>';
			}
		}
		?>
	</div>
</body>
</html>
