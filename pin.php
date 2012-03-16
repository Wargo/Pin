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

}
$pin = new Pin();
