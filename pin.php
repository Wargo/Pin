<?php
/*
Plugin Name: Pin
Description: Captura imágenes
Version: 1.0
Author: Guille
Author URI: http://artvisual.net
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Pin {

	var $logo = 'http://elembarazo.net/wp-content/themes/embarazada/images/newsletter_banner.jpg';
	var $table_name = 'pin';
	var $db_version = 1.0;

	function __construct() {
		register_activation_hook(__FILE__, array(&$this, 'install'));
		add_filter('the_content', array(&$this, 'show'));

		add_action('admin_menu', array(&$this, 'menu'));
	}

	function debug($array) {
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}

	function getCategory($category_slug) {
		$categories = $this->getCategories();
		$aux = array();
		foreach($categories as $category) {
			extract((array)$category);
			$aux[$slug] = $name;
		}
		return $aux[$category_slug];
	}

	function getCategories() {
		global $wpdb;
		$table_name = $wpdb->prefix . $this->table_name;
		return $wpdb->get_results("SELECT * FROM " . $table_name . "_categories");
	}

	function defaultCategories() {
		return array(
			array(
				'name' => 'Decoración',
				'slug' => 'decoracion',
			),
			array(
				'name' => 'Ropa',
				'slug' => 'ropa',
			),
			array(
				'name' => 'Zapatos',
				'slug' => 'zapatos',
			),
			array(
				'name' => 'Bolsos',
				'slug' => 'bolsos',
			),
			array(
				'name' => 'Ropa bebé',
				'slug' => 'ropa-bebe',
			),
			array(
				'name' => 'Cunas',
				'slug' => 'cunas',
			),
			array(
				'name' => 'Pinturas',
				'slug' => 'pinturas',
			),
			array(
				'name' => 'Comida',
				'slug' => 'comida',
			),
		);
	}

	function logo() {
		echo '<img class="logo" src="' . $this->logo . '" />';
	}

	function install() {
		global $wpdb;

		$table_name = $wpdb->prefix . $this->table_name;

		$sql = "
			CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				user_id bigint(20) NOT NULL,
				url varchar(255) NOT NULL,
				name varchar(255) NOT NULL,
				category varchar(255) NOT NULL,
				PRIMARY KEY id (id)
			);

			CREATE TABLE " . $table_name . "_categories (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				slug varchar(255) NOT NULL,
				UNIQUE KEY slug (slug),
				PRIMARY KEY id (id)
			);
		";
		
		$categories = $this->defaultCategories();
		foreach($categories as $category) {
			$sql .= 'INSERT INTO ' . $table_name . '_categories (name, slug) values (\'' . $category['name'] . '\', \'' . $category['slug'] . '\');';
		}


		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		add_option('pin_db_version', $this->db_version);
	}

	function show() {
		global $post;
		if ($post->ID == 14) {
			if (is_user_logged_in()) {
				global $wpdb, $user_ID;
				$table_name = $wpdb->prefix . $this->table_name;
				$query = "SELECT * FROM $table_name WHERE user_id = '$user_ID'";
				$pins = $wpdb->get_results($query);
				echo '<link rel="stylesheet" type="text/css" media="all" href="http://wordpress.dev/wp-content/plugins/pin/style.css" />';
				echo '<div class="show_pins clearfix">';
				foreach ($pins as $p) {
					extract((array)$p);
					echo '
					<div class="img">
						<img src="' . $url . '" />
						<br />
						<a href="' . $url . '" target="_blank">' . $name . '</a>
						<br />' . 
						$this->getCategory($category) . '
					</div>
					';
				}
				echo '</div>';
			}
		}
	}

	function menu() {
		add_options_page('Pin', 'Pin', 'manage_options', 'pin', array(&$this, 'options_page'));
	}

	function options_page() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		if (!empty($_POST['post_id'])) {
			$post_id = $_POST['post_id'];

			update_option('pin_post_id', $post_id);
		}

		global $wpdb;

		$query = "SELECT id, post_title FROM " . $wpdb->prefix . "posts WHERE post_type = 'page'";
		$pages = $wpdb->get_results($query);

		$current = get_option('pin_post_id');

		echo '
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>' . __('Ajustes', true) . '</h2>
			<p>' . __('Descripción de los ajustes', true) . '</p>
			<form action="" method="post">
				<label for="post_id">' . __('Selecciona la página Pin', true) . '</label>
				<select name="post_id" id="post_id">';
					foreach ($pages as $page) {
						extract((array)$page);
						if ($current == $id) {
							echo '<option selected="selected" value="' . $id . '">' . $post_title . '</option>';
						} else {
							echo '<option value="' . $id . '">' . $post_title . '</option>';
						}
					}
				echo '</select>
				<p class="submit"><input type="submit" value="Guardar cambios" class="button-primary" id="submit" name="submit"></p>
			</form>
		</div>
		';
	}

}
$pin = new Pin();
